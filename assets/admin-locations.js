/* Admin Locations JavaScript */
jQuery(document).ready(function($) {
    // Open modal for new location
    $('#add-location-btn, #add-first-location-btn').on('click', function(e) {
        e.preventDefault();
        openLocationModal();
    });
    
    // Open modal for editing location
    $('.edit-location-btn').on('click', function() {
        const data = $(this).data();
        openLocationModal(data);
    });
    
    // Close modal
    $('.buzzhub-modal-close').on('click', function() {
        closeLocationModal();
    });
    
    // Close modal on background click
    $('#location-modal').on('click', function(e) {
        if (e.target === this) {
            closeLocationModal();
        }
    });
    
    function openLocationModal(data = null) {
        if (data) {
            // Edit mode
            $('#modal-title').text(buzzhub_locations.edit_title);
            $('#location-id').val(data.locationId);
            $('#location-name').val(data.name);
            $('#location-address').val(data.address);
            $('#location-phone').val(data.phone);
            $('#location-website').val(data.website);
            $('#location-description').val(data.description);
            $('#save-btn-text').text(buzzhub_locations.update_text);
        } else {
            // Add mode
            $('#modal-title').text(buzzhub_locations.add_title);
            $('#location-form')[0].reset();
            $('#location-id').val('');
            $('#save-btn-text').text(buzzhub_locations.save_text);
        }
        
        $('#location-modal').show();
    }
    
    function closeLocationModal() {
        $('#location-modal').hide();
        $('#location-form')[0].reset();
    }
    
    // Save location
    $('#location-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'buzzhub_save_location');
        formData.append('nonce', buzzhub_ajax.nonce);
        
        const $submitBtn = $(this).find('button[type="submit"]');
        const originalText = $('#save-btn-text').text();
        $('#save-btn-text').text(buzzhub_locations.saving_text);
        $submitBtn.prop('disabled', true);
        
        $.ajax({
            url: buzzhub_ajax.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert(response.data);
                    location.reload();
                } else {
                    alert(buzzhub_locations.error_prefix + (response.data || buzzhub_locations.error_unknown));
                }
            },
            error: function() {
                alert(buzzhub_locations.error_network);
            },
            complete: function() {
                $('#save-btn-text').text(originalText);
                $submitBtn.prop('disabled', false);
            }
        });
    });
    
    // Delete location
    $('.delete-location-btn').on('click', function() {
        if (!confirm(buzzhub_locations.confirm_delete)) {
            return;
        }
        
        const locationId = $(this).data('location-id');
        
        $.post(buzzhub_ajax.ajaxurl, {
            action: 'buzzhub_delete_location',
            location_id: locationId,
            nonce: buzzhub_ajax.nonce
        })
        .done(function(response) {
            if (response.success) {
                alert(response.data);
                location.reload();
            } else {
                alert(buzzhub_locations.error_prefix + (response.data || buzzhub_locations.error_unknown));
            }
        })
        .fail(function() {
            alert(buzzhub_locations.error_network);
        });
    });
});

