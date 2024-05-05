@extends('layouts.app')




@section('content')
    <div class="content">

        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header card-header-border-bottom d-flex justify-content-between">
                    <h2>Products List</h2>
                    @if (auth()->user()->can('create_products'))
                    <a href="{{ url('products/create') }}" class="btn btn-primary">Create New Product</a>
                    @endif
                </div>

                <div class="card-body">

                    @livewire('product-list')
                </div>

            </div>


        </div>
    </div>
@endsection


@push('scripts')
    <script>
        function confirmDeletion(productId) {
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
                    Livewire.dispatch('deleteConfirmed', [productId]);
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                }
            })
        }
    </script>
@endpush
