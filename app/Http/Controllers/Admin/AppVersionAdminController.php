<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\IPFSService;

class AppVersionAdminController extends Controller
{
    protected IPFSService $ipfsService;

    public function __construct(IPFSService $ipfsService)
    {
        $this->ipfsService = $ipfsService;
    }

    public function index()
    {
        $versions = AppVersion::orderByDesc('version_code')->get();

        return view('admin.app_versions.index', compact('versions'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'version_code' => 'required|integer|unique:app_versions,version_code',
                'version_name' => 'required|string|max:255',
                'apk_file' => 'required|file|mimetypes:application/vnd.android.package-archive,application/octet-stream,application/zip|max:512000',
                'force_update' => 'nullable|boolean',
                'changelog' => 'nullable|string',
            ]);

            $apk = $request->file('apk_file');

            if (!$apk || !$apk->isValid()) {
                throw new \Exception('APK файл не найден или поврежден.');
            }

            // Загружаем APK в IPFS через ваш существующий сервис
            $cid = $this->ipfsService->uploadFile($apk);

            if (!$cid) {
                throw new \Exception('Не удалось получить CID после загрузки в IPFS.');
            }

            // Ссылка через ваш gateway
            $ipfsPath = 'https://daodes.space/ipfs/' . $cid;

            AppVersion::create([
                'version_code' => $request->version_code,
                'version_name' => $request->version_name,
                'apk_file' => $ipfsPath,
                'force_update' => $request->boolean('force_update'),
                'changelog' => $request->filled('changelog')
                    ? array_filter(array_map('trim', explode("\n", $request->changelog)))
                    : [],
                'is_active' => true,
            ]);

            Log::info('Новая версия приложения успешно загружена в IPFS.', [
                'version_code' => $request->version_code,
                'cid' => $cid,
                'apk_url' => $ipfsPath,
            ]);

            return redirect()->back()->with(
                'success',
                'Версия приложения успешно загружена в IPFS.'
            );

        } catch (\Exception $e) {
            Log::error('Ошибка загрузки APK в IPFS: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with(
                'error',
                'Ошибка: ' . $e->getMessage()
            );
        }
    }
}