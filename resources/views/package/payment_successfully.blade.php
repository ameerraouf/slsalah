@extends('layouts.'.($layout ?? 'primary'))
@section('content')

    <div class="row">
        <div class="alert alert-info">        تم الاشتراك في الباقة بنجاج</div>
    </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
      setTimeout(function() {
        window.location.href = "{{route('user.package')}}";
      }, 1000);
    });
</script>
