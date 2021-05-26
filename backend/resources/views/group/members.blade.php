@extends('layouts.app', ['title' => __('Members')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Consult members')
    ])

    <div class="container mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                @include('group.partials.userlist')
            </div>
        </div>
        @include('group.subject.modal')

        @include('layouts.footers.auth')
    </div>
@endsection
