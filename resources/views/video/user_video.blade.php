@extends('layouts.'.($layout ?? 'primary'))
@section('content')

    <style>
        table.dataTable thead > tr > td.sorting_desc_disabled:after {
            top: 50%;
            content: "▾";
            opacity: 1 !important;
        }
    </style>
    <div class=" row">
        <div class="col">
            <h5 class="mb-2 text-secondary fw-bolder">
                {{ __('video') }}
            </h5>

        </div>

    </div>


    <div class="row">
        <div class="col-12">
            <div class="card card-body mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="cloudonex_table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">#</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">{{ __('video_name') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">{{ __('صورة كبرفيو للفيديو') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">{{ __('video_url') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-79ps-2">{{ __('تاريخ نشر الفيديو') }}</th>
                                </tr>
                            <tbody>
                                @foreach ($videos as $video)
                                  @if (now() > Carbon\Carbon::createFromFormat('H:i:s' , $video->time))
                                  <tr>
                                    <td class="text-center">
                                        <span class="d-block text-center">{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="">

                                            <div class="d-flex flex-column justify-content-center px-3">
                                                <h6 class="mb-0 text-sm"> {{ $video->name }} </h6>
                                                <p class="text-xs text-secondary mb-0"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="">

                                            <div class="d-flex flex-column justify-content-center px-3">
                                                <h6 class="mb-0 text-sm">
                                                <img class="rounded" src="{{'/uploads/'. $video->image}}" height="60" width="60">
                                                </h6>
                                                <p class="text-xs text-secondary mb-0"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>

                                            <div class="text-center mr-3">
                                                <a target="_blank" class="d-block text-center mx-auto" href="{{ $video->url }}"
                                                    style="width: 60px; background-color: rgb(104, 210, 220); border-radius: 7px; margin-right: 60px">
                                                    {{ __('View') }}</a>
                                            </div>

                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $video->created_at }}</p>
                                    </td>
                                </tr>
                                  @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script') 

@endsection
