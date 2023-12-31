
<div class="modal-header">
    <h5 class="modal-title">{{ __('add_project_revenue_planning') }}</h5>

</div>
<div class="modal-body">
    <form method="post" id="form" action="{{ route('project-revenue-planning.store') }}" onsubmit="submitForm(event,this)">
        @csrf
        @include('project_revenue_planning.form')
    </form>
</div>
<div class="modal-footer">
    <button type="submit" form="form" class="btn btn-primary">{{__('Submit')}}</button>
</div>