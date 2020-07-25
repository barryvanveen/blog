<div class="mb-4">
    <label class="block font-bold mb-2" for="{{ $name }}">{{ $title }}</label>

    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
              name="{{ $name }}"
              type="textarea"
              placeholder="{{ $placeholder }}"
              data-editor
    >{{ $value }}</textarea>

    @if ($errors->has($name))
        @foreach($errors->get($name) as $error)
            <p class="text-red-500 italic">{{ $error }}</p>
        @endforeach
    @endif
</div>
