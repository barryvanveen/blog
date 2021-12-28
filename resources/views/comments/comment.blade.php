<div class="pt-6 mb-6 border-t-4 border-darkTeal break-words" id="comment-{{ $comment['uuid'] }}" itemprop="comment" itemscope="" itemtype="https://schema.org/Comment">
    <div>
        <span class="font-bold" itemprop="author">{{ $comment['name'] }}</span>
        <span class="article-details">
            <time itemprop="dateCreated" datetime="{{ $comment['date_meta'] }}">{{ $comment['date_human_readable'] }}</time>
        </span>
    </div>
    <div>
        <div itemprop="text">{!! nl2br(e($comment['content'])) !!}</div>
    </div>
</div>
