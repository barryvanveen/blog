@presenter(\App\Application\View\HeaderPresenter)
<div id="js-mobile-menu-popover" class="hidden">
    <div>
        <div class="fixed inset-0 bg-white overflow-y-auto z-50" role="dialog" aria-modal="true">
            <div class="flex flex-col justify-center min-h-screen py-16 px-4 z-10">
                <button id="js-mobile-menu-close" type="button" class="transition hover:text-blue-400" tabindex="0">
                    <i class="block w-6 fixed top-5 right-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </i>
                </button>
                <div class="text-center">
                    <ul>
                        @foreach($menu_items as $menu_item)
                            <li class="mb-4">
                                <a type="button" class="text-xl hover:text-blue-400 transition"
                                   href="{{ $menu_item->url() }}"
                                   @if($menu_item->openInNewWindow()) target="_blank"@endif>
                                    {{ $menu_item->name() }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>