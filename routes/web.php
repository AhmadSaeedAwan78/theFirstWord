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

// Route::get('language/{lang}', function($lang){
//      \Session::put('locale', $lang);
//      return redirect()->back();
//  })->middleware('language');
Route::get('/', function () {
    // return view('auth.login');
    
    return redirect('/login');
});
Auth::routes();

Route::get('/admin',             'CategoriesController@category')->name('admin');


Route::get('/category',       'CategoriesController@category')->name('category');
Route::get('/add_category',         'CategoriesController@add_category')->name('add_category');
Route::post('/store_category',         'CategoriesController@store_category')->name('store_category');
Route::get('/edit_category/{id}',   'CategoriesController@edit_category')->name('edit_category');
Route::post('/update_category/{id}',  'CategoriesController@update_category')->name('update_category');
Route::post('/delete_category',   'CategoriesController@delete_category')->name('delete_category');

// Items

Route::get('items/{id}',   'ItemsController@index')->name('items');
Route::get('add_item/{id}',   'ItemsController@add_item')->name('add_item');
Route::post('store_item',   'ItemsController@store_item')->name('store_item');
Route::get('/edit_item/{id}',   'ItemsController@edit_item')->name('edit_item');
Route::post('update_item/{id}',  'ItemsController@update_item')->name('update_item');
Route::post('delete_item',   'ItemsController@delete_item')->name('delete_item');

// Parents Discription

Route::get('/description',       'ParentsController@index')->name('description');
Route::get('/add_description',         'ParentsController@add_description')->name('add_description');
Route::post('/store_description',         'ParentsController@store_description')->name('store_description');
Route::get('/edit_description/{id}',   'ParentsController@edit_description')->name('edit_description');
Route::post('/update_description/{id}',  'ParentsController@update_description')->name('update_description');
Route::post('/delete_description',   'ParentsController@delete_description')->name('delete_description');

Route::get('/logout', 'Auth\LoginController@logout');


// Route::prefix("test")->group(function () {
//     Route::get("/check-email", function () {

//         Mail::raw("Hello world !", function ($message) {
//             $message->to("adnanafzal565test@gmail.com");
//         });

//         return "check email";
//     });
// });


// /* Route::get('/home', function(){
//     if (Auth::user()->role == 1)
//     {
//         return redirect('/admin');
//     }
//     if (Auth::user()->role == 2)
//     {
//         return redirect(route('forms_list'));
//     }
// }); */


// Route::get('users_management','Forms@users_management')->middleware(['auth','2fa']);
// Route::get('add_user','Forms@add_user')->middleware(['auth','2fa']);
// Route::get('edit_user/{id}','Forms@edit_user')->middleware(['auth','2fa']);
// Route::post('users/change_status', 'Forms@change_status')->middleware(['auth','2fa']);
// Route::post('store_user', 'Forms@store_user')->middleware(['auth','2fa']);
// Route::post('delete_user', 'Forms@delete_user')->middleware(['auth','2fa']);
// Route::post('store_edit/{id}', 'Forms@store_edit')->middleware(['auth','2fa']);

// /*     IncidentRegister Routes       */
//   Route::get('/incident', 'IncidentRegisterController@index')->middleware(['auth']);
//   Route::get('/add_inccident', 'IncidentRegisterController@create')->middleware(['auth']);
//   Route::post('/save_inccident', 'IncidentRegisterController@add')->middleware(['auth']);
//   Route::get('/edit_incident/{id}', 'IncidentRegisterController@edit_incident')->middleware(['auth']);
//   Route::post('/update_inccident', 'IncidentRegisterController@add')->middleware(['auth']);
//   Route::post('/incident/delete', 'IncidentRegisterController@destroy');


// Route::get('home', function(){
//     return redirect('dashboard');
// })->name('home');

// Route::get('/test',                             'HomeController@test')
//       ->name('test');

// Route::get('/dashboard',                            'UsersController@dashboard')
//       ->name('dashboard');

// Route::get('/2fa',                              'PasswordSecurityController@show2faform');

// Route::post('/2fa',                             'PasswordSecurityController@enable2fa')
//      ->name('enable2fa');

// Route::post('/generate2fasecret',         'PasswordSecurityController@generate2fasecret')
//      ->name('generate2fasecret');

// Route::post('/disable2fa',                    'PasswordSecurityController@disable2fa')
//      ->name('disable2fa');


// Route::get('/myredirect',                     'HomeController@my_redirect')
//      ->name('myredirect');

