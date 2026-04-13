@props(['model', 'options' => []])

<div class="comments-section" data-model-type="{{ get_class($model) }}" data-model-id="{{ $model->id }}">
    <h3 class="comments-title">{{ trans('comments::comments.comments') }} ({{ $model->comments->count() }})</h3>
    
    @include('comments::partials._form', ['model' => $model, 'parentId' => null])
    
    <div class="comments-list">
        @forelse($model->comments as $comment)
            @include('comments::partials._comment', ['comment' => $comment])
        @empty
            <p class="no-comments">{{ trans('comments::comments.no_comments') }}</p>
        @endforelse
    </div>
</div>
