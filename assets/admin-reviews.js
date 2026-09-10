/* Admin Reviews JavaScript */
jQuery(document).ready(function($) {
    $('.delete-review-btn').on('click', function() {
        if (!confirm(buzzhub_reviews.confirm_delete)) {
            return;
        }
        
        const reviewId = $(this).data('review-id');
        
        $.post(buzzhub_ajax.ajaxurl, {
            action: 'buzzhub_delete_review',
            review_id: reviewId,
            nonce: buzzhub_ajax.nonce
        })
        .done(function(response) {
            if (response.success) {
                alert(response.data);
                location.reload();
            } else {
                alert(buzzhub_reviews.error_prefix + (response.data || buzzhub_reviews.error_unknown));
            }
        })
        .fail(function() {
            alert(buzzhub_reviews.error_network);
        });
    });
    
    // Approve review functionality
    $('.approve-review-btn').on('click', function() {
        const reviewId = $(this).data('review-id');
        const button = $(this);
        
        button.prop('disabled', true).text(buzzhub_reviews.approving_text);
        
        $.post(buzzhub_ajax.ajaxurl, {
            action: 'buzzhub_approve_review',
            review_id: reviewId,
            nonce: buzzhub_ajax.nonce
        })
        .done(function(response) {
            if (response.success) {
                alert(response.data);
                location.reload();
            } else {
                alert(buzzhub_reviews.error_prefix + (response.data || buzzhub_reviews.error_unknown));
                button.prop('disabled', false).text(buzzhub_reviews.approve_text);
            }
        })
        .fail(function() {
            alert(buzzhub_reviews.error_network);
            button.prop('disabled', false).text(buzzhub_reviews.approve_text);
        });
    });
    
    // Reject review functionality
    $('.reject-review-btn').on('click', function() {
        if (!confirm(buzzhub_reviews.confirm_reject)) {
            return;
        }
        
        const reviewId = $(this).data('review-id');
        const button = $(this);
        
        button.prop('disabled', true).text(buzzhub_reviews.rejecting_text);
        
        $.post(buzzhub_ajax.ajaxurl, {
            action: 'buzzhub_delete_review',
            review_id: reviewId,
            nonce: buzzhub_ajax.nonce
        })
        .done(function(response) {
            if (response.success) {
                alert(buzzhub_reviews.rejected_text);
                location.reload();
            } else {
                alert(buzzhub_reviews.error_prefix + (response.data || buzzhub_reviews.error_unknown));
                button.prop('disabled', false).text(buzzhub_reviews.reject_text);
            }
        })
        .fail(function() {
            alert(buzzhub_reviews.error_network);
            button.prop('disabled', false).text(buzzhub_reviews.reject_text);
        });
    });
});

