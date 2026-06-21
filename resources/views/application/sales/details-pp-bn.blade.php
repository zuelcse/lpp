@extends('layouts/contentNavbarLayout')

@section('title', 'Memo Details')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Memo Details') }}</span>
                    <button onclick="printDiv('printArea')">🖶 Print</button>
                </div>
                
                @php $data = $data[0]; @endphp

                <div class="card-body" id="printArea">
                    <style>
                        @font-face {
                            font-family: 'nikosh';
                            src: url('/resources/assets/fonts/NikoshBAN.ttf') format('truetype');
                        }
                        body {
                            /* font-family: Arial, sans-serif;*/
                            font-family: 'nikosh';
                            background: #f5f5f5;
                        }



                        .invoice-box {
                            width: 900px;
                            margin: auto;
                            background: #fff;
                            padding: 20px;
                            border: 1px solid #ccc;
                        }

                        h2 {
                            text-align: center;
                            margin-bottom: 10px;
                        }

                        .top-section {
                            display: flex;
                            justify-content: space-between;
                        }

                        .box {
                            border: 1px solid #999;
                            padding: 10px;
                            border-radius: 8px;
                        }

                        .left-box {
                            width: 65%;
                        }

                        .right-box {
                            width: 30%;
                        }

                        .box-title {
                            font-weight: bold;
                            margin-bottom: 5px;
                        }

                        .dashed {
                            border-bottom: 1px dashed #999;
                            padding: 5px 0;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 15px;
                        }

                        table th, table td {
                            border: 1px solid #999;
                            padding: 6px;
                            font-size: 14px;
                        }

                        table th {
                            background: #eee;
                        }

                        .text-right {
                            text-align: right;
                        }

                        .bottom-section {
                            display: flex;
                            justify-content: space-between;
                            margin-top: 10px;
                        }

                        .word-box {
                            width: 60%;
                        }

                        .summary-box {
                            width: 35%;
                        }

                        .summary-box div {
                            display: flex;
                            justify-content: space-between;
                            border-bottom: 1px solid #999;
                            padding: 5px;
                        }

                        .footer {
                            margin-top: 40px;
                            display: flex;
                            justify-content: space-between;
                        }

                        @page {
                            size: A4;
                            margin: 0.5in;
                        }

                        /* PRINT STYLE */
                        @media print {
                            body {
                                background: none;
                            }
                            .invoice-box {
                                border: none;
                                padding-top: 2in;
                            }
                        }
                    </style>

                    <body>

                    <div class="invoice-box">

                        <h2>ক্যাশ মেমো</h2>

                        <div class="top-section">

                            <div class="box left-box">
                                <div class="box-title">নাম ও ঠিকানা</div>
                                <div class="dashed"><strong>{{ $data['ledger']['name_bn'] }}</strong></div>
                                <div class="dashed">{{ $data['ledger']['address_bn'] }}</div>
                            </div>

                            <div class="box right-box">
                                <div style="font-family: nikosh;"><strong>বিল নং:</strong> {{ $data['voucher_no'] }}</div>
                                <div style="font-family: nikosh;"><strong>তারিখ:</strong> {{ numberToBangla($data['date']) }}</div>
                                <div><strong>মোবাইল:</strong> {{ numberToBangla($data['ledger']['mobile']) }}</div>
                            </div>

                        </div>

                        <table>
                            <tr>
                                <th>ক্রম নং</th>
                                <th>পরিমাণ</th>
                                <th>পণ্যের বিবরণ</th>
                                <th>দর</th>
                                <th>টাকা</th>
                            </tr>
                            <?php foreach ($data['master_items'] as $key => $value): ?>
                               <tr>
                                    <td>{{ numberToBangla($key+1) }}</td>
                                    <td>{{ numberToBangla($value['sales_quantity']) }}</td>
                                    <td>
                                        {{ $value['work_name']['name_bn']??'' }}
                                        {{ $value['work_type']['name_bn']??'' }}
                                        {{ $value['size']['name_bn']??'' }}
                                        {{ $value['color']['name_bn']??'' }}
                                        {{ $value['paper']['name_bn']??'' }}
                                        {{ $value['weight']['name_bn']??'' }}
                                        {{ $value['lamination']['name_bn']??'' }}
                                        {{ $value['note'] }}
                                    </td>
                                    <td>{{ numberToBangla($value['rate']) }}</td>
                                    <td class="text-right">{{ numberToBangla($value['amount']) }}</td>
                                </tr>
                            <?php endforeach; ?>
                            <!-- Add more rows dynamically -->

                        </table>

                        <div class="bottom-section">
                            <?php $currentBalance = ($data['previous_balance']-$data['gross_amount'])+$data['paid_amount']; 
                            $sign = '+';
                            if($currentBalance < 0){
                                $currentBalance = abs($currentBalance);
                                $sign = '-';
                            }
                            // exit($currentBalance);
                            ?>
                            <div class="box word-box">
                                <strong>কথায়:</strong><br>
                                প্রদত্ত অর্থ: {{ numberToBanglaWords( (int) $data['paid_amount']) }} মাত্র।<br>
                                বর্তমান ব্যালেন্স: {{ numberToBanglaWords($currentBalance) }} মাত্র।
                            </div>

                            <div class="box summary-box">
                                <div><span>মোট:</span><span>{{ numberToBangla($data['gross_amount']) }}</span></div>
                                <div><span>পূর্ববর্তী ব্যালেন্স:</span><span>{{ numberToBangla($data['previous_balance']) }}</span></div>
                                <div><span>প্রদত্ত অর্থ:</span><span>{{ numberToBangla($data['paid_amount']) }}</span></div>
                                <div><span>বর্তমান ব্যালেন্স:</span><span>{{ numberToBangla(number_format($currentBalance, 2)) }}</span></div>
                            </div>

                        </div>

                        <div class="footer">
                            <div>ক্রেতার স্বাক্ষর</div>
                            <div>পক্ষে: লোকনাথ প্রিন্টিং প্রেস</div>
                        </div>

                    </div>

                    </body>

                </div>
            </div>
        </div>
    </div>
<script>

/*function printDiv(divId) {
    var content = document.getElementById(divId).innerHTML;
    var originalContent = document.body.innerHTML;

    document.body.innerHTML = content;
    window.print();
    document.body.innerHTML = originalContent;

    location.reload(); // optional (restore properly)
}*/
function printDiv(divId) {
    var content = document.getElementById(divId).innerHTML;

    var printWindow = window.open('', '', 'width=900,height=700');

    printWindow.document.write(`
        <html>
        <head>
            <title>{{ $data['voucher_no'] }}</title>
            <style>
                body { font-family: Arial; }
            </style>
        </head>
        <body>${content}</body>
        </html>
    `);

    printWindow.document.close();

    printWindow.onload = function () {
        printWindow.print();
        printWindow.close();
    };
}
</script>
@endsection
