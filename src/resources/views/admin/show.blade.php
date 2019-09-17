@extends('admin.layout')

@section('page-title', 'Просмотр отзыва - ')
@section('header-title', 'Просмотр отзыва')

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {!! $review->getTeaser() !!}
                <confirm-delete-model-button model-id="{{ $review->id }}">
                    @if ($moderated)
                        <template slot="other">
                            <form id="change-moderate-{{ $review->id }}"
                                  action="{{ route("admin.reviews.moderate", ['review' => $review]) }}"
                                  method="post">
                                @method('put')
                                @csrf
                            </form>
                            <a href="#"
                               class="btn btn-{{ $review->moderated ? "success" : "secondary" }}"
                               onclick="event.preventDefault();document.getElementById('change-moderate-{{ $review->id }}').submit();">
                                <i class="fas fa-toggle-{{ $review->moderated ? "on" : "off" }}"></i>
                            </a>
                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-dark">К списку отзывов</a>
                        </template>
                    @endif
                    <template slot="edit">
                        <a href="{{ route('admin.reviews.edit', ['review' => $review]) }}"
                           class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </template>
                    <template slot="delete">
                        <form action="{{ route('admin.reviews.destroy', ['review' => $review]) }}"
                              id="delete-{{ $review->id }}"
                              class="btn-group"
                              method="post">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                        </form>
                    </template>
                </confirm-delete-model-button>
            </div>
        </div>
    </div>
    @isset ($review->answerTo)
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Ответ на отзыв</h5>
                </div>
                <div class="card-body">
                    {!! $review->answerTo->getTeaser() !!}
                    <a href="{{ route('admin.reviews.show', ['review' => $review->answerTo]) }}"
                       class="btn btn-dark">
                        Просмотр
                    </a>
                </div>
            </div>
        </div>
    @endisset
    @if ($review->answers->count())
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Ответы</h5>
                </div>
                <div class="card-body">
                    @foreach ($review->answers as $answer)
                        {!! $answer->getTeaser() !!}
                        <a href="{{ route('admin.reviews.show', ['review' => $answer]) }}"
                           class="btn btn-dark">
                            Просмотр
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection
