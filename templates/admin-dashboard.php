<?php
/**
 * BuzzHub Dashboard Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php esc_html_e('BuzzHub Dashboard', 'buzzhub'); ?></h1>
    
    <div class="buzzhub-dashboard-stats">
        <div class="buzzhub-stat-box">
            <h3><?php esc_html_e('Total Reviews', 'buzzhub'); ?></h3>
            <div class="buzzhub-stat-number"><?php echo number_format($stats->total_reviews ?? 0); ?></div>
        </div>
        
        <div class="buzzhub-stat-box">
            <h3><?php esc_html_e('Average Rating', 'buzzhub'); ?></h3>
            <div class="buzzhub-stat-number"><?php echo number_format($stats->average_rating ?? 0, 1); ?> ★</div>
        </div>
        
        <div class="buzzhub-stat-box">
            <h3><?php esc_html_e('Total Locations', 'buzzhub'); ?></h3>
            <div class="buzzhub-stat-number"><?php echo count($locations); ?></div>
        </div>
        
        <div class="buzzhub-stat-box">
            <h3><?php esc_html_e('5-Star Reviews', 'buzzhub'); ?></h3>
            <div class="buzzhub-stat-number"><?php echo number_format($stats->five_star ?? 0); ?></div>
        </div>
    </div>
    
    <div class="buzzhub-dashboard-content">
        <!-- All Reviews - Now at the top for easy management -->
        <div class="buzzhub-dashboard-section buzzhub-all-reviews-section">
            <h2><?php esc_html_e('📋 All Reviews', 'buzzhub'); ?></h2>
            <?php if (!empty($recent_reviews)): ?>
                <div class="buzzhub-reviews-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th style="width: 140px;"><?php esc_html_e('Reviewer', 'buzzhub'); ?></th>
                                <th style="width: 80px;"><?php esc_html_e('Rating', 'buzzhub'); ?></th>
                                <th><?php esc_html_e('Review Text', 'buzzhub'); ?></th>
                                <th style="width: 130px;"><?php esc_html_e('Platform', 'buzzhub'); ?></th>
                                <th style="width: 90px;"><?php esc_html_e('Status', 'buzzhub'); ?></th>
                                <th style="width: 180px;"><?php esc_html_e('Actions', 'buzzhub'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_reviews as $review): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo esc_html(stripslashes($review->reviewer_name)); ?></strong>
                                        <?php if (!empty($review->location_name)): ?>
                                            <br><small><?php echo esc_html($review->location_name); ?></small>
                                        <?php endif; ?>
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
                                            <div style="max-width: 450px; max-height: 120px; overflow-y: auto; padding: 5px;">
                                                <?php echo esc_html(stripslashes($review->review_text)); ?>
                                            </div>
                                        </td>
                                    <td>
                                        <span class="buzzhub-platform buzzhub-platform-<?php echo esc_attr($review->platform); ?>">
                                            <?php echo esc_html(ucfirst(str_replace('_', ' ', $review->platform))); ?>
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
                                                    title="<?php esc_html_e('Approve this review', 'buzzhub'); ?>">
                                                <?php esc_html_e('Approve', 'buzzhub'); ?>
                                            </button>
                                            <button class="button button-small button-link-delete reject-review-btn" 
                                                    data-review-id="<?php echo esc_attr($review->id); ?>"
                                                    title="<?php esc_html_e('Reject this review', 'buzzhub'); ?>">
                                                <?php esc_html_e('Reject', 'buzzhub'); ?>
                                            </button>
                                        <?php else: ?>
                                            <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-add-review&edit=' . $review->id)); ?>" 
                                               class="button button-small">
                                                <?php esc_html_e('Edit', 'buzzhub'); ?>
                                            </a>
                                        <?php endif; ?>
                                        <button class="button button-small button-link-delete delete-review-btn" 
                                                data-review-id="<?php echo esc_attr($review->id); ?>"
                                                title="<?php esc_html_e('Delete this review', 'buzzhub'); ?>">
                                            <?php esc_html_e('Delete', 'buzzhub'); ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p style="margin-top: 15px;">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-reviews')); ?>" class="button">
                        <?php esc_html_e('View Full BuzzHub', 'buzzhub'); ?> →
                    </a>
                </p>
            <?php else: ?>
                <div class="buzzhub-empty-state">
                    <h3><?php esc_html_e('No reviews yet', 'buzzhub'); ?></h3>
                    <p><?php esc_html_e('Start by adding your first review manually.', 'buzzhub'); ?></p>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-add-review')); ?>" class="button button-primary">
                        <?php esc_html_e('Add First Review', 'buzzhub'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="buzzhub-dashboard-section">
            <h2><?php esc_html_e('Quick Actions', 'buzzhub'); ?></h2>
            <div class="buzzhub-quick-actions">
                <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-add-review')); ?>" class="button button-primary">
                    <?php esc_html_e('Add New Review', 'buzzhub'); ?>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-locations')); ?>" class="button">
                    <?php esc_html_e('Manage Locations', 'buzzhub'); ?>
                </a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-reviews')); ?>" class="button">
                    <?php esc_html_e('View All Reviews', 'buzzhub'); ?>
                </a>
            </div>
        </div>
        
        <!-- Bulk Text Replacement -->
        <div class="buzzhub-dashboard-section">
            <h2><?php esc_html_e('Bulk Text Replacement', 'buzzhub'); ?></h2>
            <p><?php esc_html_e('Replace text across all reviews (e.g., change "Old Business Name" to "New Business Name").', 'buzzhub'); ?></p>
            <form id="bulk-replace-form">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Search for', 'buzzhub'); ?></th>
                        <td>
                            <input type="text" id="search-text" class="regular-text" placeholder="<?php esc_html_e('e.g., Old Business Name', 'buzzhub'); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Replace with', 'buzzhub'); ?></th>
                        <td>
                            <input type="text" id="replace-text" class="regular-text" placeholder="<?php esc_html_e('e.g., New Business Name', 'buzzhub'); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Location', 'buzzhub'); ?></th>
                        <td>
                            <select id="replace-location">
                                <option value="0"><?php esc_html_e('All Locations', 'buzzhub'); ?></option>
                                <?php foreach ($locations as $location): ?>
                                    <option value="<?php echo esc_attr($location->id); ?>"><?php echo esc_html($location->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <button type="submit" class="button button-primary"><?php esc_html_e('Replace Text', 'buzzhub'); ?></button>
                </p>
            </form>
        </div>
        
        <!-- How to Display Reviews -->
        <div class="buzzhub-dashboard-section buzzhub-shortcode-info">
            <h2><?php esc_html_e('📋 Complete Shortcode Reference', 'buzzhub'); ?></h2>
            <p class="description"><?php esc_html_e('Copy and paste these shortcodes into any page or post to display your reviews.', 'buzzhub'); ?></p>
            
            <div class="buzzhub-shortcode-examples">
                <!-- Basic Review Display -->
                <div class="buzzhub-shortcode-card">
                    <h3>🔷 <?php esc_html_e('Basic Review Display', 'buzzhub'); ?></h3>
                    <code>[review_manager]</code>
                    <p><?php esc_html_e('Shows all approved reviews in default grid layout.', 'buzzhub'); ?></p>
                </div>
                
                <!-- Review Display with User Submission Button -->
                <div class="buzzhub-shortcode-card">
                    <h3>👤 <?php esc_html_e('Reviews with Submission Button', 'buzzhub'); ?></h3>
                    <code>[review_manager show_review_button="true"]</code>
                    <p><?php esc_html_e('Shows reviews plus a "Leave Your Own Review" button for logged-in users.', 'buzzhub'); ?></p>
                </div>
                
                <!-- Grid Layout -->
                <div class="buzzhub-shortcode-card">
                    <h3>📱 <?php esc_html_e('Grid Layout (2, 3, or 4 columns)', 'buzzhub'); ?></h3>
                    <code>[review_manager layout="grid" columns="3" max_reviews="9"]</code>
                    <p><?php esc_html_e('3-column grid showing 9 reviews. Options: columns="1|2|3|4"', 'buzzhub'); ?></p>
                </div>
                
                <!-- List Layout -->
                <div class="buzzhub-shortcode-card">
                    <h3>📝 <?php esc_html_e('List Layout', 'buzzhub'); ?></h3>
                    <code>[review_manager layout="list" max_reviews="5" min_rating="4"]</code>
                    <p><?php esc_html_e('Vertical list showing 5 reviews with 4+ stars.', 'buzzhub'); ?></p>
                </div>
                
                <!-- Review Slider -->
                <div class="buzzhub-shortcode-card">
                    <h3>🎠 <?php esc_html_e('Review Slider/Carousel', 'buzzhub'); ?></h3>
                    <code>[review_slider autoplay="true" speed="5000"]</code>
                    <p><?php esc_html_e('Auto-rotating carousel, changes every 5 seconds.', 'buzzhub'); ?></p>
                </div>
                
                <!-- Grid Slider -->
                <div class="buzzhub-shortcode-card">
                    <h3>🎯 <?php esc_html_e('Grid Slider/Carousel', 'buzzhub'); ?></h3>
                    <code>[review_grid_slider columns="3" autoplay="true" speed="4000"]</code>
                    <p><?php esc_html_e('Shows 3 reviews at once, slides to next set. Perfect for displaying multiple reviews while saving space.', 'buzzhub'); ?></p>
                </div>
                
                <!-- Platform Filtering -->
                <div class="buzzhub-shortcode-card">
                    <h3>🟦 <?php esc_html_e('Platform-Specific Reviews', 'buzzhub'); ?></h3>
                    <code>[review_manager platform="google" max_reviews="6"]</code>
                    <p><?php esc_html_e('Show only Google reviews. Options: "google", "yelp", "facebook", "manual"', 'buzzhub'); ?></p>
                </div>
                
                <!-- Multiple Platforms -->
                <div class="buzzhub-shortcode-card">
                    <h3>🔴🟦 <?php esc_html_e('Multiple Platforms', 'buzzhub'); ?></h3>
                    <code>[review_manager platform="google,yelp" columns="2"]</code>
                    <p><?php esc_html_e('Show Google and Yelp reviews only.', 'buzzhub'); ?></p>
                </div>
                
                <!-- Rating Filter -->
                <div class="buzzhub-shortcode-card">
                    <h3>⭐ <?php esc_html_e('High-Rating Reviews Only', 'buzzhub'); ?></h3>
                    <code>[review_manager min_rating="5" max_reviews="4"]</code>
                    <p><?php esc_html_e('Show only 5-star reviews. Options: min_rating="1|2|3|4|5"', 'buzzhub'); ?></p>
                </div>
                
                <!-- Review Statistics -->
                <div class="buzzhub-shortcode-card">
                    <h3>📊 <?php esc_html_e('Review Statistics', 'buzzhub'); ?></h3>
                    <code>[review_stats show_breakdown="true"]</code>
                    <p><?php esc_html_e('Shows total reviews, average rating, and star breakdown.', 'buzzhub'); ?></p>
                </div>
                
                <!-- Simple Stats -->
                <div class="buzzhub-shortcode-card">
                    <h3>📈 <?php esc_html_e('Simple Stats', 'buzzhub'); ?></h3>
                    <code>[review_stats show_breakdown="false"]</code>
                    <p><?php esc_html_e('Shows just total reviews and average rating.', 'buzzhub'); ?></p>
                </div>
            </div>
            
            <!-- Advanced Parameters -->
            <div style="margin-top: 30px; background: #f0f8ff; padding: 20px; border-radius: 8px; border-left: 4px solid #0073aa;">
                <h3>🔧 <?php esc_html_e('Advanced Parameters', 'buzzhub'); ?></h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;">
                    <div>
                        <strong>layout:</strong> "grid", "list", "slider", "grid_slider"<br>
                        <strong>columns:</strong> 1, 2, 3, 4 (grid & grid_slider)<br>
                        <strong>max_reviews:</strong> Any number (default: 10)<br>
                        <strong>min_rating:</strong> 1, 2, 3, 4, 5 (default: 1)
                    </div>
                    <div>
                        <strong>platform:</strong> "google", "yelp", "facebook", "manual", "user_submitted"<br>
                        <strong>autoplay:</strong> "true", "false" (slider only)<br>
                        <strong>speed:</strong> Milliseconds (default: 5000)<br>
                        <strong>show_breakdown:</strong> "true", "false" (stats only)<br>
                        <strong>show_review_button:</strong> "true", "false" (adds user submission button)
                    </div>
                </div>
            </div>
            
            <!-- Quick Copy Examples -->
            <div style="margin-top: 20px; background: #f9f9f9; padding: 15px; border-radius: 8px;">
                <h4>🚀 <?php esc_html_e('Quick Copy Examples', 'buzzhub'); ?></h4>
                <div style="display: grid; gap: 10px;">
                    <div style="font-family: monospace; background: white; padding: 8px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;" onclick="navigator.clipboard.writeText('[review_manager layout=&quot;grid&quot; columns=&quot;3&quot; max_reviews=&quot;9&quot; min_rating=&quot;4&quot;]')" title="Click to copy">
                        [review_manager layout="grid" columns="3" max_reviews="9" min_rating="4"]
                    </div>
                    <div style="font-family: monospace; background: white; padding: 8px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;" onclick="navigator.clipboard.writeText('[review_slider autoplay=&quot;true&quot; speed=&quot;4000&quot;]')" title="Click to copy">
                        [review_slider autoplay="true" speed="4000"]
                    </div>
                    <div style="font-family: monospace; background: white; padding: 8px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;" onclick="navigator.clipboard.writeText('[review_grid_slider columns=&quot;3&quot; autoplay=&quot;true&quot; speed=&quot;3000&quot;]')" title="Click to copy">
                        [review_grid_slider columns="3" autoplay="true" speed="3000"]
                    </div>
                    <div style="font-family: monospace; background: white; padding: 8px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;" onclick="navigator.clipboard.writeText('[review_manager platform=&quot;google,yelp&quot; layout=&quot;list&quot; max_reviews=&quot;5&quot;]')" title="Click to copy">
                        [review_manager platform="google,yelp" layout="list" max_reviews="5"]
                    </div>
                    <div style="font-family: monospace; background: white; padding: 8px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;" onclick="navigator.clipboard.writeText('[review_manager show_review_button=&quot;true&quot; max_reviews=&quot;6&quot; photo_size=&quot;large&quot;]')" title="Click to copy">
                        [review_manager show_review_button="true" max_reviews="6" photo_size="large"]
                    </div>
                </div>
                <p style="font-size: 12px; color: #666; margin: 10px 0 0 0;">💡 Click any shortcode above to copy it to your clipboard!</p>
            </div>
        </div>
    </div>
</div> 