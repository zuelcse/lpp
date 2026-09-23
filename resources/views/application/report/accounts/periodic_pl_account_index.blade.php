
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
                <form method="POST" target="_blank" action="{{route('report-accounts-ledger-statement-report')}}" enctype="multipart/form-data" onsubmit="return reportRestriction()">
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
                                        Report
                                    </legend>
                                    <div class="row">
                                        <div class="col-md-3 mb-1">
                                            <button type="submit" name="report_name" value="periodic_pl_account" class="col-12 btn btn-xs btn-primary x-small">Periodic Profit & Loss Account</button>
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
        const s_date = $('#s_date').val();
        const e_date = $('#e_date').val();

        // Debug (optional)
        // console.log('Report:', report);

            // alert('⚠️ Please select Dates!'+s_date+e_date);
        if (!s_date || !e_date) {
            alert('⚠️ Please select Dates!');
            return false; // stops submission
        }

        return true;
    }
</script>

@endsection