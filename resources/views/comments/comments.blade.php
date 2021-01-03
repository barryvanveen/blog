@presenter(App\Application\Articles\View\ArticlesCommentsPresenter)

<section>
    <h2 id="comments">Comments (<span itemprop="commentCount">{{ $total }}</span>)</h2>
    <p>{{ __('Got a question? Liked the article or got a suggestion? Leave a comment to let us know.') }}</p>

    @foreach($comments as $comment)
        @include('comments.comment', ['comment' => $comment])
    @endforeach
</section>
