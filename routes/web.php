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

Route::get('/', 'PagesController@index');
Route::get('/lodging', 'PagesController@lodging');
Route::get('/pos', 'PagesController@pos');

//Route::resource('guests', 'GuestsController');
Route::get('/transient-backpacker', 'UnitsController@transientBackpacker'); 
Route::get('/glamping', 'UnitsController@glamping'); 

Route::get('/calendar', 'UnitsController@calendar'); 

Route::get('/guestcheckout/{id}', 'UnitsController@loadGuestDetails');

//Route::get('/addusers', 'PagesController@addusers');
Route::resource('staff', 'StaffController');

//
Route::get('/loadGlampingDetails/{id}', 'UnitsController@loadGlampingUnit');
Route::get('/loadGlampingAvailableUnit/{id}', 'UnitsController@loadGlampingAvailableUnit');

Route::post('/guests', 'GuestsController@addGuest');
Auth::routes();

Route::get('/dashboard', 'UnitsController@glamping');

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');


//Check-in guests glamping
//Route::get('/checkin/{unitID}', 'GuestsController@showCheckinForm');
//Route::post('/checkinAt', 'GuestsController@checkin');
Route::get('/checkin/{unitID}', 'AccommodationsController@showCheckinForm');
Route::post('/checkinGlamping', 'AccommodationsController@checkinGlamping');

//Find Package
Route::get('/getService/{serviceID}', 'ServicesController@getPrices');


//Make reservation
Route::get('/makeReservation/{unitID}', 'ReservationsController@showReservationForm');
Route::post('/makeReservation', 'ReservationsController@makeReservation');

//Check-in backpacker
Route::get('/checkinBackpacker/{unitID}' , 'AccommodationsController@showcheckinBackpackerForm');
Route::post('/checkinBackpacker','AccommodationsController@checkinBackpacker');
Route::get('loadBackpackerDetails/');

//Edit guest details
//Route::get('/editdetails/{unitID}', 'GuestsController@editGuestDetails');
Route::get('/editdetails/{unitID}', 'GuestsController@viewGuestDetails');
Route::post('/updateDetails', 'GuestsController@updateDetails');

//Check-out guests
Route::get('/checkout/{unitID}', 'GuestsController@showCheckoutForm');

//AddReservation
//Route::get('/addReservation/{unitID}', 'AccommodationsController@showAddReserveForm');
//Route::post('/addReservation','AccommodationsController@addReservation');

//ViewGuests
Route::get('/viewguests', 'GuestsController@viewguests');

//bruteforce do not touch
Route::get('/checkinBackpacker', function() {
    return view('lodging.checkinBackpacker');
});

//ViewReservations
Route::get('/viewReservations', 'ReservationsController@viewReservations');
//Route::get('/viewReservations', 'AccommodationsController@viewReservation');

//Payment Transactions
Route::get('transactions', 'PaymentsController@viewLodgingSales');

//Select Service	
Route::get('/serviceSelect/{serviceID}', 'ServicesController@getPrices'); 

//Post additional services
Route::post('/addAdditionalService', 'ChargesController@addAdditionalService');

//View users
Route::get('/viewusers', 'UsersController@viewUsers');

//View units
Route::get('/viewunits', 'UnitsController@viewUnits');

Route::get('/getDates', 'UnitsController@getDates');
