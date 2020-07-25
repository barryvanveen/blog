@presenter(\App\Application\View\JavascriptPresenter)
@foreach($js_paths as $path)
    <script src="{{ $path  }}"></script>
@endforeach
