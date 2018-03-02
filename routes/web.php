<?php

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
    return view('index1');
});

Route::get('/proxy.php', function () {
    header('Content-type: application/json');
    $url=$_GET['url'];
    $json=file_get_contents($url);
    echo $json;
});
