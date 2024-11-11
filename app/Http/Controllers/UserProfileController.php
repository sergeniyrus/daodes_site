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
    // Получаем ID запрашиваемого профиля или текущего пользователя
    $userId = $id ?? Auth::id();
    $currentUserId = Auth::id();

    // Находим профиль пользователя по ID
    $userProfile = UserProfile::with('user')->where('user_id', $userId)->first();

    // Если профиль не найден
    if (!$userProfile) {
        // Проверяем, пытается ли текущий пользователь открыть свой профиль
        if ($userId === $currentUserId) {
            // Если у текущего пользователя нет профиля, предлагаем его создать
            return redirect()->route('user_profile.create')->with('info', 'Пожалуйста, создайте свой профиль.');
        } else {
            // Если профиль не заполнен другим пользователем, выводим сообщение
            return redirect()->route('user_profile.index')->with('error', 'Пользователь ещё не заполнил профиль.');
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
    // Валидация входящих данных
    $data = $request->validate([
        'role' => 'nullable|in:executor,client,both',
        'avatar_url' => 'nullable|string',
        'nickname' => 'nullable|string',
        'gender' => 'nullable|in:male,female',
        'birth_date' => 'nullable|date',
        'languages' => 'nullable|json',
        'timezone' => 'nullable|string',
        'education' => 'nullable|string',
        'specialization' => 'nullable|string',
        'resume' => 'nullable|string',
        'portfolio' => 'nullable|json',

        // Если потребуется включить эти поля, раскомментируйте их:
        // 'wallet_address' => 'nullable|string',
        // 'rating' => 'nullable|numeric',
        // 'trust_level' => 'nullable|numeric',
        // 'sbt_tokens' => 'nullable|integer',
        // 'tasks_completed' => 'nullable|integer',
        // 'tasks_failed' => 'nullable|integer',
        // 'recommendations' => 'nullable|json',
        // 'activity_log' => 'nullable|json',
        // 'achievements' => 'nullable|json',
    ]);

    // Дополнительная проверка после валидации
    $errors = [];

    // Проверка роли
    if (!in_array($data['role'], ['executor', 'client', 'both'])) {
        $errors[] = 'Некорректная роль пользователя.';
    }

    // // Проверка формата JSON в languages
    // if (isset($data['languages']) && !is_array(json_decode($data['languages'], true))) {
    //     $errors[] = 'Неверный формат данных в поле "Языки общения".';
    // }

    // Проверка времени (можно добавить валидацию для даты или времени)
    if (isset($data['birth_date']) && strtotime($data['birth_date']) === false) {
        $errors[] = 'Некорректная дата рождения.';
    }

    // Если есть ошибки, возвращаем их
    if (count($errors) > 0) {
        return redirect()->back()->withErrors($errors);
    }

    // Обработка загрузки файла аватара
    if ($request->hasFile('filename')) {
        $data['avatar_url'] = $this->uploadToIPFS($request->file('filename'));
    } else {
        // Если файл не был загружен, используем изображение по умолчанию
        $data['avatar_url'] = 'https://daodes.space/ipfs/QmPdPDwGSrfWYxomC3u9FLBtB9MGH8iqVGRZ9TLPxZTekj';
    }

    // Добавление идентификатора пользователя в данные профиля
    $data['user_id'] = Auth::id();

    // Логирование данных перед записью в БД
    Log::info('Данные перед записью в БД:', $data);

    // Создание нового профиля
    UserProfile::create($data);

    // Перенаправление с сообщением об успехе
    return redirect()->route('user_profile.index')->with('success', 'Профиль успешно создан');
}



    // Метод для отображения информации профиля по ID пользователя
    public function show($id)
{
    // Ищем профиль пользователя по ID и подгружаем данные связанного пользователя
    $profile = UserProfile::with('user')->find($id);

    // Если профиль не найден, возвращаем на главную с сообщением об ошибке
    if (!$profile) {
        return redirect()->route('user_profile.index')->with('error', 'Профиль не найден.');
    }

    // Возвращаем представление с данными профиля
    return view('user_profile.show', compact('profile'));
}


    public function edit()
{
    $profile = UserProfile::firstOrCreate(['user_id' => Auth::id()]);
    return view('user_profile.edit', compact('profile'));
}

    public function update(Request $request, $id)
    {
        // Валидация входных данных
        $request->validate([
            'filename' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required|string',
            'nickname' => 'nullable|string',
            'gender' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'languages' => 'nullable|string',
            'timezone' => 'nullable|string',
            'education' => 'nullable|string',
            'specialization' => 'nullable|string',
            'resume' => 'nullable|string',
            'portfolio' => 'nullable|string',

            // 'wallet_address' => 'nullable|string',
            // 'rating' => 'nullable|numeric',
            // 'trust_level' => 'nullable|numeric',
            // 'sbt_tokens' => 'nullable|integer',
            // 'tasks_completed' => 'nullable|integer',
            // 'tasks_failed' => 'nullable|integer',
            // 'recommendations' => 'nullable|json',
            // 'activity_log' => 'nullable|json',
            // 'achievements' => 'nullable|json',
        ]);        

        

        $profile = UserProfile::firstOrCreate(['user_id' => Auth::id()]);
        // $profile->update($data);

        // Если файл был загружен, загружаем на IPFS и сохраняем URL
        if ($request->hasFile('filename')) {
            $file = $request->file('filename');
            $avatarUrl = $this->uploadToIPFS($file); // загружаем на IPFS
            $profile->avatar_url = $avatarUrl; // обновляем URL аватара
        }

        // Обновление остальных данных профиля
        $profile->role = $request->input('role');
        $profile->nickname = $request->input('nickname');
        $profile->gender = $request->input('gender');
        $profile->birth_date = $request->input('birth_date');
        $profile->languages = $request->input('languages');
        $profile->timezone = $request->input('timezone');
        $profile->education = $request->input('education');
        $profile->specialization = $request->input('specialization');
        $profile->resume = $request->input('resume');
        $profile->portfolio = $request->input('portfolio');

        // Сохраняем изменения
        $profile->save();

        // Перенаправление с сообщением
        return redirect()->route('user_profile.edit', $profile->id)
                         ->with('info', 'Профиль успешно обновлен!');
    }

    // Метод для загрузки файла на IPFS
    private function uploadToIPFS($file)
    {
        $client = new Client([
            'base_uri' => 'https://daodes.space'
        ]);

        // Загружаем файл на IPFS
        $response = $client->request('POST', '/api/v0/add', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName()
                ]
            ]
        ]);

        // Получаем URL-адрес файла на IPFS
        $data = json_decode($response->getBody()->getContents(), true);
        return 'https://daodes.space/ipfs/' . $data['Hash'];
    }
}
