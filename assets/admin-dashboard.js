/* Admin Dashboard JavaScript */
jQuery(document).ready(function($) {
    // Bulk text replacement
    $('#bulk-replace-form').on('submit', function(e) {
        e.preventDefault();
        
        const searchText = $('#search-text').val().trim();
        const replaceText = $('#replace-text').val().trim();
        const locationId = $('#replace-location').val();
        
        if (!searchText || !replaceText) {
            alert(buzzhub_dashboard.empty_fields);
            return;
        }
        
        if (!confirm(buzzhub_dashboard.confirm_replace.replace('%s', searchText).replace('%s', replaceText))) {
            return;
        }
        
        $.post(ajaxurl, {
            action: 'buzzhub_bulk_replace_text',
            search_text: searchText,
            replace_text: replaceText,
            location_id: locationId,
            nonce: buzzhub_dashboard.nonce
        })
        .done(function(response) {
            if (response.success) {
                alert(buzzhub_dashboard.replace_success + response.data.updated_count);
                location.reload();
            } else {
                alert(buzzhub_dashboard.replace_error + (response.data || buzzhub_dashboard.error_unknown));
            }
        })
        .fail(function() {
            alert(buzzhub_dashboard.error_network);
        });
    });
    
    // Approve review functionality for dashboard
    $('.approve-review-btn').on('click', function() {
        const reviewId = $(this).data('review-id');
        const button = $(this);
        
        button.prop('disabled', true).text(buzzhub_dashboard.approving_text);
        
        $.post(ajaxurl, {
            action: 'buzzhub_approve_review',
            review_id: reviewId,
            nonce: buzzhub_dashboard.nonce
        })
        .done(function(response) {
            if (response.success) {
                alert(response.data);
                location.reload();
            } else {
                alert(buzzhub_dashboard.error_prefix + (response.data || buzzhub_dashboard.error_unknown));
                button.prop('disabled', false).text(buzzhub_dashboard.approve_text);
            }
        })
        .fail(function() {
            alert(buzzhub_dashboard.error_network);
            button.prop('disabled', false).text(buzzhub_dashboard.approve_text);
        });
    });
    
    // Reject review functionality for dashboard
    $('.reject-review-btn').on('click', function() {
        if (!confirm(buzzhub_dashboard.confirm_reject)) {
            return;
        }
        
        const reviewId = $(this).data('review-id');
        const button = $(this);
        
        button.prop('disabled', true).text(buzzhub_dashboard.rejecting_text);
        
        $.post(ajaxurl, {
            action: 'buzzhub_delete_review',
            review_id: reviewId,
            nonce: buzzhub_dashboard.nonce
        })
        .done(function(response) {
            if (response.success) {
                alert(buzzhub_dashboard.rejected_text);
                location.reload();
            } else {
                alert(buzzhub_dashboard.error_prefix + (response.data || buzzhub_dashboard.error_unknown));
                button.prop('disabled', false).text(buzzhub_dashboard.reject_text);
            }
        })
        .fail(function() {
            alert(buzzhub_dashboard.error_network);
            button.prop('disabled', false).text(buzzhub_dashboard.reject_text);
        });
    });
    
    // Delete review functionality for dashboard
    $('.delete-review-btn').on('click', function() {
        if (!confirm(buzzhub_dashboard.confirm_delete)) {
            return;
        }
        
        const reviewId = $(this).data('review-id');
        const button = $(this);
        
        button.prop('disabled', true).text(buzzhub_dashboard.deleting_text);
        
        $.post(ajaxurl, {
            action: 'buzzhub_delete_review',
            review_id: reviewId,
            nonce: buzzhub_dashboard.nonce
        })
        .done(function(response) {
            if (response.success) {
                alert(response.data);
                location.reload();
            } else {
                alert(buzzhub_dashboard.error_prefix + (response.data || buzzhub_dashboard.error_unknown));
                button.prop('disabled', false).text(buzzhub_dashboard.delete_text);
            }
        })
        .fail(function() {
            alert(buzzhub_dashboard.error_network);
            button.prop('disabled', false).text(buzzhub_dashboard.delete_text);
        });
    });
});

