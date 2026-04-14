@props(['comment', 'depth' => 0])

<div class="comment" id="comment-{{ $comment->id }}">
    <div class="comment-header">
        <div class="comment-author-info">
            <img class="comment-avatar" src="https://ui-avatars.com/api/?name={{ urlencode($comment->user?->name ?? $comment->guest_name ?? 'U') }}&background=random&color=fff" alt="{{ $comment->user?->name ?? $comment->guest_name }}">
            <div class="comment-author">
                @if($comment->user)
                    <a href="#" class="comment-user-name">{{ $comment->user->name ?? 'User' }}</a>
                @else
                    <span class="comment-guest-name">{{ $comment->guest_name ?? 'Guest' }}</span>
                @endif
                <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
    
    <div class="comment-content">
        {{ $comment->content }}
    </div>
    
    <div class="comment-actions">
        @if(config('comments.allow_guest_replies', true))
            <button type="button" class="btn-reply" data-comment-id="{{ $comment->id }}">
                {{ trans('comments::comments.reply') }}
            </button>
        @endif
        
        @auth
            @if(auth()->id() === $comment->user_id)
                <button type="button" class="btn-delete delete-comment" data-comment-id="{{ $comment->id }}">
                    {{ trans('comments::comments.delete') }}
                </button>
            @endif
        @endauth
    </div>
    
    <div class="comment-reply-form" id="reply-form-{{ $comment->id }}" style="display: none;">
        <div class="reply-form">
            <img class="comment-avatar" src="https://ui-avatars.com/api/?name=U&background=random&color=fff" alt="You">
            <div class="reply-form-content">
                @include('comments::partials._reply-form', [
                    'model' => $comment->commentable,
                    'parentId' => $comment->id
                ])
            </div>
        </div>
    </div>
    
    @if($comment->replies->count() > 0)
        <div class="comment-replies">
            @foreach($comment->replies as $reply)
                @include('comments::partials._comment', ['comment' => $reply, 'depth' => $depth + 1])
            @endforeach
        </div>
    @endif
</div>