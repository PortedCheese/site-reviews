<div class="form-group">
    <label for="path">Путь</label>
    <input type="text"
           id="path"
           name="data-path"
           value="{{ old("path", $config->data["path"]) }}"
           class="form-control @error("data-path") is-invalid @enderror">
    @error("data-path")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="form-group">
    <label for="data-pager">Пагинация</label>
    <input type="number"
           min="5"
           max="50"
           step="1"
           id="data-pager"
           name="data-pager"
           value="{{ old("data-pager", $config->data["pager"]) }}"
           class="form-control @error("data-pager") is-invalid @enderror">
    @error("data-pager")
    <div class="invalid-feedback" role="alert">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="form-group">
    <label for="data-email">E-mail</label>
    <input type="text"
           id="data-email"
           name="data-email"
           value="{{ old("data-email", $config->data["email"]) }}"
           class="form-control @error("data-email") is-invalid @enderror">
    @error("data-email")
        <div class="invalid-feedback" role="alert">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input type="checkbox"
               class="custom-control-input"
               id="needModerate"
               {{ (! count($errors->all()) && $config->data['needModerate']) || old("data-needModerate") ? "checked" : "" }}
               name="data-needModerate">
        <label class="custom-control-label" for="needModerate">Использовать модерацию</label>
    </div>
</div>