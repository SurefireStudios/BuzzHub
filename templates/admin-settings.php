<?php
/**
 * Admin Settings Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$display_settings = get_option('buzzhub_display_settings', array(
    'show_photos' => 1,
    'show_dates' => 1,
    'show_platform' => 1,
    'max_reviews' => 10,
    'min_rating' => 1,
    'color_theme' => 'light',
    'photo_size' => 'small'
));
?>

<div class="wrap">
    <h1><?php esc_html_e('Review Display Settings', 'buzzhub'); ?></h1>
    
    <form method="post" action="options.php">
        <?php
        settings_fields('buzzhub_settings_group');
        do_settings_sections('buzzhub_settings_group');
        ?>
        
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Show Reviewer Photos', 'buzzhub'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="buzzhub_display_settings[show_photos]" value="1" <?php checked($display_settings['show_photos'], 1); ?> />
                        <?php esc_html_e('Display reviewer photos when available', 'buzzhub'); ?>
                    </label>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><?php esc_html_e('Reviewer Photo Size', 'buzzhub'); ?></th>
                <td>
                    <select name="buzzhub_display_settings[photo_size]">
                        <option value="small" <?php selected(isset($display_settings['photo_size']) ? $display_settings['photo_size'] : 'small', 'small'); ?>>
                            <?php esc_html_e('Small Photos (Compact Layout)', 'buzzhub'); ?>
                        </option>
                        <option value="large" <?php selected(isset($display_settings['photo_size']) ? $display_settings['photo_size'] : 'small', 'large'); ?>>
                            <?php esc_html_e('Large Photos (Hero Layout)', 'buzzhub'); ?>
                        </option>
                    </select>
                    <p class="description">
                        <?php esc_html_e('Small: Compact horizontal layout with small profile photos. Large: Vertical layout with large photos filling the review container width.', 'buzzhub'); ?>
                    </p>
                </td>
            </tr>
            
            
            <tr>
                <th scope="row"><?php esc_html_e('Show Review Dates', 'buzzhub'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="buzzhub_display_settings[show_dates]" value="1" <?php checked($display_settings['show_dates'], 1); ?> />
                        <?php esc_html_e('Display review dates (shown as "2 months ago")', 'buzzhub'); ?>
                    </label>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><?php esc_html_e('Show Platform Badges', 'buzzhub'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="buzzhub_display_settings[show_platform]" value="1" <?php checked($display_settings['show_platform'], 1); ?> />
                        <?php esc_html_e('Show source badges (Google, Yelp, Facebook, etc.) on reviews', 'buzzhub'); ?>
                    </label>
                    <p class="description"><?php esc_html_e('Displays colored badges showing where each review originally came from. Helps build trust by showing review sources.', 'buzzhub'); ?></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><?php esc_html_e('Color Theme', 'buzzhub'); ?></th>
                <td>
                    <select name="buzzhub_display_settings[color_theme]">
                        <option value="light" <?php selected(isset($display_settings['color_theme']) ? $display_settings['color_theme'] : 'light', 'light'); ?>>
                            <?php esc_html_e('Light Theme', 'buzzhub'); ?>
                        </option>
                        <option value="dark" <?php selected(isset($display_settings['color_theme']) ? $display_settings['color_theme'] : 'light', 'dark'); ?>>
                            <?php esc_html_e('Dark Theme', 'buzzhub'); ?>
                        </option>
                        <option value="auto" <?php selected(isset($display_settings['color_theme']) ? $display_settings['color_theme'] : 'light', 'auto'); ?>>
                            <?php esc_html_e('Auto (System Preference)', 'buzzhub'); ?>
                        </option>
                    </select>
                    <p class="description">
                        <?php esc_html_e('Choose the color theme for your reviews. Dark theme works perfectly for dark websites, while auto detects the user\'s system preference.', 'buzzhub'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><?php esc_html_e('Maximum Reviews to Display', 'buzzhub'); ?></th>
                <td>
                    <input type="number" name="buzzhub_display_settings[max_reviews]" value="<?php echo esc_attr($display_settings['max_reviews']); ?>" min="1" max="100" class="small-text" />
                    <p class="description"><?php esc_html_e('Default number of reviews to show (can be overridden in shortcodes)', 'buzzhub'); ?></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><?php esc_html_e('Minimum Rating Filter', 'buzzhub'); ?></th>
                <td>
                    <select name="buzzhub_display_settings[min_rating]">
                        <option value="1" <?php selected($display_settings['min_rating'], 1); ?>><?php esc_html_e('Show all ratings', 'buzzhub'); ?></option>
                        <option value="2" <?php selected($display_settings['min_rating'], 2); ?>><?php esc_html_e('2+ stars', 'buzzhub'); ?></option>
                        <option value="3" <?php selected($display_settings['min_rating'], 3); ?>><?php esc_html_e('3+ stars', 'buzzhub'); ?></option>
                        <option value="4" <?php selected($display_settings['min_rating'], 4); ?>><?php esc_html_e('4+ stars', 'buzzhub'); ?></option>
                        <option value="5" <?php selected($display_settings['min_rating'], 5); ?>><?php esc_html_e('5 stars only', 'buzzhub'); ?></option>
                    </select>
                    <p class="description"><?php esc_html_e('Only show reviews with this rating or higher', 'buzzhub'); ?></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><?php esc_html_e('Button Color Theme', 'buzzhub'); ?></th>
                <td>
                    <select name="buzzhub_display_settings[button_color]">
                        <option value="blue" <?php selected(isset($display_settings['button_color']) ? $display_settings['button_color'] : 'blue', 'blue'); ?>>
                            <?php esc_html_e('Blue (Default)', 'buzzhub'); ?>
                        </option>
                        <option value="black" <?php selected(isset($display_settings['button_color']) ? $display_settings['button_color'] : 'blue', 'black'); ?>>
                            <?php esc_html_e('Black', 'buzzhub'); ?>
                        </option>
                        <option value="red" <?php selected(isset($display_settings['button_color']) ? $display_settings['button_color'] : 'blue', 'red'); ?>>
                            <?php esc_html_e('Red', 'buzzhub'); ?>
                        </option>
                        <option value="green" <?php selected(isset($display_settings['button_color']) ? $display_settings['button_color'] : 'blue', 'green'); ?>>
                            <?php esc_html_e('Green', 'buzzhub'); ?>
                        </option>
                        <option value="purple" <?php selected(isset($display_settings['button_color']) ? $display_settings['button_color'] : 'blue', 'purple'); ?>>
                            <?php esc_html_e('Purple', 'buzzhub'); ?>
                        </option>
                        <option value="orange" <?php selected(isset($display_settings['button_color']) ? $display_settings['button_color'] : 'blue', 'orange'); ?>>
                            <?php esc_html_e('Orange', 'buzzhub'); ?>
                        </option>
                        <option value="grey" <?php selected(isset($display_settings['button_color']) ? $display_settings['button_color'] : 'blue', 'grey'); ?>>
                            <?php esc_html_e('Grey', 'buzzhub'); ?>
                        </option>
                    </select>
                    <p class="description">
                        <?php esc_html_e('Choose the color theme for "Leave Your Own Review", "Read More", and other action buttons.', 'buzzhub'); ?>
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><?php esc_html_e('Review Submission Redirect URL', 'buzzhub'); ?></th>
                <td>
                    <input type="url" 
                           name="buzzhub_display_settings[redirect_after_review]" 
                           value="<?php echo esc_attr(isset($display_settings['redirect_after_review']) ? $display_settings['redirect_after_review'] : home_url()); ?>" 
                           class="regular-text" 
                           placeholder="<?php echo esc_attr(home_url()); ?>" />
                    <p class="description">
                        <?php esc_html_e('URL to redirect users to after successfully submitting a review. Leave empty or use default to redirect to homepage.', 'buzzhub'); ?>
                    </p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
    
    <hr />
    
    <h2><?php esc_html_e('Theme Preview', 'buzzhub'); ?></h2>
    <div class="buzzhub-theme-preview">
        <h3><?php esc_html_e('Light Theme', 'buzzhub'); ?></h3>
        <div class="buzzhub-preview-container">
            <div class="buzzhub-review-item" style="background: #ffffff; border: 1px solid #dddddd; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
                <div class="buzzhub-reviewer-name" style="color: #333333; font-weight: 600; margin-bottom: 8px;">John Smith</div>
                <div class="buzzhub-rating" style="color: #ffa500; margin-bottom: 10px;">★★★★★</div>
                <div class="buzzhub-review-text" style="color: #333333; line-height: 1.6;">Great service and friendly staff. Highly recommended!</div>
            </div>
        </div>
        
        <h3><?php esc_html_e('Dark Theme', 'buzzhub'); ?></h3>
        <div class="buzzhub-preview-container">
            <div class="buzzhub-review-item" style="background: #1a1a1a; border: 1px solid #404040; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
                <div class="buzzhub-reviewer-name" style="color: #e0e0e0; font-weight: 600; margin-bottom: 8px;">Sarah Johnson</div>
                <div class="buzzhub-rating" style="color: #fbbf24; margin-bottom: 10px;">★★★★★</div>
                <div class="buzzhub-review-text" style="color: #e0e0e0; line-height: 1.6;">Excellent experience! Perfect for dark-themed websites.</div>
            </div>
        </div>
    </div>
    
    <hr />
    
    <h2><?php esc_html_e('Shortcode Examples', 'buzzhub'); ?></h2>
    <div class="buzzhub-shortcode-examples">
        <h3><?php esc_html_e('Basic Display', 'buzzhub'); ?></h3>
        <code>[review_manager]</code>
        <p><?php esc_html_e('Shows reviews using default settings', 'buzzhub'); ?></p>
        
        <h3><?php esc_html_e('Grid Layout', 'buzzhub'); ?></h3>
        <code>[review_manager layout="grid" columns="3" max_reviews="6"]</code>
        <p><?php esc_html_e('Shows 6 reviews in a 3-column grid', 'buzzhub'); ?></p>
        
        <h3><?php esc_html_e('Review Slider', 'buzzhub'); ?></h3>
        <code>[review_slider autoplay="true" autoplay_speed="5000"]</code>
        <p><?php esc_html_e('Shows reviews in a slider that auto-advances every 5 seconds', 'buzzhub'); ?></p>
        
        <h3><?php esc_html_e('Review Statistics', 'buzzhub'); ?></h3>
        <code>[review_stats]</code>
        <p><?php esc_html_e('Shows total reviews, average rating, and rating breakdown', 'buzzhub'); ?></p>
        
        <h3><?php esc_html_e('Large Photo Layout', 'buzzhub'); ?></h3>
        <code>[review_manager photo_size="large" layout="grid" columns="2"]</code>
        <p><?php esc_html_e('Shows reviews with large hero-style photos in a vertical layout', 'buzzhub'); ?></p>
        
        <h3><?php esc_html_e('Dark Theme Override', 'buzzhub'); ?></h3>
        <code>[review_manager theme="dark" layout="grid" columns="2"]</code>
        <p><?php esc_html_e('Forces dark theme regardless of global setting - perfect for specific sections', 'buzzhub'); ?></p>
        
        <h3><?php esc_html_e('All Parameters', 'buzzhub'); ?></h3>
        <code>[review_manager layout="grid" columns="3" max_reviews="9" theme="auto" show_photos="true"]</code>
        <p><?php esc_html_e('All available theme options: light, dark, auto (auto detects user system preference)', 'buzzhub'); ?></p>
    </div>
</div>

