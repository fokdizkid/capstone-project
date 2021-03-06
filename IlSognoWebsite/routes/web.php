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

Route::get('/', 'ViewController@getHomePage');

Route::get('/Accomodation', 'ViewController@getRooms');

Route::get('/Packages', 'ViewController@getPackages');

Route::get('/Activities', 'ViewController@getActivities');

Route::get('/Package/RoomInfo', 'ViewController@getRoomInfo');

Route::get('/Package/ActivityInfo', 'ViewController@getActivityInfo');

Route::get('/Package/ItemInfo', 'ViewController@getItemInfo');

Route::get('/BookReservation', 'ViewController@bookReservation');

Route::get('/Reservation/Rooms', 'ViewController@getAvailableRooms');

Route::get('/Reservation/Boats', 'ViewController@getAvailableBoats');

Route::get('/Reservation/Fees', 'ViewController@getEntranceFee');

Route::post('/Reservation/Add', 'ReservationController@addReservation');

Route::post('/Reservation/Cancel', 'ReservationController@cancelReservation');

Route::post('/Reservation/DepositSlip', 'ReservationController@saveDepositSlip');

Route::get('/Reservation/Customers', 'ViewController@getCustomerReservation');

Route::post('/Reservation/Add/Package', 'ReservationController@addReservationPackage');

Route::get('/Reservation/Packages/Availability', 'ViewController@getAvailablePackages');

Route::post('/Login', 'SessionsController@create');

Route::post('/Login/VerifyCode', 'SessionsController@VerifyCode');

Route::get('/Logout', 'SessionsController@destroy');

Route::get('/BookPackages', function () {
    return view('BookPackages');
});

Route::get('/Location', 'ViewController@getLocation');

Route::get('/AboutUs', 'ViewController@getAboutUs');

Route::get('/ContactUs', 'ViewController@getContactUs');

Route::get('/Login', function () {
    return view('Login');
});

Route::get('/Reservation/{id}', 'ViewController@getReservation');

Route::get('/ReservationPackage', function () {
    return view('ReservationPackage');
});

Route::post('/Reservation/Invoice', 'InvoiceController@GenerateInvoice');
