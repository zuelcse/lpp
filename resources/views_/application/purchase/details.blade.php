@extends('layouts/contentNavbarLayout')

@section('title', 'Purchase Details')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Purchase Details') }}</span>
                </div>
                
                @if(empty($data)) 
                <h3>Data Not Found</h3>
                @else
                @php $data = $data[0]; @endphp
                <div class="card-body">
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered" class="font-size: 0.7em">
                                <tr>
                                    <th>DATE</th>
                                    <td>{{ $data['date'] }}</td>
                                </tr>
                                <tr>
                                    <th>VOUCHER NO</th>
                                    <td>{{ $data['voucher_no'] }}</td>
                                </tr>
                                <tr>
                                    <th>LEDGER NAME</th>
                                    <td>{{ $data['ledger_name'] }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>{{ $data['total_amount'] }} Tk</td>
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
                                        <th>Quantity</th>
                                        <!-- <th>Unit</th> -->
                                        <th>Rate</th>
                                        <th>AMOUNT</th>
                                    </tr>
                                </thead>
                                
                                <?php foreach ($data['purchase_items'] as $key => $value): ?>
                                   <tr>
                                        <td>{{ $value['name'] }}</td>
                                        <td>{{ $value['purchase_quantity'] }}</td>
                                        <td>{{ $value['rate'] }}</td>
                                        <td style="text-align: right;">{{ $value['amount'] }} Tk</td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td style="text-align: center;" colspan ="3">Gross Amount</td>
                                    <td style="text-align: right;">{{ $data['total_amount'] }} Tk</td>
                                </tr>
                            </table>
                        </div>                        
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

@endsection
