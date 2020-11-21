


<div class="card bg-secondary shadow">

    <div class="card-header bg-white border-0">
        <div class="card-header-details">
            <form action="{{route('groups.quit', $group)}}" method="post">                    
                @method('delete')
                @csrf
                <h3>{{__('Members of')}} {{$group->name}}
                    <button class="btn btn-sm btn-outline-danger float-right">
                        <i class="fas fa-door-open"></i> {{__('Quit')}}
                    </button>
                </h3>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-items-center">
            <thead class="thead-light">
                <tr>
                    <th scope="col">{{__('User')}}</th>
                    <th scope="col">{{__('Member since')}}</th>
                    <!-- manage section only for leader-->
                    @if($isLeader)
                    <th scope="col" class="text-center">{{__('Manage')}}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr @if($user->id == Auth::id()) class="table-info" @endif>
                    <th scope="row">
                        <div class="d-flex justify-content-start align-items-center">
                            <span class="avatar avatar-sm rounded-circle mr-2">
                                <img alt="Image placeholder" src="{{asset($user->getPicture())}}">
                            </span>
                            <span>{{$user->name}}</span>
                            @if ($user->id == $group->user_id)
                                <i class="fas fa-crown ml-1 text-warning"></i>
                            @endif
                        </div>                
                    </th>
                    <td>
                        <span class="mb-0">
                            {{__('Member since')}} {{ $user->pivot->updated_at->isoFormat('MMMM D Y, HH:mm') }}
                        </span>
                    </td>
                    <!-- manage section only for leader-->
                    @if($isLeader)
                    <td class="d-flex flex-row justify-content-center">
                        @if ($user->id != Auth::id())
                            <!-- Kick button -->
                            <form action="{{route('groups.members.delete', ["group" => $group, "user" => $user->id])}}" method="post">
                                @method('delete')
                                @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger mr-1">{{ __('Kick') }}</button>
                            </form>
                            <!-- make this user the leader of the group -->
                            <form action="{{route('groups.members.leader', ["group" => $group, "user" => $user->id])}}" method="post">
                                @method('put')
                                @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">{{ __('Concede leadership') }}</button>
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