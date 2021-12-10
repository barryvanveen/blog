@presenter(\App\Application\View\HeaderPresenter)
<header class="w-full py-6 border-b-4 border-gray-200 z-10">
    <div class="container-max-md flex justify-between items-center">
        <a class="transition-colors hover:text-blue-400" href="/">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 480 480" height="32" width="32" fill="currentColor">
                <path xmlns="http://www.w3.org/2000/svg" aria-label="B" d="M101.04 433V40.37h122.19q83.52 0 121.12 23.9 37.87 23.64 37.87 75.47 0 35.18-16.65 57.74-16.38 22.56-43.78 27.12v2.69q37.33 8.32 53.71 31.15 16.66 22.83 16.66 60.69 0 53.71-38.95 83.79Q314.54 433 247.94 433h-146.9Zm83.25-324.41v87.28h48.34q33.84 0 48.88-10.48 15.31-10.47 15.31-34.64 0-22.56-16.65-32.23-16.38-9.93-52.1-9.93h-43.78Zm51.57 153.34h-51.57v102.32h54.25q34.38 0 50.76-13.16 16.38-13.16 16.38-40.28 0-48.88-69.82-48.88Z"/>
            </svg>
        </a>
        <nav class="hidden md:block">
            <ul class="inline-flex gap-x-16">
                @foreach($menu_items as $menu_item)
                    <li>
                        <a class="text-lg hover:text-blue-400 transition-colors"
                           href="{{ $menu_item->url() }}"
                           @if($menu_item->openInNewWindow()) target="_blank"@endif>
                            {{ $menu_item->name() }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
        <button id="js-mobile-menu-open" type="button" class="hover:text-blue-400 md:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </div>
</header>
