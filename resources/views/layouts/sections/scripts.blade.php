<!-- BEGIN: Vendor JS-->
<script src="{{ asset(mix('assets/vendor/libs/jquery/jquery.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/popper/popper.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/js/bootstrap.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/js/menu.js')) }}"></script>
<script src="{{ asset(('assets/js/custom.js')) }}"></script>
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset(mix('assets/js/main.js')) }}"></script>

<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });         
</script>
<script>
    $(document).ready(function(event) {
        $('input,select,textarea,radio').keydown( function(e) {
            console.log(e.keyCode);
            var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
            if(key == 13 || key == 40) {
                e.preventDefault();
                var inputs = $(this).closest('form').find(':input:visible');
                inputs.eq( inputs.index(this)+ 1 ).focus();
            }
            if(key == 38) {
                e.preventDefault();
                var inputs = $(this).closest('form').find(':input:visible');
                inputs.eq( inputs.index(this)- 1 ).focus();
            }
        });

        $('#newItemOnEnter').click(function(e){
            var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
            newItemOnSalesOrder();
        });
    });

    function syncStockItemToTally(x) {
        event.preventDefault();
        console.log('Hello ',x);
        const url = `{{url('stockitem/sync-to-tally-action/${x}')}}`;
        const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log(data);
            })
            .catch(error => console.error('Error:', error));
    }
</script>
<script type="text/javascript">
    $("#selectAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
