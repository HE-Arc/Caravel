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
                                    <th scope="col">Avatar</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Member since</th>
                                    <th scope="col">Quit</th>
                                    <!-- manage section only for leader-->
                                    @if($isLeader)
                                    <th>Manage</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr @if($user->id == $leaderID) class="table-primary" @endif>
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
                                            Member since {{ $user->pivot->updated_at->isoFormat('D MMMM Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($user->id == Auth::id())
                                            <form action="{{route('groups.members.delete', ["group" => $group, "user" => $user->id])}}" method="post">
                                                @method('delete')
                                                @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Quit') }}</button>
                                            </form>
                                        @endif
                                    </td>
                                    <!-- manage section only for leader-->
                                    @if($isLeader)
                                    <td>
                                        @if ($user->id != Auth::id())
                                            <!-- Kick button -->
                                            <form action="{{route('groups.members.delete', ["group" => $group, "user" => $user->id])}}" method="post">
                                                @method('delete')
                                                @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Kick') }}</button>
                                            </form>
                                            <!-- make this user the leader of the group -->
                                            <form action="{{route('groups.members.leader', ["group" => $group, "user" => $user->id])}}" method="post">
                                                @method('put')
                                                @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">{{ __('Make leader') }}</button>
                                            </form>
                                        @endif
                                    </td>
                                    @endif
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
