/* Admin Add/Edit Review JavaScript */
jQuery(document).ready(function($) {
    // Star rating functionality
    $('.buzzhub-star').on('click', function() {
        const rating = $(this).data('rating');
        $('#rating').val(rating);
        
        $('.buzzhub-star').removeClass('active');
        for (let i = 1; i <= rating; i++) {
            $('.buzzhub-star[data-rating="' + i + '"]').addClass('active');
        }
    });
    
    // Star rating hover effect
    $('.buzzhub-star').on('mouseenter', function() {
        const rating = $(this).data('rating');
        
        $('.buzzhub-star').removeClass('hover');
        for (let i = 1; i <= rating; i++) {
            $('.buzzhub-star[data-rating="' + i + '"]').addClass('hover');
        }
    });
    
    $('#star-rating').on('mouseleave', function() {
        $('.buzzhub-star').removeClass('hover');
    });
    
    // Media uploader for photo
    $('#upload-photo-btn').on('click', function(e) {
        e.preventDefault();
        
        const mediaUploader = wp.media({
            title: buzzhub_add_review.media_title,
            button: {
                text: buzzhub_add_review.media_button
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#reviewer-photo').val(attachment.url);
        });
        
        mediaUploader.open();
    });
    
    // Form submission
    $('#review-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'buzzhub_save_review');
        formData.append('nonce', buzzhub_ajax.nonce);
        
        const $submitBtn = $(this).find('button[type="submit"]');
        const originalText = $submitBtn.text();
        $submitBtn.text(buzzhub_add_review.saving_text).prop('disabled', true);
        
        $.ajax({
            url: buzzhub_ajax.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    if (!$('#review-id').val()) {
                        // Redirect to edit page for new reviews
                        window.location.href = buzzhub_add_review.edit_url + response.data.review_id;
                    }
                } else {
                    alert(buzzhub_add_review.error_prefix + (response.data || buzzhub_add_review.error_unknown));
                }
            },
            error: function() {
                alert(buzzhub_add_review.error_network);
            },
            complete: function() {
                $submitBtn.text(originalText).prop('disabled', false);
            }
        });
    });
    
    // Delete review
    $('#delete-review-btn').on('click', function() {
        if (!confirm(buzzhub_add_review.confirm_delete)) {
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
                window.location.href = buzzhub_add_review.reviews_url;
            } else {
                alert(buzzhub_add_review.error_prefix + (response.data || buzzhub_add_review.error_unknown));
            }
        })
        .fail(function() {
            alert(buzzhub_add_review.error_network);
        });
    });
});

