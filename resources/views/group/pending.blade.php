@extends('layouts.app', ['title' => __('Pending')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Pending requests')
    ])

    <div class="container mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <!-- pending card -->
                <div class="card bg-secondary shadow mb-4">
                    <!-- "pending" users -->
                    <div class="card-header bg-white border-0">
                        <div class="card-header-details">
                            <h3>
                                {{__('Pending users')}}
                            </h3>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Avatar</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Request date</th>
                                    <th scope="col">Decide</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($pending)
                                @foreach ($pending as $user)
                                <tr>
                                    <th scope="row">
                                        <div class="media align-items-center">
                                            <span class="avatar avatar-sm rounded-circle">
                                                @isset($user->picture)
                                                    <img alt="Image placeholder" src="{{asset($user->picture)}}">
                                                @endisset
                                            </span>
                                        </div>                
                                    </th>
                                    <td>
                                        {{$user->name}}
                                    </td>
                                    <td>
                                        <span class="mb-0">
                                            Asked to join the {{ $user->pivot->updated_at->isoFormat('D MMMM Y') }}
                                        </span>
                                    </td>
                                    <td class="d-flex flex-col">
                                        <!-- Accept button -->
                                        <form action="{{route('groups.pending.process', ["group" => $group, "user" => $user->id, "status" => 1])}}" method="post">
                                            @method('patch')
                                            @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"><i class="far fa-check-square"></i></button>
                                        </form>
                                        <!-- Refuse button -->
                                        <form action="{{route('groups.pending.process', ["group" => $group, "user" => $user->id, "status" => 0])}}" method="post">
                                            @method('patch')
                                            @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="far fa-window-close"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card bg-secondary shadow p-2 m-2">
                    <!-- refused users -->
                    <div class="card-header">
                        <h1 class="text-center">Refused users</h1>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Avatar</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Refuse date</th>
                                    <th scope="col">Unkick</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($refused)
                                @foreach ($refused as $user)
                                <tr>
                                    <th scope="row">
                                        <div class="media align-items-center">
                                            <span class="avatar avatar-sm rounded-circle">
                                                @isset($user->picture)
                                                    <img alt="Image placeholder" src="{{asset($user->picture)}}">
                                                @endisset
                                            </span>
                                        </div>                
                                    </th>
                                    <td>
                                        {{$user->name}}
                                    </td>
                                    <td>
                                        <span class="mb-0">
                                            Refused the {{ $user->pivot->updated_at->isoFormat('D MMMM Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Go back button -->
                                        <!--<form action="{{route('groups.members.delete', ['group' => $group, 'user' => $user->id])}}" method="post">
                                            @method('delete')
                                            @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Accept') }}</button>
                                        </form>-->
                                    </td>
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
        @include('group.subject.modal')
        
        @include('layouts.footers.auth')
    </div>
@endsection
