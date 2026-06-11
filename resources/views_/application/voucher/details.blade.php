@extends('layouts/contentNavbarLayout')

@section('title', 'Voucher')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Voucher Information') }}</span>
                </div>

                <div class="card-body">
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered" class="font-size: 0.7em">
                                <tr>
                                    <th>VOUCHER NO</th>
                                    <td>{{ $data->voucher_no }}</td>
                                </tr>
                                <tr>
                                    <th>DATE</th>
                                    <td>{{ $data->date }}</td>
                                </tr>
                                <tr>
                                    <th>VOUCHER TYPE</th>
                                    <td>{{ $data->VoucherType->voucher_name }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>{{ $data->total_amount }}</td>
                                </tr>
                                @if(in_array($data->voucher_type,[11,12]))
                                <tr>
                                    <th>Cheque No</th>
                                    <td>{{ $data->cheque_no }}</td>
                                </tr>
                                <tr>
                                    <th>Cheque Date</th>
                                    <td>{{ $data->cheque_date }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Narration</th>
                                    <td>{{ $data->narration_remarks }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card-header d-flex items-center justify-content-between">
                        <span>{{ __('Details') }}</span>
                    </div>
                    
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered" class="font-size: 0.7em">
                                <thead>
                                    <tr>
                                        <th>Debit A/C</th>
                                        <th>Credit A/C</th>
                                        <th>Note</th>
                                        <th>AMOUNT</th>
                                    </tr>
                                </thead>
                                
                                <?php foreach ($data->MasterVoucher as $key => $value): 

                                    ?>
                                   <tr>
                                        <td>{{ $value->DebitLedger['alias'].'-'.$value->DebitLedger['name'] }}</td>
                                        <td>{{ $value->DebitLedger['alias'].'-'.$value->CreditLedger['name'] }}</td>
                                        <td>{{ $value['note'] }}</td>
                                        <td style="text-align: right;">{{ $value['amount'] }}</td>
                                    </tr>
                                <?php endforeach; ?>
                                    <tr>
                                        <td colspan ="3" style="text-align: center;">Gross Amount</td>
                                        <td style="text-align: right;">{{ $data->total_amount }}</td>
                                    </tr>
                            </table>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
