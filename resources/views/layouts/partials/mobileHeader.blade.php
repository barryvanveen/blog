@presenter(\App\Application\View\HeaderPresenter)
<div id="js-mobile-menu-popover" class="hidden">
    <div>
        <div class="fixed inset-0 bg-turmeric overflow-y-auto z-50" role="dialog" aria-modal="true" aria-labelledby="mobile-menu-title">
            <div class="flex flex-col justify-center min-h-screen py-16 px-4 z-10">
                <button id="js-mobile-menu-close" type="button" class="hover:text-terracotta" tabindex="0" aria-label="Close menu">
                    <i class="block w-6 fixed top-5 right-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </i>
                </button>
                <nav class="text-center" aria-labelledby="mobile-menu-title">
                    <h1 id="mobile-menu-title" class="sr-only">Menu</h1>
                    <ul>
                        <li class="mb-4">
                            <a class="text-xl hover:text-terracotta fancy-border fancy-border-turmeric"
                               href="{{ $home_url }}">
                                Home
                            </a>
                        </li>
                        @foreach($menu_items as $menu_item)
                            <li class="mb-4">
                                <a class="text-xl hover:text-terracotta fancy-border fancy-border-turmeric"
                                   href="{{ $menu_item->url() }}"
                                   @if($menu_item->openInNewWindow()) target="_blank"@endif>
                                    {{ $menu_item->name() }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
