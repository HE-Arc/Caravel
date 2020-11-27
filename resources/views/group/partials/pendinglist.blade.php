<div class="card bg-secondary shadow mb-4">

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
                    <th scope="col">{{__('User')}}</th>
                    <th scope="col">{{__('Request date')}}</th>
                    <th scope="col" class="text-center">{{__('Decide')}}</th>
                </tr>
            </thead>
            <tbody>
                @isset($pending)
                @foreach ($pending as $user)
                <tr>
                    <th scope="row">
                        <div class="d-flex justify-content-start align-items-center">
                            <div><!--avatar container-->
                                <span class="avatar avatar-sm rounded-circle mr-2">
                                    <img alt="Image placeholder" src="{{asset($user->getPicture())}}">
                                </span>
                            </div>
                            <span>{{$user->name}}</span>
                        </div>
                    </th>
                    <td>
                        <span class="mb-0">
                            {{__('Asked to join')}} {{ $user->pivot->updated_at->timezone(Auth::user()->timezone)->isoFormat('MMMM D Y, HH:mm') }}
                        </span>
                    </td>
                    <td class="d-flex flex-col justify-content-center">
                        <!-- Accept button -->
                        <form action="{{route('groups.pending.process', ["group" => $group, "user" => $user->id, "status" => 1])}}" method="post">
                            @method('patch')
                            @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success mr-1"><i class="far fa-check-square"></i></button>
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