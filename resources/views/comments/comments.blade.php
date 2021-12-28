@presenter(App\Application\Articles\View\ArticlesCommentsPresenter)

<section class="py-8">
    <h2 class="mt-0" id="comments">Comments (<span itemprop="commentCount">{{ $total }}</span>)</h2>
    <p class="italic mb-4">{{ __('Got a question? Liked the article or got a suggestion? Leave a comment to let us know.') }}</p>

    @foreach($comments as $comment)
        @include('comments.comment', ['comment' => $comment])
    @endforeach

    <div class="bg-pomelo text-gray-50 rounded-md p-4 my-4 hidden" id="comment-created" role="alert">
        You comment has been posted, thanks for your contribution!
    </div>

    @if($comments_enabled)
        @include('comments.create')
    @else
        @include('comments.disabled')
    @endif
</section>
