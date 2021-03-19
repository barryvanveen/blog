@presenter(App\Application\Articles\View\ArticlesCommentsPresenter)

<section>
    <h2 id="comments">Comments (<span itemprop="commentCount">{{ $total }}</span>)</h2>
    <p>{{ __('Got a question? Liked the article or got a suggestion? Leave a comment to let us know.') }}</p>

    @foreach($comments as $comment)
        @include('comments.comment', ['comment' => $comment])
    @endforeach

    <div class="bg-green-300 rounded p-4 my-6 hidden" id="comment-created">
        You comment has been posted, thanks for your contribution!
    </div>

    @if($comments_enabled)
        @include('comments.create')
    @else
        @include('comments.disabled')
    @endif
</section>
