<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    NewsController,
    OffersController,
    SeedController,
    KeywordResetPasswordController,
    WalletController,
    VoteController,
    CommentController,
    SpamController,
    DiscussionController,
    TasksController,
    CategoryTasksController,
    BidController,
    HomeController,
    TeamController,
    WpController,
    UserProfileController,
    UploadController,
    ChatController,
    LanguageController,
    CaptchaController,
    NotificationController,
    CookieConsentController,
    MailerAdminController,
    MailerTrackController,
    MailerClickController,
    MailTemplateController,

};
use App\Http\Middleware\SetLocale;
// use App\Services\IpStackService;

// Route::get('/test-ipstack', function () {
//     $ipstack = app('ipstack');
//     $location = $ipstack->getLocation(request()->ip());

//     return [
//         'ip' => request()->ip(),
//         'country' => $location['country_name'],
//         'city' => $location['city'],
//         'latitude' => $location['latitude'],
//         'longitude' => $location['longitude'],
//     ];
// });

//Мониторинг работы

Route::get('/health', function () {
    return response('OK', 200)->header('Content-Type', 'text/plain');
});

//почтовая рассылка
Route::middleware(['auth', 'admin'])
    ->prefix('admin/mailer')
    ->name('mailer.')
    ->group(function () 
    {

        Route::get('/', [MailerAdminController::class, 'dashboard'])->name('dashboard');

        // Маршруты для управления шаблонами писем
        Route::prefix('templates')->name('templates.')->group(function () {
            Route::get('/', [MailTemplateController::class, 'index'])->name('index');
            Route::get('/create', [MailTemplateController::class, 'create'])->name('create');
            Route::post('/', [MailTemplateController::class, 'store'])->name('store');
            Route::get('/{template}/edit', [MailTemplateController::class, 'edit'])->name('edit');
            Route::put('/{template}', [MailTemplateController::class, 'update'])->name('update');
            Route::delete('/{template}', [MailTemplateController::class, 'destroy'])->name('destroy');
            Route::get('/{template}', [MailTemplateController::class, 'show'])->name('show'); // ← вот этот добавлен
        });

        // Контакты
        Route::get('/recipients', [MailerAdminController::class, 'recipientsIndex'])->name('recipients.index');
        Route::get('/recipients/create', [MailerAdminController::class, 'recipientsCreate'])->name('recipients.create');
        Route::post('/recipients', [MailerAdminController::class, 'recipientsStore'])->name('recipients.store');
        Route::get('/recipients/{recipient}/edit', [MailerAdminController::class, 'recipientsEdit'])->name('recipients.edit');
        Route::put('/recipients/{recipient}', [MailerAdminController::class, 'recipientsUpdate'])->name('recipients.update');
        Route::delete('/recipients/{recipient}', [MailerAdminController::class, 'recipientsDestroy'])->name('recipients.destroy');
        Route::get('/recipients/import', [MailerAdminController::class, 'recipientsImportForm'])->name('recipients.import.form');
        Route::post('/recipients/import', [MailerAdminController::class, 'recipientsImport'])->name('recipients.import');

        // Списки контактов
        Route::get('/lists', [MailerAdminController::class, 'listsIndex'])->name('lists.index');
        Route::get('/lists/create', [MailerAdminController::class, 'listsCreate'])->name('lists.create');
        Route::post('/lists', [MailerAdminController::class, 'listsStore'])->name('lists.store');
        Route::get('/lists/{list}/edit', [MailerAdminController::class, 'listsEdit'])->name('lists.edit');
        Route::put('/lists/{list}', [MailerAdminController::class, 'listsUpdate'])->name('lists.update');
        Route::delete('/lists/{list}', [MailerAdminController::class, 'listsDestroy'])->name('lists.destroy');

        // Рассылки
        Route::get('/send', [MailerAdminController::class, 'sendForm'])->name('send.form');
        Route::post('/send', [MailerAdminController::class, 'send'])->name('send');
        Route::get('/history', [MailerAdminController::class, 'history'])->name('history');

        // Трекинг открытия писем
        Route::get('/track/{logId}', [MailerAdminController::class, 'trackEmail'])->name('track');

        //Статистика рассылок
        Route::get('/mailer/track/{logId}', [\App\Http\Controllers\MailerTrackController::class, 'track'])
    ->name('mailer.track');

    });

    //Отслеживаем прочтение рассылки
    Route::get('/mailer/track/{logId}', [MailerTrackController::class, 'track'])
    ->name('mailer.track');
    Route::get('/mailer/c/{logId}', [MailerClickController::class, 'redirect'])
    ->name('mailer.click');

