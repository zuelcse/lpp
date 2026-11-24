<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raw Material Ledger - {{ $materialItem->name }}</title>

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
            max-width: 1200px;
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

        .btn-secondary {
            color: #fff;
            background-color: #8592a3;
            border-color: #8592a3;
        }
        .btn-secondary:hover {
            background-color: #788393;
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
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .page-indicator {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .report-subtitle-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 6px;
        }

        .report-main-title {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .report-date-range {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* Pill / Oval / Double Line Divider from Sample */
        .pill-divider {
            width: 100%;
            height: 10px;
            border: 1px solid #000;
            border-radius: 12px;
            background: #fdfdfd;
            margin: 6px 0 16px 0;
        }

        .item-name-heading {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        /* Main Data Table */
        .ledger-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            line-height: 1.35;
        }

        .ledger-table thead th {
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000;
            padding: 6px 4px;
            font-weight: bold;
            background-color: #fff;
            white-space: nowrap;
        }

        .ledger-table tbody td {
            padding: 4px 4px;
            border-bottom: 1px solid #e2e2e2;
            vertical-align: middle;
            white-space: nowrap;
        }

        .ledger-table tbody tr:hover {
            background-color: #fafbfc;
        }

        .text-start {
            text-align: left !important;
        }
        .text-center {
            text-align: center !important;
        }
        .text-end {
            text-align: right !important;
        }

        .particulars-col {
            white-space: normal !important;
            max-width: 180px;
            word-wrap: break-word;
        }

        /* Total / Footer Row */
        .ledger-table tfoot td {
            border-top: 1.5px solid #000;
            border-bottom: 2px solid #000;
            padding: 7px 4px;
            font-weight: bold;
            white-space: nowrap;
        }

        .table-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 767.98px) {
            body { padding: 8px; }
            .report-container { padding: 12px; }
            .no-print-bar, .report-subtitle-row { flex-wrap: wrap; gap: 8px; }
        }

        /* Print Optimization */
        @media print {
            body {
                padding: 0 !important;
                margin: 0 !important;
                background: #fff !important;
                color: #000 !important;
                font-size: 10.5px !important;
            }

            .report-container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 5mm !important;
                box-shadow: none !important;
            }

            .no-print, .no-print-bar {
                display: none !important;
            }

            .ledger-table {
                font-size: 10px !important;
            }

            .ledger-table thead th {
                border-top: 1.5px solid #000 !important;
                border-bottom: 1.5px solid #000 !important;
            }

            .ledger-table tbody td {
                border-bottom: 0.5px solid #ccc !important;
            }

            .ledger-table tfoot td {
                border-top: 1.5px solid #000 !important;
                border-bottom: 2px solid #000 !important;
            }

            .pill-divider {
                border: 1px solid #000 !important;
            }

            @page {
                size: A4 landscape;
                margin: 8mm 6mm;
            }
        }
    </style>
</head>
<body>

<div class="report-container">
    <!-- Non-printable action bar -->
    <div class="no-print-bar no-print">
        <div>
            <a href="{{ route('report-materials-raw-materials-ledger') }}" class="btn btn-outline-secondary">
                &larr; Back to Filter
            </a>
        </div>
        <div>
            <button type="button" class="btn btn-primary" onclick="window.print()">
                &#128438; Print Ledger Report
            </button>
        </div>
    </div>

    <!-- Company Header -->
    <div class="company-title">
        {{ config('variables.companyName', config('variables.templateName', 'SHAH FARID ENTERPRISE')) }}
    </div>

    <div class="page-indicator">
        Page : 1
    </div>

    <div class="report-subtitle-row">
        <div class="report-main-title">
            Raw Material Ledger
        </div>
        <div class="report-date-range">
            Date : From {{ date('d.m.y', strtotime($s_date)) }} to {{ date('d.m.y', strtotime($e_date)) }}
        </div>
    </div>

    <!-- Divider Pill -->
    <div class="pill-divider"></div>

    <!-- Raw Material Item Name -->
    <div class="item-name-heading">
        Raw Material Name : {{ strtoupper($materialItem->name) }}
        @if($materialItem->barcode)
            <span style="font-weight: normal; font-size: 12px;">[{{ $materialItem->barcode }}]</span>
        @endif
        @if($materialItem->name_bn)
            <span style="font-weight: normal; font-size: 12px; font-family: Arial, sans-serif;">({{ $materialItem->name_bn }})</span>
        @endif
    </div>

    <!-- Main Table -->
    <div class="table-scroll">
    <table class="ledger-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 60px;">date</th>
                <th class="text-center" style="width: 75px;">MrrSrrNo</th>
                <th class="text-start" style="width: 140px;">Particulars</th>
                <th class="text-end" style="width: 70px;">Rcv.Qty</th>
                <th class="text-end" style="width: 80px;">Rcv.Value(Tk)</th>
                <th class="text-end" style="width: 70px;">Isu to P.Qty</th>
                <th class="text-end" style="width: 70px;">Consu.Qty</th>
                <th class="text-end" style="width: 85px;">Consu.Value(Tk)</th>
                <th class="text-end" style="width: 75px;">Flor.Bal.Qty</th>
                <th class="text-end" style="width: 75px;">Stor.Bal.Qty</th>
                <th class="text-end" style="width: 85px;">Balance.Qty</th>
                <th class="text-center" style="width: 45px;">Unit</th>
                <th class="text-end" style="width: 55px;">Rate(Tk)</th>
                <th class="text-end" style="width: 80px;">Value(Tk)</th>
            </tr>
        </thead>
        <tbody>
            <!-- Row 1: Opening Balance Row -->
            <tr>
                <td class="text-center">{{ date('d/m/y', strtotime($openingRow['date'])) }}</td>
                <td class="text-center">{{ $openingRow['voucher_no'] }}</td>
                <td class="text-start particulars-col">{{ $openingRow['particulars'] }}</td>
                <td class="text-end">{{ number_format($openingRow['rcv_qty'], 2) }}</td>
                <td class="text-end">{{ number_format($openingRow['rcv_val'], 2) }}</td>
                <td class="text-end">{{ number_format($openingRow['isu_qty'], 2) }}</td>
                <td class="text-end">{{ number_format($openingRow['con_qty'], 2) }}</td>
                <td class="text-end">{{ number_format($openingRow['con_val'], 2) }}</td>
                <td class="text-end">{{ number_format($openingRow['flor_bal'], 2) }}</td>
                <td class="text-end">{{ number_format($openingRow['stor_bal'], 2) }}</td>
                <td class="text-end">
                    {{ number_format($openingRow['total_bal'], 2) }}
                    @if($openingRow['total_bal'] > 0 && !empty($unitName))
                        {{ $unitName }}
                    @endif
                </td>
                <td class="text-center">{{ !empty($unitName) ? $unitName : '-' }}</td>
                <td class="text-end">{{ number_format($openingRow['rate'], 2) }}</td>
                <td class="text-end">{{ number_format($openingRow['value'], 2) }}</td>
            </tr>

            <!-- Dynamic Transaction Rows -->
            @foreach ($rows as $row)
                <tr>
                    <td class="text-center">{{ date('d/m/y', strtotime($row['date'])) }}</td>
                    <td class="text-center">{{ $row['voucher_no'] }}</td>
                    <td class="text-start particulars-col">{{ $row['particulars'] ?: '-' }}</td>
                    <td class="text-end">{{ number_format($row['rcv_qty'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['rcv_val'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['isu_qty'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['con_qty'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['con_val'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['flor_bal'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['stor_bal'], 2) }}</td>
                    <td class="text-end">
                        {{ number_format($row['total_bal'], 2) }}
                        @if($row['total_bal'] > 0 && !empty($unitName))
                            {{ $unitName }}
                        @endif
                    </td>
                    <td class="text-center">{{ !empty($unitName) ? $unitName : '-' }}</td>
                    <td class="text-end">{{ number_format($row['rate'], 2) }}</td>
                    <td class="text-end">{{ number_format($row['value'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-center">
                    TOTAL --
                </td>
                <td class="text-end">{{ number_format($totals['rcv_qty'], 2) }}</td>
                <td class="text-end">{{ number_format($totals['rcv_val'], 2) }}</td>
                <td class="text-end">{{ number_format($totals['isu_qty'], 2) }}</td>
                <td class="text-end">{{ number_format($totals['con_qty'], 2) }}</td>
                <td class="text-end">{{ number_format($totals['con_val'], 2) }}</td>
                <td class="text-end">{{ number_format($totals['final_flor_bal'], 2) }}</td>
                <td class="text-end">{{ number_format($totals['final_stor_bal'], 2) }}</td>
                <td class="text-end">
                    {{ number_format($totals['final_total_bal'], 2) }}
                    @if($totals['final_total_bal'] > 0 && !empty($unitName))
                        {{ $unitName }}
                    @endif
                </td>
                <td class="text-center">{{ !empty($unitName) ? $unitName : '-' }}</td>
                <td class="text-end">{{ number_format($totals['final_rate'], 2) }}</td>
                <td class="text-end">{{ number_format($totals['final_value'], 2) }}</td>
            </tr>
        </tfoot>
    </table>
    </div>
</div>

</body>
</html>
