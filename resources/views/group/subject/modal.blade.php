<!-- Modal -->
<form method="post" action="{{ route('groups.subjects.store', ['group' => $group]) }}" autocomplete="off" id="subject-form">
@csrf
<div class="modal fade" id="subject-modal" tabindex="-1" role="dialog" aria-labelledby="subject-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="subject-modal-label">{{__('New subject')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-control-label" for="input-name">{{ __('Title') }}</label>
                <input type="text" name="name" id="input-name" class="form-control form-control-alternative" placeholder="{{ __('Name') }}" required autofocus>
            </div>
            <div class="form-group">
                <select class="form-control form-control-alternative" id="subject-color" name="color" required>
                    <option value="">{{__('Select a color')}}</option>
                    @foreach (range(1,10) as $i)
                    <option value="{{$i}}" class="subject-color-{{$i}}">{{__('Color')}} {{$i}}</option>
                    @endforeach
                    
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" id="subject-modal-method" name="_method" value="post">
</form>

@push('script')
    <script>
        var form = $("#subject-form").clone(true,true); 

        $(".subject-modal-edit").click(function(event) {
            var self = $(this);
            var form = $("#subject-form");
            form.find('#subject-modal-label').text("{{__('Edit test')}}")
            form.find('#subject-modal-method').val('patch');
            form.attr('action', self.attr('data-href'));
            form.find('#input-name').val(self.attr('data-name'));
            form.find('#subject-color').val(self.attr('data-color'));
        });

        $('#subject-modal').on('hidden.bs.modal', function () {
            var form = $("#subject-modal");
            form.find('#subject-modal-label').text("{{__('New test')}}")
            form.find('#subject-modal-method').val('post');
            form.attr('action', "{{ route('groups.subjects.store', ['group' => $group]) }}");
            document.getElementById('subject-form').reset();
        });
    </script>
@endpush