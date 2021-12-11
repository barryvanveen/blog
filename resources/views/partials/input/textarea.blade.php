<div class="mb-4">
    <label id="textarea-label-{{ $name }}" class="block font-bold mb-2" for="{{ $name }}">{{ $title }}</label>

    <textarea class="appearance-none border rounded-md w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-400"
              name="{{ $name }}"
              type="textarea"
              placeholder="{{ $placeholder }}"
              aria-labelledby="textarea-label-{{ $name }}">{{ $value }}</textarea>

    @if ($errors->has($name))
        @foreach($errors->get($name) as $error)
            <p class="error">{{ $error }}</p>
        @endforeach
    @endif
</div>