// Route::get('Forms/UserForm/{id}',             'Forms@show_form')
//      ->name('user_form_link');

// Route::get('Forms/CompanyUserForm/{link_id}',         'Forms@in_users_show_form')
//      ->name('user_form_link')->middleware(['auth','2fa']);

// Route::post('Forms/AjaxSubmitExtUserForm',       'Forms@ajax_ext_user_submit_form')
//      ->name('ajax_ext_user_submit_form');

// Route::post('Forms/AjaxSubmitUserForm',         'Forms@ajax_int_user_submit_form')
//      ->name('ajax_int_user_submit_form');

// Route::post('Forms/LockUserForm',         'Forms@ajax_lock_user_form')
//      ->name('ajax_lock_user_form');

// Route::get('Forms/FormSuccess',         'Forms@show_success_msg')
//      ->name('show_success_msg');

// Route::get('/Forms/UserFormsList',        'Forms@forms_list')
//      ->name('forms_list')->middleware(['auth','2fa']);

// Route::get('/Forms/CompanyUsersSubFormsList/{id}',     'Forms@subforms_email_list')
//      ->middleware(['auth','2fa'])
//      ->name('send_subforms_list');

// Route::get('/Forms/OrgSubFormsList/{id}',     'Forms@organization_all_forms_list')
//      ->middleware(['auth','2fa'])
//      ->name('org_all_forms_list');
//      // ahmad
// Route::get('/all_generated_forms',     'Forms@organization_all_forms_list_all')
//      ->middleware(['auth','2fa'])
//      ->name('all_generated_forms');     

// Route::get('Forms/UserForm/{client_id}/{user_id}/{client_email}/{subform_id}/{user_email}/{date_time}',         'Forms@show_form')
//      ->name('user_form_link');

// Route::get('Forms/ExtUserForm/{client_id}/{user_id}/{client_email}/{subform_id}/{user_email}/{date_time}',         'Forms@ex_users_show_form')
//      ->name('ext_user_form_link');

// Route::get('/Forms/ExtUserSendSubFormsList/{id}',     'Forms@ext_users_subforms_email_list')
//      ->name('ext_users_send_subforms_list')
//      ->middleware(['auth','2fa']);

// Route::post('Forms/AsgnSubFormToExUsers',      'Forms@assign_subform_to_external_users')
//      ->name('assign_subforms_to_external_users');

// Route::get('Forms/SubFormsList/{id}',         'Forms@subforms_list')
//      ->name('subforms_list')
//      ->middleware(['auth','2fa']);

// Route::get('Forms/SubFormToSend/{id}',         'Forms@send_email_subforms_list')
//      ->name('subform_to_send')
//      ->middleware(['auth','2fa']);

// Route::get('Forms/AddSubForm/{id}',         'Forms@add_subform')
//      ->name('add_subform')
//      ->middleware(['auth','2fa']);

// Route::get('Forms/EditSubform',           'SubformActions@edit_subform')
//      ->name('edit_subform')
//      ->middleware(['auth','2fa']);

// Route::get('Forms/DeleteSubform',           'SubformActions@delete_subform')
//      ->name('delete_subform')
//      ->middleware(['auth','2fa']);

// Route::post('Forms/GenerateSubForm',      'Forms@create_subform')
//      ->name('gen_subform')
//      ->middleware(['auth','2fa']);

// Route::get('Forms/CreateTestForm',        'Forms@create_test_user_form')
//      ->name('create_test_usr_form');

// Route::get('Forms/SendForm/{id}',         'Forms@send_form_link_to_users')
//     ->name('send_form_to_users')
//     ->middleware(['auth','2fa']);

// Route::get('Forms/AdminSite',             'AdminController@index')
//      ->name('admin_site')
//      ->middleware(['auth','2fa']);

// Route::get('Forms/FormAssignees/{id}',            'Forms@form_assignees')
//      ->name('admin_assignees')
//      ->middleware(['auth']);

// Route::get('Forms/SubFormAssignees/{id}',            'Forms@subform_assignees')
//      ->name('subform_assignees')
//      ->middleware(['auth','2fa']);

// Route::post('Forms/AssignFormToClient',            'Forms@assign_form_to_client')
//      ->name('assign_form_to_client')
//      ->middleware(['auth','2fa']);

// Route::post('Forms/AssignSubFormToUsers',           'Forms@ajax_assign_subform_to_users')
//      ->name('assign_subform_to_users')->middleware(['auth','2fa']);

