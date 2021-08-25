<?php

use Illuminate\Support\Facades\Route;

/** Define here your routes */

Route::get('trades', 'TradeController@index');
Route::post('trades', 'TradeController@store');
Route::get('trades/{trade}', 'TradeController@show');
