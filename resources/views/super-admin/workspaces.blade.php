@extends('layouts.super-admin-portal')
@section('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <div class=" row mb-2">
        <div class="col">
            <h5 class=" text-secondary fw-bolder">
                {{ __('Workspaces') }}
            </h5>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @if (session('deleteSuccess'))
            <div class="alert alert-success text-white">
                <i class="far fa-check-circle"></i> تم حذف مساحة العمل بنجاح
            </div>
            @endif
            <div class="card card-body mb-4">

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="cloudonex_table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('Workspace Name') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('Created_at') }}</th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        {{ __('Plan') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('Status') }}</th>

                                    <th class=" text-uppercase text-secondary text-xxs opacity-7">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class = "target-table">
                                @foreach ($workspaces as $workspace)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $workspace->name }} </h6>
                                                    <p class="text-xs text-secondary mb-0"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ \App\Supports\DateSupport::parse($workspace->created_at)->format(config('app.date_time_format')) }}
                                            </p>

                                        </td>
                                        <td class="text-xs text-purple text-uppercase font-weight-bold mb-0">
                                            @if ($workspace->id !== $user->workspace_id)
                                                @if (isset($plans[$workspace->plan_id]))
                                                    {{ $plans[$workspace->plan_id]->name }}
                                                @endif
                                            @else
                                                <p class="text-xs font-weight-bold mb-0">{{ __('super admin') }}</p>
                                            @endif

                                        </td>
                                        <td class="align-middle text-sm">
                                            @if ($workspace->id !== $user->workspace_id)
                                                @if ($workspace->subscribed)
                                                    <span
                                                        class="badge badge-sm bg-success-light text-success">{{ __('Subscribed') }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-sm bg-pink-light text-danger">{{ __('Not Subscribed') }}</span>
                                                @endif
                                            @endif

                                        </td>
                                        <td class="align-middle">
                                            @if ($workspace->id !== $user->workspace_id)
                                                <a class="btn btn-link text-dark px-3 mb-0"
                                                    href="/edit-workspace?id={{ $workspace->id }}"><i
                                                        class=" text-dark fas fa-pencil-alt me-2"></i>{{ __('Edit') }}</a>
                                            @endif

                                            @if ($workspace->id !== $user->workspace_id)
                                                <a class="btn btn-link text-danger text-gradient px-3 mb-0 delete-btn"
                                                    href="/delete-workspace/{{ $workspace->id }}"><i
                                                        class="far fa-trash-alt me-2"></i>{{ __('Delete') }}</a>
                                            @endif
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
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>

    <script>
        "use strict";
        $(document).ready(function() {

            $('body').on('click', '.delete-btn', function(e) {
                e.preventDefault()
                var result = window.confirm("هل أنت متأكد انك تريد الحذف؟");
                let targetLink = $(this).attr('href')
                if (result) {
                    window.location.href = targetLink;
                }

            })
            if ($('.admin-wokspace-row').length > 0) {
                $('.target-table').prepend($('.admin-wokspace-row').prop('outerHTML'))
                $('.admin-wokspace-row')[1].remove()

            }
            $('#cloudonex_table').DataTable({
                searching: true,
                ordering: false,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                }
            });

        });
    </script>
@endsection
