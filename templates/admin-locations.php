<?php
/**
 * BuzzHub Locations Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>
        <?php esc_html_e('Manage Locations', 'buzzhub'); ?>
        <a href="#" class="page-title-action" id="add-location-btn"><?php esc_html_e('Add New Location', 'buzzhub'); ?></a>
    </h1>
    
    <?php if (!empty($locations)): ?>
        <div class="buzzhub-locations-table">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th scope="col"><?php esc_html_e('Name', 'buzzhub'); ?></th>
                        <th scope="col"><?php esc_html_e('Address', 'buzzhub'); ?></th>
                        <th scope="col"><?php esc_html_e('Phone', 'buzzhub'); ?></th>
                        <th scope="col"><?php esc_html_e('Reviews', 'buzzhub'); ?></th>
                        <th scope="col"><?php esc_html_e('Actions', 'buzzhub'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($locations as $location): ?>
                        <?php
                        $review_count = BuzzHub_Database::get_reviews(array(
                            'location_id' => $location->id,
                            'max_reviews' => 9999,
                            'approved_only' => false
                        ));
                        $count = count($review_count);
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($location->name); ?></strong>
                            </td>
                            <td><?php echo esc_html($location->address); ?></td>
                            <td><?php echo esc_html($location->phone); ?></td>
                            <td>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=buzzhub-reviews&location=' . $location->id)); ?>">
                                    <?php /* translators: %d: number of reviews */ ?>
                                    <?php printf(esc_html(_n('%d review', '%d reviews', $count, 'buzzhub')), absint($count)); ?>
                                </a>
                            </td>
                            <td>
                                <button class="button button-small edit-location-btn" 
                                        data-location-id="<?php echo esc_attr($location->id); ?>"
                                        data-name="<?php echo esc_attr($location->name); ?>"
                                        data-address="<?php echo esc_attr($location->address); ?>"
                                        data-phone="<?php echo esc_attr($location->phone); ?>"
                                        data-website="<?php echo esc_attr($location->website); ?>"
                                        data-description="<?php echo esc_attr($location->description); ?>">
                                    <?php esc_html_e('Edit', 'buzzhub'); ?>
                                </button>
                                <button class="button button-small button-link-delete delete-location-btn" 
                                        data-location-id="<?php echo esc_attr($location->id); ?>">
                                    <?php esc_html_e('Delete', 'buzzhub'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="buzzhub-empty-state">
            <h2><?php esc_html_e('No locations found', 'buzzhub'); ?></h2>
            <p><?php esc_html_e('Add your first location to start managing reviews.', 'buzzhub'); ?></p>
            <button class="button button-primary" id="add-first-location-btn">
                <?php esc_html_e('Add Your First Location', 'buzzhub'); ?>
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Location Modal -->
<div id="location-modal" class="buzzhub-modal" style="display: none;">
    <div class="buzzhub-modal-content">
        <div class="buzzhub-modal-header">
            <h2 id="modal-title"><?php esc_html_e('Add New Location', 'buzzhub'); ?></h2>
            <button class="buzzhub-modal-close">&times;</button>
        </div>
        
        <form id="location-form">
            <input type="hidden" id="location-id" name="location_id" value="" />
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="location-name"><?php esc_html_e('Location Name', 'buzzhub'); ?> *</label>
                    </th>
                    <td>
                        <input type="text" id="location-name" name="name" class="regular-text" required />
                        <p class="description"><?php esc_html_e('e.g., "Business Name Reviews"', 'buzzhub'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="location-address"><?php esc_html_e('Address', 'buzzhub'); ?></label>
                    </th>
                    <td>
                        <textarea id="location-address" name="address" class="large-text" rows="3"></textarea>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="location-phone"><?php esc_html_e('Phone', 'buzzhub'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="location-phone" name="phone" class="regular-text" />
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="location-website"><?php esc_html_e('Website', 'buzzhub'); ?></label>
                    </th>
                    <td>
                        <input type="url" id="location-website" name="website" class="regular-text" />
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="location-description"><?php esc_html_e('Description', 'buzzhub'); ?></label>
                    </th>
                    <td>
                        <textarea id="location-description" name="description" class="large-text" rows="4"></textarea>
                        <p class="description"><?php esc_html_e('Optional description for your records.', 'buzzhub'); ?></p>
                    </td>
                </tr>
            </table>
            
            <div class="buzzhub-modal-footer">
                <button type="submit" class="button button-primary">
                    <span id="save-btn-text"><?php esc_html_e('Save Location', 'buzzhub'); ?></span>
                </button>
                <button type="button" class="button buzzhub-modal-close">
                    <?php esc_html_e('Cancel', 'buzzhub'); ?>
                </button>
            </div>
        </form>
    </div>
</div> 