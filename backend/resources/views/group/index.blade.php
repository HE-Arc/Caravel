@extends('layouts.app', ['title' => __('Search groups')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Groups')
    ])

<div class="container mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="card-header-details">
                        <h3>{{__('Your groups')}}
                            <a class="btn btn-sm btn-default float-right " href="{{ route('groups.create')}}">
                                {{__('Add group')}}
                            </a>
                        </h3>
                    </div>
                </div>
                @if(isset($groups))
                <div class="table-responsive">
                    <table class="table align-items-center">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">{{__('Group')}}</th>
                            <th scope="col">{{__('Description')}}</th>
                            <th scope="col">{{__('Leader')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groups as $userGroup)
                            <tr>
                                <th scope="row">
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div><!--avatar container-->
                                            <span class="avatar avatar-sm rounded-circle mr-2">
                                                <img alt="Image placeholder" src="{{asset($userGroup->pictureOrDefault())}}">
                                            </span>
                                        </div>
                                        <a href="{{route('groups.show', $userGroup)}}">{{$userGroup->name}}</a>
                                    
                                        @if (Auth::id() == $userGroup->user->id)
                                            <i class="fas fa-crown ml-1 text-warning"></i>
                                        @endif
                                    </div>
                                </th>
                                <td>
                                    {{$userGroup->description ?? __("No description")}}
                                </td>
                                <td>
                                    {{ $userGroup->user->name}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                @else
                <div class="card-header bg-white border-0">
                    <div class="card-header-details">
                        <h4>{{__("You have no group,  ")}}                  
                            <a href="{{route('groups.create')}}">
                                {{__('create or join one !')}}
                            </a>
                        </h4>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection