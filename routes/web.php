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

//---------------------------------Reservation Index---------------------------------------//
Route::get('/oldindex', 'IndexController@index');
Route::get('/', 'IndexController@main');
Route::get('/showcontent/{id}','ShowNewController@show'); // show new content on index

//----------------------------------------------------------------------------------------//


//-------------------------------Contact--------------------------------------------------//
Route::group(['middleware' => 'admin'], function () {
	Route::get('/contact','ContactController@index');
	Route::get('/contact/create','ContactController@create');
	Route::post('/contact','ContactController@store');
	Route::put('/contact/{id}','ContactController@update');
	Route::get('/contact/{id}/edit','ContactController@edit');
}); 
//----------------------------------------------------------------------------------------//

//-------------------------------rule--------------------------------------------------//
Route::group(['middleware' => 'admin'], function () {
	Route::get('/rule','RuleController@index');
	Route::get('/rule/create','RuleController@create');
	Route::post('/rule','RuleController@store');
	Route::put('/rule/{id}','RuleController@update');
	Route::get('/rule/{id}/edit','RuleController@edit');
	Route::delete('/rule/{id}','RuleController@destroy');
}); 
//----------------------------------------------------------------------------------------//


//---------------------------------Reservation home---------------------------------------//


Route::group(['middleware' => 'admin'], function () {
	Route::get('/home/create','HomeController@create');
	Route::post('/home','HomeController@store');
	Route::get('/home/{id}/edit','HomeController@edit');
	Route::put('/home/{id}','HomeController@update');
	Route::delete('/home/{id}','HomeController@destroy');
	Route::put('/home/open/{id}/{id2}','HomeController@updateStatus');

	Route::post('/singleplan','SinglePlanController@store');
	Route::put('/singleplan/{id}','SinglePlanController@update');

	Route::get('/scheduleroom','ScheduleRoomController@index');
	Route::post('/scheduleroom','ScheduleRoomController@storeCloseTime');
	Route::post('/scheduleroom/create/testtime','ScheduleRoomController@storeTestTime');
	Route::delete('/scheduleroom/{id}','ScheduleRoomController@destroyCloseTime');
	Route::delete('/scheduleroom/delete/testtime','ScheduleRoomController@destroyTestTime');
}); 

Route::group(['middleware' => 'adminStaff'], function () {
	Route::get('/loadtable', 'HomeController@loadtableadminindex');	
	Route::get('/single', 'HomeController@single')->name('home');
	Route::get('/singleroom/', 'HomeController@loadsingleroom');
});

Route::group(['middleware' => 'write'], function () {
	Route::get('/userlogin','HomeController@userlogin');
	Route::get('/adminindex', 'HomeController@adminindex')->name('home');

	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/homesingleuser', 'HomeController@indexsingleuser')->name('home');


	Route::get('/singletoroom', 'HomeController@singletoroom')->name('home');
	Route::get('/home/{id}','HomeController@show');
	Route::get('/myreservation/', 'ReservationController@myreservation');

	Route::get('/sharedroom/', 'HomeController@loadsharedroom');
	Route::get('/singleroomuser/', 'HomeController@loadsingleroomuser');
}); 


Route::get('/error404/', 'IndexController@error');

//-------------------------------------------------------------------------------//

Route::get('login/google', 'Auth\LoginController@redirectToProvider');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/invalid','LoginRedirectController@invalidlogin');
Route::get('/logoutgoogle','LoginRedirectController@logoutGoogle');
Route::get('/writeuser','HomeController@writeuser');
Route::put('/writeuser/{id}','HomeController@updateuser');

//---------------------------------new---------------------------------------//

Route::group(['middleware' => 'admin'], function () {
	Route::get('/new/create','NewController@create');
	Route::post('/new','NewController@store');
	Route::get('/new/{id}','NewController@show');
	Route::get('/new/{id}/edit','NewController@edit');
	Route::put('/new/{id}','NewController@update');
	Route::delete('/new/{id}','NewController@destroy');
	Route::get('/new', 'NewController@index')->name('new');
}); 

Route::get('/migrate', 'CronJobController@index');
Route::get('/schedule1', 'CronJobController@schedule1');
Route::get('/schedule2', 'CronJobController@schedule2');

//-------------------------------------------------------------------------------//


//---------------------------------account panel---------------------------------------//

Route::group(['middleware' => 'admin'], function () {
	Route::delete('/user/{id}','UserController@destroy');
	Route::get('/user/show/staff','UserController@staffindex')->name('Staff List');
	Route::post('/user/show/staffsearch','UserController@staffindexSearch')->name('Staff List');
	Route::get('/user/show/staffsearch','UserController@staffindexSearch')->name('Staff List');
	Route::get('/staffregister','UserController@staffForm');
	Route::post('/staffregister','UserController@staff');
}); 

Route::group(['middleware' => 'adminStaff'], function () {
	Route::get('/user/{id}','UserController@show');
	Route::get('/user','UserController@index')->name('user');
	Route::post('/usersearch','UserController@indexSearch')->name('user');
	Route::get('/usersearch','UserController@indexSearch')->name('user');
	Route::get('/user/manage/password/{id}','UserController@changepassword');
	Route::put('/user/manage/password/{id}','UserController@updatepassword');
});

Route::group(['middleware' => 'write'], function () {

	Route::get('/user/manage/{id}','UserController@manage');

	Route::get('/user/manage/infomation/{id}','UserController@changeinfomation');
	Route::put('/user/manage/infomation/{id}','UserController@updateinfomation');
});



//-------------------------------------------------------------------------------//



// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');


//---------------------------------History---------------------------------------//

Route::group(['middleware' => 'adminStaff'], function () {
	Route::delete('/history/alldelete', 'HistoryController@alldelete');
	Route::delete('/history/{id}', 'HistoryController@destroy');
	Route::get('/history', 'HistoryController@show');
	Route::post('/historysearch', 'HistoryController@showSearch');
	Route::get('/historysearch', 'HistoryController@showSearch');
	Route::get('/history/{id}','HistoryController@show');
});




//-------------------------------------------------------------------------------//

//---------------------------------reservation search---------------------------------------//
Route::group(['middleware' => 'adminStaff'], function () {
	Route::get('/reservationsearch', 'ReserSearchController@show');
	Route::get('/reservationsearch/{id}','ReserSearchController@show');
	Route::get('/reservationsearchonadmin/','ReserSearchController@showonadmin');
	Route::get('/add/{id}', 'ReservationController@AddTime');
});



//-------------------------------------------------------------------------------//



//---------------------------------Reservation update,delete---------------------------------------//

Route::group(['middleware' => 'adminStaff'], function () {
	Route::put('/reservation/{id}', 'ReservationController@update');
	Route::put('/add/{id}', 'ReservationController@AddTimeUpdate');
	Route::delete('/reservation/{id}/{where}','ReservationController@destroy');
});



Route::group(['middleware' => 'write'], function () {
//-----------------------------Reservation Notification ------------------------------------------//

	Route::put('/reservation/add/{id}','ReservationController@store');
	Route::get('/MarkAllSeen/','ReservationController@AllSeen');
	Route::delete('/reservation/{id}/{where}','ReservationController@destroy');

//------------------------------------------------------------------------------------------------//
});