<?php
/**
 * BuzzHub User Reviews Class
 * Handles user-submitted reviews
 */

if (!defined('ABSPATH')) {
    exit;
}

class BuzzHub_User_Reviews {
    
    public function __construct() {
        add_action('init', array($this, 'handle_review_submission'));
        add_action('wp', array($this, 'display_review_form'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_review_scripts'));
        
        // AJAX handlers for logged-in users
        add_action('wp_ajax_buzzhub_submit_review', array($this, 'ajax_submit_review'));
        
        // Handle file uploads
        add_action('wp_ajax_buzzhub_upload_review_photo', array($this, 'ajax_upload_review_photo'));
    }
    
    public function enqueue_review_scripts() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only GET parameter check
        if (isset($_GET['buzzhub_action']) && $_GET['buzzhub_action'] === 'submit_review') {
            wp_enqueue_style('buzzhub-review-form', BUZZHUB_PLUGIN_URL . 'assets/review-form.css', array(), BUZZHUB_VERSION);
            wp_enqueue_script('buzzhub-review-form', BUZZHUB_PLUGIN_URL . 'assets/review-form.js', array('jquery'), BUZZHUB_VERSION, true);
            
            wp_localize_script('buzzhub-review-form', 'buzzhub_review_ajax', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('buzzhub_review_nonce'),
                'max_file_size' => wp_max_upload_size(),
                'allowed_types' => array('image/jpeg', 'image/jpg', 'image/png', 'image/gif')
            ));
        }
    }
    
