@extends('admin.layout')

@section('page-title', 'Просмотр отзыва - ')
@section('header-title', 'Просмотр отзыва')

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {!! $review->getTeaser() !!}

                <div role="toolbar" class="btn-toolbar">
                    <div class="btn-group btn-group-sm mr-1">
                        @can("update", \App\Review::class)
                            <a href="{{ route("admin.reviews.edit", ["review" => $review]) }}" class="btn btn-primary">
                                <i class="far fa-edit"></i>
                            </a>
                        @endcan
                        @can("delete", \App\Review::class)
                            <button type="button" class="btn btn-danger" data-confirm="{{ "delete-form-{$review->id}" }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        @endcan
                    </div>
                    <div class="btn-group btn-group-sm">
                        @can("publish", \App\Review::class)
                            @if ($moderated)
                                <button type="button" class="btn btn-{{ $review->moderated_at ? "success" : "secondary" }}"
                                        data-confirm="{{ "change-moderate-{$review->id}" }}">
                                    <i class="fas fa-toggle-{{ $review->moderated_at ? "on" : "off" }}"></i>
                                </button>
                            @endif
                        @endcan
                        @if ($review->review_id)
                            <a href="{{ route('admin.reviews.show', ['review' => $review->review_id]) }}"
                               class="btn btn-dark">
                                <i class="far fa-caret-square-up"></i>
                            </a>
                        @endif
                    </div>
                </div>
                @can("delete", \App\Review::class)
                    <confirm-form :id="'{{ "delete-form-{$review->id}" }}'">
                        <template>
                            <form action="{{ route('admin.reviews.destroy', ['review' => $review]) }}"
                                  id="delete-form-{{ $review->id }}"
                                  class="btn-group"
                                  method="post">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                        </template>
                    </confirm-form>
                @endcan
                @can("publish", \App\Review::class)
                    @if ($moderated)
                        <confirm-form :id="'{{ "change-moderate-{$review->id}" }}'"
                                      confirm-text="Да, изменить!"
                                      text="Это изменит статус показа отзыва на сайте">
                            <template>
                                <form id="change-moderate-{{ $review->id }}"
                                      action="{{ route("admin.reviews.moderate", ['review' => $review]) }}"
                                      method="post">
                                    @method('put')
                                    @csrf
                                </form>
                            </template>
                        </confirm-form>
                    @endif
                @endcan
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
