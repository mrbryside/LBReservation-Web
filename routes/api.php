<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// auth //
Route::post('/userLogin','ApiUserController@userLogin');
Route::post('/userRegister','ApiUserController@userRegister');
Route::middleware('auth:api')->get('/myinformation/{userID}','ApiUserController@myInformation');

// rooms //
Route::middleware('auth:api')->post('/reserved/{roomID}/{student_ID}/{userID}','ApiRoomController@reserved');
Route::middleware('auth:api')->get('/myreserved/{userID}','ApiRoomController@myReserved');
Route::middleware('auth:api')->get('/roomlist/{roomType}','ApiRoomController@roomList');
Route::middleware('auth:api')->get('/roomdetail/{roomID}','ApiRoomController@roomDetail');
Route::middleware('auth:api')->post('/roomsearch','ApiRoomController@roomSearch');

// news //
Route::get('/newlist','ApiNewController@newList');
Route::get('/newdetail/{newID}','ApiNewController@newDetail');

// rules //
Route::middleware('auth:api')->get('/rulelist','ApiRuleController@ruleList');