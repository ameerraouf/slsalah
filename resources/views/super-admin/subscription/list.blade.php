@extends('layouts.super-admin-portal')
@section('content')

    <div class=" row">
        <div class="col">
            <h5 class="mb-2 text-secondary fw-bolder">
                {{__('Subscription')}}
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
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('username')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('plan_name')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Subscription type (monthly-yearly)')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('amount')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('payment method')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Subscription date')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Subscription status (valid – expired)')}}</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{__('Transfer number')}}</th>
                            </tr>
                            <tbody>
                            @foreach($workspaces as $workspace)

                                <tr>
                                    <td class="text-center">
                                        {{$loop->iteration}}
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{isset($workspace->user) ? $workspace->user->first_name . ' ' . $workspace->user->last_name : ''}}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{isset($workspace->plan) ? $workspace->plan->name : ''}}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{$workspace->subscription_type}}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{$workspace->price}}</p>
                                    </td>

                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{$workspace->payment_type}}</p>
                                    </td>

                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ \Carbon\Carbon::parse($workspace->subscription_start_date)->format('Y-m-d')}}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            @if($workspace->subscribed)
                                                <span class="badge badge-sm bg-success-light text-success">ساري</span>
                                            @else
                                                <span class="badge badge-sm bg-pink-light text-danger">منتهي</span>
                                            @endif
                                        </p>
                                    </td>

                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{$workspace->transfer_number}}</p>
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
    <script>
        "use strict";
        $(document).ready(function () {
            $('#cloudonex_table').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                }
            });

        });
    </script>
@endsection
