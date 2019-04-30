@extends('admin.layout')

@section('page-title', 'Редактировать отзыв - ')
@section('header-title', 'Редактировать отзыв')

@section('admin')
    <div class="col-12">
        <form action="{{ route('admin.reviews.update', ['review' => $review]) }}"
              method="post">
            @csrf
            @method('put')
            @isset ($review->from)
                <div class="form-group">
                    <label for="from">От кого</label>
                    <input type="text"
                           id="from"
                           name="from"
                           value="{{ old('from') ? old('from') : $review->from }}"
                           required
                           class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}">
                    @if ($errors->has('from'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('from') }}</strong>
                        </span>
                    @endif
                </div>
            @endisset
            @empty ($review->from)
                <input type="hidden" name="user_id" value="{{ $review->user_id }}">
            @endempty
            <div class="form-group">
                <label for="description">Текст отзыва</label>
                <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                          name="description"
                          required
                          rows="3">{{ old('description') ? old('description') : $review->description }}</textarea>
            </div>

            <div class="btn-group"
                 role="group">
                <button type="submit" class="btn btn-success">Обновить</button>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-dark">Назад к отзывам</a>
            </div>
        </form>
    </div>
@endsection
