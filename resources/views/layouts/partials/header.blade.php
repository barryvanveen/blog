@presenter(\App\Application\View\HeaderPresenter)
<header class="w-full bg-turmeric py-6 shadow z-10">
    <div class="container-max-md flex justify-between items-center">
        @include('layouts.partials.logo', ['home_url' => $home_url])
        <nav class="hidden md:block" aria-labelledby="menu-title">
            <h1 id="menu-title" class="sr-only">Menu</h1>
            <ul class="inline-flex gap-x-16">
                @foreach($menu_items as $menu_item)
                    <li>
                        <a class="text-lg hover:text-terracotta fancy-border fancy-border-terracotta"
                           href="{{ $menu_item->url() }}"
                           @if($menu_item->openInNewWindow()) target="_blank"@endif>
                            {{ $menu_item->name() }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
        <button id="js-mobile-menu-open" type="button" class="hover:text-terracotta md:hidden" aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </div>
</header>
