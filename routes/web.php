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
use App\Http\Controllers\VouchertypeController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Auth\ACLController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\WorkTypeController;
// Main Page Route

// authentication
Auth::routes();
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
// Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

Route::get('/syncfromtallysyncnodemodule', [Analytics::class, 'syncfromtallysyncnodemodule']);

Route::group(['middleware' => 'auth'], function () { 
    
    Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');
    Route::get('/home', [Analytics::class, 'index'])->name('home');

    
    // layout
    // Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
    // Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
    // Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
    // Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
    // Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');
    
    // pages
    // Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
    // Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
    // Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
    // Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
    // Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');
    
    // cards
    // Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');
    
    // User Interface
    // Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
    // Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
    // Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
    // Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
    // Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
    // Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
    // Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
    // Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
    // Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
    // Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
    // Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
    // Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
    // Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
    // Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
    // Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
    // Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
    // Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
    // Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
    // Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');
    
    // extended ui
    // Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
    // Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');
    
    // icons
    // Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');
    
    // form elements
    // Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
    // Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');
    
    // form layouts
    // Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
    // Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');
    
    // tables
    // Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');
    
    
    Route::post('/voucher-no', [HomeController::class, 'voucherNo']);
    // Route::get('/voucher-no/{voucherType}', [HomeController::class, 'voucherNo']);
    Route::get('/sub-groups/{main_group_id}', [HomeController::class, 'getSubGroups']);
    Route::get('/ledgers/{group_id}', [HomeController::class, 'getLedgers']);
    Route::get('/stockitems/{group_id}', [HomeController::class, 'getStockItems']);
    Route::get('/areas/{region_id}', [HomeController::class, 'getAreas']);
    Route::get('/terriitorys/{area_id}', [HomeController::class, 'getTerriitorys']);

    Route::get('/worktypeveriations/{area_id}', [HomeController::class, 'getWorkTypeVeriations']);

    
    // stock item
    Route::prefix('stockitem')->group(function(){
        Route::get('/',[StockItemController::class,'index'])->name('stockitem');
        Route::get('/create',[StockItemController::class,'create'])->name('stockitem-create');
        Route::post('/create-action',[StockItemController::class,'createAction']);
        Route::get('/update/{id}',[StockItemController::class,'update']);
        Route::post('/edit-action/{id}',[StockItemController::class,'editAction']);
        Route::get('/sync-to-tally-action/{id}',[StockItemController::class,'importAction']);
        Route::get('/getItems/{id}',[StockItemController::class,'getStockItem']);
    
    });



    // Setting
    Route::prefix('setting')->group(function(){
        Route::get('/group',[SettingController::class,'group'])->name('setting-group');

        Route::get('/subgroup',[SettingController::class,'subGroup'])->name('setting-subgroup');
        Route::get('/subgroup/create',[SettingController::class,'subGroupCreate'])->name('setting-subgroup-create');
        Route::match(['get', 'post'],'/subgroup/update/{id}',[SettingController::class,'subGroupUpdate']);
        Route::post('/subgroup/create-action',[SettingController::class,'subGroupCreateAction']);

        Route::get('/item',[SettingController::class,'item'])->name('setting-item');
        Route::match(['get', 'post'],'/item-update/{id}',[SettingController::class,'itemUpdate'])->name('setting-item-update');

        Route::get('/ledger',[LedgerController::class,'index'])->name('setting-ledger');


        Route::get('/voucher-type',[SettingController::class,'voucherType'])->name('setting-voucher-type');
        Route::post('/updateVoucherType',[VouchertypeController::class,'updateVoucherType'])->name('setting.updateVoucherType');
        
        Route::get('/update/{id}',[SettingController::class,'update']);
        Route::post('/edit-action/{id}',[SettingController::class,'editAction']);
        Route::get('/sync-to-tally-action/{id}',[SettingController::class,'importAction']);
        Route::get('/getItems/{id}',[SettingController::class,'getStockItem']);


        
        Route::get('/work-name',[SettingController::class,'workName'])->name('setting-work-name');
        Route::get('/work-name/create',[SettingController::class,'workNameCreate'])->name('setting-work-name-create');
        Route::match(['get', 'post'],'/work-name/update/{id}',[SettingController::class,'workNameUpdate']);
        Route::post('/work-name/create-action',[SettingController::class,'workNameCreateAction']);



        // Route::get('/work-type',[SettingController::class,'workType'])->name('setting-work-type');
        Route::get('/work-type/create',[SettingController::class,'workTypeCreate'])->name('setting-work-type-create');
        Route::get('/work-types',[WorkTypeController::class,'workTypes'])->name('setting-work-types');
        Route::get('/work-types/edit',[WorkTypeController::class,'workTypesEdit'])->name('setting-work-types-edit');
        Route::post('/work-types/update',[WorkTypeController::class,'workTypeUpdate'])->name('setting-work-types-update');

        Route::get('/master_size',[SettingController::class,'masterSize'])->name('setting-master-size');
        Route::get('/master_size/create',[SettingController::class,'masterSizeCreate'])->name('setting-master-size-create');

        Route::get('/master_color',[SettingController::class,'masterColor'])->name('setting-master-color');
        Route::get('/master_color/create',[SettingController::class,'masterColorCreate'])->name('setting-master-color-create');

        Route::get('/master_weight',[SettingController::class,'masterWeight'])->name('setting-master-weight');
        Route::get('/master_weight/create',[SettingController::class,'masterWeightCreate'])->name('setting-master-weight-create');

        Route::get('/master_paper',[SettingController::class,'masterPaper'])->name('setting-master-paper');
        Route::get('/master_paper/create',[SettingController::class,'masterPaperCreate'])->name('setting-master-paper-create');

        Route::get('/master_lamination',[SettingController::class,'masterLamination'])->name('setting-master-lamination');
        Route::get('/master_lamination/create',[SettingController::class,'masterLaminationCreate'])->name('setting-master-lamination-create');
    });
    
    Route::prefix('ledger')->group(function(){
        Route::get('/',[LedgerController::class,'index'])->name('ledger');
        Route::get('/create',[LedgerController::class,'create'])->name('ledger-create');
        Route::post('/create-action',[LedgerController::class,'createAction']);
        Route::match(['get', 'post'],'/update/{id}',[LedgerController::class,'update']);
        Route::post('/edit-action/{id}',[LedgerController::class,'editAction']);
        Route::get('/sync-to-tally-action/{id}',[LedgerController::class,'importAction']);

        Route::get('/work-names/{id}',[LedgerController::class,'workNames']);
    });
    
    Route::prefix('vouchertype')->group(function(){
        // Route::get('/',[VouchertypeController::class,'index'])->name('voucher');
        Route::get('/type',[VouchertypeController::class,'viewType'])->name('vouchertype');
        // Route::post('/updateVoucherType',[VouchertypeController::class,'updateVoucherType'])->name('updateVoucherType');
    });
    
    Route::prefix('purchase')->group(function(){
        Route::get('/list',[PurchaseController::class,'list'])->name('purchase-list');
        Route::get('/details/{id}',[PurchaseController::class,'details'])->name('purchase-details');
        Route::match(['get', 'post'],'/edit/{id}',[PurchaseController::class,'updateFormAndStore'])->name('purchase-update');
        Route::post('/update-action',[PurchaseController::class,'updateSalesOrderAction'])->name('purchase-update-action');
        Route::get('/delete/{id}',[PurchaseController::class,'salDelete'])->name('purchase-delete');
        Route::get('/new',[PurchaseController::class,'newEntryForm'])->name('purchase-new');
        Route::get('/new-desktop',[PurchaseController::class,'newDesktopEntryForm'])->name('purchase-new-desktop');
        // Route::get('/new-desktopn',[PurchaseController::class,'newDesktopEntryFormn'])->name('purchase-new-desktopn');
        Route::get('/status/{id}',[PurchaseController::class,'changeStatus'])->name('purchase-status-changes');
        Route::post('/voucher-create',[PurchaseController::class,'newStore'])->name('purchase.voucher.create');
        Route::get('/removeSalesItems/{id}',[PurchaseController::class,'removeSalesItems'])->name('purchase-removeSalesItems');
    });
    
    Route::prefix('sales')->group(function(){
        Route::get('/list',[SalesController::class,'salesList'])->name('sales-list');
        Route::get('/details/{id}',[SalesController::class,'details'])->name('sales-details');
        Route::match(['get', 'post'],'/edit/{id}',[SalesController::class,'updateFormAndStore'])->name('sales-update');
        // Route::post('/update-action',[SalesController::class,'updateSalesOrderAction'])->name('sales-update-action');
        Route::get('/delete/{id}',[SalesController::class,'salDelete'])->name('sales-delete');
        Route::get('/new',[SalesController::class,'newEntryForm'])->name('sales-new');
        Route::get('/new-desktop',[SalesController::class,'newDesktopEntryForm'])->name('sales-new-desktop');
        Route::get('/invoice-pp',[SalesController::class,'ppEntryForm'])->name('sales-invoice-pp');
        Route::get('/status/{id}',[SalesController::class,'changeStatus'])->name('sales-status-changes');
        Route::post('/voucher-create',[SalesController::class,'newStore'])->name('sales.voucher.create');
        Route::post('/voucher-create-pp',[SalesController::class,'newStorePp'])->name('sales-invoice-create');
        Route::get('/details-pp/{id}',[SalesController::class,'detailsPp'])->name('sales-details-pp');//?ln=bn
        Route::get('/removeSalesItems/{id}',[SalesController::class,'removeSalesItems'])->name('sales-removeSalesItems');
    });
    
    Route::prefix('receipt')->group(function(){
        Route::get('/list',[ReceiptController::class,'receiptList'])->name('receipt-list');
        Route::get('/details/{id}',[ReceiptController::class,'details'])->name('receipt-details');
        Route::get('/delete/{id}',[ReceiptController::class,'recDelete'])->name('receipt-delete');
        Route::any('/edit/{id}',[ReceiptController::class,'recEdit'])->name('receipt-edit');
        Route::get('/new',[ReceiptController::class,'newReceipt'])->name('receipt-new');
        Route::post('/voucher-create',[ReceiptController::class,'newReceiptStore'])->name('receipt.voucher.create');
    });


    Route::prefix('voucher')->group(function(){
        Route::get('/list',[VoucherController::class,'voucherList'])->name('voucher-list');
        Route::get('/receive-cash',[VoucherController::class,'receiveCash'])->name('voucher-receive-cash');
        Route::get('/receive-bank',[VoucherController::class,'receiveBank'])->name('voucher-receive-bank');
        Route::get('/payment-cash',[VoucherController::class,'paymentCash'])->name('voucher-payment-cash');
        Route::get('/payment-bank',[VoucherController::class,'paymentBank'])->name('voucher-payment-bank');
        // Both of Receive & Payment
        Route::post('/cash-voucher-create',[VoucherController::class,'newCashStore'])->name('cash.voucher.create');
        Route::get('/adjustment',[VoucherController::class,'adjustment'])->name('voucher-adjustment');
        Route::post('/bank-voucher-create',[VoucherController::class,'newBankStore'])->name('bank.voucher.create');
        Route::post('/create',[VoucherController::class,'newCreate'])->name('voucher.create');
        Route::post('/receivecash-create',[VoucherController::class,'newReceiveCash'])->name('voucher-receivecash-create');
        Route::post('/paymentcash-create',[VoucherController::class,'newPaymentCash'])->name('voucher-paymentcash-create');
        Route::get('/delete/{id}',[VoucherController::class,'delete'])->name('voucher-delete');

        Route::get('/details/{id}',[VoucherController::class,'details'])->name('voucher-details');
        Route::any('/edit/{id}',[VoucherController::class,'voucherEdit'])->name('voucher-edit');

        Route::match(['get', 'post'],'/paymentcash-update/{id}',[VoucherController::class,'paymentCashEditFormAndStore'])->name('voucher-paymentcash-update');
        Route::match(['get', 'post'],'/paymentbank-update/{id}',[VoucherController::class,'paymentBankEditFormAndStore'])->name('voucher-paymentbank-update');
        Route::match(['get', 'post'],'/receivecash-update/{id}',[VoucherController::class,'receiveCashEditFormAndStore'])->name('voucher-receivecash-update');
        Route::match(['get', 'post'],'/adjustment-update/{id}',[VoucherController::class,'adjustmentEditFormAndStore'])->name('voucher-adjustment-update');
    });
    
    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::post('/report', [ReportController::class, 'report'])->name('report');

    Route::any('/daybook', [ReportController::class, 'dayBook'])->name('dayBook');
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
    Route::get('user/ledger/{id}', [UserController::class, 'userLedger'])->name('userLedger');
    Route::get('user/item/{id}', [UserController::class, 'userItem'])->name('userItem');
    Route::post('manage-user-ledger', [UserController::class, 'storeUpdateUserLadger'])->name('storeUpdateUserLadger');
    Route::post('manage-user-item', [UserController::class, 'storeUpdateUserItem'])->name('storeUpdateUserItem');
});

