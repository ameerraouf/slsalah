@extends('layouts.primary')
@section('content')
    <div class=" row">
        <div class="col">
            <h5 class="mb-2 text-secondary fw-bolder">
                {{__('SWOT Analysis')}}
            </h5>
        </div>
        <div class="col text-end">
            <a href="/write-swot" type="button" class="btn btn-info">
                {{__('New SWOT Analysis')}}
            </a>
        </div>
    </div>
@endsection
