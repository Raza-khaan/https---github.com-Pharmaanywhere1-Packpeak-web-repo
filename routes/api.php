<?php
use Illuminate\Support\Facades\Route;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'Api\UserController@login');
Route::post('register', 'Api\UserController@register');
Route::post('passwords/email', 'Api\Pharmacy@sendResetLinkEmail');
Route::post("sendNotification", "Api\PostController@sendNotification");

Route::get('packpeakapp', 'Api\Pharmacy@packpeakapp');
Route::get('patientrecord','Api\Pharmacy@patientrecord');
Route::get('getAppLogoutTime/{website_id}', 'Api\Pharmacy@getAppLogoutTime');

Route::group(['middleware' => 'auth:api'], function () {

	Route::post('details', 'Api\UserController@details');
	Route::put('updateAppLogoutTime/{website_id}', 'Api\Pharmacy@updateAppLogoutTime');
	
	 //Route::get('getAppLogoutTime/{website_id}', 'Api\Pharmacy@getAppLogoutTime');

	Route::post('create_pharmacy', 'Api\Pharmacy@create_pharmacy');
	Route::post('getAllRecords', 'Api\UserController@getAllRecords');
	Route::post('getAllRecordsOfPatient', 'Api\UserController@getAllRecordsOfPatient');

	Route::post('createTechnician', 'Api\TechnicianController@createTechnician');
	Route::post('createPatient', 'Api\PatientController@createPatient');
	Route::post('createPickup', 'Api\PickUpController@createPickup');
	Route::post('createChecking', 'Api\CheckingController@createChecking');
	Route::post('createNearMiss', 'Api\NearMissController@createNearMiss');
	Route::post('createReturns', 'Api\ReturnsController@createReturns');
	Route::post('createPatientAudit', 'Api\AuditController@createPatientAudit');
	Route::post('createNoteForPatient', 'Api\NoteForPatientController@createNoteForPatient');

	Route::post('updateProfile/{website_id}/{user_id}', 'Api\UserController@updateProfile');
	Route::post('pinChange/{website_id}/{user_id}', 'Api\UserController@pinChange');

	Route::post('runquery/{website_id}', 'Api\UserController@runquery');
	Route::post('saveAllRecords', 'Api\PostController@saveAllRecords');
	Route::post('saveEventsLogs', 'Api\EventsLogController@saveEventsLogs');
	Route::post("getNewChanges/{website_id}", "Api\PostController@getNewChanges");
	// Route::get('updateAppLogoutTime/{website_id}','Api\Pharmacy@updateAppLogoutTime');

	// Route::post("sendNotification","Api\PostController@sendNotification");
	
});