// Cookie consent routes
Route::post('/cookies/accept', [CookieConsentController::class, 'accept'])->name('cookies.accept');
Route::post('/cookies/reject', [CookieConsentController::class, 'reject'])->name('cookies.reject');

// Localized cookie policy routes
Route::get('/cookie-policy', [CookieConsentController::class, 'policy'])
    ->name('cookie.policy');

Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);

Route::get('/captcha', [CaptchaController::class, 'show'])->name('captcha.show')->withoutMiddleware([SetLocale::class]);
Route::post('/captcha', [CaptchaController::class, 'verify'])->name('captcha.verify')->withoutMiddleware([SetLocale::class]);

// Маршрут для смены языка
Route::get('/language/{locale}', [LanguageController::class, 'change'])->name('language.change');

Route::middleware('auth')->group(function () {
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/create', [ChatController::class, 'create'])->name('chats.create');
    Route::post('/chats', [ChatController::class, 'store'])->name('chats.store');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
    
    Route::get('/chats/{chat}/messages', [ChatController::class, 'getMessages'])->name('messages.get');
    Route::post('/chats/{chat}/messages', [ChatController::class, 'sendMessage'])->name('messages.send');

    Route::get('/notifications', [ChatController::class, 'notifications'])->name('chats.notifications');
    Route::post('/notifications/{notification}/mark-as-read', [ChatController::class, 'markAsRead'])->name('notifications.markAsRead');
});
Route::post('/chats/create-with-user/{userId}', [ChatController::class, 'createWithUser'])
    ->name('chats.createWithUser');

Route::post('/chats/create-or-open/{userId}', [ChatController::class, 'createOrOpen'])
    ->name('chats.createOrOpen');

// Главная страница
Route::view('/', 'home')->name('home');
Route::get('home', [HomeController::class, 'home'])->name('home.alt');

// Маршрут с дополнительными параметрами
Route::get('good/{post}/{id}/{action}', [HomeController::class, 'good'])->name('good');

// team
Route::get('team', [TeamController::class, 'team'])->name('team');

Route::get('/white_paper', [WpController::class, 'whitepaper'])->name('white_paper');

//Route::get('/category/{post}/{id}', [CategoryController::class, 'categorySort'])->name('category.sort');
//Route::get('/page/{post}/{id}', [PageController::class, 'page_sort'])->name('page.sort');

