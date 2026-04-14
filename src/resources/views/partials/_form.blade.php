@props(['model', 'parentId' => null])

<form action="{{ route('comments.store') }}" method="POST" class="comment-form-inline-main" data-parent-id="{{ $parentId }}">
    @csrf
    
    <input type="hidden" name="commentable_type" value="{{ get_class($model) }}">
    <input type="hidden" name="commentable_id" value="{{ $model->id }}">
    <input type="hidden" name="parent_id" value="{{ $parentId }}">
    
    <div class="comment-form-content">
        <textarea 
            name="content" 
            class="comment-textarea" 
            rows="1"
            placeholder="{{ trans('comments::comments.placeholder') }}"
            required
            maxlength="{{ config('comments.max_length', 1000) }}"
        >{{ old('content') }}</textarea>
        
        @auth
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
        @else
            @if(config('comments.guest_name_required', true))
                <input 
                    type="text" 
                    name="guest_name" 
                    class="guest-name-input" 
                    placeholder="{{ trans('comments::comments.guest_name') }}"
                    required
                    maxlength="{{ config('comments.guest_name_max_length', 100) }}"
                >
            @else
                <input 
                    type="text" 
                    name="guest_name" 
                    class="guest-name-input" 
                    placeholder="{{ trans('comments::comments.guest_name') }}"
                    maxlength="{{ config('comments.guest_name_max_length', 100) }}"
                >
            @endif
        @endauth
        
        <div class="form-error" id="form-error-{{ $parentId ?? 'main' }}"></div>
    </div>
    
    <button type="submit" class="btn-submit">
        {{ trans('comments::comments.post_comment') }}
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const parentId = {{ json_encode($parentId ?? 'main') }};
    const form = document.querySelector('.comment-form-inline-main[data-parent-id="' + parentId + '"]');
    if (!form) return;
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const errorDiv = document.getElementById('form-error-' + parentId);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        submitBtn.disabled = true;
        submitBtn.textContent = '...';
        errorDiv.textContent = '';
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                location.reload();
            } else {
                if (data.errors) {
                    const firstError = Object.values(data.errors)[0];
                    errorDiv.textContent = Array.isArray(firstError) ? firstError[0] : firstError;
                } else {
                    errorDiv.textContent = data.message || 'Error posting comment';
                }
            }
        } catch (error) {
            console.error('Error:', error);
            errorDiv.textContent = 'Something went wrong. Please try again.';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = {!! json_encode(trans('comments::comments.post_comment')) !!};
        }
    });
});
</script>