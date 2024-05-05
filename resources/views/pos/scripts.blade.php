@push('scripts')
    <script type="text/javascript">
        // Define the base URL
        var baseUrl = "{{ url('/') }}";
        // Define the path to the audio file
        var beepAudioUrl = baseUrl + "/audio/beep.wav";
        var createSaleUrl = "{{ route('pos.createSale') }}";
        var paymentURL = "{{ route('sales.payment') }}";

        var addCustomerUrl = "{{ route('customers.addcustomer') }}";

        var CurrencySymbol = "{{config('settings.currency_symbol')}}";
        const stocksModuleEnabled = @json($moduleStatuses['stocks']);
        
        const warehousesModuleEnabled = @json($moduleStatuses['warehouses']);
        var redirectURL ='{{ url('pos') }}/';
    </script>
    <script src="{{ assets('assets/js/pos.js') }}"></script>
@endpush
