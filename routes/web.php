<?php

// namespace App\Routes;

use App\Http\Controllers\ArticleViewController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', [ArticleViewController::class, 'index']);
Route::get('/login', function () {
    return view('welcome');
});