// Route::get('Forms/FormsList',            'Forms@forms_list')
//      ->name('client_site')
//      ->middleware(['auth','2fa']);
// // ahmad     // 
// Route::get('Forms/All_Generated_Forms',            'Forms@organization_all_forms_list_all')
//      ->name('client_site_all_generated_forms')
//      ->middleware(['auth','2fa']);     

// Route::get('Forms/CompletedFormsList',            'Forms@completed_forms_list')
//      ->name('client_site')
//      ->middleware(['auth','2fa']);

// Route::get('Forms/ViewForm/{id}',         'Forms@view_form')
//      ->name('view_form')
//      ->middleware(['auth','2fa']);

// Route::get('Forms/UserSite',              'Forms@user_site')
//      ->name('user_site')
//      ->middleware(['auth','2fa']);

// Route::get('Forms/ClientUserFormsList',     'Forms@client_user_subforms_list')
//      ->name('client_user_subforms_list')
//      ->middleware(['auth','2fa']);

// Route::get('FormSettings/SubFormsExpirySettings',   'Forms@show_subforms_expiry_settings')
//      ->name('subforms_expiry_settings')
//      ->middleware(['auth','2fa']);

// Route::post('FormSettings/SubFormsExpirySettings', 'Forms@save_subforms_expiry_settings')
//      ->name('subforms_expiry_settings')
//      ->middleware(['auth','2fa']);

// // -------------------------------- SAR FORMS --------------------------------

// // Forms/SubFormAssignees/
// // Forms/OrgSubFormsList
// Route::get('SAR/SubFormAssignees/{id}',            'Forms@subform_assignees')
//      ->name('sar_subform_assignees')
//      ->middleware(['auth','2fa']);

// Route::get('SAR/OrgSubFormsList/{id}',     'Forms@organization_all_forms_list')
//      ->middleware(['auth','2fa'])
//      ->name('sar_org_all_forms_list');

// Route::get('SAR/CompanyUserForm/{link_id}',         'Forms@in_users_show_form')
//  ->name('sar_user_form_link')->middleware(['auth','2fa']);

// Route::get('SAR/ExtUserForm/{client_id}/{user_id}/{client_email}/{subform_id}/{user_email}/{date_time}',         'Forms@ex_users_show_form')
//      ->name('sar_ext_user_form_link');


// Route::get('SAR/ShowSARAssignees/{form_id}',            'SARForm@assignee_list')
//     ->name('show_SAR_assignee_list')
//     ->middleware(['auth','2fa']);

// Route::get('SAR/SARCompletedFormsList',            'SARForm@sar_completed_forms_list')->middleware(['auth','2fa']);

// Route::get('SAR/SARInCompletedFormsList',            'SARForm@sar_incompleted_forms_list')->middleware(['auth','2fa']);


// Route::get('FormSettings/SARExpirySettings',             'SARForm@sar_expiry_settings_get')->middleware(['auth','2fa']);
// Route::post('FormSettings/SARExpirySettings',            'SARForm@sar_expiry_settings_post')->middleware(['auth','2fa']);
// Route::post('SAR/ChangeRequestStatus',          'SARForm@change_sar_request_status_post')->middleware(['auth','2fa']);


// // -------------------------------- SAR FORMS --------------------------------

// Route::post('/2faverify', function(){
//     //return redirect(URL()->previous());
//         $role_routes = [
//                           //1 => 'admin_site',
//                             1 => 'admin_def',
//                             2 => 'dashboard',
//                             3 => 'dashboard',
//                             //3 => 'client_user_subforms_list'
//                        ];
                       
//         $user_role = Auth::user()->role;               
                       
//         if (Auth::user()->user_type == '1'){
//             $user_role = 2;
//         }

//         return redirect(route($role_routes[$user_role]));

// })->name('2faverify')->middleware('2fa'); 


// // Route::post('/2faverify', function(){
// //  return redirect(URL()->previous());
// // })->name('2faverify')->middleware('2fa');

// Route::get('Forms/TestEmail',            'HomeController@test_email')
//      ->name('test_email');



// // wakeel

// Route::get('/profile/{id}','PackagesController@profile');


// Route::get('/terms', 'HomeController@terms');

// Route::get('/privacy', 'HomeController@privacy');

// Route::get('/subscriber', 'HomeController@subscriber');

// Route::get('/howtoplay', 'HomeController@howtoplay');

// Auth::routes();

// Route::get('clear', function(){

// Artisan::call('cache:clear');

