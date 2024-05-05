@extends('layouts.app')




@section('content')
    <div class="content">

        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>Sales Order List</h2>
                    @if (auth()->user()->can('create_sales'))
                    <a href="{{ url('sales/create') }}" class="btn btn-primary">New Sale</a>
                    @endif
                </div>

                <div class="card-body">

                    @livewire('sales.sales-list')
                </div>

            </div>


        </div>


    </div>
@endsection

@push('scripts')
    <script>
        function confirmDeletion(saleID) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteSale', [saleID]);
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                }
            })
        }

        document.addEventListener('DOMContentLoaded', function() {
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
