@extends('layouts/contentNavbarLayout')

@section('title', 'New Receipt')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('New Receipt') }}</span>
                    <a href="{{ url('/') }}" class="btn btn-secondary float-left">
                        Go Back
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl">
                            <div class="card mb-12">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{route('receipt.voucher.create')}}" enctype="multipart/form-data">
                                        @csrf

                                        <div class="p-3">
                                            <div class="row mb-3">
                                                <div class="col-md-6 col-s-12">
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label" for="basic-default-date">Date</label>
                                                        <div class="col-sm-8">
                                                            <input type="date" class="form-control form-control-sm " value="{{date('Y-m-d')}}" id="basic-default-date"
                                                                name="date" placeholder="Enter Date" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6 col-s-12">
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label"
                                                            for="basic-default-voucherType">Voucher Type</label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group input-group-merge">
                                                                <select onchange="salesItemsVariousInfo()" type="text" id="basic-default-voucherType"
                                                                    class="form-control form-control-sm " name="voucherType"
                                                                    placeholder="Enter Voucher Type"
                                                                    aria-label="voucherType"
                                                                    aria-describedby="basic-default-voucherType">
                                                                    <option value="">Select Voucher Type</option>
                                                                    @foreach ($voucherTypes as $key => $item)
                                                                        <option value="{{ $key }}">
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
                                                            for="basic-default-ledger">Ledger
                                                            Name</label>
                                                        <div class="col-sm-8">
                                                            <select type="text" class="form-control form-control-sm "
                                                                id="basic-default-ledger" name="ledger"
                                                                placeholder="Enter Ledger Type">
                                                                <option value="">Select Ledger Type</option>
                                                                @foreach ($ledgerTypes as $key => $item)
                                                                    <option value="{{ $key }}">{{ $item }}
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
                                                        <label class="col-sm-4 col-form-label" for="costCenter">Cost
                                                            Center</label>
                                                        <div class="col-sm-8">
                                                            <select type="text" id="costCenter" class="form-control form-control-sm   "
                                                                name="costCenter" placeholder="Enter Cost Center"
                                                                aria-label="Enter Cost Center"
                                                                aria-describedby="costCenter">
                                                                <option value="">Select Cost Center</option>
                                                                @foreach ($costCentreType as $key => $item)
                                                                    <option value="{{ $key }}">{{ $item }}
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
                                                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}
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
                                                        <label class="col-sm-4 col-form-label" for="voucher_no">Voucher
                                                            No.</label>
                                                        <div class="col-sm-8">
                                                            <input aria-disabled="true" disabled="disabled" type="text" id="voucher_no"
                                                                class="form-control form-control-sm   " name="voucher_no"
                                                                placeholder="Voucher No. will autogenerate"
                                                                aria-label="Enter Voucher No."
                                                                aria-describedby="voucher_no" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-s-12">
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label" for="dueOn">Amount</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control form-control-sm " id="basic-default-mode"
                                                                    name="amount" placeholder="Enter Amount" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6 col-s-12">
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label" for="payment_mode">Payment Mode</label>
                                                        <div class="col-sm-8">
                                                            <select type="text" id="payment_mode" class="form-control form-control-sm   "
                                                                name="payment_mode" placeholder="Enter Cost Center"
                                                                aria-label="Enter Cost Center"
                                                                aria-describedby="costCenter">
                                                                <option value="">Select Payment Mode</option>
                                                                <option value="1">Cash</option>
                                                                <option value="2">Bank Transfer</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-s-12">
                                                    <div class="row">
                                                        <label class="col-sm-4 col-form-label" for="attachment">Attachment</label>
                                                        <div class="col-sm-8">
                                                            <input aria-disabled="true" type="file" id="attachment"
                                                                class="form-control form-control-sm   " name="attachment"
                                                                aria-describedby="attachment" />
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
                                                                placeholder="Enter Narration" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row m-2">
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    </script>

@endsection