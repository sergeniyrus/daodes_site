<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\SeedController;
use App\Http\Controllers\Auth\KeywordResetPasswordController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SpamController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TasksCategoryController;
use App\Http\Controllers\BidController;

//стандартная главная 
Route::get('/', function () {
    return view('home');
});
Route::get('good/{post}/{id}/{action}', 'App\Http\Controllers\HomeController@good') ->name(name:'good');

//home c перебросом на стандартную главную
Route::get('home', 'App\Http\Controllers\HomeController@home')->name(name:'home');

Route::get('roadmap', 'App\Http\Controllers\RoadMapController@roadmap')->name(name:'roadmap');

//новости коротким списком
Route::get('/news', 'App\Http\Controllers\NewsController@news')->name(name:'news');

//Предложения открывающимся списком
Route::get('/dao', 'App\Http\Controllers\DController@offers')->name(name:'dao');

//Вывод с группировкой по категориям
Route::get('/category/{post}/{id}/', 'App\Http\Controllers\CategoryController@category_sort')->name(name:'category');

//один шаблон под новости и предложения
Route::get('/page/{post}/{id}/', 'App\Http\Controllers\PageController@page_sort')->name(name:'page');

//спам обсуждения голосование и тд
Route::post('/spam', [SpamController::class, 'store'])->name('spam.store');
Route::post('/discussion', [DiscussionController::class, 'store'])->name('discussion.store');
// маршрут для обработки голосования
Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');

//добавление изменение удаление новости
Route::middleware('auth')->group(function () {
    Route::get('/add_news', [NewsController::class, 'add'])->name('news._adds');
    Route::post('/add_news', [NewsController::class, 'create'])->middleware(['auth', 'verified'])->name('add_news');

    Route::get('/edit_news/{id}', [NewsController::class, 'edit'])->name('news._edit');
    Route::post('/edit_news/{id}', [NewsController::class, 'update'])->middleware(['auth', 'verified'])->name('edit_news');
});

//добавление изменение предложения
Route::middleware('auth')->group(function () {
    Route::get('/add_offers', [OffersController::class, 'add'])->name('offers._add');
    Route::post('/add_offers', [OffersController::class, 'create'])->middleware(['auth', 'verified'])->name('add_offers');

    Route::get('/edit_offers/{id}', [OffersController::class, 'edit'])->name('offers._edit');
    Route::post('/edit_offers/{id}', [OffersController::class, 'update'])->middleware(['auth', 'verified'])->name('edit_offers');
});


//комменты предложений
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');



// // переход за сид-фразой после регистрации
Route::get('/seed', [SeedController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('seed');
// // сохранятель сид-фразы
Route::post('/seed/save', [SeedController::class, 'saveSeed'])
->name('saveSeed');

//Сброс пароля по ключевому слову
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [KeywordResetPasswordController::class, 'showKeywordForm'])->name('custom.password.keyword');
    Route::post('/submit-keyword', [KeywordResetPasswordController::class, 'submitKeyword'])->name('custom.password.submit');
    Route::get('/reset-password', [KeywordResetPasswordController::class, 'showResetForm'])->name('custom.password.reset');
    Route::put('/update-password', [KeywordResetPasswordController::class, 'updatePassword'])->name('custom.password.update');
});

//кошельки и переводы
Route::middleware('auth')->group(function () {
    Route::get('/wallet', [WalletController::class, 'wallet'])->name('wallet.wallet');
    Route::get('/wallet/transfer', [WalletController::class, 'showTransferForm'])->name('wallet.showTransferForm');
    Route::post('/wallet/transfer', [WalletController::class, 'transfer'])->name('wallet.transfer');
    Route::get('/wallet/history', [WalletController::class, 'history'])->name('wallet.history');
});

// Группируем маршруты, доступные только авторизованным пользователям
Route::middleware(['auth'])->group(function () {
    // Маршруты для задач
    Route::get('/tasks', [TaskController::class, 'list'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create'); 
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store'); 
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{task}/bid', [TaskController::class, 'bid'])->name('tasks.bid');
    
    // Лайки, дизлайки, редактирование, удаление задач
    Route::post('/tasks/{task}/like', [TaskController::class, 'like'])->name('tasks.like');
    Route::post('/tasks/{task}/dislike', [TaskController::class, 'dislike'])->name('tasks.dislike');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit'); // Редактирование
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update'); // Обновление
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy'); // Удаление

    // Завершение задания и оценка
    Route::post('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('tasks/{task}/rate', [TaskController::class, 'rate'])->name('tasks.rate');
    Route::post('/tasks/{task}/start-work', [TaskController::class, 'startWork'])->name('tasks.start_work');
    Route::post('/tasks/{task}/fail', [TaskController::class, 'fail'])->name('tasks.fail');

    // Принятие предложения
    Route::post('bids/{bid}/accept', [BidController::class, 'accept'])->name('bids.accept');

    // Маршруты для категорий задач
    Route::prefix('categories')->group(function () {
        Route::get('/', [TasksCategoryController::class, 'index'])->name('task_categories.index');
        Route::get('/create', [TasksCategoryController::class, 'create'])->name('task_categories.create');
        Route::post('/', [TasksCategoryController::class, 'store'])->name('task_categories.store');
        Route::get('/{taskCategory}/edit', [TasksCategoryController::class, 'edit'])->name('task_categories.edit');
        Route::put('/{taskCategory}', [TasksCategoryController::class, 'update'])->name('task_categories.update');
        Route::delete('/{taskCategory}', [TasksCategoryController::class, 'destroy'])->name('task_categories.destroy');
    });
});

// Маршрут для гостевых пользователей (например, список задач может быть доступен для просмотра без авторизации)
Route::get('/tasks', [TaskController::class, 'list'])->name('tasks.index');


//админка после входа
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
