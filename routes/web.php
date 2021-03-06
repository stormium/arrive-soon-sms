<?php
use Illuminate\Support\Facades\Input;
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

Route::get('/', 'EventRuleController@index')->name('index1');

Route::get('/check', 'EventRuleController@index2')->name('index2');

Route::get('/proxy.php', function () {
    header('Content-type: application/json; charset=utf-8');
    $url=Input::get('url');
    $trackId=Input::get('trackId');
    $selectedStopId=Input::get('stopId');
    $fullUrl=$url . "&trackId=" . $trackId . "&stopId=" . $selectedStopId;
    $json=file_get_contents($fullUrl);
    echo $json;
});

Route::get('/editRule/proxy.php', function () {
    header('Content-type: application/json; charset=utf-8');
    $url=Input::get('url');
    $trackId=Input::get('trackId');
    $selectedStopId=Input::get('stopId');
    $fullUrl=$url . "&trackId=" . $trackId . "&stopId=" . $selectedStopId;
    $json=file_get_contents($fullUrl);
    echo $json;
});

Route::post('/add_rule', 'EventRuleController@store')->name('rule_store');
Route::get('/editRule/{id}', 'EventRuleController@edit')->name('editRule');
Route::post('/updateRule/{id}', 'EventRuleController@update')->name('updateRule');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/removeRule/{id}', 'EventRuleController@destroy')->name('removeRule');
