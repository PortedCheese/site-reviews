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
                                <td>{{ $review->created_human }}</td>
                                <td>
                                    {{ $review->review_id }}
                                </td>
                                <td>
                                    <confirm-delete-model-button model-id="{{ $review->id }}">
                                        @if ($moderated)
                                            <template slot="forms">
                                                <form id="change-moderate-{{ $review->id }}"
                                                      action="{{ route("admin.reviews.moderate", ['review' => $review]) }}"
                                                      method="post">
                                                    @method('put')
                                                    @csrf
                                                </form>
                                            </template>
                                        @endif
                                        <template slot="other">
                                            @if ($moderated)
                                                <a href="#"
                                                   class="btn btn-{{ $review->moderated ? "success" : "secondary" }}"
                                                   onclick="event.preventDefault();document.getElementById('change-moderate-{{ $review->id }}').submit();">
                                                    <i class="fas fa-toggle-{{ $review->moderated ? "on" : "off" }}"></i>
                                                </a>
                                            @endif
                                            @if ($review->review_id)
                                                <a href="{{ route('admin.reviews.show', ['review' => $review->review_id]) }}"
                                                   class="btn btn-dark">
                                                    <i class="far fa-caret-square-up"></i>
                                                </a>
                                            @endif
                                        </template>
                                        <template slot="edit">
                                            <a href="{{ route('admin.reviews.edit', ['review' => $review]) }}"
                                               class="btn btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </template>
                                        <template slot="show">
                                            <a href="{{ route('admin.reviews.show', ['review' => $review]) }}"
                                               class="btn btn-dark">
                                                <i class="fas fa-eye"></i>
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
