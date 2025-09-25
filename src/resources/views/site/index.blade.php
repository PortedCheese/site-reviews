@extends('layouts.boot')

@section('page-title', "Отзывы - ")

@section('header-title', "Отзывы")

@section('content')
    <div class="col-12">
        <site-reviews form-action="{{ route('site.reviews.store') }}"
                      answer-action="{{ route('site.reviews.store-answer') }}"
                      user-auth="{{ Auth::check() ? Auth::user()->id : false }}"
                      get-url="{{ route('site.reviews.list') }}"
                      company="{{ config("policy.company",'') }}"
        ></site-reviews>
    </div>
@endsection
