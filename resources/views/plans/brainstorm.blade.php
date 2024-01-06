@extends('layouts.primary')

@section('head')
    <link href="{{ PUBLIC_DIR }}/lib/canvas/css/canvas.css?v=9" rel="stylesheet" />
@endsection

@section('content')
    <form method="post" action="/save-canvas" id="save_cavas">
        <div class="alert bg-pink-light text-danger d-none" id="validation-alert">
            <ul class="list-unstyled">
                <li>الحقل العنوان مطلوب</li>
            </ul>
        </div>
        <div class="alert bg-success text-white d-none" id="success-alert">
            <ul class="list-unstyled">
                <li>تم الحفظ بنجاح</li>
            </ul>
        </div>

        <div class="row">
            <div class="col">
                <h5 class=" text-secondary fw-bolder">
                    {{ __('Brainstorming & Ideation') }}
                </h5>
            </div>

            @csrf
            <input type="hidden" name="id" id="input_id" value="{{ $canvas->id ?? 0 }}">
            <div class="col text-end">
                <button type="submit" class="btn btn-info">
                    {{ __('Save') }}
                </button>
            </div>
        </div>




        <div class="mb-3 ">
            <label for="exampleFormControlInput1" class="form-label">{{ __('Title') }}</label><label
                class="text-danger">*</label>
            <input type="text" name="title" value="{{ $canvas->title ?? (old('title') ?? '') }}" class="form-control"
                id="title">
        </div>

        <div class="my-drawing " style="height: 800px;"></div>
    </form>
@endsection

@section('script')
    <script src="{{ PUBLIC_DIR }}/lib/canvas/js/canvas.min.js?v=50"></script>
    <script>
        $(function() {
            const lc = LC.init(document.getElementsByClassName('my-drawing')[0], {
                imageURLPrefix: '{{ PUBLIC_DIR }}/lib/canvas/img',
                imageSize: {
                    width: 1100,
                    height: null,
                },
                backgroundColor: '#F2F2F2',
                tools: [LC.tools.Pencil, LC.tools.Eraser, LC.tools.Line,
                    LC.tools.Rectangle, LC.tools.Text, LC.tools.Pan, LC.tools.Ellipse, LC.tools
                    .Eyedropper
                ],
                strokeWidths: [1, 2, 5, 10, 20, ],

                @if (!empty($canvas->src))
                    snapshot: {!! $canvas->src !!},
                @endif

            });

            let $save_canvas = $('#save_cavas');
            $save_canvas.on('submit', function(event) {
                console.log('test')
                event.preventDefault();
                $.post("/save-canvas", {
                        title: $('#title').val(),
                        id: $('#input_id').val(),
                        src: JSON.stringify(lc.getSnapshot()),
                        image: lc.getImage() ? lc.getImage().toDataURL() : null,
                        _token: '{{ csrf_token() }}',
                    })
                    .done(function(data) {
                        $('#success-alert').removeClass('d-none')
                    }).fail(function(response) {
                        $('#validation-alert').removeClass('d-none')
                    });
            });


        });
    </script>
@endsection
