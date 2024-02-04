@extends('layouts.' . ($layout ?? 'primary'))
@section('head')
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
@endsection
@section('content')

    <div class="row mb-5">
        <div class=" col-lg-8 col-12 mx-auto mt-lg-0 mt-4">
            <h5 class=" text-secondary fw-bolder">
                {{ __('General Settings') }}
            </h5>
            <div class="card">
                <div class="card-body">
                    <form enctype="multipart/form-data" action="/settings" method="post">

                        @if ($errors->any())
                            <div class="alert bg-pink-light text-danger">
                                <ul class="list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mt-4" id="basic-info">
                            <div class=" pt-0">
                                <div class="row">
                                    <label class="form-label">{{ __('Workspace Name') }}</label>
                                    <div class="input-group">
                                        <input id="firstName" name="workspace_name" value="{{ $workspace->name }}"
                                            class="form-control" type="text" required="required">
                                    </div>
                                </div>

                                @if ($user->super_admin)
                                    <div class="row">
                                        <div class="col-md-12 align-self-center">
                                            <div>
                                                <label for="logo_file"
                                                    class="form-label mt-4">{{ __('Upload Logo') }}</label>
                                                <input class="form-control" name="logo" type="file" id="logo_file">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($user->super_admin)
                                    <div class="row">
                                        <div class="col-md-12 align-self-center">
                                            <div>
                                                <label for="logo_file"
                                                    class="form-label mt-4">{{ __('Upload favicon') }}</label>
                                                <input class="form-control" name="favicon" type="file" id="favicon_file">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($user->super_admin)
                                    <label class="form-label mt-3">{{ __('Currency') }}</label>

                                    <div class="input-group">
                                        <input id="currency" name="currency"
                                            value="{{ $settings['currency'] ?? config('app.currency') }}"
                                            class="form-control" type="text" required="required">
                                    </div>
                                @endif


                                <!-- @if ($user->super_admin)
    <div class="row">
                                                                                    <div class="col-md-12 align-self-center">
                                                                                        <div>
                                                                                            <label class="form-label mt-4">{{ __('Landing Page Language') }}</label>
                                                                                            <select class="form-select" name="language" id="choices-language">
                                            @foreach ($available_languages as $key => $value)
    <option value="{{ $key }}" @if (($settings['language'] ?? null) === $key) selected @endif >{{ $value }}</option>
    @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
    @endif -->

                                @if ($user->super_admin)
                                    <div class="row">
                                        <div class="col-md-12 align-self-center">
                                            <div>
                                                <label for="free_trial_days"
                                                    class="form-label mt-4">{{ __('Free Trial Days') }}</label>
                                                <input class="form-control" name="free_trial_days" type="number"
                                                    id="free_trial_days" value="{{ $settings['free_trial_days'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($user->super_admin)
                                    <div class="row">
                                        <div class="col-md-12 align-self-center">
                                            <div>
                                                <label for="free_trial_days"
                                                    class="form-label mt-4">{{ __('Landing Page') }}</label>
                                                <select class="form-select" aria-label="Default select example"
                                                    name="landingpage">

                                                    <option value="Default"
                                                        @if (($settings['landingpage'] ?? null) === 'Default') selected @endif>
                                                        {{ __('Default landing page') }}</option>
                                                    <option value="Login"
                                                        @if (($settings['landingpage'] ?? null) === 'Login') selected @endif>
                                                        {{ __('Login Page') }}</option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                    <label class="form-label mt-4">{{ __('Custom Script') }}</label>
                                    <div class="input-group">
                                        <textarea id="custom_script" name="custom_script" class="form-control" type="text">{{ $settings['custom_script'] ?? '' }}</textarea>
                                    </div>
                                    <label class="form-label mt-4">{{ __('Meta Description') }}</label>
                                    <div class="input-group">
                                        <textarea id="meta_description" name="meta_description" class="form-control" type="text">{{ $settings['meta_description'] ?? '' }}</textarea>
                                    </div>
                                @endif


                                @csrf
                                <button class="btn btn-info float-left mt-4 mb-0">{{ __('Update') }} </button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>
    @if ($user->super_admin)
        <h5 class="col-lg-8 col-12 mx-auto mb-3 text-secondary fw-bolder">
            {{ __('reCaptcha Settings') }}
        </h5>

        <div class="card col-lg-8 col-12 mx-auto shadow mb-4">
            <div class="card-body">
                <div class="accordion-1">
                    <div class="accordion" id="accordionSettings">
                        <div class="accordion-item mb-3">
                            <h5 class="accordion-header" id="headingTwo">
                                <button class="accordion-button border-bottom font-weight-bold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                    {{ __('reCAPTCHA v2') }}
                                    {{--   <i class="collapse-open fa fa-plus text-xs pt-1 position-absolute end-0 me-3"
                                        aria-hidden="true"></i> --}}
                                    <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3"
                                        aria-hidden="true"></i>
                                </button>
                            </h5>
                            <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                                data-bs-parent="#accordionRental">
                                <div class="accordion-body text-sm">
                                    <form action="/settings/save-recaptcha-config" method="post">
                                        <div class="mt-3">
                                            <div class=" pt-0">
                                                <div class="row mb-4">
                                                    <label for="recaptcha_api_key"
                                                        class="form-label">{{ __('Site Key') }}</label>
                                                    <div class="input-group">
                                                        <input id="recaptcha_api_key" name="recaptcha_api_key"
                                                            value="{{ $settings['recaptcha_api_key'] ?? '' }}"
                                                            class="form-control" type="text" required="required">
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <label for="recaptcha_api_secret"
                                                        class="form-label">{{ __('Secret Key') }}</label>

                                                    <div class="input-group">
                                                        <input id="recaptcha_api_secret" name="recaptcha_api_secret"
                                                            value="{{ $settings['recaptcha_api_secret'] ?? '' }}"
                                                            class="form-control" type="text" required="required">
                                                    </div>
                                                </div>
                                                @if ($user->super_admin)
                                                    <div class="form-check form-switch mt-3">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="config_recaptcha_in_user_login"
                                                            name="config_recaptcha_in_user_login" value="1"
                                                            @if (!empty($settings['config_recaptcha_in_user_login'])) checked @endif>

                                                        <label class="form-check-label"
                                                            for="config_recaptcha_in_user_login">{{ __('Enable Recaptcha in User Login') }}</label>
                                                    </div>
                                                    <div class="form-check form-switch mt-3">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="config_recaptcha_in_admin_login"
                                                            name="config_recaptcha_in_admin_login" value="1"
                                                            @if (!empty($settings['config_recaptcha_in_admin_login'])) checked @endif>

                                                        <label class="form-check-label"
                                                            for="config_recaptcha_in_admin_login">{{ __('Enable Recaptcha in Admin Login') }}</label>
                                                    </div>
                                                    <div class="form-check form-switch mt-3">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            id="config_recaptcha_in_user_signup"
                                                            name="config_recaptcha_in_user_signup" value="1"
                                                            @if (!empty($settings['config_recaptcha_in_user_signup'])) checked @endif>

                                                        <label class="form-check-label"
                                                            for="config_recaptcha_in_user_signup">{{ __('Enable Recaptcha in Signup Page') }}</label>
                                                    </div>
                                                @endif

                                                @csrf
                                                <button
                                                    class="btn btn-info float-left mb-0 mt-3">{{ __('Save') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="col-lg-8 col-12 mx-auto mb-3 text-secondary fw-bolder">
            {{ __('OpenAI Settings') }}
        </h5>
        <div class="card col-lg-8 col-12 mx-auto shadow mb-6">
            <div class="card-body">
                <div class="accordion-1">
                    <div class="accordion" id="accordionSettings">
                        <div class="accordion-item mb-3">
                            <h5 class="accordion-header" id="headingItem">
                                <button class="accordion-button border-bottom font-weight-bold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseItem" aria-expanded="false"
                                    aria-controls="collapseItem">
                                    {{ __('OpenAI Settings') }}
                                    {{--   <i class="collapse-open fa fa-plus text-xs pt-1 position-absolute end-0 me-3"
                                        aria-hidden="true"></i> --}}
                                    <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3"
                                        aria-hidden="true"></i>
                                </button>
                            </h5>
                            <div id="collapseItem" class="accordion-collapse collapse show" aria-labelledby="headingItem"
                                data-bs-parent="#accordionRental">
                                <div class="accordion-body text-sm">
                                    <form action="/settings/save-openai-api-keys" method="post">
                                        <div class="mt-3">
                                            <div class=" pt-0">
                                                <div class="row mb-4">
                                                    <label for="recaptcha_api_key"
                                                        class="form-label">{{ __('API key') }}</label>
                                                    <div class="input-group">
                                                        <input id="openai_api_keys" name="openai_api_keys"
                                                            class="form-control" type="text">
                                                    </div>
                                                    <div style="width:90%; margin-inline:auto;" class="d-flex">
                                                        <button class="btn btn-primary test-keys mt-2"
                                                            style="margin-inline:auto;">{{ __('Test Keys') }}</button>
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <label for="recaptcha_api_secret"
                                                        class="form-label">{{ __('API Module') }}</label>
                                                    <div class="input-group">
                                                        <select name="api_module" id="api_module"
                                                            class="form-select mb-2">
                                                            <option value="1">1
                                                            </option>
                                                            <option value="2">2
                                                            </option>
                                                            <option value="3">3
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="alert alert-info text-white">
                                                        {{ __('Api Message') }}
                                                    </div>
                                                </div>
                                                @csrf
                                                <button
                                                    class="btn btn-info float-left mb-0 mt-3">{{ __('Save') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        let test = "{{ $api_keys_test }}"
        if (test !== null && test !== '') {
            test = test.split(",");
        }
        var apiKeys = document.getElementById('openai_api_keys')
        tagify = new Tagify(apiKeys);
        tagify.addTags(test);


        // api module 
        let apiModule = "{{ $api_module }}"
        if (apiModule) {
            $('#api_module').val(apiModule)
        }
        // test api keys
        $('body').on('click', '.test-keys', function(e) {
            e.preventDefault()

            let apiKeys = JSON.parse($('#openai_api_keys').val())
            apiKeys.forEach(key => {
                let apiKey = key.value;
                // Send the prompt to the OpenAI API
                $.ajax({
                    url: 'https://api.openai.com/v1/chat/completions',
                    headers: {
                        'Authorization': 'Bearer ' + apiKey,
                        'Content-Type': 'application/json'
                    },
                    method: 'POST',
                    data: JSON.stringify({
                        model: 'gpt-3.5-turbo',
                        messages: [{
                                role: 'system',
                                content: 'You are a helpful assistant.'
                            },
                            {
                                role: 'user',
                                content: 'This is a test'
                            }
                        ]
                    }),
                    success: function(response) {
                        // Extract the assistant's reply from the API response
                        // const reply = response.choices[0].message.content;
                        // console.log('Assistant reply:', reply);
                        Toastify({
                            text: apiKey + " success",
                            duration: 3000,
                            close: true,
                            gravity: "top", // `top` or `bottom`
                            position: "right", // `left`, `center` or `right`
                            stopOnFocus: true, // Prevents dismissing of toast on hover
                            style: {
                                background: "green",
                            },
                        }).showToast();
                    },
                    error: function(xhr, status, error) {
                        Toastify({
                            text: apiKey + " fail",
                            duration: 3000,
                            close: true,
                            gravity: "top", // `top` or `bottom`
                            position: "right", // `left`, `center` or `right`
                            stopOnFocus: true, // Prevents dismissing of toast on hover
                            style: {
                                background: "red",
                            },
                        }).showToast();
                    }
                });
            });
        })
    </script>
@endsection
