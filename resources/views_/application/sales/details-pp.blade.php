@extends('layouts/contentNavbarLayout')

@section('title', 'Memo Details')

@section('content')

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
                        body {
                            font-family: Arial, sans-serif;
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

                        /* PRINT STYLE */
                        @media print {
                            body {
                                background: none;
                            }
                            .invoice-box {
                                border: none;
                            }
                        }
                    </style>

                    <body>

                    <div class="invoice-box">

                        <h2>Cash Memo</h2>

                        <div class="top-section">

                            <div class="box left-box">
                                <div class="box-title">Name & Address</div>
                                <div class="dashed"><strong>{{ $data['debit_head'] }} Ali S. R CARTONE</strong></div>
                                <div class="dashed">MADHOBDI, NARSINGDI.</div>
                            </div>

                            <div class="box right-box">
                                <div><strong>Bill No:</strong> {{ $data['voucher_no'] }}</div>
                                <div><strong>Date:</strong> {{ $data['date'] }}</div>
                                <div><strong>Cell:</strong> 01712423272</div>
                            </div>

                        </div>

                        <table>
                            <tr>
                                <th>S.No</th>
                                <th>Quantity</th>
                                <th>Description</th>
                                <th>Rate</th>
                                <th>Taka</th>
                            </tr>
                            <?php foreach ($data['master_items'] as $key => $value): ?>
                               <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value['sales_quantity'] }}</td>
                                    <td>
                                        {{ $value['work_name']['name']??'' }}
                                        {{ $value['work_type']['name']??'' }}
                                        {{ $value['size']['name']??'' }}
                                        {{ $value['color']['name']??'' }}
                                        {{ $value['paper']['name']??'' }}
                                        {{ $value['weight']['name']??'' }}
                                        {{ $value['lamination']['name']??'' }}
                                        {{ $value['note'] }}
                                    </td>
                                    <td>{{ $value['rate'] }}</td>
                                    <td class="text-right">{{ $value['amount'] }}</td>
                                </tr>
                            <?php endforeach; ?>
                            <!-- Add more rows dynamically -->

                        </table>

                        <div class="bottom-section">

                            <div class="box word-box">
                                <strong>In Word:</strong><br>
                                PAYMENT: Three Lac Only.<br>
                                CURRENT BALANCE: Four Lac Seven Thousand Two Hundred Fifty Only.
                            </div>

                            <div class="box summary-box">
                                <div><span>Total:</span><span>{{ $data['gross_amount'] }}</span></div>
                                <div><span>Previous Balance:</span><span>0</span></div>
                                <div><span>Payment:</span><span>{{ $data['paid_amount'] }}</span></div>
                                <div><span>Current Balance:</span><span>{{ $data['gross_amount']-$data['paid_amount'] }}</span></div>
                            </div>

                        </div>

                        <div class="footer">
                            <div>Signature of buyer</div>
                            <div>For: Locknath Printing Press</div>
                        </div>

                    </div>

                    </body>

                </div>
            </div>
        </div>
    </div>
<script>
function printDiv(divId) {
    var content = document.getElementById(divId).innerHTML;
    var originalContent = document.body.innerHTML;

    document.body.innerHTML = content;
    window.print();
    document.body.innerHTML = originalContent;

    location.reload(); // optional (restore properly)
}
</script>
@endsection
