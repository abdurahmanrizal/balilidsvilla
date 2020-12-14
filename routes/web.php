<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VillaController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\DescriptionController;
use App\Http\Controllers\SocialMediaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route untuk client
Route::get('/',[ClientController::class,'index'])->name('landing-page');
Route::get('blog/post/{id}',[BlogController::class,'post'])->name('blog.post');
Route::get('villa/data/{id}',[VillaController::class,'postVilla'])->name('villa.post');
Route::get('activity/data/{id}',[ActivityController::class,'postActivity'])->name('activity.post');
Route::get('package/data/{id}',[PackageController::class,'postPackage'])->name('package.post');
Route::get('gallery/more',[GalleryController::class,'moreGallery'])->name('gallery.more');


Auth::routes();

// ROUTE ADMIN
Route::group(['middleware' => ['auth', 'role']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::group(['prefix' => 'villa', 'as' => 'villa.'], function(){
        Route::get('/',[VillaController::class,'index'])->name('index');
        Route::post('/store',[VillaController::class,'store'])->name('store');
        Route::post('/edit',[VillaController::class,'show'])->name('show');
        Route::post('/update',[VillaController::class,'update'])->name('update');
        Route::post('/delete',[VillaController::class,'destroy'])->name('destroy');
        Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function(){
            Route::get('/',[VillaController::class,'gallery'])->name('index');
            Route::post('/store',[VillaController::class,'galleryStore'])->name('store');
            Route::post('/edit',[VillaController::class,'galleryEdit'])->name('show');
            Route::post('/update',[VillaController::class,'galleryUpdate'])->name('update');
            Route::post('/delete',[VillaController::class,'galleryDestroy'])->name('destroy');
        });
    });
    Route::group(['prefix' => 'activity', 'as' => 'activity.'], function() {
        Route::get('/',[ActivityController::class,'index'])->name('index');
        Route::post('/store',[ActivityController::class,'store'])->name('store');
        Route::post('/edit',[ActivityController::class,'show'])->name('show');
        Route::post('/update',[ActivityController::class,'update'])->name('update');
        Route::post('/delete',[ActivityController::class,'destroy'])->name('destroy');
        Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function(){
            Route::get('/',[ActivityController::class,'gallery'])->name('index');
            Route::post('/store',[ActivityController::class,'galleryStore'])->name('store');
            Route::post('/edit',[ActivityController::class,'galleryEdit'])->name('show');
            Route::post('/update',[ActivityController::class,'galleryUpdate'])->name('update');
            Route::post('/delete',[ActivityController::class,'galleryDestroy'])->name('destroy');
        });
    });
    Route::group(['prefix' => 'package','as' => 'package.'], function() {
        Route::get('/',[PackageController::class,'index'])->name('index');
        Route::post('/store',[PackageController::class,'store'])->name('store');
        Route::post('/edit',[PackageController::class,'show'])->name('show');
        Route::post('/update',[PackageController::class,'update'])->name('update');
        Route::post('/delete',[PackageController::class,'destroy'])->name('destroy');
        Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function(){
            Route::get('/',[PackageController::class,'gallery'])->name('index');
            Route::post('/store',[PackageController::class,'galleryStore'])->name('store');
            Route::post('/edit',[PackageController::class,'galleryEdit'])->name('show');
            Route::post('/update',[PackageController::class,'galleryUpdate'])->name('update');
            Route::post('/delete',[PackageController::class,'galleryDestroy'])->name('destroy');
        });
    });
    Route::group(['prefix' => 'blog', 'as' => 'blog.'],function() {
        Route::get('/', [BlogController::class,'index'])->name('index');
        Route::post('/store', [BlogController::class,'store'])->name('store');
        Route::post('/edit', [BlogController::class,'show'])->name('show');
        Route::post('/update', [BlogController::class,'update'])->name('update');
        Route::post('/delete', [BlogController::class,'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function() {
        Route::get('/',[GalleryController::class,'index'])->name('index');
        Route::post('/store',[GalleryController::class,'store'])->name('store');
        Route::post('/show',[GalleryController::class,'show'])->name('show');
        Route::post('/edit',[GalleryController::class,'edit'])->name('edit');
        Route::post('/destroy',[GalleryController::class,'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'description', 'as' => 'description.'], function() {
        Route::get('/',[DescriptionController::class,'index'])->name('index');
        Route::post('/store',[DescriptionController::class,'store'])->name('store');
        Route::post('/show',[DescriptionController::class,'show'])->name('show');
        Route::post('/edit',[DescriptionController::class,'edit'])->name('edit');
        Route::post('/destroy',[DescriptionController::class,'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'banner', 'as' => 'banner.'], function() {
        Route::get('/',[BannerController::class,'index'])->name('index');
        Route::post('/store',[BannerController::class,'store'])->name('store');
        Route::post('/show',[BannerController::class,'show'])->name('show');
        Route::post('/edit',[BannerController::class,'edit'])->name('edit');
        Route::post('/destroy',[BannerController::class,'destroy'])->name('destroy');
    });
    Route::group(['prefix' => 'social-media', 'as' => 'social-media.'], function() {
        Route::get('/',[SocialMediaController::class,'index'])->name('index');
        Route::post('/store',[SocialMediaController::class,'store'])->name('store');
        Route::post('/show',[SocialMediaController::class,'show'])->name('show');
        Route::post('/edit',[SocialMediaController::class,'edit'])->name('edit');
        Route::post('/destroy',[SocialMediaController::class,'destroy'])->name('destroy');
    });
});
// END ROUTE ADMIN
