@extends('frontend.layout')
@section('title','Pricing')
@section('content')
    <a href="{{route('paypalProcessTransaction', ['package' => 4])}}">pay with paypal</a>
@endsection