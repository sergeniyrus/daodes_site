<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    public function index($id = null)
    {
        $userId = $id ?? Auth::id();
        $currentUserId = Auth::id();

        // Ищем профиль пользователя
        $userProfile = UserProfile::with('user')->where('user_id', $userId)->first();

        if (!$userProfile) {
            if ($userId === $currentUserId) {
                return redirect()->route('user_profile.create')->with('info', __('message.create_profile'));
            } else {
                return redirect()->route('user_profile.index')->with('error', __('message.profile_not_filled'));
            }
        }

        return view('user_profile.index', compact('userProfile'));
    }

    public function create()
    {
        return view('user_profile.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role' => 'nullable|in:executor,customer,both',
            'avatar_url' => 'nullable|string',
            'nickname' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'languages' => 'nullable|string',
            'timezone' => 'nullable|string',
            'education' => 'nullable|string',
            'resume' => 'nullable|string',
            'portfolio' => 'nullable|string',
            'specialization' => 'nullable|string',
        ]);

        // Log::info('Данные перед записью в БД 1 :', $data);

        if ($request->hasFile('filename')) {
            // Загрузка аватара на IPFS
            $avatarUrl = $this->uploadToIPFS($request->file('filename'));
            if ($avatarUrl) {
                $data['avatar_url'] = $avatarUrl;
            } else {
                // Log::error('Ошибка при загрузке аватара на IPFS.');
                return redirect()->back()->withErrors(__('message.avatar_upload_error'));
            }
        } else {
            $data['avatar_url'] = 'https://daodes.space/ipfs/QmPdPDwGSrfWYxomC3u9FLBtB9MGH8iqVGRZ9TLPxZTekj';
        }

        $data['user_id'] = Auth::id();

        // Log::info('Данные перед записью в БД 2:', $data);

        UserProfile::create($data);

        return redirect()->route('user_profile.index')->with('success', __('message.profile_created'));
    }

    public function show($id)
    {
        $profile = UserProfile::with('user')->find($id);

        if (!$profile) {
            return redirect()->route('user_profile.index')->with('error', __('message.profile_not_found'));
        }

        return view('user_profile.show', compact('profile'));
    }

    public function edit()
    {
        // Создание профиля, если его нет
        $profile = UserProfile::firstOrCreate(['user_id' => Auth::id()]);
        return view('user_profile.edit', compact('profile'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'filename' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required|in:executor,customer,both',
            'nickname' => 'nullable|string',
            'gender' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'languages' => 'nullable|string',
            'timezone' => 'nullable|string',
            'education' => 'nullable|string',
            'specialization' => 'nullable|string',
            'resume' => 'nullable|string',
            'portfolio' => 'nullable|string',
        ]);

        // Находим или создаем профиль
        $profile = UserProfile::firstOrCreate(['user_id' => Auth::id()]);

        if ($request->hasFile('filename')) {
            // Загружаем аватар на IPFS
            $file = $request->file('filename');
            $avatarUrl = $this->uploadToIPFS($file);
            if ($avatarUrl) {
                $profile->avatar_url = $avatarUrl;
            }
        }

        // Обновляем остальные данные профиля
        $profile->fill($request->except(['filename']));

        // Log::info('Данные перед обновлением записей в БД:', $profile->toArray());
        $profile->save();

        return redirect()->route('user_profile.edit', $profile->id)
            ->with('info', __('message.profile_updated'));
    }

    private function uploadToIPFS($file)
    {
        try {
            $client = new Client(['base_uri' => 'https://daodes.space']);

            $response = $client->request('POST', '/api/v0/add', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $file->getClientOriginalName()
                    ]
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['Hash'])) {
                return 'https://daodes.space/ipfs/' . $data['Hash'];
            } else {
                // Log::error('Ответ от IPFS не содержит хэш: ' . $response->getBody());
                return null;
            }
        } catch (\Exception $e) {
            // Log::error('Ошибка при загрузке файла на IPFS: ' . $e->getMessage());
            return null;
        }
    }
}
