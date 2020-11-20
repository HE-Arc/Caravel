<div class="card bg-secondary shadow">

    <div class="card-header bg-white border-0">
        <div class="card-header-details">
            <h3>
                {{__('Refused users')}}
            </h3>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-items-center">
            <thead class="thead-light">
                <tr>
                    <th scope="col">User</th>
                    <th scope="col">Refuse date</th>
                    <th scope="col">Unkick</th>
                </tr>
            </thead>
            <tbody>
                @isset($refused)
                @foreach ($refused as $user)
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