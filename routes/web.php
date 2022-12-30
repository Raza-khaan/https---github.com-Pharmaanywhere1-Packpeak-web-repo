<?php
use Illuminate\Support\Facades\Route;
// use Spatie\Honeypot\ProtectAgainstSpam;

// Sub-Domain Tenant Routes
require_once 'tenant.php';

// Sub-Admin Routes
require_once 'super-admin.php';
 
/*  Cron Job  Time Shedule  */
Route::get('reports/{report}/{id}', 'Admin\Patient@email_new_patients_report_data');

Route::get('create_expiry_notification', 'Home@before_expiry_send_notification');
Route::get('on_expiry_send_notification', 'Home@on_expiry_send_notification');
Route::get('on_trail_expiry_notification', 'Home@on_trail_expiry_notification');
Route::get('create_archive', 'Home@create_archive');
Route::get('notify_pharmacy', 'Home@notifyPharmacy');

// Login view button for terms and condition
Route::get('term_condition', 'Home@term_condition');
// Login view button for privacy policies
Route::get('privacy_policy', 'Home@privacy_policy');


Route::get('resend_verification', 'Admin\Pharmacist@resend_verification');

Route::get('getpickupnotifications', 'Home@getpickupnotifications');
 
Route::get('pickupReport/{start_date}/{end_date}', 'Home@pickupCustomDateRange');
Route::get('checkinReport/{start_date}/{end_date}', 'Home@checkinsCustomDateRange');
Route::get('nearmissReport/{start_date}/{end_date}', 'Home@nearmissCustomDateRange');
Route::get('returnReport/{start_date}/{end_date}', 'Home@returnCustomDateRange');
Route::get('auditReport/{start_date}/{end_date}', 'Home@auditCustomDateRange');
Route::get('noteforpatientReport/{start_date}/{end_date}', 'Home@noteforpatientCustomDateRange');

/*End Cron Job Route */
Route::post('/host-forward', 'Controller@pharmacy_login');
Route::get('pharmacist_login', 'Admin\Pharmacist@pharmacist_login');
Route::get('pharmacist_signup', 'Admin\Pharmacist@pharmacist');
Route::post('add_pharmacist', 'Admin\Pharmacist@add_pharmacist');
Route::get('pharmacist_signup/verifyAccount/{row_id}', 'Admin\Pharmacist@pharmacist_verification');

Route::get('pharmacist_signup/resendVerificationEmail/{id}/{email}/{details}', 'Admin\Pharmacist@resend_verification');

// Route::get('admin/add_pharmacy','Admin\Pharmacist@add_pharmacy');
// Route::post('admin/save_pharmacy','Admin\Pharmacist@register');
Route::post('add_phermacist', 'Admin\Pharmacist@add_phermacist');

/* ADMIN  AUTH */
Route::get('/', 'Admin\Auth\LoginController@showLoginForm');

Route::get('cancel', 'App\Http\Controllers\LoginController@cancel')->name('payment.cancel');
Route::get('payment/success', 'App\Http\Controllers\LoginController@success')->name('payment.success');

Route::get('admin-login', 'Admin\Auth\LoginController@showLoginForm');
Route::get('admin/passwords/reset', 'Admin\Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('admin/passwords/email', 'Admin\Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('admin/passwords/reset/{row_id}', 'Admin\Auth\ForgotPasswordController@reset');
Route::post('admin/passwords/reset/{row_id}', 'Admin\Auth\ForgotPasswordController@updatePassword');
Route::get('admin/passwords/changepassword', 'Admin\Auth\ForgotPasswordController@changepassword');
Route::get('admin/passwords/updatepassword', 'Admin\Auth\ForgotPasswordController@updatepassword');


// Route::get('/subscribe/paypal/{id}', 'Tenant\PaypalController@paypalRedirect')->name('paypal.redirect');
// Route::POST('/subscribe/paypal/return', 'Tenant\PaypalController@paypalReturn')->name('paypal.return');


// Pharmacy  Forgot password  By  api
Route::get('passwords/reset/{row_id}', 'Admin\AdminController@reset');
Route::post('passwords/reset/{row_id}', 'Admin\AdminController@updatePassword');

Route::get('/superadmin', 'Tenant\Pharmacist@def_pharmacy');
Route::post('save_pharmacy', 'Tenant\Pharmacist@register');
// Route::get('admin-login', function () {
//     $redirect= 'http://'.env('TENANCY_DEFAULT_HOSTNAME').'.'.env('PROJECT_HOST').'/Pack-Peak/public/';
//     return redirect($redirect);
// });

Route::post("sign_in", "Admin\Auth\LoginController@sign_in");
Auth::routes(['verify' => true]);

// Route::group(['middleware'=>['checkadmin']],function () {
          Route::get('admin/dashboard6','Admin\AdminController@dashboard');
//           Route::get('admin/all_pharmacies','Admin\Pharmacist@all_pharmacies');
// });

// Auth::routes();



Route::get('paywithpaypal','PaypalController@payWithPaypal')->name('paywithpaypal');
Route::post('paypal','PaypalController@postPaymentWithpaypal')->name('paypal');
Route::get('paypal', 'PaypalController@getPaymentStatus')->name('status');