<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\ImportProductController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\CategoryController;

// Route::post('products/import', ImportProductController::class)->name('products.import');

// Route::post('uploads/process', [FileUploadController::class, 'process'])->name('uploads.process');

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





//добавление изменение новости
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

    Route::get('/edit_offers/{post}/{id}', [OffersController::class, 'edit'])->name('offers._edit');
    Route::post('/edit_offers', [OffersController::class, 'update'])->middleware(['auth', 'verified'])->name('edit_offers');
});


//добавление изменение категорий
Route::middleware('auth')->group(function () {
    Route::get('/add_cat/{post}', [CategoryController::class, 'add'])->name('category._add');
    Route::post('/add_cat', [CategoryController::class, 'create'])->middleware(['auth', 'verified'])->name('add_cat');

    Route::get('/edit_cat/{post}/{id}', [CategoryController::class, 'edit'])->name('category._edit');
    Route::post('/edit_cat', [CategoryController::class, 'update'])->middleware(['auth', 'verified'])->name('edit_cat');
});


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
