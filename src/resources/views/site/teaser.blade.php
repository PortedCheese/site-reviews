<div class="row mb-4 mt-4">
    <div class="col-12 col-md-2 mb-2">
        <div class="row">
            <div class="col-3 col-md-12 text-center">
                @if(! empty($avatar))
                    @pic([
                    "image" => $avatar,
                    "template" => "avatar-small",
                    "grid" => [],
                    "imgClass" => "img-thumbnail rounded-circle",
                    ])
                @else
                    <i class="fas fa-angle-double-right fa-3x pt-2 text-primary"></i>
                @endif
            </div>
            <div class="col align-self-center d-block d-md-none">
                <h6 class="">
                    {{ $review->name }}
                    <br>
                    <small class="text-black-50">
                        <b>
                            {{ $review->registered_human }}
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
                            {{ $review->registered_human }}
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
