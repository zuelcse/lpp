
@extends($pdf?'layouts/blankLayout':'layouts/contentNavbarLayout')

@section('title', 'Report')

@section('content')
    <style>
        .form-label, .col-form-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: inherit;
    }
    </style>
    

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form method="POST" target="_blank" action="{{route('report')}}" enctype="multipart/form-data" onsubmit="return reportRestriction()">
                    @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl">
                            <div class="card mb-1">
                                <fieldset class="border p-2 rounded">
                                    <legend class="float-none w-auto px-2 text-dark fw-bold">
                                        Report Filters
                                    </legend>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="row">
                                                <label class="col-3 col-sm-3 col-form-label" for="group">Group</label>
                                                <div class="col-9 col-sm-9">
                                                    <select type="text" class="form-control form-control-sm js-example-basic-single" id="group" name="group" placeholder="Enter Ledger Type" onchange="subGroups()">
                                                        <option value="">Select Group</option>
                                                        @foreach ($group as $key => $data)
                                                            <option value="{{ $data->id }}">{{ $data->alias .'-'. $data->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="row">
                                                <label class="col-3 col-sm-3 col-form-label" for="sub_group">Sub Group</label>
                                                <div class="col-9 col-sm-9">
                                                    <select type="text" class="form-control form-control-sm js-example-basic-single" id="sub_group" name="sub_group" placeholder="Enter Ledger Type" onchange="getLedgers()">
                                                        <option value="">Select Sub Group</option>
                                                        @foreach ($subgroups as $key => $data)
                                                            <option value="{{ $data->id }}">{{ $data->alias .'-'. $data->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="row">
                                                <label class="col-3 col-sm-3 col-form-label" for="ledger">Ledger</label>
                                                <div class="col-9 col-sm-9">
                                                    <select type="text" class="form-control form-control-sm js-example-basic-single" id="ledger" name="ledger">
                                                        <option value="">Select Ledger</option>
                                                        @foreach ($ledger as $key => $data)
                                                            <option value="{{ $data->id }}">{{ $data->alias .'-'. $data->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="row">
                                                <label class="col-4 col-sm-3 col-form-label" for="itemgroup">Item Group</label>
                                                <div class="col-8 col-sm-9">
                                                    <select type="text" class="form-control form-control-sm js-example-basic-single" id="itemgroup" name="itemgroup" onchange="getItems()">
                                                        <option value="">Select Item Group</option>
                                                        @foreach ($itemgroups as $key => $data)
                                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="row">
                                                <label class="col-3 col-sm-3 col-form-label" for="item">Item</label>
                                                <div class="col-9 col-sm-9">
                                                    <select type="text" class="form-control form-control-sm js-example-basic-single" id="item" name="item">
                                                        <option value="">Select Item</option>
                                                        @foreach ($items as $key => $data)
                                                            <option value="{{ $data->id }}">{{ $data->alias .'-'. $data->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 col-sm-12">
                                            <div class="row">
                                                <div class="col-5 col-sm-5">
                                                    <input type="date" class="form-control form-control-sm" value="{{ optional($info)['s_date']?$info['s_date']:date('Y-m-d') }}" id="s_date" name="s_date" placeholder="Enter Date" />
                                                </div>
                                                <div class="col-2 col-sm-2 col-form-label">To</div>
                                                <div class="col-5 col-sm-5">
                                                    <input type="date" class="form-control form-control-sm" value="{{ optional($info)['e_date']?$info['e_date']:date('Y-m-d') }}" id="e_date" name="e_date" placeholder="Enter Date" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="border p-2 rounded">
                                    <legend class="float-none w-auto px-2 text-dark fw-bold">
                                        Others Report
                                    </legend>
                                    <div class="row">
                                        <div class="col-md-3 mb-1">
                                            <button type="submit" name="report_name" value="ledger_statement" class="col-12 btn btn-xs btn-primary x-small">Ledger Statemet</button>
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <button type="submit" name="report_name" value="sub_group_statement" class="col-12 btn btn-xs btn-primary x-small">Sub Group Summary</button>
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <button type="submit" name="report_name" value="main_group_statement" class="col-12 btn btn-xs btn-primary x-small">Main Group Summary</button>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="border p-2 rounded">
                                    <legend class="float-none w-auto px-2 text-dark fw-bold">
                                        Cash Flow Statement
                                    </legend>

                                    <div class="row">
                                        <div class="col-md-3 mb-1">
                                            <button type="submit" name="report_name" value="cash_flow" class="col-12 btn btn-xs btn-primary">Cash Flow</button>
                                        </div>

                                        <div class="col-md-4 mb-1">
                                            <button type="submit" name="report_name" value="ledger_statement" class="col-12 btn btn-xs btn-primary ">Receive Payment with OB OC
                                            </button>
                                        </div>

                                        <div class="col-md-3 mb-1">
                                            <button type="submit" name="report_name" value="voucher_statement" class="col-12 btn btn-xs btn-success">Payment Statement</button>
                                        </div>

                                        <div class="col-md-2 mb-1">
                                            <button type="submit" name="report_name" value="voucher_statement" class="col-12 btn btn-xs btn-success">Expenditure</button>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="border p-2 rounded">
                                    <legend class="float-none w-auto px-2 text-primary fw-bold">
                                        Voucher Statement
                                    </legend>

                                    <div class="row">
                                        <div class="col-md-2 mb-1">
                                            <button type="submit" name="report_name" value="comming_soon" class="col-12 btn btn-xs btn-primary">BPV</button>
                                        </div>
                                        <div class="col-md-2 mb-1">
                                            <button type="submit" name="report_name" value="comming_soon" class="col-12 btn btn-xs btn-primary">BRV</button>
                                        </div>
                                        <div class="col-md-2 mb-1">
                                            <button type="submit" name="report_name" value="comming_soon" class="col-12 btn btn-xs btn-primary">CPV</button>
                                        </div>
                                        <div class="col-md-2 mb-1">
                                            <button type="submit" name="report_name" value="comming_soon" class="col-12 btn btn-xs btn-primary">CRV</button>
                                        </div>
                                        <div class="col-md-2 mb-1">
                                            <button type="submit" name="report_name" value="comming_soon" class="col-12 btn btn-xs btn-primary">BPL</button>
                                        </div>
                                        <div class="col-md-2 mb-1">
                                            <button type="submit" name="report_name" value="comming_soon" class="col-12 btn btn-xs btn-primary">JOV</button>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="border p-2 rounded">
                                    <legend class="float-none w-auto px-2 text-dark fw-bold">
                                        Sales/Item Report
                                    </legend>

                                    <div class="row">

                                        <div class="col-md-3 mb-1">
                                            <button type="submit" name="report_name" value="sales_statement" class="col-12 btn btn-xs btn-primary">Sales Statement</button>
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <button type="submit" name="report_name" value="stock_position" class="col-12 btn btn-xs btn-primary">Stock Position</button>
                                        </div>
                                        <div class="col-md-4 mb-1">
                                            <button type="submit" name="report_name" value="party_sales" class="col-12 btn btn-xs btn-primary form-control">Party-Wise Sales Report</button>
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <button type="submit" name="report_name" value="item_history" class="col-12 btn btn-xs btn-primary">Item History</button>
                                        </div>
                                        <div class="col-md-4 mb-1">
                                            <button type="submit" name="report_name" value="comming_soon" class="col-12 btn btn-xs btn-primary form-control">Profit/Loss Sales Purchase</button>
                                        </div>
                                        <div class="col-md-4 mb-1">
                                            <button type="submit" name="report_name" value="comming_soon" class="col-12 btn btn-xs btn-primary">Sales & Purchase Statement</button>
                                        </div>
                                        <div class="col-md-1 mb-1">
                                            <button type="submit" name="report_name" value="comming_soon" class="col-12 btn btn-xs btn-primary">DD</button>
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <button type="submit" name="report_name" value="comming_soon" class="col-12 btn btn-xs btn-primary">Purchase Statement</button>
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <button type="submit" name="report_name" value="comming_soon" class="col-12 btn btn-xs btn-primary">Sales Item History</button>
                                        </div>
                                        <div class="col-md-3 mb-1">
                                            <button type="submit" name="report_name" value="comming_soon" class="col-12 btn btn-xs btn-primary">BPL</button>
                                        </div>
                                    </div>
                                </fieldset>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

<script>
    function reportRestriction() {
        const report = $(event.submitter).val();
        const group = $('#group').val();
        const sub_group = $('#sub_group').val();
        const ledger = $('#ledger').val();
        const itemgroup = $('#itemgroup').val();
        const item = $('#item').val();
        const s_date = $('#s_date').val();
        const e_date = $('#e_date').val();

        // Debug (optional)
        // console.log('Report:', report);

        if (report === 'main_group_statement' && !group) {
            alert('⚠️ Please select the Group!');
            return false; // stops submission
        }else if (report === 'sub_group_statement' && !sub_group) {
            alert('⚠️ Please select the Sub Group!');
            return false; // stops submission
        }else if (report === 'ledger_statement' && !ledger) {
            alert('⚠️ Please select the Ledger!');
            return false; // stops submission
        }else if (report === 'party_sales' && !ledger) {
            alert('⚠️ Please select the Ledger!');
            return false; // stops submission
        }

        return true;
    }


    function subGroups(){
        var group = $('#group').val();

        $('#sub_group').empty().append('<option value="">-- Select Sub Group --</option>');
        if(group){
            var url = "{{url('/sub-groups/')}}/"+group;
            const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                $.each(data, function(id, name){
                    $('#sub_group').append(`<option value="${id}">${name}</option>`);
                });
            })
            // .catch(error => console.error('Error:', error));
            // alert('G'+data);
        }
        getLedgers();
    }

    function getLedgers(){
        var sub_group = $('#sub_group').val();
        $('#ledger').empty().append('<option value="">-- Select Ledger --</option>');
        if(sub_group){
            var url = "{{url('/ledgers/')}}/"+sub_group;
            const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                $.each(data, function(id, name){
                    $('#ledger').append(`<option value="${id}">${name}</option>`);
                });
            })
        }
    }

    function getItems(){
        var itemgroup = $('#itemgroup').val();
        $('#item').empty().append('<option value="">-- Select Item --</option>');
        if(itemgroup){
            var url = "{{url('/stockitems/')}}/"+itemgroup;
            const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                $.each(data, function(id, name){
                    $('#item').append(`<option value="${id}">${name}</option>`);
                });
            })
        }
    }

</script>

@endsection