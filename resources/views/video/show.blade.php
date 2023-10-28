@extends('layouts.'.($layout ?? 'primary'))
@section('content')

    <style>
        table.dataTable thead > tr > td.sorting_desc_disabled:after {
            top: 50%;
            content: "▾";
            opacity: 1 !important;
        }
    </style>


    <div class="row">
        <div class="col-12">
            <div class="card card-body mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="cloudonex_table">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">
                                    {{ __('video_url') }}</th>

                            </tr>
                            <tbody>

                                <tr>

                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center px-3">
                                                <a class="text-center p-2" target="_blank" href="{{ $video->url }}"
                                                   style="width: 60px; background-color: rgb(104, 210, 220); border-radius: 7px; margin-right: 60px">
                                                    {{ __('View_video') }}</a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
