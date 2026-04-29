@props([ 'name'])

<p {{ $attributes->merge() }}  id="{{ $name }}" name="{{ $name }}">...</p>

@error($name)
    <span class="invalid-feedback d-block">{{$name}}</span>
 @enderror
