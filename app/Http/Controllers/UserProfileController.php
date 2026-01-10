<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use App\Models\UserProfile;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use GuzzleHttp\Client;

class UserProfileController extends Controller
{
    public function edit(): View
    {
        $profile = UserProfile::firstOrCreate(['user_id' => Auth::id()]);
        return view('user_profile.edit', compact('profile'));
    }

    public function updateProfileData(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('name')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('user_profile.edit')->with('status', __('message.profile_updated'));
    }

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
            'role' => 'nullable|in:executor,client,both',
            'nickname' => [
                'nullable',
                'string',
                'regex:/^[a-zA-Z0-9_]{5,32}$/',
                'unique:user_profiles,nickname',
            ],
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

        $data['avatar_url'] = $this->handleAvatarUpload($request);
        $data['user_id'] = Auth::id();

        UserProfile::create($data);

        return redirect()->route('user_profile.index')
            ->with('success', __('message.profile_created'));
    }

    public function show($id)
    {
        $profile = UserProfile::with('user')->find($id);
        if (!$profile) {
            return redirect()->route('user_profile.index')
                ->with('error', __('message.profile_not_found'));
        }

        return view('user_profile.index', compact('profile'));
    }

    public function updateFullProfile(Request $request)
    {
        Log::info('Запущен updateFullProfile()');

        $user = auth()->user();

        $validated = $request->validate([
            'nickname' => [
                'nullable',
                'string',
                'regex:/^[a-zA-Z0-9_]{5,32}$/',
                Rule::unique('user_profiles', 'nickname')->ignore($user->profile?->id ?? 0, 'id'),
            ],
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

        $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);
        $profile->fill($validated);

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
            // Убраны лишние пробелы в URL!
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
            // Исправлен URL: убраны пробелы
            return isset($data['Hash']) ? 'https://daodes.space/ipfs/' . $data['Hash'] : null;
        } catch (\Exception $e) {
            Log::error('IPFS upload error: ' . $e->getMessage());
            return null;
        }
    }

    private function handleAvatarUpload(Request $request, string $existingAvatar = null): string
    {
        if ($request->hasFile('filename')) {
            $file = $request->file('filename');
            Log::info('handleAvatarUpload(): файл передан', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize()
            ]);

            $avatarUrl = $this->uploadToIPFS($file);

            if ($avatarUrl) {
                Log::info('Аватар загружен в IPFS', ['url' => $avatarUrl]);
                return $avatarUrl;
            } else {
                Log::warning('Не удалось загрузить аватар на IPFS.');
            }
        }

        return $existingAvatar ?: 'https://daodes.space/ipfs/QmPdPDwGSrfWYxomC3u9FLBtB9MGH8iqVGRZ9TLPxZTekj';
    }

    public function setPublicKey(Request $request)
{
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $request->validate([
        'public_key' => 'required|string|regex:/^[A-Za-z0-9+\/=]+$/|max:255',
    ]);

    $user = auth()->user();

    // 🔐 Если ключ уже есть — НЕ обновляем!
    if ($user->profile?->public_key) {
        // Можно вернуть текущий ключ для проверки
        return response()->json([
            'message' => 'already exists',
            'public_key' => $user->profile->public_key
        ]);
    }

    // Иначе — создаём или обновляем профиль с ключом
    $user->profile()->updateOrCreate([], [
        'public_key' => $request->public_key,
    ]);

    return response()->json(['message' => 'ok']);
}



}
