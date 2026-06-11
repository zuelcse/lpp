@extends('layouts/contentNavbarLayout')

@section('title', 'Receipt Details')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">


                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Receipt Details') }}</span>
                </div>

                <div class="card-body">
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered" class="font-size: 0.7em">
                                <tr>
                                    <th>DATE</th>
                                    <td>{{ $data->date }}</td>
                                </tr>
                                <tr>
                                    <th>VOUCHER NO</th>
                                    <td>{{ $data->voucher_no }}</td>
                                </tr>
                                <tr>
                                    <th>VOUCHER TYPE</th>
                                    <td>{{ $data->VoucherType->voucher_name }}</td>
                                </tr>
                                <tr>
                                    <th>LEDGER NAME</th>
                                    <td>{{ $data->Ledger->name }}</td>
                                </tr>
                                <!-- <tr>
                                    <th>Cost Center</th>
                                    <td></td>
                                </tr> -->
                                <tr>
                                    <th>Amount</th>
                                    <td>{{ $data->amount }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Mode</th>
                                    <td>{{ $data->payment_mode==1?'Cash':'Bank Transfer' }}</td>
                                </tr>
                                <tr>
                                    <th>Narration</th>
                                    <td>{{ $data->narration }}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">Attachment
                                    <br>
                                    @if($data->attachment)
                                        <iframe style="width:500px; height:auto;" src="/storage/{{$data->attachment}}" title="description"></iframe>
                                    @endif
                                    </th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
