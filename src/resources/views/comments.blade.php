@props(['model', 'options' => []])

<div class="comments-section" data-model-type="{{ get_class($model) }}" data-model-id="{{ $model->id }}">
    <h3 class="comments-title">
        {{ trans('comments::comments.comments') }}
        <span class="comments-count">{{ $model->comments->count() }}</span>
    </h3>
    
    <div class="comment-form-inline">
        <img class="comment-avatar" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()?->name ?? 'U') }}&background=random&color=fff" alt="You">
        @include('comments::partials._form', ['model' => $model, 'parentId' => null])
    </div>
    
    <div class="comments-list">
        @forelse($model->comments as $comment)
            @include('comments::partials._comment', ['comment' => $comment])
        @empty
            <p class="no-comments">{{ trans('comments::comments.no_comments') }}</p>
        @endforelse
    </div>
</div>