// Artisan::call('route:clear');

// return redirect()->back();

// });





// // Users

// Route::get('/users/edit/{id}', 'UsersController@edit');

// Route::get('/users/detail/{id}', 'UsersController@detail');

// Route::get('/users/add', 'UsersController@addUser');

// Route::post('/users/store', 'UsersController@store');

// Route::post('/users/delete', 'UsersController@destroy');


// Route::post('/users/edit_store/{id}', 'UsersController@edit_store');

// Route::post('/client/store', 'UsersController@clientStore');

// Route::get('/client/add', 'UsersController@addClient');


// Route::post('/save_images','PackagesController@save_images')->name('save_images');

// Route::post('package/save_related_product','PackagesController@save_related_product');


// Route::get('/Forms/SecCategory/{id}', 'Forms@section_category');
// Route::post('/Forms/UpdateFormSection/', 'Forms@ajax_update_form_section_heading')
// ->name('update_form_section_heading');

// Route::post('/Forms/AsgnSecCategory/', 'Forms@assign_section_category')
//      ->middleware(['auth','2fa']);

// Route::post('/Forms/AsgnSecCategory/', 'Forms@assign_section_category')
//     ->name('asgn_sec_ctgry')
//     ->middleware(['auth','2fa']);
    
// Route::get('/Reports/AssetsReports/{id}',   'Reports@response_reports')
//      ->name('assets_reports')
//      ->middleware(['auth','2fa']);

// Route::get('/Reports/DataInvReports/{id}', 'Reports@response_reports')
//      ->name('data_inventory_reports')
//      ->middleware(['auth','2fa']);

// Route::get('/Reports/AssetsReportsEx/{id}',   'Reports@response_reports_external_users')
//      ->name('assets_reports_ex')
//      ->middleware(['auth','2fa']);
     
// Route::get('/Reports/DataInvReportsEx/{id}',  'Reports@response_reports_external_users')
//      ->name('data_inventory_reports_ex')
//      ->middleware(['auth','2fa']);
     
// Route::get('/Reports/AssetsReportsReg/{id}',   'Reports@response_reports_registered_users')
//      ->name('assets_reports_in')
//      ->middleware(['auth','2fa']);
     
// Route::get('/Reports/DataInvReportsReg/{id}',  'Reports@response_reports_registered_users')
//      ->name('data_inventory_reports_in')
//      ->middleware(['auth','2fa']);

// Route::get('Reports/SummaryReports', 'Reports@summary_reports_sfw')
//      ->name('summary_reports')
//      ->middleware(['auth','2fa']);
     
// // Route::get('Reports/CompanyReports', 'Reports@summary_reports_all')
// //      ->name('summary_reports_all')
// //      ->middleware(['auth','2fa']);
//      // 
//      // Ahmad
// Route::get('Reports/GlobalDataInventory', 'Reports@summary_reports_all')
//      ->name('summary_reports_all')
//      ->middleware(['auth','2fa']);

//      Route::get('Reports/DetailedDataInventory', 'Reports@summary_reports_all_2')
//      ->name('detail_data_inventory_report')
//      ->middleware(['auth','2fa']);
//      //
//      // 
// Route::post('Reports/CompanyReportsPDF', 'Reports@summary_reports_all_PDF')
//     ->name('summary_reports_all_pdf')
//     ->middleware(['auth','2fa']);

// Route::get('Reports/DownloadCompanyReportsPDF/{pdfname}', 'Reports@download_generated_pdf')
//     ->name('download_summary_reports_all_pdf')
//     ->middleware(['auth','2fa']);

// Route::get('question',function(){

//   return view('admin.question.questionForm');
// });

// Route::post('reset_password', 'HomeController@reset');

// Route::post('/profile/edit','PackagesController@profile_edit');

// // Route::get('/send_email/{id}','PackagesController@send_email');

// // Route::get('/admin', 'UsersController@index')->name('admin_def');



// // -------------------------------- Login Image SETTINGS --------------------------------
// Route::get('/login_img_settings', 'HomeController@login_img_settings')->middleware(['auth']);
// Route::post('/update_login_img', 'HomeController@update_login_img')->middleware(['auth']);
// // -------------------------------- Login Image SETTINGS --------------------------------


// // -------------------------------- FORMS SETTINGS --------------------------------
// Route::post('/formsettings/unlock_form', 'FormSettings@unlock_form')->middleware(['auth'])->name('unlock_form');
// Route::post('/formsettings/change_form_access', 'FormSettings@change_form_access')->middleware(['auth'])->name('change_form_access');

