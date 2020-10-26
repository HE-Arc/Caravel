@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Create a Task')
    ])

    <div class="container-fluid mt--7">
        <div class="card bg-white">

            <div class="card-header">
                <h1 class="text-center">{{ __("Type your group Here") }}</h1>
            </div>

            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center">
                    <input type="text" id="class-input" class="form-control w-75 p-3" placeholder="ide1-a, inf3-dlm..." aria-label="Field to input the class">
                </div>

                <div class="d-flex flex-column justify-content-start align-items-center my-4"> 
                    <div class="card bg-white w-50 p-3 dashed-bottom">
                        <div class="d-flex flex-row justify-content-between align-items-center">
                            <h1>{{__("Test class 1")}}</h1>
                            <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                        </div>
                    </div>
                    <div class="card bg-white w-50 p-3">
                        <div class="d-flex flex-row justify-content-between align-items-center">
                            <h1>{{__("Test class 2")}}</h1>
                            <button type="submit" class="btn btn-info">{{ __('Requested') }}</button>
                        </div>
                    </div>
                    <div class="card bg-white w-50 p-3">
                        <div class="d-flex flex-row justify-content-between align-items-center">
                            <h1>{{__("Test class 3")}}</h1>
                            <button type="submit" class="btn btn-success">{{ __('Join') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection
