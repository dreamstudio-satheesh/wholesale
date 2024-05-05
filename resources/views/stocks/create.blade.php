@extends('layouts.app')




@section('content')
<div class="content">
    
    @livewire('update-stock')
    
</div> 
@endsection


@push('scripts')


<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('showErrorToast', event => {
            toastr.options = {
                closeButton: true,
                positionClass: "toast-top-right",
            };

            toastr.error(event.detail[0].message);
        });


        window.addEventListener('show-toastr', event => {
            toastr.options = {
                closeButton: true,
                positionClass: "toast-top-right",
            };
           
            toastr.success(event.detail[0].message);

        });
    });
</script>
    
@endpush

