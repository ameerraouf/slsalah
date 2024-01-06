@extends('layouts.primary')
@section('head')
    <style>
        /* Style the form */
        #regForm {
            background-color: #ffffff;
            margin: 100px auto;
            padding: 40px;
            width: 70%;
            min-width: 300px;
        }

        /* Style the input fields */
        input {
            padding: 10px;
            width: 100%;
            font-size: 17px;
            font-family: Raleway;
            border: 1px solid #aaaaaa;
        }

        /* Mark input boxes that gets an error on validation: */
        input.invalid {
            background-color: #ffdddd;
        }

        /* Hide all steps by default: */
        .tab {
            display: none;
        }

        /* Make circles that indicate the steps of the form: */
        .step {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbbbbb;
            border: none;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5;
        }

        /* Mark the active step: */
        .step.active {
            opacity: 1;
        }

        button {
            background-color: #077ebf;
            color: #ffffff;
            outline: 0;
            padding: .5rem 1rem;
            border-radius: 14px;
            border: 0;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish {
            background-color: #077ebf;
        }
    </style>
@endsection
@section('content')
    <div class=" row">
        <div class="col">
            <h5 class="mb-2 text-secondary fw-bolder">
                {{ __('marketing_and_economic_plan') }}
            </h5>
        </div>
    </div>

    <div class="main-page-content">
        <form id="regForm" action="">
            <div class="alert alert-danger text-white d-none" id="error-alert">{{ __('All Fields Are Required') }}</div>
            <div class="alert alert-success text-white d-none" id="success-alert">تم إنشاء تحليل عن طريق الذكاء الاصطناعي و
                يمكنك مشاهدة كل تحليل بالمكان المخصص له</div>
            <!-- One "tab" for each step in the form: -->
            <div class="tab mb-3">
                {{ __('industry') }}
                <div class="">
                    <input type="radio"name="industry" value="technology" style="width: auto;">
                    <label>{{ __('technology') }}</label><br>
                </div>
                <div class="">
                    <input type="radio"name="industry" value="health care" style="width: auto;">
                    <label>{{ __('health') }}</label><br>
                </div>
                <div class="">
                    <input type="radio"name="industry" value="retail" style="width: auto;">
                    <label>{{ __('retail') }}</label><br>
                </div>
                <div class="">
                    <input type="radio"name="industry" value="finance" style="width: auto;">
                    <label>{{ __('finance') }}</label><br>
                </div>
            </div>

            <div class="tab mb-3">
                {{ __('business_size') }}
                <div class="">
                    <input type="radio"name="business_size" value="micro" style="width: auto;">
                    <label>{{ __('micro') }}</label><br>
                </div>
                <div class="">
                    <input type="radio"name="business_size" value="small" style="width: auto;">
                    <label>{{ __('small') }}</label><br>
                </div>
                <div class="">
                    <input type="radio"name="business_size" value="medium" style="width: auto;">
                    <label>{{ __('medium') }}</label><br>
                </div>
                <div class="">
                    <input type="radio"name="business_size" value="large" style="width: auto;">
                    <label>{{ __('large') }}</label><br>
                </div>
            </div>

            <div class="tab mb-3">
                {{ __('audience') }}
                <div class="">
                    <input type="radio" name="audience" value="b2c" style="width: auto;">
                    <label>{{ __('b2c') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="audience" value="b2b" style="width: auto;">
                    <label>{{ __('b2b') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="audience" value="Government agencies and institutions" style="width: auto;">
                    <label>{{ __('governate') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="audience" value="b2b b2c and Government agencies " style="width: auto;">
                    <label>{{ __('all') }}</label><br>
                </div>
            </div>

            <div class="tab mb-3">
                {{ __('product_nature') }}
                <div class="">
                    <input type="radio" name="product_nature" value="phyiscal products" style="width: auto;">
                    <label>{{ __('Physical products') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="product_nature" value="digital products" style="width: auto;">
                    <label>{{ __('Digital Products') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="product_nature" value="services" style="width: auto;">
                    <label>{{ __('Services') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="product_nature" value="phyiscal and digital products and services"
                        style="width: auto;">
                    <label>{{ __('all') }}</label><br>
                </div>
            </div>

            <div class="tab mb-3">
                {{ __('Technology Focus') }}
                <div class="">
                    <input type="radio" name="tech_focus" value="Artifical Intelligence" style="width: auto;">
                    <label>{{ __('Ai') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="tech_focus" value="Renewable Energy" style="width: auto;">
                    <label>{{ __('Renewable Energy') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="tech_focus" value="Online Trading" style="width: auto;">
                    <label>{{ __('Online Trading') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="tech_focus" value="FinTech" style="width: auto;">
                    <label>{{ __('FinTech') }}</label><br>
                </div>
            </div>


            <div class="tab mb-3">
                {{ __('Market Position') }}
                <div class="">
                    <input type="radio" name="market_position" value="lowers the cost" style="width: auto;">
                    <label>{{ __('Lowering The Cost') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="market_position" value="special" style="width: auto;">
                    <label>{{ __('Special') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="market_position" value="spcialized markers" style="width: auto;">
                    <label>{{ __('Special Market') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="market_position" value="all markets" style="width: auto;">
                    <label>{{ __('all') }}</label><br>
                </div>
            </div>

            <div class="tab mb-3">
                {{ __('Geo Location') }}
                <div class="">
                    <input type="radio" name="location" value="Modern Citites" style="width: auto;">
                    <label>{{ __('Modern') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="location" value="Suburbs" style="width: auto;">
                    <label>{{ __('Suberns') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="location" value="country side" style="width: auto;">
                    <label>{{ __('Country Side') }}</label><br>
                </div>
                <div class="">
                    <input type="radio" name="location" value="global" style="width: auto;">
                    <label>{{ __('Global') }}</label><br>
                </div>
            </div>

            <div style="overflow:auto;">
                <div style="float:right;">
                    <button type="button" id="prevBtn" onclick="nextPrev(-1)"> {{ __('Previous_button') }}</button>
                    <button type="button" id="nextBtn" onclick="nextPrev(1)"> {{ __('test') }}</button>
                    <button type="button" id = "submitBtn" class="submit-btn" style="display: none">
                        {{ __('submit') }}</button>
                </div>
            </div>

            <!-- Circles which indicates the steps of the form: -->
            <div style="text-align:center;margin-top:40px;">
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
            </div>

        </form>
        <!-- /.MultiStep Form -->
    </div>
@endsection

@section('script')
    <script>
        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab

        function showTab(n) {
            // This function will display the specified tab of the form ...
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            // ... and fix the Previous/Next buttons:
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").style.display = "none";
                document.getElementById("submitBtn").style.display = "inline";
            } else {
                document.getElementById("nextBtn").innerHTML = "{{ __('Next_button_test') }}";
            }
            // ... and run a function that displays the correct step indicator:
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            // This function will figure out which tab to display
            var x = document.getElementsByClassName("tab");
            // Exit the function if any field in the current tab is invalid:
            if (n == 1 && !validateForm()) return false;
            // Hide the current tab:
            x[currentTab].style.display = "none";
            // Increase or decrease the current tab by 1:
            currentTab = currentTab + n;
            // if you have reached the end of the form... :
            if (currentTab >= x.length) {
                //...the form gets submitted:
                // document.getElementById("regForm").submit();
                return false;
            }
            // Otherwise, display the correct tab:
            showTab(currentTab);
        }

        function validateForm() {
            // This function deals with validation of the form fields
            var x, y, i, valid = true;
            x = document.getElementsByClassName("tab");
            y = x[currentTab].getElementsByTagName("input");
            // A loop that checks every input field in the current tab:
            for (i = 0; i < y.length; i++) {
                // If a field is empty...
                if (y[i].value == "") {
                    // add an "invalid" class to the field:
                    y[i].className += " invalid";
                    // and set the current valid status to false:
                    valid = false;
                }
            }
            // If the valid status is true, mark the step as finished and valid:
            if (valid) {
                document.getElementsByClassName("step")[currentTab].className += " finish";
            }
            return valid; // return the valid status
        }

        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            //... and adds the "active" class to the current step:
            x[n].className += " active";
        }


        // submitting the form 
        $('body').on('click', '.submit-btn', function(e) {
            $('#error-alert').addClass('d-none');
            $('#success-alert').addClass('d-none');
            $(this).prop('disabled', true)
            $(this).css({
                "opacity": "0.5"
            })
            let data = new FormData(regForm);
            $.ajax({
                method: "POST",
                url: "{{ route('economiccPlan.create') }}",
                data: data,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#success-alert').removeClass('d-none');
                    $('.submit-btn').css({"opacity": "1"})
                    $('.submit-btn').prop('disabled', false)
                },
                error: function(response) {
                    $('#error-alert').removeClass('d-none');
                    $('.submit-btn').prop('disabled', false)
                    $('.submit-btn').css({"opacity": "1"})
                }
            })
        });
    </script>
@endsection
