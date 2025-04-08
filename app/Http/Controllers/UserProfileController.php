<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\Wallet;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    // Показ формы редактирования
    public function edit(): View
    {
        $profile = UserProfile::firstOrCreate(['user_id' => Auth::id()]);
        return view('user_profile.edit', compact('profile'));
    }

    // Обновление данных из основной таблицы users
    public function updateProfileData(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('name')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('user_profile.edit')->with('status', __('message.profile_updated'));
    }

    // Удаление аккаунта
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // Просмотр профиля
    public function index($id = null)
    {
        $userId = $id ?? Auth::id();
        $currentUserId = Auth::id();

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
        'nickname' => 'nullable|string',
        'gender' => 'nullable|in:male,female',
        'birth_date' => 'nullable|date',
        'languages' => 'nullable|string',
        'timezone' => 'nullable|string',
        'education' => 'nullable|string',
        'resume' => 'nullable|string',
        'portfolio' => 'nullable|string',
        'specialization' => 'nullable|string',
        'filename' => 'nullable|image|max:2048',
    ]);

    // Обработка аватара через общую функцию
    $data['avatar_url'] = $this->handleAvatarUpload($request);

    $data['user_id'] = Auth::id();
    UserProfile::create($data);

    return redirect()->route('user_profile.index')->with('success', __('message.profile_created'));
}


    public function show($id)
    {
        $profile = UserProfile::with('user')->find($id);
        if (!$profile) {
            return redirect()->route('user_profile.index')->with('error', __('message.profile_not_found'));
        }

        return view('user_profile.index', compact('profile'));
    }

    public function updateFullProfile(Request $request)
{
    Log::info('Запущен updateFullProfile()');

    $user = auth()->user();

    $validated = $request->validate([
        'nickname' => 'nullable|string|max:255',
        'filename' => 'nullable|image|max:5120',
        'role' => 'nullable|string|in:executor,client,both',
        'gender' => 'nullable|string|in:male,female',
        'birth_date' => 'nullable|date',
        'languages' => 'nullable|string',
        'timezone' => 'nullable|string',
        'education' => 'nullable|string',
        'specialization' => 'nullable|string',
        'resume' => 'nullable|string',
        'portfolio' => 'nullable|string',
    ]);

    // Получаем или создаём профиль
    $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);
    $profile->fill($validated);

    // Обработка аватара с защитой от ошибок
    if ($request->hasFile('filename')) {
        try {
            $avatarUrl = $this->uploadToIPFS($request->file('filename'));
            if ($avatarUrl) {
                $profile->avatar_url = $avatarUrl;
            } else {
                Log::warning('Не удалось загрузить аватар на IPFS.');
                return redirect()->back()
                    ->withErrors(['filename' => __('message.avatar_upload_failed')])
                    ->withInput();
            }
        } catch (\Throwable $e) {
            Log::error('Ошибка при загрузке файла на IPFS: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['filename' => __('message.avatar_upload_exception')])
                ->withInput();
        }
    }

    $profile->save();

    return redirect()->route('user_profile.show', $profile->id)
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
            return isset($data['Hash']) ? 'https://daodes.space/ipfs/' . $data['Hash'] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function handleAvatarUpload(Request $request, string $existingAvatar = null): string
    {
        if ($request->hasFile('filename')) {
            $file = $request->file('filename');
    
            Log::info('handleAvatarUpload(): файл передан в uploadToIPFS', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize()
            ]);
    
            $avatarUrl = $this->uploadToIPFS($file);
    
            if ($avatarUrl) {
                Log::info('Аватар успешно загружен в IPFS', ['url' => $avatarUrl]);
                return $avatarUrl;
            } else {
                Log::warning('Не удалось загрузить аватар на IPFS.');
            }
        } else {
            Log::info('handleAvatarUpload(): файл не передан');
        }
    
        return $existingAvatar ?: 'https://daodes.space/ipfs/QmPdPDwGSrfWYxomC3u9FLBtB9MGH8iqVGRZ9TLPxZTekj';
    }
    
    

}
