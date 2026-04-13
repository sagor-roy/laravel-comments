document.addEventListener('DOMContentLoaded', function() {
    const replyButtons = document.querySelectorAll('.btn-reply');
    replyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.getElementById('reply-form-' + commentId);
            
            if (replyForm) {
                replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
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
                        commentElement.remove();
                    }
                    location.reload();
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
