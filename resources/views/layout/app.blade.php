@extends('root')

@section('body')
<div class="container-fluid">
    <div class="row">
        <x-sidebar />
        <div class="col p-3 py-5">
            @yield('content')
        </div>
    </div>
</div>
@endsection
