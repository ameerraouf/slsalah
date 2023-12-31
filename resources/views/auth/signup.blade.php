@extends('frontend.layout')
@section('title', 'Home')

<script>
    document.addEventListener('DOMContentLoaded', function () {
            console.log("Efewffe");
            var accountTypeSelect = document.getElementById('account_type');
            var option1Fields = document.getElementById('option1Fields');
            var option2Fields = document.getElementById('option2Fields');
            var option3Fields = document.getElementById('option3Fields');

            accountTypeSelect.addEventListener('change', function () {
                console.log("Efewffe");

                var selectedOption = accountTypeSelect.value;

                if (selectedOption === '1') {
                    option1Fields.style.display = 'block';
                    option2Fields.style.display = 'none';
                    option3Fields.style.display = 'none';
                } else if (selectedOption === '2') {
                    option1Fields.style.display = 'none';
                    option2Fields.style.display = 'block';
                    option3Fields.style.display = 'block';
                } else {
                    option1Fields.style.display = 'none';
                    option2Fields.style.display = 'none';
                    option3Fields.style.display = 'none';
                    // Handle other cases if needed
                }
            });
        });

    (function() {
        "use strict";
        // $('#account_type').change(function () {
        //     console.log("ddddddddd");
        //     var selectedOption = $(this).val();

        //     if (selectedOption === '1') {
        //         $('#option1Fields').show();
        //         $('#option2Fields').hide();
        //     } else if (selectedOption === '2') {
        //         $('#option1Fields').hide();
        //         $('#option2Fields').show();
        //     } else {
        //         // Handle other cases if needed
        //     }
        // });
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    })();
</script>

@section('content')

<section class="min-vh-100">
    <div class="row my-6">
        <div class="col-md-7">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="text-center mx-auto">
                        <h1 class=" text-purple mb-4 mt-10">{{ __('“Play by the rules, but be ferocious.” ') }}</h1>
                        <h6 class="text-lead text-success">{{ __('– Phil Knight') }}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="container">
                <div class=" card z-index-0 mt-5">
                    <div class="card-header text-start pt-4">
                        <h4>{{ __('SignUp') }}</h4>
                    </div>
                    <div class="card-body">
                        <form role="form text-left" method="post" action="/signup">
                            @if (session()->has('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                            @endif
                            @if ($errors->any())
                            <div class="alert bg-pink-light text-danger">
                                <ul class="list-unstyled">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <label>{{ __('First Name') }} <span class="text-danger">*</span></label>
                            <div class="mb-3">
                                <input name="first_name" class="form-control" type="text"
                                    placeholder="{{ __('First Name') }}" value="{{ old('first_name') }}"
                                    aria-describedby="email-addon">
                            </div>
                            <label>{{ __('Last Name') }} <span class="text-danger">*</span></label>
                            <div class="mb-3">
                                <input type="text" name="last_name" class="form-control"
                                    placeholder="{{ __('Last Name') }}" value="{{ old('last_name') }}"
                                    aria-describedby="email-addon">
                            </div>
                            <label>{{ __('Email') }} <span class="text-danger">*</span></label>
                            <div class="mb-3">
                                <input type="email" placeholder="{{ __('Email') }}" name="email" class="form-control"
                                    value="{{ old('email') }}" aria-label="Email" aria-describedby="email-addon">
                            </div>
                            <label>{{ __('Choose Password') }} <span class="text-danger">*</span></label>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control"
                                    placeholder="{{ __('Password') }}" aria-label="Password"
                                    aria-describedby="password-addon">
                            </div>

                            <label for="account_type"> {{__('Account_type')}} </label>
                            <div class="mb-3">
                                <select id="account_type" name="account_type" class="form-control">
                                    <option value="0">{{ __('select_account_type') }}</option>
                                    <option value="2">مستسمر</option>
                                    <option value="1">رائد اعمال</option>
                                </select>
                            </div>

                            <div id="option1Fields" style="display: none;">
                                <label>{{ __('company_name') }} <span class="text-danger">*</span></label>
                                <div class="mb-3">
                                    <input name="company_name" class="form-control" type="text"
                                        placeholder="{{ __('company_name') }}" value="{{ old('company_name') }}"
                                        aria-describedby="email-addon">
                                </div>
                            </div>
                            <div id="option2Fields" style="display: none;">

                                <label>{{ __('How many startups are in your portfolio?') }} <span class="text-danger">*</span></label>
                                <div class="mb-3">
                                    <input name="count_startup_company" class="form-control" type="number"
                                        placeholder="{{ __('How many startups are in your portfolio?') }}" value="{{ old('input2') }}"
                                        aria-describedby="email-addon" id="option2Fields">
                                </div>
                            </div>
                            <div id="option3Fields" style="display: none;">

                                <label>{{ __('Investment term') }} <span class="text-danger">*</span></label>
                                <div class="mb-3">
                                    <div class="d-flex">
                                        <input name="from" class="form-control" type="number"
                                               placeholder="{{ __('from') }}" value="{{ old('from') }}"
                                               aria-describedby="email-addon" id="option3Fields">
                                        <div style="    width: 50px;"></div>
                                        <input name="to" class="form-control" type="number"
                                               placeholder="{{ __('to') }}" value="{{ old('to') }}"
                                               aria-describedby="email-addon" id="option3Fields">
                                    </div>

                                </div>
                            </div>


                            @if (!empty($super_settings['config_recaptcha_in_user_signup']))
                            <div class="g-recaptcha" data-sitekey="{{ $super_settings['recaptcha_api_key'] }}">

                            </div>
                            @endif
                            @csrf
                            <div class="text-start">
                                <button type="submit" class="btn btn-info  my-4 mb-2">{{ __('Sign up') }}</button>
                            </div>
                            <p class="text-sm mt-3 mb-0">{{ __('Already have an account?') }} <a href="/login"
                                    class="text-dark font-weight-bolder">{{ __('Sign in') }}</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




@endsection