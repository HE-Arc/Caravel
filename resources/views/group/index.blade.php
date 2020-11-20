@extends('layouts.app', ['title' => __('Your groups')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Groups')
    ])

<div class="container mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                @if(isset($groups))
                <div class="table-responsive">
                    <table class="table align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Group</th>
                            <th scope="col">Description</th>
                            <th scope="col">Leader</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groups as list($group, $leader))
                            <tr>
                                <th scope="row">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <span class="avatar avatar-sm rounded-circle mr-2">
                                            <img alt="Image placeholder" src="{{asset($group->pictureOrDefault())}}">
                                        </span>
                                        <a href="{{route('groups.show', $group)}}">{{$group->name}}</a>
                                    </div>
                                </th>
                                <td>
                                    {{$group->description ?? __("No description")}}
                                </td>
                                <td>
                                    {{$leader}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                @else
                <div class="text-center ">
                    <h1>You have no group, join one <a href="{{route('groups.create')}}">here !</a></h1>
                </div>
                @endif
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection