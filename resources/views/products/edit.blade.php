@extends('layouts.app')




@section('content')
<div class="content">

    @livewire('edit-product',['productId' => $id ])

</div> 
@endsection