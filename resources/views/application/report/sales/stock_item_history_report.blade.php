<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Item History - {{ $stockItem->name }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #f4f5f7;
            color: #000;
            font-family: "Courier New", Courier, "Lucida Console", monospace, Arial, sans-serif;
            font-size: 12px;
        }

        .report-container {
            max-width: 1250px;
            margin: 0 auto;
            background: #fff;
            padding: 25px 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Action Toolbar */
        .no-print-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #ddd;
            font-family: Arial, Helvetica, sans-serif;
        }

        .btn {
            display: inline-block;
            font-weight: 500;
            line-height: 1.5;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            padding: 6px 14px;
            font-size: 13px;
            border-radius: 4px;
            border: 1px solid transparent;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary {
            color: #fff;
            background-color: #696cff;
            border-color: #696cff;
        }
        .btn-primary:hover {
            background-color: #5f61e6;
        }

        .btn-outline-secondary {
            color: #697a8d;
            border-color: #d9dee3;
            background: transparent;
        }
        .btn-outline-secondary:hover {
            background-color: #f5f5f9;
        }

        /* Report Header */
        .company-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .company-address {
            text-align: center;
            font-size: 11px;
            color: #444;
            margin-bottom: 10px;
            white-space: pre-line;
            line-height: 1.4;
        }

        .report-subtitle-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 15px;
            margin-bottom: 8px;
            font-family: Arial, sans-serif;
        }

        .report-main-title {
            font-size: 15px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .report-date-range {
            font-size: 11px;
            color: #333;
        }

        .item-info-bar {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 8px 12px;
            margin-bottom: 15px;
            font-family: Arial, sans-serif;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
        }

        .pill-divider {
            height: 2px;
            background: #222;
            margin-bottom: 15px;
        }

        /* Main Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 15px;
        }

        .data-table th, 
        .data-table td {
            border: 1px solid #000;
            padding: 6px 7px;
        }

        .data-table thead th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 10.5px;
        }

        .text-start { text-align: left; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }

        .data-table tbody tr:hover {
            background-color: #fafafa;
        }

        .opening-row {
            background-color: #fff9e6;
            font-weight: bold;
        }

        .data-table tfoot th {
            border-top: 2px solid #000;
            border-bottom: 3px double #000;
            background-color: #f0f0f0;
            font-weight: bold;
            padding: 7px 8px;
            font-size: 11px;
        }

        /* Report Footer / Signature */
        .report-footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            padding: 0 30px;
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .signature-box {
            border-top: 1px dotted #000;
            width: 160px;
            text-align: center;
            padding-top: 5px;
        }

        .table-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 767.98px) {
            body { padding: 8px; }
            .report-container { padding: 12px; }
            .no-print-bar, .report-subtitle-row, .item-info-bar { flex-wrap: wrap; gap: 8px; }
            .report-footer { flex-wrap: wrap; gap: 16px; }
            .signature-box { width: 45%; min-width: 120px; }
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .report-container {
                box-shadow: none;
                padding: 10px;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
            .data-table {
                page-break-inside: auto;
            }
            .data-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            .data-table thead {
                display: table-header-group;
            }
            .data-table tfoot {
                display: table-footer-group;
            }
        }
    </style>
</head>
<body>

<div class="report-container">
    <!-- Non-printable action bar -->
    <div class="no-print-bar no-print">
        <div>
            <a href="{{ route('report-sales-stock-item-history') }}" class="btn btn-outline-secondary">
                &larr; Back to Filter
            </a>
        </div>
        <div>
            <button type="button" class="btn btn-primary" onclick="window.print()">
                &#128438; Print Item History Report
            </button>
        </div>
    </div>

    <!-- Company Header -->
    <div class="company-title">
        {{ $company->site_name ?? config('variables.templateName', 'Trading Solution') }}
    </div>

    @if(!empty($company->company_address))
        <div class="company-address">{{ $company->company_address }}</div>
    @endif

    <div class="report-subtitle-row">
        <div class="report-main-title">
            Stock Item History Report
        </div>
        <div class="report-date-range">
            Period: {{ date('d M Y', strtotime($s_date)) }} To {{ date('d M Y', strtotime($e_date)) }}
        </div>
    </div>

    <!-- Item Information Bar -->
    <div class="item-info-bar">
        <div>
            <strong>Item Name:</strong> {{ $stockItem->name }}
            @if($stockItem->barcode)
                <span style="color: #666;">[{{ $stockItem->barcode }}]</span>
            @endif
        </div>
        <div>
            <strong>Unit:</strong> {{ $stockItem->unitDetail->name ?? '-' }}
        </div>
    </div>

    <div class="pill-divider"></div>

    <!-- Main Table -->
    <div class="table-scroll">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 85px;">Voucher Date</th>
                <th style="width: 95px;">Voucher No</th>
                <th class="text-start">Perticulars</th>
                <th class="text-end" style="width: 90px;">Production Qty</th>
                <th class="text-end" style="width: 110px;">Production Value</th>
                <th class="text-end" style="width: 75px;">Rate</th>
                <th class="text-end" style="width: 85px;">Sales Qty</th>
                <th class="text-end" style="width: 100px;">Sales Amount</th>
                <th class="text-end" style="width: 95px;">Balance Qty</th>
            </tr>
        </thead>
        <tbody>
            <!-- Opening Balance Row -->
            <tr class="opening-row">
                <td class="text-center">{{ date('d-m-Y', strtotime($s_date)) }}</td>
                <td class="text-center">-</td>
                <td class="text-start">Opening Balance</td>
                <td class="text-end">-</td>
                <td class="text-end">-</td>
                <td class="text-end">-</td>
                <td class="text-end">-</td>
                <td class="text-end">-</td>
                <td class="text-end" style="font-weight: bold;">
                    {{ number_format($openingBalance, 2) }}
                </td>
            </tr>

            <!-- Transactions Rows -->
            @forelse($transactions as $row)
                <tr>
                    <td class="text-center">{{ date('d-m-Y', strtotime($row['date'])) }}</td>
                    <td class="text-center">{{ $row['voucher_no'] }}</td>
                    <td class="text-start">{{ $row['particulars'] }}</td>
                    <td class="text-end">
                        {{ $row['prod_qty'] > 0 ? number_format($row['prod_qty'], 2) : '-' }}
                    </td>
                    <td class="text-end">
                        {{ $row['prod_value'] > 0 ? number_format($row['prod_value'], 2) : '-' }}
                    </td>
                    <td class="text-end">
                        {{ $row['rate'] > 0 ? number_format($row['rate'], 2) : '-' }}
                    </td>
                    <td class="text-end">
                        {{ $row['sales_qty'] > 0 ? number_format($row['sales_qty'], 2) : '-' }}
                    </td>
                    <td class="text-end">
                        {{ $row['sales_amount'] > 0 ? number_format($row['sales_amount'], 2) : '-' }}
                    </td>
                    <td class="text-end" style="font-weight: bold; {{ $row['balance_qty'] < 0 ? 'color: red;' : '' }}">
                        {{ number_format($row['balance_qty'], 2) }}
                    </td>
                </tr>
            @empty
                @if($openingBalance == 0)
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 15px; color: #666;">
                            No transactions found for this item in the selected period.
                        </td>
                    </tr>
                @endif
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Grand Total :</th>
                <th class="text-end">{{ number_format($grandProdQty, 2) }}</th>
                <th class="text-end">{{ number_format($grandProdValue, 2) }}</th>
                <th class="text-end">
                    @if($grandProdQty > 0)
                        {{ number_format($grandProdValue / $grandProdQty, 2) }}
                    @else
                        -
                    @endif
                </th>
                <th class="text-end">{{ number_format($grandSalesQty, 2) }}</th>
                <th class="text-end">{{ number_format($grandSalesAmount, 2) }}</th>
                <th class="text-end" style="font-size: 12px;">{{ number_format($finalBalanceQty, 2) }}</th>
            </tr>
        </tfoot>
    </table>
    </div>

    <div class="report-footer no-print">
        <div class="signature-box">Prepared By</div>
        <div class="signature-box">Verified By</div>
        <div class="signature-box">Authorized Signature</div>
    </div>
</div>

</body>
</html>
