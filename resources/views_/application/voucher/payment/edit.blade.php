@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Receipt')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Edit Receipt') }}</span>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary float-left btn-sm">
                        Go Back
                    </a>
                </div>
               <div class="card-body">
                                    <form method="POST" action="{{route('receipt-edit',$existingdata->id)}}" enctype="multipart/form-data">
                                        @csrf

                                        <div class="p-3">
                                            <input type="hidden" name="id" value="{{$existingdata->id}}">
                                            <div class="row mb-3">
                                                <div class="col-md-6 col-s-12">
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label"
                                                            for="basic-default-voucherType">Voucher Type</label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group input-group-merge">
                                                                <select onchange="voucherNo()" type="text" id="basic-default-voucherType"
                                                                    class="form-control form-control-sm " name="voucherType"
                                                                    placeholder="Enter Voucher Type"
                                                                    aria-label="voucherType"
                                                                    aria-describedby="
                                                                    basic-default-voucherType" required="required">
                                                                    <option value="">Select Voucher Type</option>
                                                                    @foreach ($voucherTypes as $key => $item)
                                                                        <option value="{{ $key }}" {{ $existingdata->voucher_type==$key?'selected':'' }}>
                                                                            {{ $item }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-s-12">
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label"
                                                            for="basic-default-ledger">Party Ledger
                                                            Name</label>
                                                        <div class="col-sm-8">
                                                            <select type="text" class="form-control form-control-sm js-example-basic-single"
                                                                id="basic-default-ledger" name="ledger"
                                                                placeholder="Enter Ledger Type" required="required">
                                                                <option value="">Select Ledger Type</option>
                                                                @foreach ($ledgerTypes as $key => $data)
                                                                    @foreach ($data->Ledger as $item)
                                                                        <option value="{{ $item->id }}" {{ $existingdata->ledger_party==$item->id?'selected':'' }}>{{ $item->name }}</option>
                                                                    @endforeach
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6 col-s-12">
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label" for="costCenter">Cost
                                                            Center</label>
                                                        <div class="col-sm-8">
                                                            <select type="text" id="costCenter" class="form-control form-control-sm   "
                                                                name="costCenter" placeholder="Enter Cost Center"
                                                                aria-label="Enter Cost Center"
                                                                aria-describedby="costCenter">
                                                                <option value="">Select Cost Center</option>
                                                                @foreach ($costCentreType as $key => $item)
                                                                    <option value="{{ $key }}" {{ $existingdata->cost_center==$key?'selected':'' }}>{{ $item }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-s-12">
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label" for="costCenter">Bank/Cash Name</label>
                                                        <div class="col-sm-8">
                                                            <select type="text" id="costCenter" class="form-control form-control-sm   "
                                                                name="bank_chas_name" placeholder="Enter Cost Center"
                                                                aria-label="Enter Cost Center"
                                                                aria-describedby="costCenter">
                                                                <option value="">Select Bank/Cash Name</option>
                                                                @foreach ($ledgerBankCash as $key => $item)
                                                                    <option value="{{ $item['id'] }}" {{ $existingdata->bank_chas_name==$item['id']?'selected':'' }}>{{ $item['name'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6 col-s-12">
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label" for="dueOn">Amount</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control form-control-sm " id="basic-default-mode"
                                                                    name="amount" value="{{ $existingdata->amount}}"  placeholder="Enter Amount" required="required"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-s-12">
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label" for="payment_mode">Payment Mode</label>
                                                        <div class="col-sm-8">
                                                            <select type="text" id="payment_mode" class="form-control form-control-sm   "
                                                                name="payment_mode" placeholder="Enter Cost Center"
                                                                aria-label="Enter Cost Center"
                                                                aria-describedby="costCenter">
                                                                <option value="">Select Payment Mode</option>
                                                                <option value="1" {{ $existingdata->payment_mode==1?'selected':''}}>Cash</option>
                                                                <option value="2" {{ $existingdata->payment_mode==2?'selected':''}} >Bank Transfer</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <label class="col-sm-2 col-form-label" for="narration">Narration</label>
                                                        <div class="col-sm-10">
                                                            <textarea type="text" class="form-control form-control-sm " id="narration" name="narration"
                                                                placeholder="Enter Narration" rows="2">{{ $existingdata->narration }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row m-2">
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-primary" onclick="return confirm('Are you want to proceed?')">Save</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
            </div>
        </div>
    </div>

    <script>
    async function voucherNo() {
            $(`#voucher_no`).val('');
            
            var voucherType = $(`#basic-default-voucherType`).val();
            var url = "{{url('/voucher-no/')}}/"+voucherType;

            const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                // console.log('Data from api : ',data);
                $(`#voucher_no`).val(data.voucher_no);
            })
            // $(`#voucher_no`).val(data);
            .catch(error => console.error('Error:', error));
        }
    </script>

@endsection