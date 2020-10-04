<div class="mb-4">
    <label class="block font-bold mb-2" for="select-{{ $name }}">{{ $title }}</label>

    <div class="mt-2 inline-block relative w-full">
        <select name="{{ $name }}"
            id="select-{{ $name }}"
            class="block shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
        >
            @foreach($options as $option)
                <option value="{{ $option['value'] }}"
                    @if($option['value'] === $value) selected @endif
                >
                    {{ $option['name'] }}
                </option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
    </div>

    @if ($errors->has($name))
        @foreach($errors->get($name) as $error)
            <p class="text-red-500 italic">{{ $error }}</p>
        @endforeach
    @endif
</div>
