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
                    <th scope="col">User</th>
                    <th scope="col">Request date</th>
                    <th scope="col" class="text-center">Decide</th>
                </tr>
            </thead>
            <tbody>
                @isset($pending)
                @foreach ($pending as $user)
                <tr>
                    <th scope="row">
                        <div class="d-flex justify-content-start align-items-center">
                            <span class="avatar avatar-sm rounded-circle mr-2">
                                @isset($user->picture)
                                    <img alt="Image placeholder" src="{{asset($user->picture)}}">
                                @endisset
                            </span>
                            <span>{{$user->name}}</span>
                        </div>
                    </th>
                    <td>
                        <span class="mb-0">
                            Asked to join the {{ $user->pivot->updated_at->isoFormat('D MMMM Y') }}
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