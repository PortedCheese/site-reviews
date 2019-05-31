<div class="row">
    <div class="col-12 col-md-2 mb-2">
        <div class="row">
            <div class="col-3 col-md-12 text-center">
                @if(! empty($review->user->avatar))
                    <img src="{{ route('imagecache', [
                    'template' => 'avatar-small',
                    'filename' => $review->user->avatar->file_name
                 ]) }}"
                         class="img-thumbnail rounded-circle"
                         alt="{{ $review->user->avatar->name }}">
                @else
                    <i class="fas fa-user fa-3x"></i>
                @endif
            </div>
            <div class="col align-self-center d-block d-md-none">
                <h6 class="">
                    {{ $review->name }}
                    <br>
                    <small class="text-black-50">
                        <b>
                            {{ date("d.m.Y", strtotime($review->created_at)) }}
                        </b>
                    </small>
                </h6>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-10">
        <h4 class="d-none d-md-block">
            {{ $review->name }}
            <small class="text-black-50 pl-3">
                <b>
                    {{ date("d.m.Y", strtotime($review->created_at)) }}
                </b>
            </small>
        </h4>
        <div class="review description">
            {!! $review->description !!}
        </div>
    </div>
</div>