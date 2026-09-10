/**
 * Review Form JavaScript
 */
jQuery(document).ready(function($) {
    
    // Star rating functionality
    $('.buzzhub-star-rating input').on('change', function() {
        const rating = $(this).val();
        const texts = {
            '1': 'Poor',
            '2': 'Fair', 
            '3': 'Good',
            '4': 'Very Good',
            '5': 'Excellent'
        };
        $('.buzzhub-rating-text').text(texts[rating] || '');
    });
    
    // Photo upload preview
    $('#reviewer_photo').on('change', function() {
        const file = this.files[0];
        if (file) {
            if (file.size > buzzhub_review_ajax.max_file_size) {
                alert('File size too large. Maximum size is ' + Math.round(buzzhub_review_ajax.max_file_size / 1024 / 1024) + 'MB');
                $(this).val('');
                return;
            }
            
            if (buzzhub_review_ajax.allowed_types.indexOf(file.type) === -1) {
                alert('Invalid file type. Please upload a JPG, PNG, or GIF image.');
                $(this).val('');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.buzzhub-photo-preview img').attr('src', e.target.result);
                $('.buzzhub-photo-preview').show();
                $('.buzzhub-upload-instructions').hide();
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Remove photo
    $('.buzzhub-remove-photo').on('click', function() {
        $('#reviewer_photo').val('');
        $('.buzzhub-photo-preview').hide();
        $('.buzzhub-upload-instructions').show();
    });
    
    // Form submission
    $('#buzzhub-review-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('.buzzhub-submit-btn');
        const formData = new FormData(this);
        
        // Add AJAX data
        formData.append('action', 'buzzhub_submit_review');
        formData.append('nonce', buzzhub_review_ajax.nonce);
        
        // Disable form and show loading
        form.addClass('buzzhub-form-loading');
        submitBtn.prop('disabled', true);
        
        // Clear previous errors
        $('.buzzhub-error-message').remove();
        
        $.ajax({
            url: buzzhub_review_ajax.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Redirect to success page instead of showing AJAX message
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('submitted', 'success');
                    window.location.href = currentUrl.toString();
                    
                } else {
                    // Show error message
                    const errorHtml = '<div class="buzzhub-error-message">' + response.message + '</div>';
                    form.before(errorHtml);
                    
                    // Scroll to error message
                    $('html, body').animate({
                        scrollTop: $('.buzzhub-error-message').offset().top - 50
                    }, 500);
                }
            },
            error: function(xhr, status, error) {
                const errorHtml = '<div class="buzzhub-error-message">An error occurred. Please try again.</div>';
                form.before(errorHtml);
                
                console.error('Review submission error:', error);
            },
            complete: function() {
                // Re-enable form
                form.removeClass('buzzhub-form-loading');
                submitBtn.prop('disabled', false);
            }
        });
    });
    
    // Form validation
    function validateForm() {
        let isValid = true;
        const form = $('#buzzhub-review-form');
        
        // Clear previous validation
        $('.buzzhub-form-group').removeClass('has-error');
        
        // Required fields
        const requiredFields = ['reviewer_name', 'reviewer_email', 'location_id', 'rating', 'review_text'];
        
        requiredFields.forEach(function(fieldName) {
            const field = $('[name="' + fieldName + '"]');
            const value = field.val();
            
            if (!value || (fieldName === 'rating' && !$('input[name="rating"]:checked').length)) {
                field.closest('.buzzhub-form-group').addClass('has-error');
                isValid = false;
            }
        });
        
        // Email validation
        const email = $('[name="reviewer_email"]').val();
        if (email && !isValidEmail(email)) {
            $('[name="reviewer_email"]').closest('.buzzhub-form-group').addClass('has-error');
            isValid = false;
        }
        
        return isValid;
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // Real-time validation
    $('#buzzhub-review-form input, #buzzhub-review-form select, #buzzhub-review-form textarea').on('blur change', function() {
        const field = $(this);
        const group = field.closest('.buzzhub-form-group');
        
        if (field.val()) {
            group.removeClass('has-error');
        }
        
        // Special validation for email
        if (field.attr('type') === 'email') {
            if (field.val() && !isValidEmail(field.val())) {
                group.addClass('has-error');
            } else {
                group.removeClass('has-error');
            }
        }
    });
    
    // Character counter for review text
    const reviewTextarea = $('#review_text');
    if (reviewTextarea.length) {
        const maxLength = 1000; // Set a reasonable limit
        const counterHtml = '<div class="buzzhub-char-counter"><span class="buzzhub-char-count">0</span>/' + maxLength + ' characters</div>';
        reviewTextarea.after(counterHtml);
        
        reviewTextarea.on('input', function() {
            const length = $(this).val().length;
            $('.buzzhub-char-count').text(length);
            
            if (length > maxLength) {
                $('.buzzhub-char-counter').addClass('over-limit');
            } else {
                $('.buzzhub-char-counter').removeClass('over-limit');
            }
        });
    }
    
    // Auto-save draft functionality (optional enhancement)
    let draftTimer;
    $('#buzzhub-review-form input, #buzzhub-review-form select, #buzzhub-review-form textarea').on('input change', function() {
        clearTimeout(draftTimer);
        draftTimer = setTimeout(saveDraft, 2000); // Save after 2 seconds of inactivity
    });
    
    function saveDraft() {
        const formData = $('#buzzhub-review-form').serialize();
        localStorage.setItem('buzzhub_review_draft', formData);
    }
    
    function loadDraft() {
        const draft = localStorage.getItem('buzzhub_review_draft');
        if (draft) {
            const draftData = new URLSearchParams(draft);
            draftData.forEach(function(value, key) {
                const field = $('[name="' + key + '"]');
                if (field.attr('type') === 'radio') {
                    $('[name="' + key + '"][value="' + value + '"]').prop('checked', true).trigger('change');
                } else {
                    field.val(value);
                }
            });
        }
    }
    
    // Load draft on page load
    loadDraft();
    
    // Clear draft on successful submission
    $(document).on('buzzhub-review-submitted', function() {
        localStorage.removeItem('buzzhub_review_draft');
    });
});
