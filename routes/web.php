<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

// Route::get('/', function () {

// 	return redirect('/sendOtp');
// });
Route::get('/', function () {

	//return redirect('/dashboard');
	
    if(Auth::check()) { 
    	return redirect('/dashboard');
		/*if (session()->has('otp_verified')) {
			return redirect()->back();
		   //return redirect('/dashboard');
		} else {
		  return view('auth.otp');
		}*/
    } else {
    	//dd('1');
    return view('auth.login');
    }
    
});
// Route::get('/login', function () {
	
//     if(Auth::check()) { 
// 		if (session()->has('otp_verified')) {
// 			return redirect()->back();
// 		   //return redirect('/dashboard');
// 		} else {
// 		  return view('auth.otp');
// 		}
//     } else {
//     	//dd('1');
//     return view('auth.login');
//     }
    
// });
//Route::get('/captcha', 'Mc@index');


Route::group(['middleware' => 'auth'], function () {
	
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/{id}', 'DashboardController@easDashboard');

//home	
Route::get('/home', 'HomeController@index');
//Route::get('/', 'HomeController@index')->name("main");
Route::get('/minor', 'HomeController@minor')->name("minor");
//logout
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/auto', 'RolesController@auto');
//Status
Route::resource('/status', 'StatusController');
//RO routes
Route::resource('/ro', 'RoController');
Route::get('/updateInvoiceDetails','RoController@updateInvoiceDetails');
Route::get('/download-ro_pdf/{id}','RoController@downloadRoPdf');

//Route::post('ro/view','RoController@view');
// Route::get('/ro/create', 'RoController@create')->name("ro-create");
// Route::get('/ro/list', 'RoController@list')->name("ro-list");
//GAR routes
Route::resource('/gar', 'GarController');
Route::post('/dispachRegisterEntry', 'GarController@dispachRegisterEntry');
Route::post('/diaryRegisterEntry', 'GarController@diaryRegisterEntry');
Route::post('/tallyEntry', 'GarController@tallyEntry');
Route::get('/upload-cheque', 'GarController@generateChequeListView');
Route::post('/delete-cheque', 'GarController@deleteCheque');
Route::post('/create-cheque', 'GarController@generateCheque');
Route::get('/list-cheque', 'GarController@listCheque');
Route::get('/download-gar/{id}', 'GarController@downloadGarPdf');
Route::get('/download-cheque/{id}', 'GarController@downloadCheque');
Route::post('/garRegisterEntry', 'GarController@garRegisterEntry');
Route::post('/garEcRegisterEntry', 'GarController@garEcRegisterEntry');

//Forwarding Letter
Route::resource('/forwarding-letter', 'ForwardingLetterController');
Route::post('/forwarding-letter-delete', 'ForwardingLetterController@destory');
Route::get('/download-forwardingletter/{id}', 'ForwardingLetterController@downloadForwardingLetter');

//Other Views
// Route::get('/purchase-order/create', 'PurchaseController@create')->name("purchase-order");
// Route::get('/diary-register/create', 'DairyRegisterController@create')->name("diary-register");
// Route::get('/dispatch-register/create', 'DispatchRegisterController@create')->name("dispatch-register");
// Route::get('/forwarding-letter/list', 'ForwardingLetterController@list')->name("forwarding-letter");
//EAS
//Route::post('view','EasMastersController@view');
Route::resource('/eas', 'EasMastersController');
Route::get('/get-vendor-details/{vendor_id}', 'EasMastersController@getVendorDetails');
Route::get('/get-deptartment-details/{department_id}', 'EasMastersController@getDepartmentDetails');
Route::get('/download-eas-pdf/{id}','EasMastersController@downloadEasPdf');
Route::get('/deleteItemDetails/{id}','EasMastersController@deleteItemDetails'); 
Route::post('/update-item-details','EasMastersController@updateItemDetails');
Route::post('/add-item-details','EasMastersController@addItemDetails'); 
Route::post('/add-copy','CommonController@addCopyTo'); 
//Workflow
Route::get('/workflow/list', 'AdminController@workflowList');
//Purchase Order

Route::resource('purchase-order', 'PurchaseOrderController');
Route::post('vendor_details/{id}', 'PurchaseOrderController@vendorDetails');
Route::get('/download-po_pdf/{id}','PurchaseOrderController@downloadPoPdf');

//Transaction 
Route::resource('transaction', 'TransactionController');
//Entity 
Route::resource('/entity', 'EntityController');
//Departments
Route::resource('/departments', 'DepartmentsController');
//Roles 
Route::resource('/roles', 'RolesController');
Route::get('/role-dept-entity', 'RolesController@roleDeptEntityList');
Route::get('/role-dept-entity/create', 'RolesController@roleDeptEntityView');
Route::get('/role-dept-entity/{id}/edit', 'RolesController@roleDeptEntityEdit');
Route::post('store/role-dept-entity', 'RolesController@roleDeptEntityStore');
//Conditions 
Route::resource('conditions', 'ConditionController');
//Office types 
Route::resource('office-type', 'OfficeTypeController');
//Locations 
Route::resource('location', 'LocationController');
//Users 
Route::get('/users', 'Auth\RegisterController@usersList');
Route::get('/users/{id}/edit', 'Auth\RegisterController@edit');
Route::patch('/users/{id}', 'Auth\RegisterController@update');
Route::delete('/users/{id}', 'Auth\RegisterController@destroy');
Route::post('/users/change-status/{id}', 'Auth\RegisterController@changeStatus');
Route::post('/reset-password', 'Auth\RegisterController@resetPassword');
//Permission 
Route::resource('permission', 'PermissionMapperController');
//Common routes 
Route::post('/updateCurrentTransaction','CommonController@updateCurrentTransaction');
Route::get('/getVendorDetails', 'CommonController@getVendorDetails');
Route::get('/getRoList', 'CommonController@getRoList');
Route::get('/getRoDetails', 'CommonController@getRoDetails');
Route::get('/download/{entity_id}/{master_id}','CommonController@downloadFile');
Route::get('/revision/{id}/{entity_slug}','CommonController@revisionLog');
Route::post('view','CommonController@logView')->name('revision-view');
Route::post('updateCopyTo','CommonController@updateCopyTo');
Route::get('deleteCopyTo/{id}','CommonController@deleteCopyTo');
Route::get('/getDepartmentWiseUserList/{id}','CommonController@getDepartmentWiseUserList');
//Vendor Views
Route::resource('/vendor', 'VendorController');
Route::post('/vendor/{id}', 'VendorController@destroy');


Route::resource('manage-profile', 'ManageProfileController');
Route::patch('/update/{id}', 'ManageProfileController@update');

Route::post('change_password', 'ManageProfileController@change_password');
Route::post('/removefile', 'CommonController@removefile');
Route::post('/uploadCheque', 'GarController@uploadCheque');
Route::get('/generatePdf', 'GarController@generatePdf');
Route::get('/mail', 'CommonController@sendMail');
Route::get('/otp', 'Auth\LoginController@sendOtp');
Route::post('/verify-otp', 'Auth\LoginController@verifyOtp');

//});
//Route::get('/getVendorDetails/', 'CommonController@getVendorDetails');
//Route::get('/getRoList', 'CommonController@getRoList');

//Budget
Route::get('/budget/edit', 'BudgetController@edit');
Route::post('/budget/update', 'BudgetController@update');
Route::resource('budget', 'BudgetController');
Route::post('budget_year', 'BudgetController@budgetList')->name('budget-year');
Route::post('eas_details', 'DashboardController@eas_details');
Route::post('search', 'DashboardController@search');
Route::post('get_status', 'DashboardController@getStatus');
Route::resource('bank-reconciliation', 'BankReconciliationController');

Route::get('/gar-register', 'RegistersController@garRegister');
Route::get('/ec-register', 'RegistersController@ecRegister');
Route::get('/diary-register', 'RegistersController@diaryRegister');
Route::get('/dispatch-register', 'RegistersController@dispatchRegister');
});
//Route::get('/getVendorDetails/', 'CommonController@getVendorDetails');
//Route::get('/getRoList', 'CommonController@getRoList');



//Route::get('/getVendorDetails/', 'CommonController@getVendorDetails');
//Route::get('/getRoList', 'CommonController@getRoList');


