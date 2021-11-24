<div class="mb-8">
    <label class="block font-bold mb-2" for="editor-{{ $name }}">{{ $title }}</label>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <textarea class="appearance-none border rounded-md w-full py-2 px-3 leading-tight focus:outline-none focus:ring focus:border-blue-400"
                      id="editor-{{ $name }}"
                      name="{{ $name }}"
                      type="textarea"
                      placeholder="{{ $placeholder }}"
                      data-editor
                      data-preview="#editor-preview-{{ $name }}"
            >{{ $value }}</textarea>

            @if ($errors->has($name))
                @foreach($errors->get($name) as $error)
                    <p class="text-red-500 italic">{{ $error }}</p>
                @endforeach
            @endif
        </div>

        <div class="border rounded w-full py-2 px-3 leading-tight"
             id="editor-preview-{{ $name }}">
        </div>
    </div>
</div>
