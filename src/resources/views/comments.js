document.addEventListener('DOMContentLoaded', function() {
    const replyButtons = document.querySelectorAll('.btn-reply');
    replyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.getElementById('reply-form-' + commentId);
            
            if (replyForm) {
                const isHidden = replyForm.style.display === 'none' || !replyForm.style.display;
                replyForm.style.display = isHidden ? 'block' : 'none';
                
                if (isHidden) {
                    const textarea = replyForm.querySelector('textarea');
                    if (textarea) textarea.focus();
                }
            }
        });
    });

    const deleteButtons = document.querySelectorAll('.delete-comment');
    deleteButtons.forEach(button => {
        button.addEventListener('click', async function() {
            if (!confirm('Are you sure you want to delete this comment?')) {
                return;
            }
            
            const commentId = this.getAttribute('data-comment-id');
            const commentElement = document.getElementById('comment-' + commentId);
            
            try {
                const response = await fetch('/comments/' + commentId, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                                        document.querySelector('input[name="_token"]')?.value || ''
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    if (commentElement) {
                        commentElement.style.opacity = '0';
                        commentElement.style.transform = 'translateX(-20px)';
                        commentElement.style.transition = 'all 0.3s ease';
                        setTimeout(() => {
                            commentElement.remove();
                            location.reload();
                        }, 300);
                    } else {
                        location.reload();
                    }
                } else {
                    alert(data.message || 'Error deleting comment');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
            }
        });
    });
});

function hideReplyForm(commentId) {
    const replyForm = document.getElementById('reply-form-' + commentId);
    if (replyForm) {
        replyForm.style.display = 'none';
    }
}