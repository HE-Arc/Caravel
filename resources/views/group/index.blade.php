@extends('layouts.app', ['title' => __('Your groups')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Groups')
    ])

<div class="container mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="table-responsive">
                    <table class="table align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Picture</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Leader</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($groups)
                            @foreach ($groups as list($group, $leader))
                                <tr>
                                    <td scope="row">
                                        <span class="avatar avatar-sm rounded-circle">
                                            <img alt="Image placeholder" src="{{asset($group->pictureOrDefault())}}">
                                        </span>
                                    </td>
                                    <th scope="row">
                                        <a href="{{route('groups.show', $group)}}">{{$group->name}}</a>
                                    </th>
                                    <td>
                                        {{$group->description ?? __("No description")}}
                                    </td>
                                    <td>
                                        {{$leader}}
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
    @include('layouts.footers.auth')
</div>
@endsection