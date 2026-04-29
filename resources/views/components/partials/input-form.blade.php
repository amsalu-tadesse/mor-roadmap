@props(['title', 'name', 'type'])

<label for="{{ $name }}">{{ $title }}</label>
<input type="{{ $type }}" name="{{ $name }}" value="{{ old($name) }}" class="form-control" id="{{ $name }}"

    >
@error($name)
    <span class="invalid-feedback d-block">{{ $message }}</span>
@enderror
