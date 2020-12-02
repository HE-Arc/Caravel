<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('groups.index') }}">
            <img src="{{ asset('assets') }}/img/brand/brand.png" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            @isset(auth()->user()->picture)
                                <img alt="Image placeholder" src="{{ auth()->user()->picture }}">
                            @endisset
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="{{route('groups.index')}}" class="dropdown-item">
                        <i class="fas fa-list-alt"></i>
                        {{ __('My groups') }}
                    </a>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('groups.index') }}">
                            <img src="{{ asset('assets') }}/img/brand/brand.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Navigation -->
            <div class="text-muted">
                <div class="d-flex justify-content-start align-items-center">
                    <div><!--avatar container-->
                        @isset($group)
                        <span class="avatar avatar-sm rounded-circle mr-2">
                            
                            <img alt="Image placeholder" src="{{asset($group->pictureOrDefault())}}">
                        </span>
                        @endisset
                    </div>
                    <div class="dropdown d-block">
                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @if (isset($group))
                            {{$group->name}}
                        @else
                            {{ __('Select class') }}
                        @endif
                        </a>
                    
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            @foreach (auth()->user()->groupsAvailable as $item)
                                @if (!isset($group) || $group->id != $item->id)
                                    <a class="dropdown-item" href="{{route('groups.show', $item->id)}}">{{ $item->name }}</a>
                                @endif
                            @endforeach
                            <a class="dropdown-item" href="{{ route('groups.create') }}">
                                <i class="fas fa-plus"></i>
                                {{__('Add a class')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            @isset($group)

                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link{{ request()->route()->named('groups.tasks.index') ? ' active' : '' }}" href="{{ route('groups.tasks.index', $group->id) }}">
                            <i class="fas fa-calendar-alt text-primary"></i> {{ __('Upcoming') }}
                            <i class=""></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link{{ request()->route()->named('groups.subjects.index') ? ' active' : '' }}" href="{{route('groups.subjects.index', $group)}}">
                            <i class="fas fa-tasks text-primary"></i>
                            <span class="nav-link-text" >{{ __('Subjects') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-examples" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-examples">
                            <i class="fas fa-toolbox text-primary"></i>
                            <span class="nav-link-text" >{{ __('Manage') }}</span>
                        </a>
    
                        <div class="collapse show" id="navbar-examples">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('groups.edit', $group)}}">
                                        <i class="fas fa-cog text-primary"></i>
                                        <span class="nav-link-text" >{{ __('Settings') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('groups.members', $group)}}">
                                        <i class="fas fa-users text-primary"></i>
                                        <span class="nav-link-text" >{{ __('Members') }}</span>
                                        @isset($groupMembersCount)
                                            <span class="badge badge-primary ml-1">{{$groupMembersCount}}</span>
                                        @endisset
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('groups.pending', $group)}}">
                                        <i class="fas fa-user-clock text-primary"></i>
                                        <span class="nav-link-text">{{__('Requests')}}
                                            @if($groupRequestsCount > 0)
                                            <span class="badge badge-danger ml-1">{{$groupRequestsCount}}</span>
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            @endisset
        </div>
    </div>
</nav>
