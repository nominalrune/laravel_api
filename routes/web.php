<?php

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

Route::get('/', function () {
    return ['APP_URL' => env('APP_URL', 'null'), 'FRONTEND_URL' => env('FRONTEND_URL', 'null')];
});
Route::prefix("doc")->group(function () {
    if (env('APP_ENV') != 'local') {
        return response(status:403);
    }
    Route::get('/', function () {
        $content = file_get_contents(base_path('doc/index.html'));
        $replace= [
            'href="' => 'href="doc/',
            'src="' => 'src="doc/',
        ];
        $content = str_replace(array_keys($replace), array_values($replace), $content);
        return response($content);
    });
    Route::get('/{page}', function ($page) {
        $content = file_get_contents(base_path("doc/$page"));
        return response($content);
    });
    Route::get('/assets/{file}', function ($file) {
        $content = file_get_contents(base_path("doc/assets/$file"));
        $mime = match(pathinfo($file, PATHINFO_EXTENSION)){
            'css' => 'text/css',
            'js' => 'text/javascript',
            // 'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'default' => mime_content_type(base_path("doc/assets/$file")),
        };
        return response($content)->header('Content-Type', $mime);
    });
});
require __DIR__ . '/auth.php';
