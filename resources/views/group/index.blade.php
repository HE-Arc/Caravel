@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Consult, join or create a group')
    ])

    <div class="container mt--7">
        <div class="card bg-white">

            <div class="card-header">
                <h1 class="text-center">{{ __("Type your group Here") }}</h1>
            </div>

            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center">
                    <input type="text" id="groupInput" class="form-control w-75 p-3" placeholder="ide1-a, inf3-dlm..." aria-label="Field to input the class">
                </div>

                <div id="groupsContainer" class="d-flex flex-column justify-content-start align-items-center my-4"> 
                    <div id="createGroupCard" class="card bg-white w-50 p-3 dashed-bottom">
                        <form action="{{route('groups.store')}}" method="post">
                        @method('post')
                        @csrf
                        <div class="d-flex flex-row justify-content-between align-items-center">
                            <input type="hidden" name="name" value="" id="inputNameHidden">
                            <h1 id="createClassName"></h1>
                            <button @guest disabled="true" @endguest id="createButton" class="disabled btn btn-primary">{{ __('Create') }}</button>
                        </div>
                        </form>
                    </div>
                    <div id="model" class="card bg-white w-50 p-3 d-none" data-id="-1">
                        <div class="d-flex flex-row justify-content-between align-items-center">
                            <h1 class="groupName">{{__("Test class 2")}}</h1>
                            <button type="submit" @guest disabled="true" @endguest class="btn groupButton">{{ __('Requested') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('script')
<script>
    //set timeout on live search
    let timeout;
    $("#groupInput").keyup(function () {
        $("#inputNameHidden").val($("#groupInput").val());
        clearTimeout(timeout);
        timeout = setTimeout(liveSearch, 250); 
    });

    $("#createButton").click(function(e) {
        console.log("hi");
        let completeUrl = $(this).attr('href') + "/?name=" +  $("#groupInput").val();
        $(this).attr('href', completeUrl);
        console.log("url : " + completeUrl);
    });

    function liveSearch(){
        var groupName = $("#groupInput").val()
        $("#createClassName").text(groupName)
        if(!groupName){
            $("#createClassName").text("Type a group name");
            buildListGroups();
        } else {
            $.ajax({
                /* the route pointing to the post function */
                url: "{{ route('groups.filtered', '') }}" + '/' + groupName,
                type: 'GET',
                dataType: 'JSON',
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                    @auth
                    if(data.valid){
                        $("#createButton").removeClass("disabled");
                    } else {
                        $("#createButton").addClass("disabled");
                    }
                    @endauth
                    if(data.groups){
                        buildListGroups(data.groups);
                    }
                }
            });
        }
    }

    function buildListGroups(groups){
        //clean groups except "create" field
        $("#groupsContainer").children().not("#createGroupCard, #model").remove();
        //build groups
        if(groups){
            groups.forEach(group => {
                buildGroup(group); //build groups on page
            });
        }
    }

    function buildGroup(group){
        //clone model tweet
        let groupBody = $("#model").clone();
        //add data-id attribute
        groupBody.attr("data-id", group.id);
        //add group name
        groupBody.find(".groupName").text(group.name);
        //customize button
        let buttonType; let buttonText;
        if(group.requested){
            buttonType = "btn-info";
            buttonText = "requested";
        } else {
            buttonType = "btn-success";
            buttonText = "join";
        }
        groupBody.find(".groupButton").addClass(buttonType);
        groupBody.find(".groupButton").html(buttonText);

        //remove model-related tags
        groupBody.removeClass("d-none");
        groupBody.removeAttr("id");

        //append : Ready to go !
        $("#groupsContainer").append(groupBody[0]);
    }

    //init "create" group name
    $("#createClassName").text("Type a group name")
</script>
@endpush