@props(['title', 'name'])




<label for="{{ $name }}">{{ $title }}</label>
<hr>
<code id="{{ $name }}" name="{{ $name }}">...</code>


 {{-- <textarea name="{{ $name }}" id="{{ $name }}" value="{{ old($name) }}" cols="30" rows="15" class="form-control" readonly style="width: 100%;"></textarea> --}}

@error($name)
    <span class="invalid-feedback d-block">{{ $message }}</span>
 @enderror
