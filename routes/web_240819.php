<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\StockItemController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\Auth\ACLController;
use App\Http\Controllers\Auth\UserController;
// Main Page Route
Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');
Route::get('/home', [Analytics::class, 'index'])->name('home');

// layout
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

// cards
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

// User Interface
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

// form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');

Auth::routes();

// stock item
Route::prefix('stockitem')->group(function(){
    Route::get('/',[StockItemController::class,'index'])->name('stockitem');
    Route::get('/create',[StockItemController::class,'create']);
    Route::post('/create-action',[StockItemController::class,'createAction']);
    Route::get('/update/{id}',[StockItemController::class,'update']);
    Route::post('/edit-action/{id}',[StockItemController::class,'editAction']);
    Route::get('/sync-to-tally-action/{id}',[StockItemController::class,'importAction']);
    Route::get('/getItems/{id}',[StockItemController::class,'getStockItem']);

});

Route::prefix('ledger')->group(function(){
    Route::get('/',[LedgerController::class,'index']);
});

Route::prefix('voucher')->group(function(){
    Route::get('/',[VoucherController::class,'index']);
    Route::get('/type',[VoucherController::class,'viewType']);
});

Route::prefix('sales')->group(function(){
    Route::get('/list',[SalesController::class,'salesOrderList']);
    Route::get('/new',[SalesController::class,'newSalesOrder']);
    Route::post('/voucher-create',[SalesController::class,'newSalesOrderStore'])->name('sales.voucher.create');
});

Route::prefix('receipt')->group(function(){
    Route::get('/list',[ReceiptController::class,'receiptList']);
    Route::get('/new',[ReceiptController::class,'newReceipt']);
    Route::post('/voucher-create',[ReceiptController::class,'newReceiptStore'])->name('receipt.voucher.create');
});

/* Manage acl Routes */    
Route::group(['middleware' => 'auth', 'prefix' => 'acl'], function () {  
      
    // ACL routes
    Route::get('user/roles', [ACLController::class, 'manageUserRole'])->name('acl-manageUserRole');
    Route::post('role/store', [ACLController::class, 'storeRole'])->name('storeRole');
    Route::get('update/role-permissions/{id}', [ACLController::class, 'updateRolePermissions'])->name('acl-updateRolePermissions');
    Route::post('update/role', [ACLController::class, 'updateRoleItem'])->name('updateRoleItem');
    Route::get('role/delete/{role_id}', [ACLController::class, 'roleItemDelete'])->name('roleItemDelete');

    Route::post('role/user/update', [ACLController::class, 'roleUpdateToUser'])->name('roleUpdateToUser');
    Route::post('load/ajax-role-permissions', [ACLController::class, 'ajaxRolePermissionLoad'])->name('ajaxRolePermissionLoad');

    Route::get('user/permissions', [ACLController::class, 'manageUserPermissions'])->name('acl-manageUserPermissions');
    Route::get('user/manage-permissions/{id}', [ACLController::class, 'updateUserPermission'])->name('updateUserPermission');

    Route::get('permission/delete/{id}', [ACLController::class, 'permissionItemDelete'])->name('permissionItemDelete');

    Route::post('store/permission', [ACLController::class, 'storePermission'])->name('storePermission');
    Route::post('update/permission', [ACLController::class, 'updatePermission'])->name('updatePermission');
    Route::post('store/parent-permission', [ACLController::class, 'storePatentPermission'])->name('storePatentPermission');

    Route::post('manage-user-permissions', [ACLController::class, 'storeUpdateUserPermissionAll'])->name('storeUpdateUserPermissionAll');

    Route::get('/user/create', [RegisterBasic::class, 'index'])->name('acl-auth-register-basic');

    Route::get('user/list', [UserController::class, 'index'])->name('acl-userList');
    Route::post('update/user', [UserController::class, 'updateUser'])->name('updateUser');
    Route::get('user/delete/{id}', [UserController::class, 'userDelete'])->name('userDelete');
});

