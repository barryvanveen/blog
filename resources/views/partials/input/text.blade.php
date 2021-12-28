<div class="mb-4">
    <label class="block font-bold mb-2" for="text-{{ $name }}">{{ $title }}</label>

    <input class="appearance-none border rounded-md w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:ring-teal"
           name="{{ $name }}"
           id="text-{{ $name }}"
           type="text"
           placeholder="{{ $placeholder }}"
           value="{{ $value }}"
    >

    @if ($errors->has($name))
        @foreach($errors->get($name) as $error)
            <p class="error">{{ $error }}</p>
        @endforeach
    @endif
</div>
