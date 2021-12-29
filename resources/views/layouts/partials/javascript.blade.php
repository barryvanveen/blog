@presenter(\App\Application\View\JavascriptPresenter)
<script type="text/javascript">window.app = @json($js_variables)</script>

@foreach($js_paths as $path)
    <script src="{{ $path  }}"></script>
@endforeach
