<?php
Route::domain('{account}.' . env('PROJECT_HOST', 'localhost'))->group(function () {
	//Login Routes
	Route::get('/', 'Auth\LoginController@showLoginForm');
	Route::get('admin-login', 'Auth\LoginController@showLoginForm')->name('admin-login');
	Route::post('pharmacylogin', 'Auth\LoginController@pharmacylogin')->name('pharmacylogin');


	Route::get('paypal/{website_id}/{row_id}/{subscription_id}', 'Auth\LoginController@paypal');
	Route::get('success', 'Auth\LoginController@success');
	Route::get('cancel', 'Auth\LoginController@cancel');

	Route::post('isverification', 'Auth\LoginController@isverification');
	Route::get('sendverificationemail/{email}', 'Auth\LoginController@sendverificationemail');

	Route::get('passwords/reset', 'Tenant\Auth\ForgotPasswordController@showLinkRequestForm');
	Route::post('passwords/email', 'Tenant\Auth\ForgotPasswordController@sendResetLinkEmail');
	Route::get('passwords/reset/{row_id}', 'Tenant\Auth\ForgotPasswordController@reset');
	Route::post('passwords/reset/{row_id}', 'Tenant\Auth\ForgotPasswordController@updatePassword');

	
	// Route::get('logout', 'Tenant\PharmacyController@logout');
	/* Dashboard Route */
	// Route::middleware('auth:pharmacy')->group(function () {
	 Route::group(['middleware' => ['checkpharmacy']], function () {
		Route::name('tenant.')->namespace('Tenant')->group(function () {

			// Route::get('patients/delete/{id}', function ($id,$id1) {
			//     return $id1;
			// });
			
			// Route::get('paypal/{id}', 'PaypalController@paypalRedirect')->name('paypal.redirect');
			
			Route::get('paypal/{id}', 'PaypalController@paypalRedirect');


			Route::get('subscribe/paypal/return', 'PaypalController@paypalReturn')->name('paypal.return');
			Route::get('text_guidelines', 'HomeController@textGuidelines')->name('text_guidelines');

			Route::get('testPage', 'CommonController@testPage')->name('testPage');
			Route::get('createpaypalplana', 'PaypalController@create_plan')->name('createpaypalplan');
			Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');
			Route::get('packboard', 'HomeController@packboard')->name('packboard');
			//Route::post('packboard', 'HomeController@packboard')->name('packboard');
				
			Route::get('logout', 'PharmacyController@logout')->name('logout');
			Route::get('profile', 'HomeController@profile')->name('profile');
			Route::post('update_profile', 'HomeController@update_profile')->name('update_profile');
			Route::post('update_access', 'HomeController@update_access')->name('update_access');
			Route::post('update_password', 'HomeController@update_password')->name('update_password');

			Route::get('technician', 'Technician@technician')->name('technician')->middleware('CheckPharmacyAdmin');
			Route::post('add_technician', 'Technician@add_technician')->name('add_technician')->middleware('CheckPharmacyAdmin');;
			Route::get('all_technician', 'Technician@all_technician')->name('all_technician');
			Route::get('all_suspended_technician', 'Technician@all_suspended_technician')->name('all_suspended_technician');
			Route::get('all_admin', 'Technician@all_admin')->name('all_admin');

			Route::get('technician/status/{id}', 'Technician@technicianStatus')->name('technician/status/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('technician/edit/{id}', 'Technician@technicianEdit')->name('technician/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::post('technician/edit/{id}', 'Technician@technicianEdit')->name('technician/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('technician/delete/{id}', 'Technician@technicianForceDelete')->name('technician/delete/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('technician/suspend/{id}', 'Technician@technicianDelete')->name('technician/suspend/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('technician/restore/{id}', 'Technician@technicianRestore')->name('technician/restore/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');

			Route::get('add_pharmacy', 'Pharmacist@add_pharmacy')->name('add_pharmacy');
			Route::get('all_pharmacies', 'Pharmacist@all_pharmacies')->name('all_pharmacies');
			Route::get('subscriptions', 'Subscriptions@subscriptions')->name('subscriptions');
			Route::get('{form}/add_form/{id}', 'Subscriptions@add_form')->name('{form}/add_form/{id}');
			Route::post('update_form', 'Subscriptions@update_form')->name('update_form');
			Route::get('edit_subscription/{row_id}', 'Subscriptions@edit_subscription')->name('edit_subscription/{row_id}')->middleware('CheckPharmacyAdmin');
			Route::post('update_subscription/{row_id}', 'Subscriptions@update_subscription')->name('update_subscription/{row_id}');

			Route::get('pickups', 'PickUp@pickups')->name('pickups');
			Route::get('Sms_settings','PickUp@Sms_settings')->name('Sms_settings');
			
			Route::get('pickups/delete/{id}', 'PickUp@pickupsDelete')->name('pickups/delete/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('pickups/edit/{id}', 'PickUp@pickupsEdit')->name('pickups/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::post('pickups/edit/{id}', 'PickUp@pickupsEdit')->name('pickups/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('pickups/show/{id}', 'PickUp@pickupsShow')->name('pickups/show/{id}')->where('id', '[0-9]+');

			Route::post('add_pickups', 'PickUp@add_pickups')->name('add_pickups');
			Route::get('pickups_reports', 'PickUp@pickups_reports')->name('pickups_reports');

			Route::get('pickups_reports_calender/{id}', 'PickUp@pickups_reports_calender')->name('pickups_reports_calender/{id}');

			

			Route::get('pickups_archived_reports', 'PickUp@pickups_archived_reports')->name('pickups_archived_reports');

			Route::get('pickups_notifications', 'PickUp@pickups_notifications')->name('pickups_notifications');
			Route::get('patients_picked_up_last_month', 'PickUp@patients_picked_up_last_month')->name('patients_picked_up_last_month');
			Route::get('pickups_calender', 'PickUp@pickups_calender')->name('pickups_calender');
			Route::post('getAllPickupForMonth', 'PickUp@getAllPickupForMonth');
			Route::get('six_month_compliance', 'PickUp@six_month_compliance')->name('six_month_compliance');
			Route::get('all_compliance', 'PickUp@all_compliance')->name('all_compliance');
			Route::get('patients', 'Patient@patients')->name('patients');
			Route::post('save_patient', 'Patient@save_patient')->name('save_patient');
			Route::get('patients/delete/{id}', 'Patient@patientsDelete')->name('patients/delete/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');

			Route::get('patients/softarchive/{id}', 'Patient@softarchive')->name('patients/softarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('patients/softunarchive/{id}', 'Patient@softunarchive')->name('patients/softunarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');

			Route::get('patients/notification/{id}', 'Patient@notification')->name('patients/notification/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('patients/edit/{id}', 'Patient@patientsEdit')->name('patients/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::post('patients/edit/{id}', 'Patient@patientsEdit')->name('patients/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');

			Route::get('new_patients_report', 'Patient@new_patients_report')->name('new_patients_report');
			Route::get('archived_patients_report', 'Patient@archived_patients_report')->name('archived_patients_report');
			
			Route::post('checkduplicatePatient', 'Patient@checkduplicatePatient')->name('checkduplicatePatient');

			Route::get('notes_for_patients', 'Notes_For_Patient@notes_for_patients')->name('notes_for_patients');

			Route::post('save_notes_for_patients', 'Notes_For_Patient@save_notes_for_patients')->name('save_notes_for_patients');
			Route::get('notes_for_patients/edit/{id}', 'Notes_For_Patient@notes_for_patientsEdit')->name('notes_for_patients/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::post('notes_for_patients/edit/{id}', 'Notes_For_Patient@notes_for_patientsEdit')->name('notes_for_patients/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('notes_for_patients/delete/{id}', 'Notes_For_Patient@notes_for_patientsDelete')->name('notes_for_patients/delete/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');

			Route::get('notes_for_patients_report', 'Notes_For_Patient@notes_for_patients_report')->name('notes_for_patients_report');

			Route::get('archive_notes_for_patients_report', 'Notes_For_Patient@archive_notes_for_patients_report')->name('archive_notes_for_patients_report');
			Route::get('notes_for_patients/softarchive/{id}', 'Notes_For_Patient@softarchive')->name('notes_for_patients/softarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('notes_for_patients/softunarchive/{id}', 'Notes_For_Patient@softunarchive')->name('notes_for_patients/softunarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');

			Route::get('sms_tracking_report', 'Notes_For_Patient@sms_tracking_report')->name('sms_tracking_report');

			Route::get('checkings', 'Checking@checkings')->name('checkings');
			
			Route::get('checkings/edit/{id}', 'Checking@checkingsEdit')->name('checkings/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::post('checkings/edit/{id}', 'Checking@checkingsEdit')->name('checkings/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('checkings/delete/{id}', 'Checking@checkingsDelete')->name('checkings/delete/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::post('save_checking', 'Checking@save_checking')->name('save_checking');
            Route::post('save_packed_fields', 'checking@save_packed_fields')->name('save_packed_fields');
            Route::get('packed_Delete/{id}', 'Checking@packed_Delete')->name('packed_Delete/{id}');
			// export pdf and excel new routes for pharmacy admin
			Route::get('export_excel_phar', 'Checking@export_excel_phar')->name('export_excel_phar');
			Route::get('export_pdf_phar', 'Checking@export_pdf_phar')->name('export_pdf_phar');
			Route::get('export_excel_checking_phar', 'Checking@export_excel_checking_phar')->name('export_excel_checking_phar');
			Route::get('export_pdf_checking_phar', 'Checking@export_pdf_checking_phar')->name('export_pdf_checking_phar');
			Route::get('export_excel_pickup_phar', 'PickUp@export_excel_pickup_phar')->name('export_excel_pickup_phar');
			Route::get('export_pdf_pickup_phar', 'PickUp@export_pdf_pickup_phar')->name('export_pdf_pickup_phar');


			Route::get('packed', 'Checking@packed')->name('packed');
			Route::post('save_packed', 'Checking@save_packed')->name('save_packed');
			Route::get('checkings/packedit/{id}', 'Checking@packEdit')->name('checkings/packedit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('packed/delete/{id}', 'Checking@packedDelete')->name('packed/delete/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::post('checkings/packedit/{id}', 'Checking@packEdit')->name('checkings/packedit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
		//	Route::post('save_pack', 'Checking@save_pack')->name('save_pack');
			
			//packboard routeschecking_board_Delete
		//	Route::get('checkings_board', 'Checking@checkings_board')->name('checkings_board');
			Route::post('save_checkings_board', 'Checking@save_checkings_board')->name('save_checkings_board');
			Route::get('checking_board_Delete/{id}', 'Checking@checking_board_Delete')->name('checking_board_Delete/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('pickup_board_Delete/{id}', 'Checking@pickup_board_Delete')->name('pickup_board_Delete/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('packed_hold_button/{id}', 'Checking@packed_hold_button')->name('packed_hold_button');
			Route::get('packed_unhold_button/{id}', 'Checking@packed_unhold_button')->name('packed_unhold_button');
			Route::get('checking_hold_button/{id}', 'Checking@checking_hold_button')->name('checking_hold_button');
			Route::get('checking_unhold_button/{id}', 'Checking@checking_unhold_button')->name('checking_unhold_button');
			Route::get('pickup_hold_button/{id}', 'Checking@pickup_hold_button')->name('pickup_hold_button');
			Route::get('pickup_unhold_button/{id}', 'Checking@pickup_unhold_button')->name('pickup_unhold_button');
			Route::get('/time_limt_packed', 'Checking@time_limt_packed')->name('time_limt_packed');
			Route::get('/time_limt_checking', 'Checking@time_limt_checking')->name('time_limt_checking');
			//Route::get('gettopack/{id}', 'Checking@pickup_hold_button')->name('pickup_hold_button');
            Route::get('gettopack', 'HomeController@gettopack')->name('gettopack');
			//-----------------------------------------------------------------
			Route::get('full_calender', 'Checking@full_calender')->name('full_calender');
			Route::post('calender_events', 'Checking@calender_events')->name('calender_events');
			Route::get('calender_events_fetch', 'Checking@calender_events_fetch')->name('calender_events_fetch');
			Route::get('calender_events_edit', 'Checking@calender_events_edit')->name('calender_events_edit');
			Route::get('calender_event_delete', 'Checking@calender_event_delete')->name('calender_event_delete');
//-------------------------------------------------------------------------------

			Route::get('checkings_report', 'Checking@checkings_report')->name('checkings_report');
			Route::get('archive_checkings_report', 'Checking@archive_checkings_report')->name('archive_checkings_report');
			Route::get('checkings/softarchive/{id}', 'Checking@softarchive')->name('checkings/softarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('checkings/softunarchive/{id}', 'Checking@softunarchive')->name('checkings/softunarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');

		//	packboard post form
			
			Route::get('packed_report', 'Checking@packed_report')->name('packed_report');
			Route::get('archive_packed_report', 'Checking@archive_packed_report')->name('archive_packed_report');
			Route::get('checkings/softpacakarchive/{id}', 'Checking@softpacakarchive')->name('checkings/softpacakarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('checkings/softpackunarchive/{id}', 'Checking@softpackunarchive')->name('checkings/softpackunarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');




			Route::get('returns', 'Return_@returns')->name('returns');
			Route::post('save_return', 'Return_@save_return')->name('save_return');
			
			Route::get('all_returns', 'Return_@all_returns')->name('all_returns');
			Route::get('archive_all_returns', 'Return_@archive_all_returns')->name('archive_all_returns');
			Route::get('return/softarchive/{id}', 'Return_@softarchive')->name('return/softarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('return/softunarchive/{id}', 'Return_@softunarchive')->name('return/softunarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			
			
			Route::get('return/edit/{id}', 'Return_@returnEdit')->name('return/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::post('return/edit/{id}', 'Return_@returnEdit')->name('return/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('return/delete/{id}', 'Return_@returnDelete')->name('return/delete/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			/* new  */
			Route::get('audits', 'Audit@audits')->name('audits');
			Route::get('all_audits', 'Audit@all_audits')->name('all_audits');
			Route::post('save_audits', 'Audit@save_audits')->name('save_audits');
			Route::get('audits/edit/{id}', 'Audit@auditsEdit')->name('audits/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::post('audits/edit/{id}', 'Audit@auditsEdit')->name('audits/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('audits/delete/{id}', 'Audit@auditsDelete')->name('audits/delete/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');


			Route::get('archive_all_audits', 'Audit@archive_all_audits')->name('archive_all_audits');
			Route::get('audits/softarchive/{id}', 'Audit@softarchive')->name('audits/softarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('audits/softunarchive/{id}', 'Audit@softunarchive')->name('audits/softunarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');


			Route::get('near_miss', 'Near_Miss@near_miss')->name('near_miss');
			Route::get('all_near_miss', 'Near_Miss@all_near_miss')->name('all_near_miss');
			Route::get('nm_last_month', 'Near_Miss@nm_last_month')->name('nm_last_month');
			Route::get('nm_monthly', 'Near_Miss@nm_monthly')->name('nm_monthly');
			Route::post('save_near_miss', 'Near_Miss@save_near_miss')->name('save_near_miss');
			Route::get('near_miss/edit/{id}', 'Near_Miss@near_missEdit')->name('near_miss/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::post('near_miss/ed8it/{id}', 'Near_Miss@near_missEdit')->name('near_miss/edit/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('near_miss/delete/{id}', 'Near_Miss@near_missDelete')->name('near_miss/delete/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');


			Route::get('archive_all_near_miss', 'Near_Miss@archive_all_near_miss')->name('archive_all_near_miss');
			Route::get('near_miss/softarchive/{id}', 'Near_Miss@softarchive')->name('near_miss/softarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('near_miss/softunarchive/{id}', 'Near_Miss@softunarchive')->name('near_miss/softunarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');

			Route::post('deleteAll/{modelName}', 'CommonController@deleteAll')->name('deleteAll/{modelName}')->where('modelName', '[a-zA-Z]+')->middleware('CheckPharmacyAdmin');
			Route::post('archiveAll/{modelName}', 'CommonController@archiveAll')->name('archiveAll/{modelName}')->where('modelName', '[a-zA-Z]+');
			Route::post('unarchiveAll/{modelName}', 'CommonController@unarchiveAll')->name('unarchiveAll/{modelName}')->where('modelName', '[a-zA-Z]+');

			Route::get('notification_details/{id}', 'HomeController@notification_details')->name('notification_details/{id}')->where('id', '[0-9]+');

			Route::get('search', 'Search@index');
			Route::post('search_patient', 'Search@search_patient');
			Route::get('create_patient_details_pdf/{row_id}', 'Search@create_patient_details_pdf');

			Route::get('patients_notification', 'Patient@patients_notification');
			Route::post('import_patients', 'Patient@import_patients');

			Route::post('updateSMSSetting', 'HomeController@updateSMSSetting')->name('updateSMSSetting');

			// Route::get('paywithpaypal/{website_id}/{row_id}/{subscription_id}','PaypalController@payWithPaypal')->name('paywithpaypal');
			
			
			Route::get('paywithpaypal/{website_id}/{row_id}/{subscription_id}','PaypalController@payWithPaypal')->name('paywithpaypal');
			Route::post('paypal','PaypalController@postPaymentWithpaypal')->name('paypal');
			Route::get('paypal', 'PaypalController@getPaymentStatus')->name('status');

			Route::post('verifyuser','Technician@verifyuser')->name('verifyuser');



			Route::get('pickups/softdelete/{id}', 'PickUp@soft_delete_pickup')->name('pickups/delete/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');
			Route::get('pickups/softunarchive/{id}', 'PickUp@softunarchive')->name('pickups/softunarchive/{id}')->where('id', '[0-9]+')->middleware('CheckPharmacyAdmin');

			Route::get('exempted	_patients','Patient@exempted_patients');
			Route::post('add_exempted_patient','Patient@add_exempted_patient');

			Route::post('update_pharmacy_tenant','PickUp@update_pharmacy_tenant')->name('update_pharmacy_tenant');
			

		});
	 });

 });