Route::get('/news/create', [NewsController::class, 'create'])->name('news.create')->middleware('auth');
// Управление новостями
Route::prefix('news')->group(function () {
    // Маршруты для всех пользователей
    Route::get('/', [NewsController::class, 'list'])->name('news.index');
    Route::get('/{id}', [NewsController::class, 'show'])->name('news.show'); // Просмотр полной новости

    // Только авторизованные пользователи могут добавлять, редактировать и удалять новости
    Route::middleware('auth')->group(function () {
        //Route::get('/add', [NewsController::class, 'add'])->name('news.add');
        Route::post('/', [NewsController::class, 'store'])->name('news.store');

        Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::put('/{id}', [NewsController::class, 'update'])->name('news.update');
        Route::delete('/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
    });
});

// Управление категориями новостей
Route::prefix('newscategories')->name('newscategories.')->middleware('auth')->group(function () {
    Route::get('/', [NewsController::class, 'categoryIndex'])->name('index');
    Route::get('/create', [NewsController::class, 'categoryCreate'])->name('create');
    Route::post('/', [NewsController::class, 'categoryStore'])->name('categoryStore');
    Route::get('/{id}/edit', [NewsController::class, 'categoryEdit'])->name('edit');
    Route::put('/{id}', [NewsController::class, 'categoryUpdate'])->name('update');
    Route::delete('/{id}', [NewsController::class, 'categoryDestroy'])->name('destroy');
});


// Управление предложениями
Route::get('offers/create', [OffersController::class, 'create'])->name('offers.create')->middleware('auth');
Route::post('offers/store', [OffersController::class, 'store'])->name('offers.store')->middleware('auth');

Route::prefix('offers')->group(function () {
    // Маршруты для всех пользователей
    Route::get('/', [OffersController::class, 'index'])->name('offers.index');
    Route::get('/{id}', [OffersController::class, 'show'])->name('offers.show'); // Просмотр 

    // Только авторизованные пользователи могут добавлять, редактировать и удалять новости
    Route::middleware('auth')->group(function () {
        
       // Route::get('/create', [OffersController::class, 'create'])->name('offers.create');
       // Route::post('/store', [OffersController::class, 'store'])->name('offers.store');
        Route::get('/{id}/edit', [OffersController::class, 'edit'])->name('offers.edit');
        Route::put('/{id}', [OffersController::class, 'update'])->name('offers.update');
        Route::delete('/{id}', [OffersController::class, 'destroy'])->name('offers.destroy');
    });
});

// Управление категориями предложений
Route::middleware('auth')->prefix('offerscategories')->name('offerscategories.')->group(function () {
    Route::get('/', [OffersController::class, 'categoryIndex'])->name('index');
    Route::get('/create', [OffersController::class, 'categoryCreate'])->name('create');
    Route::post('/', [OffersController::class, 'categoryStore'])->name('categoryStore');

    // Route::post('/', [OffersController::class, 'categoryStoreState'])->name('categoryStoreState');
    
    Route::get('/{id}/edit', [OffersController::class, 'categoryEdit'])->name('edit');
    Route::put('/{id}', [OffersController::class, 'categoryUpdate'])->name('update');
    
    Route::delete('/{id}', [OffersController::class, 'categoryDestroy'])->name('destroy');
});





// Комментарии
Route::post('/commentsoffers', [CommentController::class, 'offers'])->name('comments.offers');

Route::post('/commentsnews', [CommentController::class, 'news'])->name('comments.news');

// Спам, обсуждения, голосования
Route::post('/spam', [SpamController::class, 'store'])->name('spam.store');
Route::post('/discussion', [DiscussionController::class, 'store'])->name('discussion.store');
Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');

// Seed фраза
Route::middleware(['guest'])->prefix('seed')->name('seed.')->group(function () {
    Route::get('/', [SeedController::class, 'index'])->name('index'); // Было 'seed' → стало 'index'
    Route::post('/save', [SeedController::class, 'saveSeed'])->name('save');
});

Route::get('/phpinfo', function () {
    phpinfo();
});

// Сброс пароля по ключевому слову
Route::middleware('guest')->prefix('password')->name('password.')->group(function () {
    Route::get('/forgot', [KeywordResetPasswordController::class, 'showKeywordForm'])->name('keyword');
    Route::post('/submit', [KeywordResetPasswordController::class, 'submitKeyword'])->name('submit');
    Route::get('/reset', [KeywordResetPasswordController::class, 'showResetForm'])->name('reset');
    Route::put('/update', [KeywordResetPasswordController::class, 'updatePassword'])->name('update');
});

// Кошелёк и переводы
Route::middleware('auth')->prefix('wallet')->name('wallet.')->group(function () {
    Route::get('/', [WalletController::class, 'wallet'])->name('index');
    Route::get('/transfer', [WalletController::class, 'showTransferForm'])->name('transfer.form'); // GET форма
    Route::post('/transfer', [WalletController::class, 'transfer'])->name('transfer.submit'); // POST обработка
    Route::get('/history', [WalletController::class, 'history'])->name('history');
});

/// Маршруты задач
Route::middleware('auth')->prefix('tasks')->name('tasks.')->group(function () {
    
    // Просмотр задач
    Route::get('/', [TasksController::class, 'list'])->name('list');
    Route::get('/{task}', [TasksController::class, 'show'])->name('show');
    
    // Создание задачи
    Route::get('/create', [TasksController::class, 'create'])->name('create');
    Route::post('/store', [TasksController::class, 'store'])->name('store');
    
    // Редактирование задачи
    Route::get('/{task}/edit', [TasksController::class, 'edit'])->name('edit');
    Route::put('/{task}', [TasksController::class, 'update'])->name('update');
    Route::delete('/{task}', [TasksController::class, 'destroy'])->name('destroy');
    
    // Взаимодействие с задачей
    Route::post('/{task}/bid', [TasksController::class, 'bid'])->name('bid');
    Route::post('/{task}/like', [TasksController::class, 'like'])->name('like');
    Route::post('/{task}/dislike', [TasksController::class, 'dislike'])->name('dislike');
    
    // Работа с задачей
    Route::post('/{task}/start-work', [TasksController::class, 'startWork'])->name('start_work');
    Route::post('/{task}/complete', [TasksController::class, 'complete'])->name('complete');
    Route::post('/{task}/freelancer-complete', [TasksController::class, 'freelancerComplete'])->name('freelancerComplete');
    Route::post('/{task}/accept', [TasksController::class, 'acceptTask'])->name('accept');
    Route::post('/{task}/revision', [TasksController::class, 'requestRevision'])->name('revision');
    Route::post('/{task}/continue', [TasksController::class, 'continueTask'])->name('continue');
    Route::post('/{task}/fail', [TasksController::class, 'fail'])->name('fail');
    
    // Оценки и оплата
    Route::post('/{task}/rate', [TasksController::class, 'rate'])->name('rate');
    Route::post('/{task}/accept-bid/{bid}', [TasksController::class, 'acceptBid'])->name('accept-bid');
});

// Альтернативный маршрут для создания задачи (если нужен отдельный URL)
Route::get('/addtask', [TasksController::class, 'create'])
    ->name('addtask')
    ->middleware('auth');




// Принятие предложений
Route::post('/bids/{bid}/accept', [BidController::class, 'accept'])->name('bids.accept');

// Категории задач
Route::middleware('auth')->prefix('taskscategories')->name('taskscategories.')->group(function () {
    Route::get('/', [CategoryTasksController::class, 'index'])->name('index');
    Route::get('/create', [CategoryTasksController::class, 'create'])->name('create');
    Route::post('/', [CategoryTasksController::class, 'store'])->name('store');
    Route::get('/{taskCategory}/edit', [CategoryTasksController::class, 'edit'])->name('edit');
    Route::put('/{taskCategory}', [CategoryTasksController::class, 'update'])->name('update');
    Route::delete('/{taskCategory}', [CategoryTasksController::class, 'destroy'])->name('destroy');
});

/// Защищённые маршруты профиля
Route::middleware(['auth'])->prefix('profile')->name('user_profile.')->group(function () {

    // Просмотр и редактирование расширенного профиля
    Route::get('/', [UserProfileController::class, 'index'])->name('index'); // если есть профиль
    Route::get('/{id}', [UserProfileController::class, 'index'])->whereNumber('id')->name('show'); // чужой профиль

    Route::get('/create', [UserProfileController::class, 'create'])->name('create');
    Route::post('/store', [UserProfileController::class, 'store'])->name('store');

    Route::get('/edit', [UserProfileController::class, 'edit'])->name('edit');    
    Route::put('/update', [UserProfileController::class, 'updateFullProfile'])->name('update');


    // Удаление аккаунта
    Route::delete('/', [UserProfileController::class, 'destroy'])->name('destroy');
});



// Загрузка изображений для CKEditor
Route::post('/upload-image', [UploadController::class, 'uploadImage'])->name('upload.image');

// Админка
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

//Route::post('/telegram/webhook', [TelegramController::class, 'webhook']);

// Route::domain('deschat.daodes.space')->group(function () {
//     Route::get('/', function () {
//         return redirect('/');
//     });
// });

require __DIR__ . '/auth.php';
