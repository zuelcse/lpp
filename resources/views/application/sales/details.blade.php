@extends('layouts/contentNavbarLayout')

@section('title', 'Sales Details')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Sales Details') }}</span>
                </div>
                
                @php $data = $data[0]; @endphp

                <div class="card-body">
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
                                        <th>Name</th>
                                        {{--<th>Description</th>--}}
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Rate</th>
                                        <th>AMOUNT</th>
                                        <th>Discount</th>
                                        {{--<th>DISCOUNT Amount</th>--}}
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                
                                <?php foreach ($data['sales_items'] as $key => $value): ?>
                                   <tr>
                                        <td>{{ $value['name'] }}</td>
                                        {{--<td>{{ $value['item_description'] }}</td>--}}
                                        <td>{{ $value['sales_quantity'] }}</td>
                                        <td>{{ $value['unit'] }}</td>
                                        <td>{{ $value['rate'] }}</td>
                                        <td>{{ $value['amount'] }} Tk</td>
                                        <td>{{ $value['discount_amount'] }}</td>
                                        {{--<td>{{ $value['discount_amount'] }} Tk</td>--}}
                                        <td style="text-align: right;">{{ $value['net_amount'] }} Tk</td>
                                    </tr>
                                <?php endforeach; ?>
                                    <tr>
                                        <td colspan ="6">EXTRA DISCOUNT (TK)</td>
                                        <td style="text-align: right;">{{ $data['discount_amount'] }} Tk</td>
                                    </tr>
                                    <tr>
                                        <td colspan ="6">Gross Amount</td>
                                        <td style="text-align: right;">{{ $data['gross_amount'] }} Tk</td>
                                    </tr>
                            </table>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
