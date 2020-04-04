<div class="mb-4">
    <label class="block font-bold mb-2" for="{{ $name }}">{{ $title }}</label>

    <input class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
           name="{{ $name }}"
           type="text"
           placeholder="{{ $placeholder }}"
           value="{{ $value }}"
    >

    @if ($errors->has($name))
        @foreach($errors->get($name) as $error)
            <p class="text-red-500 italic">{{ $error }}</p>
        @endforeach
    @endif
</div>
