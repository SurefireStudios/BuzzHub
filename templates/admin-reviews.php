<?php
/**
 * Admin Reviews Template
 * 
 * Note: All variables ($reviews, $locations, $location_filter, $platform_filter, $search_term)
 * are passed from the reviews_page() method after proper security checks.
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>
        <?php esc_html_e('Manage Reviews', 'buzzhub'); ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-add-review')); ?>" class="page-title-action">
            <?php esc_html_e('Add New Review', 'buzzhub'); ?>
        </a>
    </h1>
    
    <!-- Filters -->
    <div class="tablenav top">
        <form method="get" action="">
            <input type="hidden" name="page" value="buzzhub-reviews" />
            
            <select name="location">
                <option value=""><?php esc_html_e('All Locations', 'buzzhub'); ?></option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?php echo esc_attr($location->id); ?>" <?php selected($location_filter, $location->id); ?>>
                        <?php echo esc_html($location->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <select name="platform">
                <option value=""><?php esc_html_e('All Platforms', 'buzzhub'); ?></option>
                <option value="google" <?php selected($platform_filter, 'google'); ?>><?php esc_html_e('Google', 'buzzhub'); ?></option>
                <option value="yelp" <?php selected($platform_filter, 'yelp'); ?>><?php esc_html_e('Yelp', 'buzzhub'); ?></option>
                <option value="manual" <?php selected($platform_filter, 'manual'); ?>><?php esc_html_e('Manual', 'buzzhub'); ?></option>
                <option value="user_submitted" <?php selected($platform_filter, 'user_submitted'); ?>><?php esc_html_e('User Submitted', 'buzzhub'); ?></option>
            </select>
            
            <input type="search" name="search" value="<?php echo esc_attr($search_term); ?>" placeholder="<?php esc_attr_e('Search reviews...', 'buzzhub'); ?>" />
            
            <button type="submit" class="button"><?php esc_html_e('Filter', 'buzzhub'); ?></button>
            
            <?php if ($location_filter || $platform_filter || $search_term): ?>
                <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-reviews')); ?>" class="button">
                    <?php esc_html_e('Clear Filters', 'buzzhub'); ?>
                </a>
            <?php endif; ?>
        </form>
    </div>
    
    <?php if (!empty($reviews)): ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" style="width: 180px;"><?php esc_html_e('Reviewer', 'buzzhub'); ?></th>
                    <th scope="col" style="width: 80px;"><?php esc_html_e('Rating', 'buzzhub'); ?></th>
                    <th scope="col"><?php esc_html_e('Review Text', 'buzzhub'); ?></th>
                    <th scope="col" style="width: 100px;"><?php esc_html_e('Date', 'buzzhub'); ?></th>
                    <th scope="col" style="width: 130px;"><?php esc_html_e('Platform', 'buzzhub'); ?></th>
                    <th scope="col" style="width: 90px;"><?php esc_html_e('Status', 'buzzhub'); ?></th>
                    <th scope="col" style="width: 180px;"><?php esc_html_e('Actions', 'buzzhub'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <?php if (!empty($review->reviewer_photo_url)): ?>
                                    <img src="<?php echo esc_url($review->reviewer_photo_url); ?>" 
                                         alt="<?php echo esc_attr($review->reviewer_name); ?>" 
                                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" />
                                <?php endif; ?>
                                <div>
                                    <strong><?php echo esc_html($review->reviewer_name); ?></strong>
                                    <?php if (!empty($review->location_name)): ?>
                                        <br><small><?php echo esc_html($review->location_name); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="color: #ffa500;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php echo $i <= $review->rating ? '★' : '☆'; ?>
                                <?php endfor; ?>
                            </div>
                            <small>(<?php echo number_format($review->rating, 1); ?>)</small>
                        </td>
                        <td>
                            <div style="max-width: 400px; max-height: 120px; overflow-y: auto; padding: 5px;">
                                <?php echo esc_html(stripslashes($review->review_text)); ?>
                            </div>
                        </td>
                        <td>
                            <?php echo esc_html(date_i18n('M j, Y', strtotime($review->review_date))); ?>
                        </td>
                        <td>
                            <span class="buzzhub-platform-admin buzzhub-platform-admin-<?php echo esc_attr($review->platform); ?>">
                                <?php echo wp_kses_post($this->get_platform_svg_admin($review->platform)); ?>
                                <span class="buzzhub-platform-name"><?php echo esc_html(ucfirst($review->platform)); ?></span>
                            </span>
                        </td>
                        <td>
                            <?php if ($review->is_approved): ?>
                                <span style="color: #46b450;">✓ <?php esc_html_e('Approved', 'buzzhub'); ?></span>
                            <?php else: ?>
                                <span style="color: #dc3232;">✗ <?php esc_html_e('Pending', 'buzzhub'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($review->platform === 'user_submitted' && !$review->is_approved): ?>
                                <button class="button button-small button-primary approve-review-btn" 
                                        data-review-id="<?php echo esc_attr($review->id); ?>"
                                        title="<?php esc_attr_e('Approve this review', 'buzzhub'); ?>">
                                    <?php esc_html_e('Approve', 'buzzhub'); ?>
                                </button>
                                <button class="button button-small button-link-delete reject-review-btn" 
                                        data-review-id="<?php echo esc_attr($review->id); ?>"
                                        title="<?php esc_attr_e('Reject this review', 'buzzhub'); ?>">
                                    <?php esc_html_e('Reject', 'buzzhub'); ?>
                                </button>
                            <?php else: ?>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-add-review&edit=' . $review->id)); ?>" 
                                   class="button button-small">
                                    <?php esc_html_e('Edit', 'buzzhub'); ?>
                                </a>
                            <?php endif; ?>
                            <button class="button button-small button-link-delete delete-review-btn" 
                                    data-review-id="<?php echo esc_attr($review->id); ?>">
                                <?php esc_html_e('Delete', 'buzzhub'); ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="buzzhub-empty-state">
            <h2><?php esc_html_e('No reviews found', 'buzzhub'); ?></h2>
            <p><?php esc_html_e('Try adjusting your filters or add your first review.', 'buzzhub'); ?></p>
            <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-add-review')); ?>" class="button button-primary">
                <?php esc_html_e('Add Your First Review', 'buzzhub'); ?>
            </a>
        </div>
    <?php endif; ?>
</div> 