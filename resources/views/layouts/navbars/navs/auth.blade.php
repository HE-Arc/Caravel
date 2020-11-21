<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="{{ route('groups.index') }}">{{ __('Caravel') }}</a>

        <div class="dropdown d-none d-md-block">
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
                    {{__('Create a class')}}
                </a>
            </div>
          </div>
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img alt="Image placeholder" src="{{ asset(auth()->user()->getPicture()) }}">
                        </span>
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm  d-inline-block font-weight-bold text-truncate" style="max-width: 40vw">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
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
    </div>
</nav>