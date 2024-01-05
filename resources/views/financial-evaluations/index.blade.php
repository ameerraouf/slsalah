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
                {{ __('Finanical Evaluation') }}
            </h5>
        </div>
    </div>

    <div class="main-page-content">
        <form id="regForm" action="">
            <div class="alert alert-danger text-white d-none" id="error-alert">{{ __('All Fields Are Required') }}</div>
            {{-- <div class="alert alert-success text-white d-none" id="success-alert">تم إنشاء تحليل عن طريق الذكاء الاصطناعي و
                يمكنك مشاهدة كل تحليل بالمكان المخصص له</div> --}}
            <!-- One "tab" for each step in the form: -->
            <div class="tab mb-3">
                {{ __('Company Buisness') }}
                <div class="row">
                    <div class="col-4 p-0">
                        <div class="">
                            <input type="radio"name="industry" value="MarTech" style="width: auto;">
                            <label>{{ __('Martech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="FinTech" style="width: auto;">
                            <label>{{ __('FinTechNew') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="EdTech" style="width: auto;">
                            <label>{{ __('EdTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="RegTech" style="width: auto;">
                            <label>{{ __('RegTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="ERP Software" style="width: auto;">
                            <label>{{ __('ERP Software') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="HRM Software" style="width: auto;">
                            <label>{{ __('HRM Software') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="LMS Software" style="width: auto;">
                            <label>{{ __('LMS Software') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="General SaaS" style="width: auto;">
                            <label>{{ __('General SaaS') }}</label><br>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="">
                            <input type="radio"name="industry" value="AgriTech" style="width: auto;">
                            <label>{{ __('AgriTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="InsurTech" style="width: auto;">
                            <label>{{ __('InsurTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="PropTech" style="width: auto;">
                            <label>{{ __('PropTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="InfraTech" style="width: auto;">
                            <label>{{ __('InfraTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="DataTech" style="width: auto;">
                            <label>{{ __('DataTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="BI & Analytics" style="width: auto;">
                            <label>{{ __('BI & Analytics') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="PM Software" style="width: auto;">
                            <label>{{ __('PM Software') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="CRM Software" style="width: auto;">
                            <label>{{ __('CRM Software') }}</label><br>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="">
                            <input type="radio"name="industry" value="CleanTech" style="width: auto;">
                            <label>{{ __('CleanTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="GovTech" style="width: auto;">
                            <label>{{ __('GovTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="AdTech" style="width: auto;">
                            <label>{{ __('AdTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="HealthTech" style="width: auto;">
                            <label>{{ __('HealthTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="LegalTech" style="width: auto;">
                            <label>{{ __('LegalTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="CommTech" style="width: auto;">
                            <label>{{ __('CommTech') }}</label><br>
                        </div>
                        <div class="">
                            <input type="radio"name="industry" value="E-Commerce" style="width: auto;">
                            <label>{{ __('E-Commerce') }}</label><br>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab mb-3">
                {{ __('Customers Number') }}
                <div class="">
                    <input type="radio"name="customers_number" value="0-1000" style="width: auto;">
                    <label>0 - 1.000</label><br>
                </div>
                <div class="">
                    <input type="radio"name="customers_number" value="1000 - 10000" style="width: auto;">
                    <label>1.000 - 10.000</label><br>
                </div>
                <div class="">
                    <input type="radio"name="customers_number" value="10000" style="width: auto;">
                    <label>أكثر من 10.000</label><br>
                </div>
            </div>

            <div class="tab mb-3">
                {{ __('Yearly Income') }}
                <div class="mt-2">
                    <input type="number" name="yearly_income" class="form-control" placeholder="SAR">
                </div>
            </div>

            <div class="tab mb-3">
                {{ __('Last Year Growth') }}
                <div class="">
                    <input type="radio" name="revnue_rate" value="0 - 10%" style="width: auto;">
                    <label>0 - 10%</label><br>
                </div>
                <div class="">
                    <input type="radio" name="revnue_rate" value="10 - 20%" style="width: auto;">
                    <label>10 - 20%</label><br>
                </div>
                <div class="">
                    <input type="radio" name="revnue_rate" value="20 - 40%" style="width: auto;">
                    <label>20 - 40%</label><br>
                </div>
                <div class="">
                    <input type="radio" name="revnue_rate" value="40 - 60%" style="width: auto;">
                    <label>40 - 50%</label><br>
                </div>
                <div class="">
                    <input type="radio" name="revnue_rate" value="60 - 100%" style="width: auto;">
                    <label>60 - 100%</label><br>
                </div>
                <div class="">
                    <input type="radio" name="revnue_rate" value="100" style="width: auto;">
                    <label>أكثر من 100%</label><br>
                </div>
            </div>

            <div class="tab mb-3">
                {{ __('Investements Till Now') }}
                <div class="">
                    <input type="radio" name="investments" value="0" style="width: auto;">
                    <label>لا يوجد</label><br>
                </div>
                <div class="">
                    <input type="radio" name="investments" value="0 - 500.000" style="width: auto;">
                    <label>من 0 الى 500 ألف ريال</label><br>
                </div>
                <div class="">
                    <input type="radio" name="investments" value="500.000 - 1.000.000" style="width: auto;">
                    <label>من 500 ألف ريال - 1 مليون ريال</label><br>
                </div>
                <div class="">
                    <input type="radio" name="investments" value="1.000.000 - 10.000.000" style="width: auto;">
                    <label>من 1 مليون ريال - 10 مليون ريال</label><br>
                </div>
                <div class="">
                    <input type="radio" name="investments" value="10.000.000" style="width: auto;">
                    <label>أكثر من 10 مليون ريال</label><br>
                </div>
            </div>


            <div class="tab mb-3">
                {{ __('Average Experience') }}

                <div class="">
                    <input type="radio" name="experience" value="0" style="width: auto;">
                    <label>لا يوجد خبرة</label><br>
                </div>
                <div class="">
                    <input type="radio" name="experience" value="1 - 3" style="width: auto;">
                    <label>1 ل 3</label><br>
                </div>
                <div class="">
                    <input type="radio" name="experience" value="3 - 5" style="width: auto;">
                    <label>3 ل 5</label><br>
                </div>
                <div class="">
                    <input type="radio" name="experience" value="5 - 10" style="width: auto;">
                    <label>5 ل 10</label><br>
                </div>
                <div class="">
                    <input type="radio" name="experience" value="10" style="width: auto;">
                    <label>أكثر من 10</label><br>
                </div>
            </div>

            <div class="tab mb-3">
                {{ __('Number Of Rivals') }}
                <div class="">
                    <input type="radio" name="rivals" value="0" style="width: auto;">
                    <label>لا أعرف</label><br>
                </div>
                <div class="">
                    <input type="radio" name="rivals" value="1 -3" style="width: auto;">
                    <label>1 ل 3</label><br>
                </div>
                <div class="">
                    <input type="radio" name="rivals" value="3 - 5" style="width: auto;">
                    <label>3 ل 5</label><br>
                </div>
                <div class="">
                    <input type="radio" name="rivals" value="5 - 10" style="width: auto;">
                    <label>5 ل 10</label><br>
                </div>
                <div class="">
                    <input type="radio" name="rivals" value="10" style="width: auto;">
                    <label>10+</label><br>
                </div>
            </div>

            <div class="tab mb-3">
                {{ __('Targeted Market') }}
                <div class="">
                    <input type="radio" name="market" value="1" style="width: auto;">
                    <label>أقل من 1 مليار ريال </label><br>
                </div>
                <div class="">
                    <input type="radio" name="market" value="1 - 3" style="width: auto;">
                    <label>1 ل 3 مليار ريال</label><br>
                </div>
                <div class="">
                    <input type="radio" name="market" value="3 - 5" style="width: auto;">
                    <label>3 ل 5 مليار ريال</label><br>
                </div>
                <div class="">
                    <input type="radio" name="market" value="5 - 10" style="width: auto;">
                    <label>5 ل 10 مليار ريال </label><br>
                </div>
                <div class="">
                    <input type="radio" name="market" value="10" style="width: auto;">
                    <label>أكثر من 10 مليار ريال</label><br>
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
                <span class="step"></span>
                <span class="step"></span>
            </div>
            <div class="alert alert-success d-none text-white mt-3" id="success-message">
                {{ __('Finanical Evaluation') }} : <span id="result"></span>
            </div>
            @if ($evaluation)
                <div class="alert alert-success text-white mt-3" id="success-message-existing">
                    {{ __('Finanical Evaluation') }} : {{ $evaluation->value }}<span id="result"></span>
                </div>
            @endif
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
            $('#success-message').addClass('d-none');
            $('#success-message-existing').addClass('d-none');
            $(this).prop('disabled', true)
            $(this).css({
                "opacity": "0.5"
            })
            let data = new FormData(regForm);
            $.ajax({
                method: "POST",
                url: "{{ route('financial_evaluation.create') }}",
                data: data,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('.submit-btn').css({
                        "opacity": "1"
                    })
                    $('.submit-btn').prop('disabled', false)
                    $('#success-message').removeClass('d-none');
                    $('#success-message-existing').remove();
                    $('#result').html(response)
                },
                error: function(response) {
                    $('#error-alert').removeClass('d-none');
                    $('.submit-btn').prop('disabled', false)
                    $('.submit-btn').css({
                        "opacity": "1"
                    })
                }
            })
        });
    </script>
@endsection
