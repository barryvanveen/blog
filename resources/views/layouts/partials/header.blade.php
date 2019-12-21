@presenter(\App\Application\View\HeaderPresenter)
<header class="w-full">
    <div class="container mx-auto max-w-2xl px-4 py-4 flex-none sm:flex items-center">
        <div class="flex-none">
            <a class="flex text-2xl hover:underline" href="{{ $home_url }}">Barry van Veen</a>
        </div>
        <div class="flex-grow pl-4 text-base">
            <nav class="flex justify-end flex-1 md:flex-none items-center">
                @foreach($menu_items as $menu_item)
                    <a class="mr-4 inline-block hover:underline"
                       href="{{ $menu_item->url() }}"@if($menu_item->openInNewWindow()) target="_blank"@endif>
                        {{ $menu_item->name() }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>
</header>
