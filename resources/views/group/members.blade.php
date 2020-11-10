@extends('layouts.app', ['title' => __('Members')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Consult members')
    ])

    <div class="container mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="table-responsive">
                        <table class="table align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Tasks</th>
                                <th scope="col">Color</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <th scope="row">
                                    {{$user->name}}
                                </th>
                                <td>
                                    @if ($user->id == $leaderID)
                                    <p>
                                        leader !
                                    </p>
                                @endif

                                </td>
                                <td>
                                    0
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        @include('group.subject.modal')
        
        @include('layouts.footers.auth')
    </div>
@endsection