    public function display_review_form() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only GET parameter check
        if (isset($_GET['buzzhub_action']) && $_GET['buzzhub_action'] === 'submit_review') {
            if (!is_user_logged_in()) {
                wp_redirect(wp_login_url(get_permalink()));
                exit;
            }
            
            add_filter('the_content', array($this, 'inject_review_form'));
        }
    }
    
    public function inject_review_form($content) {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only GET parameter check
        if (isset($_GET['buzzhub_action']) && $_GET['buzzhub_action'] === 'submit_review') {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only GET parameter for pre-filling form
            $location_id = isset($_GET['location_id']) ? intval($_GET['location_id']) : 0;
            $current_user = wp_get_current_user();
            
            // Get locations for dropdown
            $locations = BuzzHub_Database::get_locations();
            
            ob_start();
            ?>
            <div class="buzzhub-review-form-container">
                <h2><?php esc_html_e('Submit Your Review', 'buzzhub'); ?></h2>
                
                <?php 
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only GET parameter check
                if (isset($_GET['submitted']) && $_GET['submitted'] === 'success'): 
                    $display_settings = get_option('buzzhub_display_settings', array());
                    $redirect_url = isset($display_settings['redirect_after_review']) ? $display_settings['redirect_after_review'] : home_url();
                ?>
                    <div class="buzzhub-success-message">
                        <p><?php esc_html_e('Thank you! Your review has been submitted and is pending approval.', 'buzzhub'); ?></p>
                        <div class="buzzhub-buttons">
                            <a href="<?php echo esc_url(remove_query_arg(array('buzzhub_action', 'location_id', 'submitted'))); ?>" class="buzzhub-back-btn">
                                <?php esc_html_e('Back to Reviews', 'buzzhub'); ?>
                            </a>
                            <a href="<?php echo esc_url($redirect_url); ?>" class="buzzhub-back-btn">
                                <?php esc_html_e('Continue Browsing', 'buzzhub'); ?>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <form id="buzzhub-review-form" class="buzzhub-review-form" enctype="multipart/form-data">
                        <?php wp_nonce_field('buzzhub_review_nonce', 'buzzhub_review_nonce'); ?>
                        
                        <div class="buzzhub-form-group">
                            <label for="reviewer_name"><?php esc_html_e('Your Name', 'buzzhub'); ?> <span class="required">*</span></label>
                            <input type="text" id="reviewer_name" name="reviewer_name" value="<?php echo esc_attr($current_user->display_name); ?>" required />
                        </div>
                        
                        <div class="buzzhub-form-group">
                            <label for="reviewer_email"><?php esc_html_e('Your Email', 'buzzhub'); ?> <span class="required">*</span></label>
                            <input type="email" id="reviewer_email" name="reviewer_email" value="<?php echo esc_attr($current_user->user_email); ?>" required />
                        </div>
                        
                        <?php if (!empty($locations)): ?>
                        <div class="buzzhub-form-group">
                            <label for="location_id"><?php esc_html_e('Location', 'buzzhub'); ?> <span class="required">*</span></label>
                            <select id="location_id" name="location_id" required>
                                <option value=""><?php esc_html_e('Select a location', 'buzzhub'); ?></option>
                                <?php foreach ($locations as $location): ?>
                                    <option value="<?php echo esc_attr($location->id); ?>" <?php selected($location_id, $location->id); ?>>
                                        <?php echo esc_html($location->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php else: ?>
                            <input type="hidden" name="location_id" value="1" />
                        <?php endif; ?>
                        
                        <div class="buzzhub-form-group">
                            <label for="rating"><?php esc_html_e('Rating', 'buzzhub'); ?> <span class="required">*</span></label>
                            <div class="buzzhub-star-rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="star<?php echo esc_attr($i); ?>" name="rating" value="<?php echo esc_attr($i); ?>" required />
                                    <label for="star<?php echo esc_attr($i); ?>" class="buzzhub-star-label">★</label>
                                <?php endfor; ?>
                            </div>
                            <div class="buzzhub-rating-text"></div>
                        </div>
                        
                        <div class="buzzhub-form-group">
                            <label for="review_text"><?php esc_html_e('Your Review', 'buzzhub'); ?> <span class="required">*</span></label>
                            <textarea id="review_text" name="review_text" rows="6" placeholder="<?php esc_attr_e('Share your experience...', 'buzzhub'); ?>" required></textarea>
                        </div>
                        
                        <div class="buzzhub-form-group">
                            <label for="reviewer_photo"><?php esc_html_e('Your Photo (Optional)', 'buzzhub'); ?></label>
                            <div class="buzzhub-photo-upload">
                                <input type="file" id="reviewer_photo" name="reviewer_photo" accept="image/*" />
                                <div class="buzzhub-photo-preview" style="display: none;">
                                    <img src="" alt="Preview" />
                                    <button type="button" class="buzzhub-remove-photo"><?php esc_html_e('Remove', 'buzzhub'); ?></button>
                                </div>
                                <div class="buzzhub-upload-instructions">
                                    <p><?php esc_html_e('Upload a profile photo (JPG, PNG, GIF - Max 2MB)', 'buzzhub'); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="buzzhub-form-actions">
                            <button type="submit" class="buzzhub-submit-btn"><?php esc_html_e('Submit Review', 'buzzhub'); ?></button>
                            <a href="<?php echo esc_url(remove_query_arg(array('buzzhub_action', 'location_id'))); ?>" class="buzzhub-cancel-btn">
                                <?php esc_html_e('Cancel', 'buzzhub'); ?>
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <?php
            $form_content = ob_get_clean();
            return $form_content;
        }
        
        return $content;
    }
    
    public function handle_review_submission() {
        // Handle non-AJAX form submission as fallback
        if (isset($_POST['buzzhub_review_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['buzzhub_review_nonce'])), 'buzzhub_review_nonce')) {
            if (!is_user_logged_in()) {
                wp_die(esc_html__('You must be logged in to submit a review.', 'buzzhub'));
            }
            
            $this->process_review_submission();
        }
    }
    
    public function ajax_submit_review() {
        check_ajax_referer('buzzhub_review_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_die(json_encode(array('success' => false, 'message' => __('You must be logged in to submit a review.', 'buzzhub'))));
        }
        
        $result = $this->process_review_submission();
        wp_die(json_encode($result));
    }
    
    private function process_review_submission() {
        $current_user = wp_get_current_user();
        
        // Sanitize and validate input
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in calling methods
        $reviewer_name = isset($_POST['reviewer_name']) ? sanitize_text_field(wp_unslash($_POST['reviewer_name'])) : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in calling methods
        $reviewer_email = isset($_POST['reviewer_email']) ? sanitize_email(wp_unslash($_POST['reviewer_email'])) : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in calling methods
        $location_id = isset($_POST['location_id']) ? intval($_POST['location_id']) : 0;
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in calling methods
        $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : 0;
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in calling methods
        $review_text = isset($_POST['review_text']) ? sanitize_textarea_field(wp_unslash($_POST['review_text'])) : '';
        
        // Validation
        $errors = array();
        
        if (empty($reviewer_name)) {
            $errors[] = __('Name is required.', 'buzzhub');
        }
        
        if (empty($reviewer_email) || !is_email($reviewer_email)) {
            $errors[] = __('Valid email is required.', 'buzzhub');
        }
        
        if ($location_id <= 0) {
            $errors[] = __('Please select a location.', 'buzzhub');
        }
        
        if ($rating < 1 || $rating > 5) {
            $errors[] = __('Please select a rating.', 'buzzhub');
        }
        
        if (empty($review_text)) {
            $errors[] = __('Review text is required.', 'buzzhub');
        }
        
        if (!empty($errors)) {
            return array('success' => false, 'message' => implode(' ', $errors));
        }
        
        // Check for duplicate reviews from same user
        $existing_review = $this->check_duplicate_review($current_user->ID, $location_id);
        if ($existing_review) {
            return array('success' => false, 'message' => __('You have already submitted a review for this location.', 'buzzhub'));
        }
        
        // Handle photo upload if provided
        $photo_url = '';
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing -- File upload array validated before use, nonce verified in calling methods
        if (isset($_FILES['reviewer_photo']) && !empty($_FILES['reviewer_photo']['name'])) {
            // Validate file upload
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing -- File upload array validated before use, nonce verified in calling methods
            if (!isset($_FILES['reviewer_photo']['error']) || is_array($_FILES['reviewer_photo']['error'])) {
                return array('success' => false, 'message' => __('Invalid file upload.', 'buzzhub'));
            }
            
            // Check for upload errors
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing -- File upload validated by error check, nonce verified in calling methods
            if ($_FILES['reviewer_photo']['error'] !== UPLOAD_ERR_OK) {
                return array('success' => false, 'message' => __('File upload error.', 'buzzhub'));
            }
            
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing -- File upload passed to validation function, nonce verified in calling methods
            $upload_result = $this->handle_photo_upload($_FILES['reviewer_photo']);
            if (is_wp_error($upload_result)) {
                return array('success' => false, 'message' => $upload_result->get_error_message());
            }
            $photo_url = $upload_result;
        }
        
        // Prepare review data
        $review_data = array(
            'location_id' => $location_id,
            'reviewer_name' => $reviewer_name,
            'reviewer_email' => $reviewer_email,
            'reviewer_photo_url' => $photo_url,
            'rating' => $rating,
            'review_text' => $review_text,
            'review_date' => current_time('Y-m-d'),
            'platform' => 'user_submitted',
            'is_featured' => 0,
            'is_approved' => 0 // User reviews require approval
        );
        
        // Add user ID for tracking
        $review_data['user_id'] = $current_user->ID;
        
        $result = BuzzHub_Database::create_review($review_data);
        
        if ($result) {
            // Send notification to admin
            $this->send_admin_notification($review_data);
            
            if (wp_doing_ajax()) {
                return array('success' => true, 'message' => __('Review submitted successfully! It will be reviewed before being published.', 'buzzhub'));
            } else {
                // Redirect with success message
                $redirect_url = add_query_arg(array(
                    'buzzhub_action' => 'submit_review',
                    'submitted' => 'success'
                ), get_permalink());
                wp_redirect($redirect_url);
                exit;
            }
        } else {
            return array('success' => false, 'message' => __('Failed to submit review. Please try again.', 'buzzhub'));
        }
    }
    
    private function check_duplicate_review($user_id, $location_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'buzzhub_reviews';
        
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table query for duplicate check
        return $wpdb->get_var($wpdb->prepare(
            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name is safely constructed
            "SELECT id FROM $table WHERE user_id = %d AND location_id = %d",
            $user_id,
            $location_id
        ));
    }
    
    private function handle_photo_upload($file) {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        
        // Additional file validation
        $max_file_size = 5 * 1024 * 1024; // 5MB
        if (isset($file['size']) && $file['size'] > $max_file_size) {
            return new WP_Error('file_too_large', __('File size must be less than 5MB.', 'buzzhub'));
        }
        
        // Validate file name
        if (isset($file['name'])) {
            $file['name'] = sanitize_file_name($file['name']);
        }
        
        $uploadedfile = $file;
        $upload_overrides = array(
            'test_form' => false,
            'mimes' => array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                'gif' => 'image/gif',
                'png' => 'image/png',
            )
        );
        
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
        
        if ($movefile && !isset($movefile['error'])) {
            return esc_url_raw($movefile['url']);
        } else {
            $error_message = isset($movefile['error']) ? $movefile['error'] : __('Unknown upload error.', 'buzzhub');
            return new WP_Error('upload_error', sanitize_text_field($error_message));
        }
    }
    
    private function send_admin_notification($review_data) {
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        
        /* translators: %s: site name */
        $subject = sprintf(__('[%s] New Review Submission', 'buzzhub'), $site_name);
        
        /* translators: 1: reviewer name, 2: reviewer email, 3: rating number, 4: review text */
        $message = sprintf(
            __("A new review has been submitted and is pending approval.\n\nReviewer: %1\$s\nEmail: %2\$s\nRating: %3\$s/5\nReview: %4\$s\n\nPlease log in to your admin panel to review and approve this submission.", 'buzzhub'),
            $review_data['reviewer_name'],
            $review_data['reviewer_email'],
            $review_data['rating'],
            $review_data['review_text']
        );
        
        wp_mail($admin_email, $subject, $message);
    }
    
    public function ajax_upload_review_photo() {
        check_ajax_referer('buzzhub_review_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_die(json_encode(array('success' => false, 'message' => __('You must be logged in to upload photos.', 'buzzhub'))));
        }
        
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- File upload array validated before use
        // Validate file upload
        if (!isset($_FILES['photo']) || empty($_FILES['photo']['name'])) {
            wp_die(json_encode(array('success' => false, 'message' => __('No file uploaded.', 'buzzhub'))));
        }
        
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- File upload array validated before use
        // Check for upload errors
        if (!isset($_FILES['photo']['error']) || is_array($_FILES['photo']['error'])) {
            wp_die(json_encode(array('success' => false, 'message' => __('Invalid file upload.', 'buzzhub'))));
        }
        
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- File upload validated by error check
        if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            wp_die(json_encode(array('success' => false, 'message' => __('File upload error.', 'buzzhub'))));
        }
        
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- File upload passed to validation function
        $result = $this->handle_photo_upload($_FILES['photo']);
        
        if (is_wp_error($result)) {
            wp_die(json_encode(array('success' => false, 'message' => $result->get_error_message())));
        } else {
            wp_die(json_encode(array('success' => true, 'url' => $result)));
        }
    }
}
