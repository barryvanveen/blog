<div class="my-6" id="comment-{{ $comment['uuid'] }}" itemprop="comment" itemscope="" itemtype="https://schema.org/Comment">
    <div>
        <span class="font-bold" itemprop="author">{{ $comment['name'] }}</span>
        <span class="text-gray-700 text-sm">
            <time itemprop="dateCreated" datetime="{{ $comment['date_meta'] }}">{{ $comment['date_human_readable'] }}</time>
        </span>
    </div>
    <div>
        <div class="col-xs-12" itemprop="text">{!! nl2br(e($comment['content'])) !!}</div>
    </div>
</div>
