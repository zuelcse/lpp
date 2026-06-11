<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sales;
use App\Models\Receipt;
use App\Models\Voucher;
use DB;
use Auth;

class Analytics extends Controller
{
	public function __construct()
    {
        $this->middleware('auth')->except('syncfromtallysyncnodemodule');
    
    }
    
  	public function index()
  	{
  	    $today = date('Y-m-d');
  	    $sales = Sales::select(
  	        DB::raw('COUNT(id) as sales_qty'),
  	        DB::raw('SUM(gross_amount) as sales_value')
  	        )
  	        ->where(['date'=>$today,'user_id'=>Auth::user()->id])
  	        ->first();
  	    $receipt = Voucher::select(
  	        DB::raw('COUNT(id) as receipt_qty'),
  	        DB::raw('SUM(total_amount) as receipt_value')
  	        )
  	        ->where(['date'=>$today,'user_id'=>Auth::user()->id])
  	        ->first();

        // Path to your batch file
        // $batchFile = 'D:\\laragon\\www\\TallySync\\run2.bat';

        // Run the batch file hidden
        // $output = system($batchFile);
        // dd($output);

    	return view('content.dashboard.dashboards-analytics',compact('sales','receipt'));
  	}

    // This Method Run from the run2.bat file at customer Local server Pc. ZuelAli (+8801738051123), 250109
    // This link will execute as run2.bat file execute or open.
    // Link: curl https://smartpolymerbms.com/syncfromtallysyncnodemodule
    // Link: curl https://tallyweb.smartpolymerbms.com/syncfromtallysyncnodemodule
    public function syncfromtallysyncnodemodule(){

        DB::select("INSERT INTO units ( name,isSimpleUnit,alterId,decimalPlaces,numberOfUnits)
            SELECT mu.name, mu.isSimpleUnit, mu.alterId, mu.decimalPlaces, mu.numberOfUnits
            FROM mst_units mu
            LEFT JOIN units u
            ON mu.name = u.name 
            WHERE u.name  IS NULL");

        DB::select("INSERT INTO mst_cost_centre ( name,parent,category)
            SELECT ct.name, ct.parent, ct.category
            FROM mst_cost_centre_tally ct
            LEFT JOIN mst_cost_centre c
            ON ct.name = c.name 
            WHERE c.name  IS NULL");

        DB::select("INSERT INTO stock_items (`name`, `parent`, `alias`, `baseUnits`, `openingBalance`, `openingValue`, `openingRate`, `closing_balance`, `standard_price`,`masterId`)
            SELECT msi.`name`, msi.`parent`, msi.`alias`, u.`id`, msi.`opening_balance`, msi.`opening_value`, msi.`opening_rate`, msi.`closing_balance`, msi.`standard_price`, msi.`masterId`
            FROM  mst_stock_item msi
            LEFT JOIN  units u
            ON msi.base_units = u.name
            ON DUPLICATE KEY UPDATE
            name = VALUES(`name`),
            parent = VALUES(`parent`),
            alias = VALUES(`alias`),
            baseUnits = VALUES(`baseUnits`),
            closing_balance = VALUES(`closing_balance`),
            standard_price = VALUES(`standard_price`)
        ");

        DB::select("INSERT INTO mst_ledger (`guid`, `name`, `CreditLimit`, `parent`, `alias`, `is_revenue`, `is_deemedpositive`, `opening_balance`,`closing_balance`, `description`, `mailing_name`, `mailing_address`, `mailing_state`, `mailing_country`, `mailing_pincode`, `email`, `it_pan`, `gstn`, `gst_registration_type`, `gst_supply_type`, `gst_duty_head`, `tax_rate`, `bank_account_holder`, `bank_account_number`, `bank_ifsc`, `bank_swift`, `bank_name`, `bank_branch`,`masterId`)
            SELECT mlt.`guid`, mlt.`name`, mlt.`CreditLimit`, mlt.`parent`, mlt.`alias`, mlt.`is_revenue`, mlt.`is_deemedpositive`, mlt.`opening_balance`,mlt.`closing_balance`, mlt.`description`, mlt.`mailing_name`, mlt.`mailing_address`, mlt.`mailing_state`, mlt.`mailing_country`, mlt.`mailing_pincode`, mlt.`email`, mlt.`it_pan`, mlt.`gstn`, mlt.`gst_registration_type`, mlt.`gst_supply_type`, mlt.`gst_duty_head`, mlt.`tax_rate`, mlt.`bank_account_holder`, mlt.`bank_account_number`, mlt.`bank_ifsc`, mlt.`bank_swift`, mlt.`bank_name`, mlt.`bank_branch`,mlt.`masterId`
            FROM  mst_ledger_tally mlt
            ON DUPLICATE KEY UPDATE
            name = VALUES(`name`),
            alias = VALUES(`alias`),
            parent = VALUES(`parent`),
            mailing_name = VALUES(`mailing_name`),
            mailing_address = VALUES(`mailing_address`),
            bank_account_number = VALUES(`bank_account_number`),
            bank_name = VALUES(`bank_name`),
            opening_balance = VALUES(`opening_balance`),
            closing_balance = VALUES(`closing_balance`),
            CreditLimit = VALUES(`CreditLimit`)
        ");

        DB::select("INSERT IGNORE INTO all_voucher_list (`guid`, `IsPerticulats`, `VoucherTypeName`, `VoucherNumber`, `OptionalVoucher`, `LedgerName`, `DrAMT`, `CrAMT`, `Date`, `IsOpeningBalance`, `updated_at`, `IsClosingBalance`) SELECT avlt.`guid`, avlt.`IsPerticulats`, avlt.`VoucherTypeName`, avlt.`VoucherNumber`, avlt.`OptionalVoucher`, avlt.`LedgerName`, avlt.`DrAMT`, avlt.`CrAMT`, avlt.`Date`, avlt.`IsOpeningBalance`, avlt.`updated_at`, avlt.`IsClosingBalance` FROM all_voucher_list_tally avlt WHERE 1");

        DB::select("INSERT IGNORE INTO all_voucher_list (`guid`, `IsPerticulats`, `VoucherTypeName`, `VoucherNumber`, `OptionalVoucher`, `LedgerName`, `DrAMT`, `CrAMT`, `Date`, `IsOpeningBalance`, `updated_at`, `IsClosingBalance`) SELECT avlt.`guid`, avlt.`IsPerticulats`, avlt.`VoucherTypeName`, avlt.`VoucherNumber`, avlt.`OptionalVoucher`, avlt.`LedgerName`, avlt.`DrAMT`, avlt.`CrAMT`, avlt.`Date`, avlt.`IsOpeningBalance`, avlt.`updated_at`, avlt.`IsClosingBalance` FROM all_voucher_list_tally_journal avlt WHERE 1");
        
        
            
        echo "Web synced!";
        exit();
    }
}
