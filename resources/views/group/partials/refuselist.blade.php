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
                    <th scope="col">{{__('User')}}</th>
                    <th scope="col">{{__('Refuse date')}}</th>
                    <th scope="col">{{__('Unkick')}}</th>
                </tr>
            </thead>
            <tbody>
                @isset($refused)
                @foreach ($refused as $user)
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
                            {{__('Refused')}} @isset($user->pivot->updated_at) {{ $user->pivot->updated_at->isoFormat('MMMM D Y, HH:mm') }} @endisset 
                        </span>
                    </td>
                    <td>
                        <!-- Unban button -->
                        <form action="{{route('groups.pending.allowBack', ['group' => $group, 'user' => $user->id])}}" method="post">
                            @method('patch')
                            @csrf
                                <button type="submit" class="btn btn-sm btn-outline-warning">{{ __('Unkick') }}</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @endisset
            </tbody>
        </table>
    </div>

</div>