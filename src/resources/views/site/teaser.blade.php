<div class="row mb-4 mt-4">
    <div class="col-2 text-center">
        @isset($review->user->avatar)
            <img src="{{ route('imagecache', [
                    'template' => 'avatar',
                    'filename' => $review->user->avatar->file_name
                 ]) }}"
                 class="img-thumbnail"
                 alt="{{ $review->user->avatar->name }}">
        @endisset
        @empty($review->user->avatar)
            <i class="fas fa-user fa-3x"></i>
        @endempty
    </div>
    <div class="col-10 pb-4">
        <h6>{{ $review->name }}</h6>
        <p class="text-black-50">
            {{ $review->created_human }}
        </p>
        <div class="review description">
            {!! $review->description !!}
        </div>
    </div>
</div>