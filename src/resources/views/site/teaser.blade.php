<div class="row mb-4 mt-4">
    <div class="col-12 col-md-2 mb-2">
        <div class="row">
            <div class="col-3 col-md-12 text-center">
                @if(! empty($avatar))
                    <img src="{{ route('imagecache', [
                                    'template' => 'avatar-small',
                                    'filename' => $avatar->file_name
                                 ]) }}"
                         class="img-thumbnail rounded-circle"
                         alt="{{ $avatar->name }}">
                @else
                    <i class="fas fa-user fa-3x pt-2"></i>
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
        <div class="row">
            <div class="col-12 col-md-4">
                <h4 class="d-none d-md-block">
                    {{ $review->name }}
                    <hr>
                    <small class="text-black-50 lead">
                        <b class="small">
                            {{ date("d.m.Y", strtotime($review->created_at)) }}
                        </b>
                    </small>
                </h4>
            </div>
        </div>

        <div class="review description mt-3">
            {!! $review->description !!}
        </div>
    </div>
</div>
