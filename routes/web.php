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
    TaskController,
    TasksCategoryController,
    BidController,
    HomeController,
    TeamController,
    WpController,
    UserProfileController,
    UploadController,
    ChatController,
    LanguageController,
    CaptchaController
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


Route::get('/captcha', [CaptchaController::class, 'show'])->name('captcha.show')->withoutMiddleware([SetLocale::class]);
Route::post('/captcha', [CaptchaController::class, 'verify'])->name('captcha.verify')->withoutMiddleware([SetLocale::class]);

// Маршрут для смены языка
Route::get('/language/{locale}', [LanguageController::class, 'change'])->name('language.change');

Route::middleware('auth')->group(function () {
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/create', [ChatController::class, 'create'])->name('chats.create');
    Route::post('/chats', [ChatController::class, 'store'])->name('chats.store');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
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

Route::get('/news/add', [NewsController::class, 'add'])->name('news.add')->middleware('auth');
// Управление новостями
Route::prefix('news')->group(function () {
    // Маршруты для всех пользователей
    Route::get('/', [NewsController::class, 'list'])->name('news.index');
    Route::get('/{id}', [NewsController::class, 'show'])->name('news.show'); // Просмотр полной новости

    // Только авторизованные пользователи могут добавлять, редактировать и удалять новости
    Route::middleware('auth')->group(function () {
        //Route::get('/add', [NewsController::class, 'add'])->name('news.add');
        Route::post('/create', [NewsController::class, 'create'])->name('news.create');

        Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::put('/{id}', [NewsController::class, 'update'])->name('news.update');
        Route::delete('/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
    });
});

// Управление категориями новостей
Route::prefix('newscategories')->name('newscategories.')->middleware('auth')->group(function () {
    Route::get('/', [NewsController::class, 'categoryIndex'])->name('index');
    Route::get('/create', [NewsController::class, 'categoryCreate'])->name('create');
    Route::post('/', [NewsController::class, 'categoryStore'])->name('store');
    Route::get('/{id}/edit', [NewsController::class, 'categoryEdit'])->name('edit');
    Route::put('/{id}', [NewsController::class, 'categoryUpdate'])->name('update');
    Route::delete('/{id}', [NewsController::class, 'categoryDestroy'])->name('destroy');
});

// Управление предложениями
Route::get('/offers/add', [OffersController::class, 'add'])->name('offers.add')->middleware('auth');
// Управление новостями
Route::prefix('offers')->group(function () {
    // Маршруты для всех пользователей
    Route::get('/', [OffersController::class, 'list'])->name('offers.index');
    Route::get('/{id}', [OffersController::class, 'show'])->name('offers.show'); // Просмотр полной новости

    // Только авторизованные пользователи могут добавлять, редактировать и удалять новости
    Route::middleware('auth')->group(function () {
        //Route::get('/add', [OffersController::class, 'add'])->name('offers.add');
        Route::post('/create', [OffersController::class, 'create'])->name('offers.create');

        Route::get('/{id}/edit', [OffersController::class, 'edit'])->name('offers.edit');
        Route::put('/{id}', [OffersController::class, 'update'])->name('offers.update');
        Route::delete('/{id}', [OffersController::class, 'destroy'])->name('offers.destroy');
    });
});

// Управление категориями предложений
Route::middleware('auth')->prefix('offerscategories')->name('offerscategories.')->group(function () {
    Route::get('/', [OffersController::class, 'categoryIndex'])->name('index');
    Route::get('/create', [OffersController::class, 'categoryCreate'])->name('create');
    Route::post('/', [OffersController::class, 'categoryStore'])->name('store');
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
Route::middleware(['auth'])->prefix('seed')->name('seed.')->group(function () {
    Route::get('/', [SeedController::class, 'index'])->name('index');
    Route::post('/save', [SeedController::class, 'saveSeed'])->name('save');
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
    Route::get('/transfer', [WalletController::class, 'showTransferForm'])->name('transfer.form');
    Route::post('/transfer', [WalletController::class, 'transfer'])->name('transfer');
    Route::get('/history', [WalletController::class, 'history'])->name('history');
});

// Задачи
Route::get('/tasks', [TaskController::class, 'list'])->name('tasks.index');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

Route::get('/addtask', [TaskController::class, 'create'])
    ->name('addtask')
    ->middleware('auth');
Route::post('/tasks/create', [TaskController::class, 'store'])
    ->name('tasks.create')
    ->middleware('auth');

Route::post('/tasks/{task}/accept-bid/{bid}', [TaskController::class, 'acceptBid'])
    ->name('tasks.accept-bid')
    ->middleware('auth');

Route::post('/tasks/{task}/continue', [TaskController::class, 'continueTask'])
    ->name('tasks.continue')
    ->middleware('auth');

Route::post('/tasks/{task}/freelancerComplete', [TaskController::class, 'freelancerComplete'])
    ->name('tasks.freelancerComplete')
    ->middleware('auth');

Route::post('/tasks/{task}/fail', [TaskController::class, 'fail'])
    ->name('tasks.fail')
    ->middleware('auth');

Route::post('/tasks/store', [TaskController::class, 'store'])
    ->name('tasks.store')
    ->middleware('auth');

Route::middleware('auth')->prefix('tasks')->name('tasks.')->group(function () {

    //ds-    Route::get('/create', [TaskController::class, 'create'])->name('create');

    Route::post('/{task}/bid', [TaskController::class, 'bid'])->name('bid');
    Route::post('/{task}/like', [TaskController::class, 'like'])->name('like');
    Route::post('/{task}/dislike', [TaskController::class, 'dislike'])->name('dislike');
    Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
    Route::put('/{task}', [TaskController::class, 'update'])->name('update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
    Route::post('/tasks/{task}/continue', [TaskController::class, 'continueTask'])->name('tasks.continue'); //ds+
    Route::post('/{task}/complete', [TaskController::class, 'complete'])->name('complete');
    Route::post('/{task}/rate', [TaskController::class, 'rate'])->name('rate');
    Route::post('/{task}/start-work', [TaskController::class, 'startWork'])->name('start_work');
    Route::post('/{task}/fail', [TaskController::class, 'fail'])->name('fail');

    Route::post('/tasks/{task}/accept', [TaskController::class, 'acceptTask'])->name('tasks.accept');
    Route::post('/tasks/{task}/revision', [TaskController::class, 'requestRevision'])->name('tasks.revision');
});

// Принятие предложений
Route::post('/bids/{bid}/accept', [BidController::class, 'accept'])->name('bids.accept');

// Категории задач
Route::middleware('auth')->prefix('taskscategories')->name('taskscategories.')->group(function () {
    Route::get('/', [TasksCategoryController::class, 'index'])->name('index');
    Route::get('/create', [TasksCategoryController::class, 'create'])->name('create');
    Route::post('/', [TasksCategoryController::class, 'store'])->name('store');
    Route::get('/{taskCategory}/edit', [TasksCategoryController::class, 'edit'])->name('edit');
    Route::put('/{taskCategory}', [TasksCategoryController::class, 'update'])->name('update');
    Route::delete('/{taskCategory}', [TasksCategoryController::class, 'destroy'])->name('destroy');
});

// Профиль пользователя
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

//Для подробной анкеты
Route::middleware(['auth'])->prefix('user-profile')->name('user_profile.')->group(function () {
    Route::get('/', [UserProfileController::class, 'index'])->name('index');
    Route::get('/create', [UserProfileController::class, 'create'])->name('create');
    Route::post('/store', [UserProfileController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [UserProfileController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [UserProfileController::class, 'update'])->name('update');

    Route::get('/{id?}', [UserProfileController::class, 'index'])->name('show');
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