// // -------------------------------- FORMS SETTINGS --------------------------------



// // -------------------------------- ASSETS --------------------------------

// Route::get('/assets',    'AssetsController@index')->name('asset_list')->middleware(['auth','2fa']);
// Route::get('/add_asset', 'AssetsController@add_asset')->name('add_asset')->middleware(['auth','2fa']);
// // Route::get('/del_asset', 'AssetsController@delete_asset')->name('delete_asset')->middleware(['auth']);
// Route::post('delete_asset', 'AssetsController@delete_asset')->middleware(['auth']);
// Route::post('/asset_update', 'AssetsController@asset_update')->name('asset_update');
// Route::post('/asset_add', 'AssetsController@asset_add')->name('asset_add')->middleware(['auth','2fa']);
// Route::post('/update_asset', 'AssetsController@update_asset')->name('update_asset')->middleware(['auth','2fa']);
// Route::get('/asset_delete/{id}', 'AssetsController@asset_delete');
// Route::get('/asset_edit/{id}', 'AssetsController@asset_edit')->name('asset_edit')->middleware(['auth','2fa']);


// // -------------------------------- ASSETS --------------------------------
// // -------------------------------- ACTIVITIES --------------------------------

// Route::get('/activities',    'ActivitiesController@index')->name('activity_list')->middleware(['auth','2fa']);

// // -------------------------------- ACTIVITIES --------------------------------

// Route::get('/Orgusers/permissions/{id}', 'Forms@permissions')->middleware(['auth']);
// Route::post('orgusers/permissions/store', 'Forms@permissions_store')->middleware(['auth']);


// Route::group(["middleware" => 'admin'], function () {
    
// Route::get('/site_admins',       'Admin@site_admins')->name('site_admins');
// Route::get('/add_admin',         'Admin@add_admin')->name('add_admin');
// Route::post('/add_admin',        'Admin@add_admin_act')->name('add_admin_act');
// Route::get('/edit_admin/{id}',   'Admin@edit_admin')->name('edit_admin');
// Route::post('/edit_admin/{id}',  'Admin@edit_admin_act')->name('edit_admin_act');
// Route::get('edit_form/{id}',     'Admin@edit_form')->name('edit_form_info');
// Route::post('edit_form/{id}',    'Admin@edit_form_act')->name('edit_form_info_act');

// // category







// Route::get('/company', 'UsersController@company')->middleware(['auth']); 

// Route::get('selectClient', 'PackagesController@index')->middleware(['auth']);

// Route::get('client', 'PackagesController@client')->middleware(['auth']);

// Route::post('saveClient/{id}', 'PackagesController@saveClient')->middleware(['auth']);

// Route::get('client/user/{id}', 'PackagesController@showUser')->middleware(['auth']);


// Route::get('/users/edit/{id}', 'UsersController@edit');
// // Route::get('/orgUser/permission/{id}', 'Forms@addPermission')->middleware(['auth']);

// Route::get('/users/edit_company/{id}', 'UsersController@edit_company')->middleware(['auth']);
// Route::get('/users/permissions/{id}', 'UsersController@permissions')->middleware(['auth']);
// Route::get('/users/detail/{id}', 'UsersController@detail')->middleware(['auth']);
// Route::get('/users/add/{id}', 'UsersController@addUser')->middleware(['auth']);
// Route::post('/users/store', 'UsersController@store')->middleware(['auth']);
// Route::post('users/permissions/store', 'UsersController@permissions_store')->middleware(['auth']);
// Route::post('/users/delete', 'UsersController@destroy')->middleware(['auth']);
// Route::post('/users/edit_store/{id}', 'UsersController@edit_store')->middleware(['auth']);
// Route::post('/users/editCompany_store/{id}', 'UsersController@editCompany_store')->middleware(['auth']);
// Route::post('/client/store', 'UsersController@clientStore')->middleware(['auth']);
// Route::get('/client/add', 'UsersController@addClient')->middleware(['auth']);
// Route::get('Forms/AdminFormsList/{type?}',            'Forms@all_forms_list')
//      ->name('admin_forms_list')->middleware(['auth']);
// Route::get('Forms/AdminFormsList',            'Forms@all_forms_list')
//      ->name('admin_forms_list')->middleware(['auth']);

// // category



// });


// Route::get('/category',       'CategoriesController@category')->name('category');
// Route::get('/add_category',         'CategoriesController@add_category')->name('add_category');



