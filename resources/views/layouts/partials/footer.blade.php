@presenter(\App\Application\View\FooterPresenter)
<footer class="w-full">
    <div class="container mx-auto max-w-2xl px-4 py-4 flex-none sm:flex items-center justify-center text-gray-700">

        @foreach($menu_items as $menu_item)
            <a class="pr-4 align-items-center hover:text-black hover:underline"
               href="{{ $menu_item->url() }}" @if($menu_item->openInNewWindow())target="_blank"@endif>
                {{ $menu_item->name() }}
            </a>
        @endforeach

    </div>
</footer>
