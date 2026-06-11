@extends('layouts/contentNavbarLayout')

@section('title', 'Sales Details')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Sales Details') }}</span>
                    <button onclick="printDiv('printArea')">🖶 Print</button>
                </div>
                
                @php $data = $data[0]; @endphp

                <div class="card-body" id="printArea">
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered" class="font-size: 0.7em">
                                <tr>
                                    <th>VOUCHER NO</th>
                                    <td>{{ $data['voucher_no'] }}</td>
                                </tr>
                                <tr>
                                    <th>DATE</th>
                                    <td>{{ $data['date'] }}</td>
                                </tr>
                                <tr>
                                    <th>LEDGER NAME</th>
                                    <td>{{ $data['debit_head'] }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>{{ $data['gross_amount'] }} Tk</td>
                                </tr>
                                <tr>
                                    <th>Paid Amount</th>
                                    <td>{{ $data['paid_amount'] }} Tk</td>
                                </tr>
                                <tr>
                                    <th>Narration/Remarks</th>
                                    <td>{{ $data['narration'] ?? null }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card-header d-flex items-center justify-content-between">
                        <span>{{ __('Item Details') }}</span>
                    </div>
                    
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered" class="font-size: 0.7em">
                                <thead>
                                    <tr>
                                        <th>S. No.</th>
                                        <th>Quantity</th>
                                        <th style="width:450px;">Description</th>
                                        <th>Rate</th>
                                        <th>AMOUNT</th>
                                    </tr>
                                </thead>
                                
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
                                        <td style="text-align: right;">{{ $value['amount'] }} Tk</td>
                                    </tr>
                                <?php endforeach; ?>
                                    <tr>
                                        <td colspan ="4">EXTRA DISCOUNT (TK)</td>
                                        <td style="text-align: right;">{{ $data['discount_amount'] }} Tk</td>
                                    </tr>
                                    <tr>
                                        <td colspan ="4">Gross Amount</td>
                                        <td style="text-align: right;">{{ $data['gross_amount'] }} Tk</td>
                                    </tr>
                            </table>
                        </div>                        
                    </div>
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
