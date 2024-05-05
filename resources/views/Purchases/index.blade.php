@extends('layouts.app')




@section('content')
    <div class="content">

        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>Purchases Order List</h2>
                    @if (auth()->user()->can('create_purchases'))
                    <a href="{{ route('purchases.create') }}" class="btn btn-primary">New Purchase</a>
                    @endif
                </div>

                <div class="card-body">

                    @livewire('purchase.purchase-list')
                </div>

            </div>


        </div>


    </div>
@endsection


@push('scripts')
    <script>
        function confirmDeletion(purchaseID) {
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
                    Livewire.dispatch('deletePurchase', [purchaseID]);
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