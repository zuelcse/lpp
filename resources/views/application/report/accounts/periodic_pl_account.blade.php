<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profit & Loss A/C</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #f2f2f2;
            font-family: Arial, Helvetica, sans-serif;
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
            margin-bottom: 18px;
        }

        .company-name {
            font-size: 19px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 12px;
            line-height: 1.5;
        }

        .company-phone {
            font-size: 12px;
            margin-top: 2px;
        }

        /* =========================
           REPORT TITLE
        ========================== */

        .report-title {
            text-align: center;
            font-size: 17px;
            font-weight: bold;
            margin-top: 12px;
            margin-bottom: 4px;
        }

        .report-period {
            text-align: center;
            font-size: 12px;
            margin-bottom: 18px;
        }

        /* =========================
           MAIN TABLE
        ========================== */

        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .report-table td {
            padding: 5px 6px;
            vertical-align: middle;
        }

        .col-particular {
            width: 63%;
        }

        .col-note {
            width: 15%;
            text-align: center;
        }

        .col-amount {
            width: 22%;
            text-align: right;
        }

        /* =========================
           SECTION ROWS
        ========================== */

        .section-row td {
            font-weight: bold;
            padding-top: 9px;
            padding-bottom: 5px;
        }

        /* =========================
           NORMAL ROWS
        ========================== */

        .item-row td {
            border-bottom: 1px dotted #999;
        }

        .item-row:last-child td {
            border-bottom: none;
        }

        /* =========================
           TOTAL / RESULT ROWS
        ========================== */

        .result-row td {
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding-top: 7px;
            padding-bottom: 7px;
        }

        .sub-result td {
            font-weight: bold;
            padding-top: 7px;
            padding-bottom: 7px;
        }

        .gross-profit td {
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px double #000;
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .net-profit td {
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 3px double #000;
            padding-top: 8px;
            padding-bottom: 8px;
        }

        /* =========================
           SECONDARY CALCULATION
        ========================== */

        .calculation-title {
            margin-top: 18px;
            margin-bottom: 6px;
            font-weight: bold;
            font-size: 13px;
        }

        .calculation-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .calculation-table th,
        .calculation-table td {
            padding: 5px 6px;
        }

        .calculation-table th {
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            text-align: left;
        }

        .calculation-table th:last-child {
            text-align: right;
        }

        .calculation-table td {
            border-bottom: 1px dotted #999;
        }

        .calculation-table .amount {
            text-align: right;
        }

        .calculation-table .total td {
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 3px double #000;
        }

        .calculation-table .final-total td {
            font-weight: bold;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        /* =========================
           FOOTER
        ========================== */

        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 11px;
        }

        .footer-company {
            font-weight: bold;
            margin-bottom: 4px;
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

        .bold {
            font-weight: bold;
        }

        .indent {
            padding-left: 22px !important;
        }

        .amount {
            white-space: nowrap;
        }

        /* =========================
           PRINT
        ========================== */

        @page {
            size: A4;
            margin: 0;
        }

        @media print {

            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }

            .page {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 15mm 15mm 12mm 15mm;
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }
        }

        /* =========================
           RESPONSIVE
        ========================== */

        @media screen and (max-width: 800px) {

            body {
                padding: 0;
                background: #fff;
            }

            .page {
                width: 100%;
                min-height: auto;
                padding: 20px;
                box-shadow: none;
            }

            .report-table,
            .calculation-table {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <!-- =====================================
         COMPANY HEADER
    ====================================== -->

    <div class="company-header">

        <div class="company-name">
            Business Name
        </div>

        <div class="company-address">
            Business Address
        </div>

        <div class="company-phone">
            Tel: 017 xx xxxxxx
        </div>

    </div>


    <!-- =====================================
         REPORT TITLE
    ====================================== -->

    <div class="report-title">
        PROFIT &amp; LOSS A/C
    </div>

    <div class="report-period">
        for the Period of {{ date('d/m/Y', strtotime($data["info"]["s_date"])) }} to {{$data["info"]["e_date"]}}
    </div>


    <!-- =====================================
         PROFIT & LOSS TABLE
    ====================================== -->

    <table class="report-table">

        <colgroup>
            <col class="col-particular">
            <col class="col-note">
            <col class="col-amount">
        </colgroup>

        <tbody>
            <tr>
                <th style="width:230px;">Particulars</th>
                <th style="width:220px;">Schedules/Notes</th>
                <th style="width:200px;">Amount(Tk)</th>
            </tr>

            <!-- SALES -->

            <tr class="item-row">
                <td>
                    Sales
                </td>

                <td class="text-center">
                    1
                </td>

                <td class="amount">
                    {{$_1 = $data["data"][33]["credit"]}}
                </td>
            </tr>


            <!-- COGS -->

            <tr class="item-row">
                <td>
                    Cost of Goods Sold
                </td>

                <td class="text-center">
                    2
                </td>

                <td class="amount">
                    {{$_2 = $data["data"][35]["credit"] + $data["data"][35]["credit"]}}
                </td>
            </tr>


            <!-- GROSS PROFIT -->

            <tr class="gross-profit">
                <td>
                    GROSS PROFIT/(LOSS)
                </td>

                <td class="text-center">
                    1-2
                </td>

                <td class="amount">
                    {{$_12 = $_1 - $_2}}
                </td>
            </tr>


            <!-- OPERATING EXPENSES -->
            @php 
            $_3 = $data["data"][49]["credit"];
            $_4 = $data["data"][50]["credit"];
            @endphp 

            <tr class="section-row">
                <td>
                    OPERATING EXPENSES
                </td>

                <td class="text-center">
                    3+4
                </td>

                <td class="amount">
                    ({{$_34 = $_3+$_4}})
                </td>
            </tr>


            <!-- ADMINISTRATIVE EXPENSE -->

            <tr class="item-row">
                <td class="indent">
                    Administrative Expenses
                </td>

                <td class="text-center">
                    3
                </td>

                <td class="amount">
                    {{$_3}}
                </td>
            </tr>


            <!-- SELLING EXPENSE -->

            <tr class="item-row">
                <td class="indent">
                    Selling &amp; Distribution Expenses
                </td>

                <td class="text-center">
                    4
                </td>

                <td class="amount">
                    {{$_4}}
                </td>
            </tr>


            <!-- OPERATING PROFIT -->

            <tr class="result-row">
                <td>
                    PROFIT/(LOSS) FROM OPERATIONS
                </td>

                <td class="text-center">
                    (1-2)-(3+4)
                </td>

                <td class="amount">
                    {{$_1234 = ($_12)-($_34)}}
                </td>
            </tr>


            <!-- FINANCIAL EXPENSE -->

            <tr class="item-row">
                <td>
                    Financial Expenses
                </td>

                <td class="text-center">
                    5
                </td>

                <td class="amount">
                    {{$_5 = $data["data"][51]["credit"] + $data["data"][52]["credit"]}}
                </td>
            </tr>


            <!-- PROFIT AFTER FINANCIAL EXPENSE -->

            <tr class="sub-result">
                <td>
                    PROFIT/(LOSS)
                </td>

                <td class="text-center">
                    (1-2)-(3+4)-5
                </td>

                <td class="amount">
                    {{$_12345 = $_1234 - $_5}}
                </td>
            </tr>


            <!-- NON OPERATING INCOME -->

            <tr class="item-row">
                <td>
                    Non-Operating Income
                </td>

                <td class="text-center">
                    6
                </td>

                <td class="amount">
                    {{$_6 = $data["data"][53]["credit"]}}
                </td>
            </tr>


            <!-- PROFIT BEFORE WPP -->

            <tr class="sub-result">
                <td>
                    PROFIT/(LOSS)
                </td>

                <td class="text-center">
                    (1-2)-(3+4)-5+6
                </td>

                <td class="amount">
                    {{$_123456 = $_12345 + $_6}}
                </td>
            </tr>


            <!-- WPP -->

            <tr class="item-row">
                <td>
                    Contribution to WPP &amp; WF
                </td>

                <td class="text-center">
                    7
                </td>

                <td class="amount">
                    {{$_7 = 0}}
                </td>
            </tr>


            <!-- NET PROFIT BEFORE TAX -->

            <tr class="result-row">
                <td>
                    NET PROFIT/(LOSS) BEFORE TAX
                </td>

                <td class="text-center">
                    (1-2)-(3+4)-5+6-7
                </td>

                <td class="amount">
                    {{$_1234567 = $_123456 - $_7}}
                </td>
            </tr>


            <!-- TAX -->

            <tr class="item-row">
                <td>
                    Provision for Income Tax
                </td>

                <td class="text-center">
                    8
                </td>

                <td class="amount">
                    {{$_8 = 0}}
                </td>
            </tr>


            <!-- NET PROFIT AFTER TAX -->

            <tr class="net-profit">
                <td>
                    NET PROFIT/(LOSS) AFTER TAX
                </td>

                <td class="text-center">
                    (1-2)-(3+4)-5+6-7-8
                </td>

                <td class="amount">
                    {{$_12345678 = $_1234567 - $_8}}
                </td>
            </tr>

        </tbody>
    </table>


    <!-- =====================================
         COST OF GOODS CALCULATION
    ====================================== -->

    <div class="calculation-title">
        Cost of Goods Sold Calculation
    </div>

    <table class="calculation-table">

        <colgroup>
            <col style="width: 65%;">
            <col style="width: 13%;">
            <col style="width: 22%;">
        </colgroup>

        <thead>
            <tr>
                <th>
                    Particulars
                </th>

                <th class="text-center">
                    Schedules/Notes
                </th>

                <th class="text-right">
                    Amount (Tk)
                </th>
            </tr>
        </thead>

        <tbody>

            <!-- WIP OPENING -->

            <tr>
                <td>
                    Work-In-Process (Opening)
                </td>

                <td class="text-center">
                    mg 22
                </td>

                <td class="amount">
                    0.00
                </td>
            </tr>


            <!-- RAW MATERIAL -->

            <tr>
                <td>
                    Raw Materials Consumption Calculation
                </td>

                <td class="text-center">
                </td>

                <td class="amount">
                    84,22,445.17
                </td>
            </tr>


            <!-- PACKING -->

            <tr>
                <td>
                    Packing Material Consumption Calculation
                </td>

                <td class="text-center">
                </td>

                <td class="amount">
                    0.00
                </td>
            </tr>


            <!-- CHEMICAL -->

            <tr>
                <td>
                    Chemical Consumption Calculation
                </td>

                <td class="text-center">
                </td>

                <td class="amount">
                    0.00
                </td>
            </tr>


            <!-- STORES -->

            <tr>
                <td>
                    Stores &amp; Spares Consumption Calculation
                </td>

                <td class="text-center">
                </td>

                <td class="amount">
                    0.00
                </td>
            </tr>


            <!-- FACTORY OVERHEAD -->

            <tr>
                <td>
                    Factory Overheads
                </td>

                <td class="text-center">
                    mg(47,4)
                </td>

                <td class="amount">
                    0.00
                </td>
            </tr>


            <!-- DEPRECIATION -->

            <tr>
                <td>
                    Depreciation
                </td>

                <td class="text-center">
                    mg(48,4)
                </td>

                <td class="amount">
                    0.00
                </td>
            </tr>


            <!-- CLOSING WIP -->

            <tr>
                <td>
                    Less : Work-In-Process (Closing)
                </td>

                <td class="text-center">
                    Input-1
                </td>

                <td class="amount">
                    0.00
                </td>
            </tr>


            <!-- COST OF GOODS MANUFACTURED -->

            <tr class="total">
                <td>
                    COST OF GOODS MANUFACTURED
                </td>

                <td class="text-center">
                    Calculation
                </td>

                <td class="amount">
                    84,22,445.17
                </td>
            </tr>


            <!-- FINISHED GOODS OPENING -->

            <tr>
                <td>
                    Finished Goods (Opening)
                </td>

                <td class="text-center">
                    mg(23,4)
                </td>

                <td class="amount">
                    0.00
                </td>
            </tr>


            <!-- FINISHED GOODS CLOSING -->

            <tr>
                <td>
                    Less : Finished Goods (Closing)
                </td>

                <td class="text-center">
                    Input-2
                </td>

                <td class="amount">
                    0.00
                </td>
            </tr>


            <!-- COST OF GOODS SOLD -->

            <tr class="final-total">
                <td>
                    COST OF GOODS SOLD
                </td>

                <td class="text-center">
                    Calculation
                </td>

                <td class="amount">
                    84,22,445.17
                </td>
            </tr>

        </tbody>

    </table>


    <!-- =====================================
         FOOTER
    ====================================== -->

    <div class="footer">

        <div class="footer-company">
            RAISA POULTRY FEED AND CHICKS
        </div>

        <div>
            Chowmuhoni More, Natore.
        </div>

        <div>
            Tel: 01725-905021, 01760-721891, 01725-589775
        </div>

    </div>

</div>

</body>
</html>