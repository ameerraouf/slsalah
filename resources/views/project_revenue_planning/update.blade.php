
<div class="modal-header">
    <h5 class="modal-title">{{ __('update_project_revenue_planning') }}</h5>

</div>
<div class="modal-body">
    <form method="post" id="form" action="{{ route('project-revenue-planning.update',$projectRevenuePlanning->id) }}" onsubmit="submitForm(event,this)">
        @csrf
        @method('put')
        @include('project_revenue_planning.form')
    </form>
</div>
<div class="modal-footer">
    <button type="submit" form="form" class="btn btn-primary">{{__('Submit')}}</button>
</div>