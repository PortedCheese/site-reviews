@extends('admin.layout')

@section('page-title', 'Отзывы - ')
@section('header-title', 'Отзывы')

@section('admin')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="responsive-table">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>От кого</th>
                            <th>Дата</th>
                            <th>Ответ на</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($reviews as $review)
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>
                                    @isset($review->from)
                                        {{ $review->from }}
                                    @endisset
                                    @empty($review->from)
                                        {{ $review->user->email }}
                                    @endempty
                                </td>
                                <td>{{ datehelper()->format($review->created_at) }}</td>
                                <td>
                                    {{ $review->review_id }}
                                </td>
                                <td>
                                    <div role="toolbar" class="btn-toolbar">
                                        <div class="btn-group btn-group-sm mr-1">
                                            <a href="{{ route("admin.reviews.edit", ["review" => $review]) }}" class="btn btn-primary">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.reviews.show', ['review' => $review]) }}" class="btn btn-dark">
                                                <i class="far fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-confirm="{{ "delete-form-{$review->id}" }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            @if ($moderated)
                                                <button type="button" class="btn btn-{{ $review->moderated ? "success" : "secondary" }}"
                                                        data-confirm="{{ "change-moderate-{$review->id}" }}">
                                                    <i class="fas fa-toggle-{{ $review->moderated ? "on" : "off" }}"></i>
                                                </button>
                                            @endif
                                            @if ($review->review_id)
                                                <a href="{{ route('admin.reviews.show', ['review' => $review->review_id]) }}"
                                                   class="btn btn-dark">
                                                    <i class="far fa-caret-square-up"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
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
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
