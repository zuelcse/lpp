<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Sheet</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #f2f2f2;
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            font-size: 13px;
        }

        /* =========================
           A4 PAGE
        ========================== */
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #fff;
            padding: 15mm 15mm 12mm 15mm;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
        }

        /* =========================
           COMPANY HEADER
        ========================== */
        .company-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 19px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 12px;
            line-height: 1.5;
        }

        /* =========================
           REPORT TITLE
        ========================== */
        .report-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 18px;
            letter-spacing: 0.5px;
        }

        /* =========================
           MAIN TABLE
        ========================== */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 13px;
        }

        .report-table th,
        .report-table td {
            padding: 2.5px 6px;
            vertical-align: middle;
        }

        .col-particular {
            width: 53%;
        }

        .col-note {
            width: 23%;
            text-align: center;
        }

        .col-amount {
            width: 24%;
            text-align: right;
        }

        .header-row th {
            background-color: #cfd4da;
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000;
            font-weight: bold;
            padding: 6px 8px;
            font-size: 13px;
        }

        .header-row th:first-child {
            border-left: 1.5px solid #000;
            text-align: left;
            padding-left: 10px;
        }

        .header-row th:last-child {
            border-right: 1.5px solid #000;
            text-align: right;
            padding-right: 10px;
        }

        /* =========================
           BOXED AMOUNT CELLS
        ========================== */
        .box-top {
            border-top: 1px solid #000 !important;
            border-left: 1px solid #000 !important;
            border-right: 1px solid #000 !important;
            border-bottom: none !important;
            text-align: right;
            padding-right: 8px !important;
            white-space: nowrap;
        }

        .box-mid {
            border-left: 1px solid #000 !important;
            border-right: 1px solid #000 !important;
            border-top: none !important;
            border-bottom: none !important;
            text-align: right;
            padding-right: 8px !important;
            white-space: nowrap;
        }

        .box-bot {
            border-bottom: 1px solid #000 !important;
            border-left: 1px solid #000 !important;
            border-right: 1px solid #000 !important;
            border-top: none !important;
            text-align: right;
            padding-right: 8px !important;
            white-space: nowrap;
        }

        .box-all {
            border: 1px solid #000 !important;
            text-align: right;
            padding-right: 8px !important;
            white-space: nowrap;
        }

        /* =========================
           ROW TOTAL BORDERS
        ========================== */
        .total-row td {
            border-top: 1.5px solid #000 !important;
            border-bottom: 1.5px solid #000 !important;
            font-weight: bold;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .total-row td:first-child {
            border-left: 1.5px solid #000 !important;
            padding-left: 8px;
        }

        .total-row td:last-child {
            border-right: 1.5px solid #000 !important;
            padding-right: 8px;
            text-align: right;
        }

        /* =========================
           UTILITY
        ========================== */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .indent {
            padding-left: 20px !important;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
            padding-right: 8px;
        }

        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            padding-top: 12px;
            padding-bottom: 6px;
        }

        /* =========================
           PRINT
        ========================== */
        @page {
            size: A4;
            margin: 10mm;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }

            .page {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

<div class="no-print" style="text-align: center; margin-bottom: 15px;">
    <button onclick="window.print()" style="padding: 7px 22px; font-size: 13px; font-weight: bold; cursor: pointer; background: #007bff; color: #fff; border: 1px solid #0056b3; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
        🖨️ Print / Save as PDF
    </button>
</div>

<div class="page">

    <!-- COMPANY HEADER -->
    <div class="company-header">
        <div class="company-name">RAISA POULTRY FEED AND CHICKS</div>
        <div class="company-address">Chowmuhoni More, Natore. &nbsp; Tel: 01725-905021, 01760-721891, 01725-589775</div>
    </div>

    <!-- REPORT TITLE -->
    <div class="report-title">
        BALANCE SHEET AS ON {{ !empty($data['info']['e_date']) ? date('d/m/Y', strtotime($data['info']['e_date'])) : date('d/m/Y') }}
    </div>

    <!-- BALANCE SHEET TABLE -->
    <table class="report-table">
        <colgroup>
            <col class="col-particular">
            <col class="col-note">
            <col class="col-amount">
        </colgroup>

        <thead>
            <tr class="header-row">
                <th>Particulars</th>
                <th class="text-center">Schedules/Notes</th>
                <th class="text-right">Amount (Tk)</th>
            </tr>
        </thead>

        <tbody>
            <!-- NET ASSETS -->
            <tr>
                <td colspan="3" class="section-title">NET ASSETS</td>
            </tr>

            <!-- Non-Current Assets -->
            <tr>
                <td class="bold">Non-Current Assets</td>
                <td class="text-center">7+8+9</td>
                <td class="amount bold">{{ formatIndianCurrency($data['data']['non_current_assets']['total'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Tangible Fixed Assets - Property Plant</td>
                <td class="text-center">7</td>
                <td class="box-top">{{ formatIndianCurrency($data['data']['non_current_assets']['tangible_fixed_assets'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Deffered Expenses</td>
                <td class="text-center">8</td>
                <td class="box-mid">{{ formatIndianCurrency($data['data']['non_current_assets']['deferred_expenses'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Investment</td>
                <td class="text-center">9</td>
                <td class="box-bot">{{ formatIndianCurrency($data['data']['non_current_assets']['investment'], true) }}</td>
            </tr>

            <!-- Inter Company Current A/C -->
            <tr>
                <td class="bold" style="padding-top: 14px; padding-bottom: 14px;">Inter Company Current A/C</td>
                <td class="text-center" style="padding-top: 14px; padding-bottom: 14px;">9A</td>
                <td class="amount" style="padding-top: 14px; padding-bottom: 14px;">
                    {{ formatIndianCurrency($data['data']['inter_company_current_ac'], true) }}
                </td>
            </tr>

            <!-- Current Assets -->
            <tr>
                <td class="bold">Current Assets</td>
                <td class="text-center">10+11+12+13+14</td>
                <td class="amount bold">{{ formatIndianCurrency($data['data']['current_assets']['total'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Inventories</td>
                <td class="text-center">10</td>
                <td class="box-top">{{ formatIndianCurrency($data['data']['current_assets']['inventories'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Trade Debtors</td>
                <td class="text-center">11</td>
                <td class="box-mid">{{ formatIndianCurrency($data['data']['current_assets']['trade_debtors'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Accounts Receivable</td>
                <td class="text-center">12</td>
                <td class="box-mid">{{ formatIndianCurrency($data['data']['current_assets']['accounts_receivable'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Advance Deposits &amp; Prepayments</td>
                <td class="text-center">13</td>
                <td class="box-mid">{{ formatIndianCurrency($data['data']['current_assets']['advance_deposits_prepayments'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Cash &amp; Cash Equivalents</td>
                <td class="text-center">14</td>
                <td class="box-bot">{{ formatIndianCurrency($data['data']['current_assets']['cash_cash_equivalents'], true) }}</td>
            </tr>

            <!-- Current Liabilities & Provisions -->
            <tr>
                <td class="bold" style="padding-top: 10px;">Current Liabilities &amp; Provisions</td>
                <td class="text-center" style="padding-top: 10px;">15+16+17+18+18b+19+20+20b+20c</td>
                <td class="amount bold" style="padding-top: 10px;">{{ formatIndianCurrency($data['data']['current_liabilities']['total'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Loans</td>
                <td class="text-center">15</td>
                <td class="box-top">{{ formatIndianCurrency($data['data']['current_liabilities']['loans'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Interest Payable</td>
                <td class="text-center">16</td>
                <td class="box-mid">{{ formatIndianCurrency($data['data']['current_liabilities']['interest_payable'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Creditors</td>
                <td class="text-center">17</td>
                <td class="box-mid">{{ formatIndianCurrency($data['data']['current_liabilities']['creditors'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Accrued Expenses</td>
                <td class="text-center">18</td>
                <td class="box-mid">{{ formatIndianCurrency($data['data']['current_liabilities']['accrued_expenses'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Advance Against Sales</td>
                <td class="text-center">18b</td>
                <td class="box-mid">{{ formatIndianCurrency($data['data']['current_liabilities']['advance_against_sales'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Liabilites for Other Finance</td>
                <td class="text-center">19</td>
                <td class="box-mid">{{ formatIndianCurrency($data['data']['current_liabilities']['liabilities_other_finance'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Liabilities for Capital Expenditure</td>
                <td class="text-center">20</td>
                <td class="box-mid">{{ formatIndianCurrency($data['data']['current_liabilities']['liabilities_capital_expenditure'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Provision for Taxation</td>
                <td class="text-center">20b</td>
                <td class="box-mid">{{ formatIndianCurrency($data['data']['current_liabilities']['provision_taxation'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Dividend Payable</td>
                <td class="text-center">20c</td>
                <td class="box-bot">{{ formatIndianCurrency($data['data']['current_liabilities']['dividend_payable'], true) }}</td>
            </tr>

            <!-- Net Current Assets -->
            <tr>
                <td class="bold" style="padding-top: 8px;">Net Current Assets</td>
                <td class="text-center" style="padding-top: 8px;">Curr Ast - Curr Lia</td>
                <td class="amount bold" style="padding-top: 8px;">{{ formatIndianCurrency($data['data']['net_current_assets'], true) }}</td>
            </tr>

            <!-- Deferred Liabilities -->
            <tr>
                <td class="bold" style="padding-top: 4px; padding-bottom: 8px;">Deferred Liabilities</td>
                <td class="text-center" style="padding-top: 4px; padding-bottom: 8px;">21</td>
                <td class="amount bold" style="padding-top: 4px; padding-bottom: 8px;">{{ formatIndianCurrency($data['data']['deferred_liabilities'], true) }}</td>
            </tr>

            <!-- TOTAL (ASSET SIDE) -->
            <tr class="total-row">
                <td>TOTAL (ASSET SIDE)</td>
                <td class="text-center">HEADING</td>
                <td>{{ formatIndianCurrency($data['data']['total_asset_side'], true) }}</td>
            </tr>

            <!-- FINANCED BY -->
            <tr>
                <td colspan="3" class="section-title" style="padding-top: 22px;">FINANCED BY</td>
            </tr>

            <!-- Shareholders Equity -->
            <tr>
                <td class="bold">Shareholders Equity</td>
                <td class="text-center">1</td>
                <td class="amount bold">{{ formatIndianCurrency($data['data']['shareholders_equity']['total'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Share Capital</td>
                <td class="text-center">22</td>
                <td class="box-top">{{ formatIndianCurrency($data['data']['shareholders_equity']['share_capital'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Retained Earnings</td>
                <td class="text-center">22A</td>
                <td class="box-bot">{{ formatIndianCurrency($data['data']['shareholders_equity']['retained_earnings'], true) }}</td>
            </tr>

            <!-- Non-Current Liabilities -->
            <tr>
                <td class="bold" style="padding-top: 6px;">Non-Current Liabilities</td>
                <td class="text-center" style="padding-top: 6px;">1</td>
                <td class="amount bold" style="padding-top: 6px;">{{ formatIndianCurrency($data['data']['non_current_liabilities']['total'], true) }}</td>
            </tr>
            <tr>
                <td class="indent">Long Term Loan</td>
                <td class="text-center">23</td>
                <td class="box-all">{{ formatIndianCurrency($data['data']['non_current_liabilities']['long_term_loan'], true) }}</td>
            </tr>

            <!-- TOTAL (LIABILITY SIDE) -->
            <tr class="total-row">
                <td style="margin-top: 6px;">TOTAL (LIABILITY SIDE)</td>
                <td class="text-center">HEADING</td>
                <td>{{ formatIndianCurrency($data['data']['total_liability_side'], true) }}</td>
            </tr>
        </tbody>
    </table>

</div>

</body>
</html>
