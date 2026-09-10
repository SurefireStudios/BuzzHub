<?php
/**
 * BuzzHub Add Review Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$is_edit = !empty($review);
$page_title = $is_edit ? esc_html__('Edit Review', 'buzzhub') : esc_html__('Add New Review', 'buzzhub');
?>

<div class="wrap">
    <h1><?php echo esc_html($page_title); ?></h1>
    
    <?php if (empty($locations)): ?>
        <div class="notice notice-warning">
            <p>
                <?php esc_html_e('You need to add at least one location before you can add reviews.', 'buzzhub'); ?>
                <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-locations')); ?>" class="button">
                    <?php esc_html_e('Add Location', 'buzzhub'); ?>
                </a>
            </p>
        </div>
    <?php else: ?>
        
        <form id="review-form" method="post" action="">
            <?php wp_nonce_field('buzzhub_save_review', 'buzzhub_review_nonce'); ?>
            <input type="hidden" id="review-id" name="review_id" value="<?php echo $is_edit ? esc_attr($review->id) : ''; ?>" />
            
            <div class="buzzhub-form-container">
                <!-- Left Column - Main Fields -->
                <div class="buzzhub-form-main">
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2><?php esc_html_e('Review Details', 'buzzhub'); ?></h2>
                        </div>
                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="reviewer-name"><?php esc_html_e('Reviewer Name', 'buzzhub'); ?> *</label>
                                    </th>
                                    <td>
                                        <input type="text" id="reviewer-name" name="reviewer_name" class="regular-text" 
                                               value="<?php echo $is_edit ? esc_attr($review->reviewer_name) : ''; ?>" required />
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="reviewer-email"><?php esc_html_e('Reviewer Email', 'buzzhub'); ?></label>
                                    </th>
                                    <td>
                                        <input type="email" id="reviewer-email" name="reviewer_email" class="regular-text" 
                                               value="<?php echo $is_edit ? esc_attr($review->reviewer_email) : ''; ?>" />
                                        <p class="description"><?php esc_html_e('Optional - for your records only, not displayed publicly.', 'buzzhub'); ?></p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="reviewer-photo"><?php esc_html_e('Reviewer Photo URL', 'buzzhub'); ?></label>
                                    </th>
                                    <td>
                                        <input type="url" id="reviewer-photo" name="reviewer_photo_url" class="regular-text" 
                                               value="<?php echo $is_edit ? esc_attr($review->reviewer_photo_url) : ''; ?>" />
                                        <button type="button" class="button" id="upload-photo-btn">
                                            <?php esc_html_e('Upload Photo', 'buzzhub'); ?>
                                        </button>
                                        <p class="description"><?php esc_html_e('Optional - provide a URL or upload a photo for the reviewer.', 'buzzhub'); ?></p>
                                        
                                        <?php if ($is_edit && !empty($review->reviewer_photo_url)): ?>
                                            <div class="buzzhub-photo-preview">
                                                <img src="<?php echo esc_url($review->reviewer_photo_url); ?>" 
                                                     alt="<?php echo esc_attr($review->reviewer_name); ?>" 
                                                     style="max-width: 50px; height: 50px; border-radius: 50%; object-fit: cover;" />
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="rating"><?php esc_html_e('Rating', 'buzzhub'); ?> *</label>
                                    </th>
                                    <td>
                                        <div class="buzzhub-star-rating" id="star-rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="buzzhub-star <?php echo ($is_edit && $i <= $review->rating) ? 'active' : ''; ?>" 
                                                      data-rating="<?php echo esc_attr($i); ?>">★</span>
                                            <?php endfor; ?>
                                        </div>
                                        <input type="hidden" id="rating" name="rating" 
                                               value="<?php echo $is_edit ? esc_attr($review->rating) : '5'; ?>" required />
                                        <p class="description"><?php esc_html_e('Click the stars to set the rating.', 'buzzhub'); ?></p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="review-text"><?php esc_html_e('Review Text', 'buzzhub'); ?> *</label>
                                    </th>
                                    <td>
                                        <textarea id="review-text" name="review_text" rows="8" cols="50" class="large-text" required><?php echo $is_edit ? esc_textarea($review->review_text) : ''; ?></textarea>
                                        <p class="description"><?php esc_html_e('The main review content. You can edit this to change business names or other details.', 'buzzhub'); ?></p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="review-date"><?php esc_html_e('Review Date', 'buzzhub'); ?> *</label>
                                    </th>
                                    <td>
                                        <input type="date" id="review-date" name="review_date" 
                                               value="<?php echo $is_edit ? esc_attr($review->review_date) : esc_attr(current_time('Y-m-d')); ?>" required />
                                        <p class="description"><?php esc_html_e('When was this review originally posted?', 'buzzhub'); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Settings -->
                <div class="buzzhub-form-sidebar">
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2><?php esc_html_e('Review Settings', 'buzzhub'); ?></h2>
                        </div>
                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="location-id"><?php esc_html_e('Location', 'buzzhub'); ?> *</label>
                                    </th>
                                    <td>
                                        <select id="location-id" name="location_id" required>
                                            <option value=""><?php esc_html_e('Select a location...', 'buzzhub'); ?></option>
                                            <?php foreach ($locations as $location): ?>
                                                <option value="<?php echo esc_attr($location->id); ?>" 
                                                        <?php echo ($is_edit && $review->location_id == $location->id) ? 'selected' : ''; ?>>
                                                    <?php echo esc_html($location->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="platform"><?php esc_html_e('Review Source Platform', 'buzzhub'); ?> *</label>
                                    </th>
                                    <td>
                                        <select id="platform" name="platform" style="width: 200px;">
                                            <option value="manual" <?php echo (!$is_edit || $review->platform === 'manual') ? 'selected' : ''; ?>>
                                                <?php esc_html_e('📝 Manual Entry', 'buzzhub'); ?>
                                            </option>
                                            <option value="google" <?php echo ($is_edit && $review->platform === 'google') ? 'selected' : ''; ?>>
                                                <?php esc_html_e('🟦 Google Reviews', 'buzzhub'); ?>
                                            </option>
                                            <option value="yelp" <?php echo ($is_edit && $review->platform === 'yelp') ? 'selected' : ''; ?>>
                                                <?php esc_html_e('🔴 Yelp Reviews', 'buzzhub'); ?>
                                            </option>
                                            <option value="facebook" <?php echo ($is_edit && $review->platform === 'facebook') ? 'selected' : ''; ?>>
                                                <?php esc_html_e('🔵 Facebook Reviews', 'buzzhub'); ?>
                                            </option>
                                            <option value="other" <?php echo ($is_edit && $review->platform === 'other') ? 'selected' : ''; ?>>
                                                <?php esc_html_e('⭐ Other Platform', 'buzzhub'); ?>
                                            </option>
                                        </select>
                                        <p class="description">
                                            <strong><?php esc_html_e('Important:', 'buzzhub'); ?></strong> 
                                            <?php esc_html_e('Select where this review originally came from. This will display a badge (Google, Yelp, etc.) on your website to show the review source.', 'buzzhub'); ?>
                                        </p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row"><?php esc_html_e('Status', 'buzzhub'); ?></th>
                                    <td>
                                        <fieldset>
                                            <label>
                                                <input type="checkbox" name="is_approved" value="1" 
                                                       <?php echo ($is_edit && $review->is_approved) || !$is_edit ? 'checked' : ''; ?> />
                                                <?php esc_html_e('Approved for display', 'buzzhub'); ?>
                                            </label><br>
                                            
                                            <label>
                                                <input type="checkbox" name="is_featured" value="1" 
                                                       <?php echo ($is_edit && $review->is_featured) ? 'checked' : ''; ?> />
                                                <?php esc_html_e('Featured review', 'buzzhub'); ?>
                                            </label>
                                        </fieldset>
                                        <p class="description"><?php esc_html_e('Control visibility and prominence of this review.', 'buzzhub'); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2><?php esc_html_e('Actions', 'buzzhub'); ?></h2>
                        </div>
                        <div class="inside">
                            <div class="buzzhub-actions">
                                <p class="submit">
                                    <button type="submit" class="button button-primary button-large">
                                        <?php echo $is_edit ? esc_html__('Update Review', 'buzzhub') : esc_html__('Add Review', 'buzzhub'); ?>
                                    </button>
                                </p>
                                
                                <?php if ($is_edit): ?>
                                    <p>
                                        <button type="button" class="button button-secondary" id="delete-review-btn" data-review-id="<?php echo esc_attr($review->id); ?>">
                                            <?php esc_html_e('Delete Review', 'buzzhub'); ?>
                                        </button>
                                    </p>
                                <?php endif; ?>
                                
                                <p>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-reviews')); ?>" class="button">
                                        <?php esc_html_e('Back to Reviews', 'buzzhub'); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($is_edit && $review->is_edited): ?>
                        <!-- Original Review Info -->
                        <div class="postbox">
                            <div class="postbox-header">
                                <h2><?php esc_html_e('Original Review', 'buzzhub'); ?></h2>
                            </div>
                            <div class="inside">
                                <p><strong><?php esc_html_e('This review has been edited.', 'buzzhub'); ?></strong></p>
                                <p><?php esc_html_e('Original text:', 'buzzhub'); ?></p>
                                <div class="buzzhub-original-text">
                                    <?php echo nl2br(esc_html($review->original_review_text)); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        
    <?php endif; ?>
</div> 