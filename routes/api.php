<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(
    [
        'prefix' => config('telegram.bots.money.hash'),
    ],
    function () {
        Route::group(
            [
                'prefix' => 'webhook',
            ],
            function() {
                Route::post('', 'SettingsController@update');
            }
        );

        Route::group(
            [
                'prefix' => 'settings',
            ],
            function () {
                Route::group(
                    [
                        'prefix' => 'webhook',
                    ],
                    function () {
                        Route::get('set', 'SettingsController@setWebHook');
                        Route::get('getinfo', 'SettingsController@getWebHookInfo');
                        Route::get('delete', 'SettingsController@deleteWebhook');
                        //Route::get('show', 'SettingsController@show');
                    }
                );
            }
        );
    }
);

Route::group(
    [
        'prefix' => 'test',
    ],
    function () {
        Route::get('plural', 'SettingsController@test');
    }
);
