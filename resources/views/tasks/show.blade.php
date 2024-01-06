@extends('layouts.primary')
@section('content')
    <style>
        .tooltip-inner {
            background-color: #fff;
        }

        .tooltip[data-active='true'] .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
    </style>
    <div class=" row">
        <div class="col">
            <h5 class=" text-secondary fw-bolder">
                {{ __('Tasks') }}
            </h5>
        </div>
    </div>
    <div class="card ">
        <div class=" card-body table-responsive">
            <div class="row">
                <h6>{{ __('strategic_objective') }} </h6>
                <p>{{ isset($taskGoal) ? $taskGoal->description : '' }}</p>
            </div>
            <div class="row">
                <h6>{{ __('workplan') }}</h6>

            </div>
            <table class="table align-items-center mb-0" id="cloudonex_table">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            {{ __('Subject/Task') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            {{ __('Assigned To') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            {{ __('Start Date') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Due Date') }}
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('Status') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td>{{ $task->subject }}</td>
                            <td>{{ \App\Models\User::find($task->contact_id) ? (\App\Models\User::find($task->contact_id)->first_name). ' ' .(\App\Models\User::find($task->contact_id)->last_name)  : '' }}
                            </td>
                            <td>
                                @if (!empty($task->start_date))
                                    {{ \App\Supports\DateSupport::parse($task->start_date)->format(config('app.date_time_format')) }}
                                @endif
                            </td>
                            <td>
                                @if (!empty($task->due_date))
                                    {{ $task->due_date->format(config('app.date_time_format')) }}
                                @endif
                            </td>
                            <td>

                                <span
                                    class="text-xs btn btn-sm
                                         @if ($task->status === 'Not Started') btn-info
@elseif($task->status === 'done')
                                                btn-success
@elseif($task->status === 'in_progress')
                                                btn-primary
@elseif($task->status === 'in_review')
                                                btn-warning
@else
                                                btn-secondary @endif
                                                "
                                    type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $task->status ?? 'todo' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="exampleModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
    </script>
@endsection
