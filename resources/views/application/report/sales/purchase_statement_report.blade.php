<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Statement Report</title>

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
            max-width: 1000px;
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

        .pill-divider {
            height: 2px;
            background: #222;
            margin-bottom: 15px;
        }

        /* Main Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
            margin-bottom: 15px;
        }

        .data-table th, 
        .data-table td {
            border: 1px solid #000;
            padding: 7px 10px;
        }

        .data-table thead th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .text-start { text-align: left; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }

        .data-table tbody tr:hover {
            background-color: #fafafa;
        }

        .data-table tfoot th {
            border-top: 2px solid #000;
            border-bottom: 3px double #000;
            background-color: #f0f0f0;
            font-weight: bold;
            padding: 8px 10px;
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
            <a href="{{ route('report-sales-purchase-statement') }}" class="btn btn-outline-secondary">
                &larr; Back to Filter
            </a>
        </div>
        <div>
            <button type="button" class="btn btn-primary" onclick="window.print()">
                &#128438; Print Purchase Statement Report
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
            Purchase Statement Report
        </div>
        <div class="report-date-range">
            Period: {{ date('d M Y', strtotime($s_date)) }} To {{ date('d M Y', strtotime($e_date)) }}
        </div>
    </div>

    <!-- Divider Pill -->
    <div class="pill-divider"></div>

    <!-- Main Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50px;">SL</th>
                <th class="text-start">Item Name</th>
                <th style="width: 100px;">Unit</th>
                <th class="text-end" style="width: 180px;">Purchase Qty</th>
                <th class="text-end" style="width: 200px;">Purchase value</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-start">
                        <strong>{{ $row['name'] }}</strong>
                        @if(!empty($row['barcode']))
                            <span style="font-size: 10px; color: #555;">[{{ $row['barcode'] }}]</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $row['unit'] }}</td>
                    <td class="text-end">{{ number_format($row['purchase_qty'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['purchase_value'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #666;">
                        No purchase records found between {{ date('d-m-Y', strtotime($s_date)) }} and {{ date('d-m-Y', strtotime($e_date)) }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($reportData) > 0)
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Grand Total :</th>
                    <th class="text-end">{{ number_format($totalPurchaseQty, 2) }}</th>
                    <th class="text-end">{{ number_format($totalPurchaseValue, 2) }}</th>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="report-footer no-print">
        <div class="signature-box">Prepared By</div>
        <div class="signature-box">Verified By</div>
        <div class="signature-box">Authorized Signature</div>
    </div>
</div>

</body>
</html>
