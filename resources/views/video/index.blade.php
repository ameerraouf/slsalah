@extends('layouts.super-admin-portal')
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
                {{ __('control_video') }}
            </h5>

        </div>
        <div class="col text-end">
            <a href="{{ route('video.create') }}" type="button" class="btn btn-info">
                {{ __('Add New Video') }}
            </a>
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">
                                        {{ __('video_name') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">
                                        {{ __('Description_in_video') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">
                                        {{ __('video_url') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-79ps-2">
                                        {{ __('Created at') }}</th>
                                    <th
                                        class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">
                                        {{ __('Status') }}</th>
                                    <th class="text-secondary opacity-9"> {{ __('Actions') }}</th>
                                </tr>
                            <tbody>
                                @foreach ($videos as $video)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">

                                                <div class="d-flex flex-column justify-content-center px-3">
                                                    <h6 class="mb-0 text-sm"> {{ $video->name }} </h6>
                                                    <p class="text-xs text-secondary mb-0"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">

                                                <div class="d-flex flex-column justify-content-center px-3">
                                                    <h6 class="mb-0 text-sm"> {{ $video->description }} </h6>
                                                    <p class="text-xs text-secondary mb-0"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center px-3">
                                                    <a target="_blank" href="{{ $video->url }}"
                                                        style="width: 60px; background-color: rgb(104, 210, 220); border-radius: 7px; margin-right: 60px">
                                                        {{ __('View') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $video->created_at }}</p>
                                        </td>

                                        <td>
                                            <h6 class="mb-0  ">
                                                @if ($video->isActive == 1)
                                                    <span class="badge bg-pink-light text-danger mb-0 ms-3">
                                                        {{ __('Show') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-success-light mb-0  text-success">
                                                        {{ __('Hidden') }}
                                                    </span>
                                                @endif

                                            </h6>
                                        </td>

                                        <td class="align-middle text-right">
                                            <div class="ms-auto">

                                                <a class="btn btn-link text-danger text-gradient px-3 mb-0" 
                                                onclick="deleteItem('{{ route('video.destroy', $video->id) }}')"> 
                                                    <i class="far fa-trash-alt me-2"></i>{{ __('Delete') }}</a>
                                                <a class="btn btn-link text-dark px-3 mb-0"
                                                    href="{{ route('video.edit', $video->id) }}"><i
                                                        class="fas fa-pencil-alt text-dark me-2"
                                                        aria-hidden="true"></i>{{ __('Edit') }}</a>
                                            </div>  
                                        </td>
                                    </tr>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        "use strict";
        $(document).ready(function() {
            $('#cloudonex_table').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                }
            });

        });
    </script>
    <script>
        function deleteItem(url) {

            Swal.fire({
                title: 'هل أنت متأكد ؟',
                text: "لن تستطيع التراجع",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم ، احذف !',
                cancelButtonText: 'لا !'
            }).then(
                function(result) {
                    if (result.value) { 

                        window.location.href=url;
                    }
                });
        }
    </script>
@endsection
