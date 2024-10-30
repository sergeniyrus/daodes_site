<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    NewsController,
    OffersController,
    SeedController,
    Auth\KeywordResetPasswordController,
    WalletController,
    VoteController,
    CommentController,
    SpamController,
    DiscussionController,
    TaskController,
    TasksCategoryController,
    BidController,
    HomeController,
    RoadMapController,
    DController,
    CategoryController,
    PageController
};

// Главная страница
Route::view('/', 'home')->name('home');
Route::get('home', [HomeController::class, 'home'])->name('home.alt');

// Маршрут с дополнительными параметрами
Route::get('good/{post}/{id}/{action}', [HomeController::class, 'good'])->name('good');

// Разделы
Route::get('roadmap', [RoadMapController::class, 'roadmap'])->name('roadmap');
Route::get('/news', [NewsController::class, 'news'])->name('news');
Route::get('/dao', [DController::class, 'offers'])->name('dao');
Route::get('/category/{post}/{id}', [CategoryController::class, 'category_sort'])->name('category.sort');
Route::get('/page/{post}/{id}', [PageController::class, 'page_sort'])->name('page.sort');

// Спам, обсуждения, голосования
Route::post('/spam', [SpamController::class, 'store'])->name('spam.store');
Route::post('/discussion', [DiscussionController::class, 'store'])->name('discussion.store');
Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');

// Управление новостями

Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'news'])->name('news.index');
    Route::get('/add', [NewsController::class, 'add'])->name('news.add');
    Route::post('/create', [NewsController::class, 'create'])->name('news.create');

    // Маршруты для категорий новостей
    Route::get('/categories', [NewsController::class, 'categoryIndex'])->name('news.categories.index');
    Route::get('/categories/create', [NewsController::class, 'categoryCreate'])->name('news.categories.create');
    Route::post('/categories', [NewsController::class, 'categoryStore'])->name('news.categories.store');
    Route::get('/categories/{id}/edit', [NewsController::class, 'categoryEdit'])->name('news.categories.edit');
    Route::put('/categories/{id}', [NewsController::class, 'categoryUpdate'])->name('news.categories.update');
    Route::delete('/categories/{id}', [NewsController::class, 'categoryDestroy'])->name('news.categories.destroy');

    // Маршруты для редактирования и удаления новостей
    Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
});


// Управление предложениями
Route::middleware('auth')->prefix('offers')->group(function () {
    // Основные маршруты для предложений
    Route::get('/', [OffersController::class, 'index'])->name('offers.index');
    Route::get('/add', [OffersController::class, 'add'])->name('offers.add');
    Route::post('/create', [OffersController::class, 'create'])->name('offers.create')->middleware('verified');
    
    // Редактирование и удаление предложений
    Route::get('/{id}/edit', [OffersController::class, 'edit'])->name('offers.edit');
    Route::put('/{id}', [OffersController::class, 'update'])->name('offers.update')->middleware('verified');
    Route::delete('/{id}', [OffersController::class, 'destroy'])->name('offers.destroy');

    // Маршруты для категорий предложений
    Route::get('/categories', [OffersController::class, 'categoryIndex'])->name('offers.categories.index');
    Route::get('/categories/create', [OffersController::class, 'categoryCreate'])->name('offers.categories.create');
    Route::post('/categories', [OffersController::class, 'categoryStore'])->name('offers.categories.store');
    Route::get('/categories/{id}/edit', [OffersController::class, 'categoryEdit'])->name('offers.categories.edit');
    Route::put('/categories/{id}', [OffersController::class, 'categoryUpdate'])->name('offers.categories.update');
    Route::delete('/categories/{id}', [OffersController::class, 'categoryDestroy'])->name('offers.categories.destroy');
});


// Комментарии
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

// Seed фраза
Route::middleware(['auth', 'verified'])->prefix('seed')->name('seed.')->group(function () {
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
Route::middleware('auth')->prefix('tasks')->name('tasks.')->group(function () {
    Route::get('/', [TaskController::class, 'list'])->name('index');
    Route::get('/create', [TaskController::class, 'create'])->name('create');
    Route::post('/', [TaskController::class, 'store'])->name('store');
    Route::get('/{task}', [TaskController::class, 'show'])->name('show');
    Route::post('/{task}/bid', [TaskController::class, 'bid'])->name('bid');
    Route::post('/{task}/like', [TaskController::class, 'like'])->name('like');
    Route::post('/{task}/dislike', [TaskController::class, 'dislike'])->name('dislike');
    Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
    Route::put('/{task}', [TaskController::class, 'update'])->name('update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
    Route::post('/{task}/complete', [TaskController::class, 'complete'])->name('complete');
    Route::post('/{task}/freelancer-complete', [TaskController::class, 'freelancerComplete'])->name('freelancer-complete');
    Route::post('/{task}/rate', [TaskController::class, 'rate'])->name('rate');
    Route::post('/{task}/start-work', [TaskController::class, 'startWork'])->name('start_work');
    Route::post('/{task}/fail', [TaskController::class, 'fail'])->name('fail');
    Route::post('/{task}/continue', [TaskController::class, 'continueTask'])->name('continue');
});

// Принятие предложений
Route::post('/bids/{bid}/accept', [BidController::class, 'accept'])->name('bids.accept');

// Категории задач
Route::middleware('auth')->prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [TasksCategoryController::class, 'index'])->name('index');
    Route::get('/create', [TasksCategoryController::class, 'create'])->name('create');
    Route::post('/', [TasksCategoryController::class, 'store'])->name('store');
    Route::get('/{taskCategory}/edit', [TasksCategoryController::class, 'edit'])->name('edit');
    Route::put('/{taskCategory}', [TasksCategoryController::class, 'update'])->name('update');
    Route::delete('/{taskCategory}', [TasksCategoryController::class, 'destroy'])->name('destroy');
});




// Управление категориями предложений
Route::middleware('auth')->prefix('offers/categories')->name('offers.categories.')->group(function () {
    Route::get('/', [OffersController::class, 'categoryIndex'])->name('index'); // Список категорий
    Route::get('/create', [OffersController::class, 'categoryCreate'])->name('create'); // Создание категории
    Route::post('/', [OffersController::class, 'categoryStore'])->name('store'); // Сохранение категории
    Route::get('/{category}/edit', [OffersController::class, 'categoryEdit'])->name('edit'); // Редактирование категории
    Route::put('/{category}', [OffersController::class, 'categoryUpdate'])->name('update'); // Обновление категории
    Route::delete('/{category}', [OffersController::class, 'categoryDestroy'])->name('destroy'); // Удаление категории
});


// Профиль пользователя
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

// Админка
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

require __DIR__.'/auth.php';
