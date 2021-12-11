<div class="mb-4">
    <label class="block font-bold mb-2">{{ $title }}</label>

    <div class="mt-2">
        @foreach($options as $option)
            <label class="inline-flex items-center">
                <input type="radio"
                       name="{{ $name }}"
                       value="{{ $option['value'] }}"
                       @if($option['value'] === $value)checked="checked"@endif
                >
                <span class="ml-2">{{ $option['title'] }}</span>
            </label>
        @endforeach
    </div>

    @if ($errors->has($name))
        @foreach($errors->get($name) as $error)
            <p class="error">{{ $error }}</p>
        @endforeach
    @endif
</div>
