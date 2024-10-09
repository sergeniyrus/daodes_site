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

//биржа заданий

Route::get('/tasks', [TaskController::class, 'list'])->name('tasks.list');
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create'); // Для отображения формы создания
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store'); // Для создания задачи
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
Route::post('/tasks/{task}/bid', [TaskController::class, 'bid'])->name('tasks.bid');


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
