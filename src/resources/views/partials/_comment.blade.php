@props(['comment'])

<div class="comment" id="comment-{{ $comment->id }}">
    <div class="comment-header">
        <div class="comment-author-info">
            <div class="comment-avatar">
                @if($comment->user)
                    {{ strtoupper(substr($comment->user->name ?? 'U', 0, 2)) }}
                @else
                    {{ strtoupper(substr($comment->guest_name ?? 'G', 0, 2)) }}
                @endif
            </div>
            <div class="comment-author">
                @if($comment->user)
                    <span class="comment-user-name">{{ $comment->user->name ?? 'User' }}</span>
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
        @include('comments::partials._reply-form', [
            'model' => $comment->commentable,
            'parentId' => $comment->id
        ])
    </div>
    
    @if($comment->replies->count() > 0)
        <div class="comment-replies">
            @foreach($comment->replies as $reply)
                @include('comments::partials._comment', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>
