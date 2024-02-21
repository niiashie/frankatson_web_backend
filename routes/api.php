<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register',[Controller::class,'register']);
Route::post('/auth/login',[Controller::class,'login']);

Route::get('/news',[Controller::class,'getNews']);
Route::middleware('auth:sanctum')->post('/news',[Controller::class,'createNews']);
Route::middleware('auth:sanctum')->delete('/deleteNews/{id}',[Controller::class,'deleteNews']);
Route::middleware('auth:sanctum')->post('/gallery',[Controller::class,'addGallery']);
Route::get('/gallery',[Controller::class,'getGallery']);
Route::middleware('auth:sanctum')->delete('/deleteGallery/{id}',[Controller::class,'deleteGallery']);
Route::get('/blogCategories',[Controller::class,'getBlogCategories']);
Route::middleware('auth:sanctum')->post('/blogCategories',[Controller::class,'addBlogCategory']);
