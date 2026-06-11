<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class tallyBDsync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tally-b-dsync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        exit('This script is off permanantly, it will execue with- https://tallyweb.smartpolymerbms.com/syncfromtallysyncnodemodule');
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

            // DB::select("INSERT INTO stock_items (`name`, `parent`, `alias`, `baseUnits`, `openingBalance`, `openingValue`, `openingRate`, `closing_balance`, `standard_price`)
            // SELECT msi.`name`, msi.`parent`, msi.`alias`, u.`id`, msi.`opening_balance`, msi.`opening_value`, msi.`opening_rate`, msi.`closing_balance`, msi.`standard_price`
            // FROM  mst_stock_item msi
            // LEFT JOIN  stock_items st
            // ON msi.name = st.name 
            // LEFT JOIN  units u
            // ON msi.base_units = u.name
            // WHERE st.name  IS NULL");

            DB::select("INSERT INTO stock_items (`name`, `parent`, `alias`, `baseUnits`, `openingBalance`, `openingValue`, `openingRate`, `closing_balance`, `standard_price`)
            SELECT msi.`name`, msi.`parent`, msi.`alias`, u.`id`, msi.`opening_balance`, msi.`opening_value`, msi.`opening_rate`, msi.`closing_balance`, msi.`standard_price`
            FROM  mst_stock_item msi
            LEFT JOIN  units u
            ON msi.base_units = u.name
            ON DUPLICATE KEY UPDATE
            standard_price = VALUES(`standard_price`)");

            // DB::select("INSERT INTO mst_ledger (`guid`, `name`, `CreditLimit`, `parent`, `alias`, `is_revenue`, `is_deemedpositive`, `opening_balance`,`closing_balance`, `description`, `mailing_name`, `mailing_address`, `mailing_state`, `mailing_country`, `mailing_pincode`, `email`, `it_pan`, `gstn`, `gst_registration_type`, `gst_supply_type`, `gst_duty_head`, `tax_rate`, `bank_account_holder`, `bank_account_number`, `bank_ifsc`, `bank_swift`, `bank_name`, `bank_branch`)
            // SELECT mlt.`guid`, mlt.`name`, mlt.`CreditLimit`, mlt.`parent`, mlt.`alias`, mlt.`is_revenue`, mlt.`is_deemedpositive`, mlt.`opening_balance`,mlt.`closing_balance`, mlt.`description`, mlt.`mailing_name`, mlt.`mailing_address`, mlt.`mailing_state`, mlt.`mailing_country`, mlt.`mailing_pincode`, mlt.`email`, mlt.`it_pan`, mlt.`gstn`, mlt.`gst_registration_type`, mlt.`gst_supply_type`, mlt.`gst_duty_head`, mlt.`tax_rate`, mlt.`bank_account_holder`, mlt.`bank_account_number`, mlt.`bank_ifsc`, mlt.`bank_swift`, mlt.`bank_name`, mlt.`bank_branch`
            // FROM  mst_ledger_tally mlt
            // LEFT JOIN  mst_ledger ml
            // ON mlt.name = ml.name 
            // WHERE ml.name  IS NULL");

            DB::select("INSERT INTO mst_ledger (`guid`, `name`, `CreditLimit`, `parent`, `alias`, `is_revenue`, `is_deemedpositive`, `opening_balance`,`closing_balance`, `description`, `mailing_name`, `mailing_address`, `mailing_state`, `mailing_country`, `mailing_pincode`, `email`, `it_pan`, `gstn`, `gst_registration_type`, `gst_supply_type`, `gst_duty_head`, `tax_rate`, `bank_account_holder`, `bank_account_number`, `bank_ifsc`, `bank_swift`, `bank_name`, `bank_branch`)
            SELECT mlt.`guid`, mlt.`name`, mlt.`CreditLimit`, mlt.`parent`, mlt.`alias`, mlt.`is_revenue`, mlt.`is_deemedpositive`, mlt.`opening_balance`,mlt.`closing_balance`, mlt.`description`, mlt.`mailing_name`, mlt.`mailing_address`, mlt.`mailing_state`, mlt.`mailing_country`, mlt.`mailing_pincode`, mlt.`email`, mlt.`it_pan`, mlt.`gstn`, mlt.`gst_registration_type`, mlt.`gst_supply_type`, mlt.`gst_duty_head`, mlt.`tax_rate`, mlt.`bank_account_holder`, mlt.`bank_account_number`, mlt.`bank_ifsc`, mlt.`bank_swift`, mlt.`bank_name`, mlt.`bank_branch`
            FROM  mst_ledger_tally mlt
            ON DUPLICATE KEY UPDATE
            opening_balance = VALUES(`opening_balance`),
            closing_balance = VALUES(`closing_balance`),
            CreditLimit = VALUES(`CreditLimit`),
            parent = VALUES(`parent`)
            ");

            // DB::select("INSERT INTO all_voucher_list (`guid`, `IsPerticulats`, `VoucherTypeName`, `VoucherNumber`, `OptionalVoucher`, `LedgerName`, `DrAMT`, `CrAMT`, `Date`, `IsOpeningBalance`, `updated_at`, `IsClosingBalance`)
            // SELECT avlt.`guid`, avlt.`IsPerticulats`, avlt.`VoucherTypeName`, avlt.`VoucherNumber`, avlt.`OptionalVoucher`, avlt.`LedgerName`, avlt.`DrAMT`, avlt.`CrAMT`, avlt.`Date`, avlt.`IsOpeningBalance`, avlt.`updated_at`, avlt.`IsClosingBalance`
            // FROM  all_voucher_list_tally avlt
            // LEFT JOIN  all_voucher_list avl
            // ON avlt.guid = avl.guid 
            // WHERE avl.guid  IS NULL");

            // DB::select("INSERT INTO all_voucher_list (`guid`, `IsPerticulats`, `VoucherTypeName`, `VoucherNumber`, `OptionalVoucher`, `LedgerName`, `DrAMT`, `CrAMT`, `Date`, `IsOpeningBalance`, `updated_at`, `IsClosingBalance`)
            // SELECT avlt.`guid`, avlt.`IsPerticulats`, avlt.`VoucherTypeName`, avlt.`VoucherNumber`, avlt.`OptionalVoucher`, avlt.`LedgerName`, avlt.`DrAMT`, avlt.`CrAMT`, avlt.`Date`, avlt.`IsOpeningBalance`, avlt.`updated_at`, avlt.`IsClosingBalance`
            // FROM  all_voucher_list_tally_journal avlt
            // LEFT JOIN  all_voucher_list avl
            // ON avlt.guid = avl.guid 
            // WHERE avl.guid  IS NULL");

            DB::select("INSERT IGNORE INTO all_voucher_list (`guid`, `IsPerticulats`, `VoucherTypeName`, `VoucherNumber`, `OptionalVoucher`, `LedgerName`, `DrAMT`, `CrAMT`, `Date`, `IsOpeningBalance`, `updated_at`, `IsClosingBalance`) SELECT avlt.`guid`, avlt.`IsPerticulats`, avlt.`VoucherTypeName`, avlt.`VoucherNumber`, avlt.`OptionalVoucher`, avlt.`LedgerName`, avlt.`DrAMT`, avlt.`CrAMT`, avlt.`Date`, avlt.`IsOpeningBalance`, avlt.`updated_at`, avlt.`IsClosingBalance` FROM all_voucher_list_tally avlt WHERE 1");

            DB::select("INSERT IGNORE INTO all_voucher_list (`guid`, `IsPerticulats`, `VoucherTypeName`, `VoucherNumber`, `OptionalVoucher`, `LedgerName`, `DrAMT`, `CrAMT`, `Date`, `IsOpeningBalance`, `updated_at`, `IsClosingBalance`) SELECT avlt.`guid`, avlt.`IsPerticulats`, avlt.`VoucherTypeName`, avlt.`VoucherNumber`, avlt.`OptionalVoucher`, avlt.`LedgerName`, avlt.`DrAMT`, avlt.`CrAMT`, avlt.`Date`, avlt.`IsOpeningBalance`, avlt.`updated_at`, avlt.`IsClosingBalance` FROM all_voucher_list_tally_journal avlt WHERE 1");


            // DB::raw("UPDATE units u, mst_units mu SET u.name=mu.name,u.isSimpleUnit=mu.isSimpleUnit,u.alterId=mu.alterId,u.decimalPlaces=mu.decimalPlaces,u.numberOfUnits=mu.numberOfUnits WHERE mu.name = u.name");
    }
}
