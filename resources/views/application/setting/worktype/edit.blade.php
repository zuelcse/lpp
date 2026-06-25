@extends('layouts/contentNavbarLayout')
@section('title', 'Work Type Update')
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Work Type Update') }}</span>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm float-left">
                        Go Back
                    </a>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{route('setting-work-types-update', ['id' => $data['id']])}}">
                        @csrf
                        <div class="mb-4 p-4 border border-secondary">
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-2">
                                    <div class="row">
                                        <label class="col-sm-12 col-form-label">Veriations of the Work Type is <span class=" text-warning fw-bold">{{ $data['name'].' | '.$data['name_bn'] }}</span></label>
                                    </div>
                                </div>
                                <hr>
                               
                                <div class="col-12 mb-2">
                                    <div class="row">
                                        <label class="col-8 col-sm-2 col-form-label" for="basic-size">Size Name</label>
                                        <div class="col-12 col-sm-10">
                                            <select type="text" multiple class="form-control form-control-sm js-example-basic-single"
                                                id="basic-size" name="size[]"
                                                placeholder="Enter Ledger Type">
                                                <!-- <option value="">-Select Color-</option> -->
                                                @foreach ($sizes as $key => $item)
                                                    <option value="{{ $item->id }}" {{ isset($data) && $data['sizes']->pluck('id')->contains($item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-2">
                                    <div class="row">
                                        <label class="col-8 col-sm-2 col-form-label"
                                            for="basic-color">Color Name</label>
                                        <div class="col-12 col-sm-10">
                                            <select type="text" multiple class="form-control form-control-sm js-example-basic-single"
                                                id="basic-color" name="color[]"
                                                placeholder="Enter Ledger Type">
                                                <!-- <option value="">-Select Color-</option> -->
                                                @foreach ($colors as $key => $item)
                                                    <option value="{{ $item->id }}" {{ isset($data) && $data['colors']->pluck('id')->contains($item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-2">
                                    <div class="row">
                                        <label class="col-8 col-sm-2 col-form-label"
                                            for="basic-weight">Weight Name</label>
                                        <div class="col-12 col-sm-10">
                                            <select type="text" multiple class="form-control form-control-sm js-example-basic-single"
                                                id="basic-weight" name="weight[]"
                                                placeholder="Enter Ledger Type">
                                                <!-- <option value="">-Select Color-</option> -->
                                                @foreach ($weights as $key => $item)
                                                    <option value="{{ $item->id }}" {{ isset($data) && $data['weights']->pluck('id')->contains($item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-2">
                                    <div class="row">
                                        <label class="col-8 col-sm-2 col-form-label"
                                            for="basic-paper">Paper Name</label>
                                        <div class="col-12 col-sm-10">
                                            <select type="text" multiple class="form-control form-control-sm js-example-basic-single"
                                                id="basic-paper" name="paper[]"
                                                placeholder="Enter Ledger Type">
                                                <!-- <option value="">-Select Color-</option> -->
                                                @foreach ($papers as $key => $item)
                                                    <option value="{{ $item->id }}" {{ isset($data) && $data['papers']->pluck('id')->contains($item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-2">
                                    <div class="row">
                                        <label class="col-8 col-sm-2 col-form-label"
                                            for="basic-lamination">Lamination Name</label>
                                        <div class="col-12 col-sm-10">
                                            <select type="text" multiple class="form-control form-control-sm js-example-basic-single"
                                                id="basic-lamination" name="lamination[]"
                                                placeholder="Enter Ledger Type">
                                                <!-- <option value="">-Select Lamination-</option> -->
                                                @foreach ($laminations as $key => $item)
                                                    <option value="{{ $item->id }}" {{ isset($data) && $data['laminations']->pluck('id')->contains($item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary active d-table m-0 m-auto" onclick="return confirm('Are you want to proceed?')">Save</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>     
    <script>
    $(document).ready(function() {
        discount(0);
    });
    </script>
    <script>
        async function fetchItemDetails(i) {
            $(`#rate${i}`).val('');
            // $(`#units${i}`).val('');
            $(`#amount${i}`).val('');
            // $(`#quantity${i}`).val('');
            $(`#basic-default-damount${i}`).val(0);
            $(`#basic-default-amountwithtax${i}`).val(0);
            $(`#basic-default-discount${i}`).val(0);
            
            var itemId = $(`#items${i}`).val();
            var quantity = $(`#quantity${i}`).val();
            var url = "{{url('/stockitem/getItems/')}}/"+itemId;

            const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Data from api : ',data);
                var rate = data?.salesRate;
                $(`#rate${i}`).val(rate);
                // $(`#units${i}`).val(data?.baseUnits);
                // var amount = parseFloat(data?.salesRate) * parseFloat(data?.baseUnits);
                var amount = parseFloat(rate*quantity);
                $(`#amount${i}`).val(amount);
                discount(i);
            })
            .catch(error => console.error('Error:', error));
        }

        function getAmount(i) {
            var openingRate = $(`#rate${i}`).val();
            var quantity = $(`#quantity${i}`).val();
            if(quantity == '') quantity = 1;
            var amount = parseFloat(openingRate) * parseFloat(quantity);
            $(`#amount${i}`).val(amount);
            discount(i);
        }

        function getRate(i) {
            var amount = $(`#amount${i}`).val();
            var quantity = $(`#quantity${i}`).val();
            if(amount == '') amount = 1;
            if(quantity == '') quantity = 1;
            var rate = (parseFloat(amount) / parseFloat(quantity));
            $(`#rate${i}`).val(rate);
            discount(i);
        }

        function discount(i) {

            var discount  = parseFloat($(`#basic-default-discount${i}`).val());
            var amount = parseFloat($(`#amount${i}`).val());
            var discountAmount = parseFloat((discount/100) * amount).toFixed(2);
            // console.log(amount,' - ',discountAmount);
            var totalAmount = amount - discountAmount;
            $(`#basic-default-damount${i}`).val(discountAmount);
            $(`#basic-default-amountwithtax${i}`).val(totalAmount);

            var total=parseFloat(0);
            $(".total" ).each(function( index ) {
                total += parseFloat($(this).val());
            });

            var extra_discount = $(`#extra_discount`).val();
            if(extra_discount == '') extra_discount = 0;
            var gran_total = parseFloat(total - extra_discount);
            $(`.gran_total`).html(gran_total);
        }
    

        function removeItemOnSalesOrder(x) {
            $('#key'+x+'').remove();
            discount(x);
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let inputs = document.querySelectorAll("input, select, textarea"); 

            inputs.forEach((input, index) => {
                input.addEventListener("keydown", function (e) {
                    if (e.key === "Enter") {
                        e.preventDefault(); // stop form submit

                        if (e.shiftKey) {
                            // Shift + Enter → go to previous field
                            let prev = inputs[index - 1];
                            if (prev) prev.focus();
                        } else {
                            // Enter → go to next field
                            let next = inputs[index + 1];
                            if (next) next.focus();
                        }
                    }else if (e.key === "Backspace" && !this.value) {
                        let prev = inputs[index - 1];
                        if (prev) prev.focus();
                    }
                });
            });
        });
    </script>


@endsection