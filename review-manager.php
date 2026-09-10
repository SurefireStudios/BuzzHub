<?php
/**
 * Plugin Name: BuzzHub
 * Plugin URI: https://github.com/SurefireStudios/BuzzHub
 * Description: A comprehensive WordPress plugin for managing and displaying customer reviews with user submission capabilities, multiple display layouts, and complete editorial control.
 * Version: 1.2.4
 * Author: Surefire Studios
 * Author URI: https://surefirestudios.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: buzzhub
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Tested up to: 6.8
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('BUZZHUB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BUZZHUB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BUZZHUB_VERSION', '1.2.4');

class BuzzHub {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Load plugin files
        $this->load_dependencies();
        
        // Check for database updates
        $this->check_database_updates();
        
        // Initialize components
        if (is_admin()) {
            new BuzzHub_Admin();
        }
        
        new BuzzHub_Frontend();
        new BuzzHub_Shortcodes();
        new BuzzHub_User_Reviews();
    }
    
    private function check_database_updates() {
        $current_version = get_option('buzzhub_version', '1.0.0');
        
        if (version_compare($current_version, BUZZHUB_VERSION, '<')) {
            // Run database updates
            require_once BUZZHUB_PLUGIN_DIR . 'includes/class-database.php';
            BuzzHub_Database::create_tables(); // This will add new columns if they don't exist
            
            // Update version
            update_option('buzzhub_version', BUZZHUB_VERSION);
        }
    }
    
    private function load_dependencies() {
        require_once BUZZHUB_PLUGIN_DIR . 'includes/class-database.php';
        require_once BUZZHUB_PLUGIN_DIR . 'includes/class-admin.php';
        require_once BUZZHUB_PLUGIN_DIR . 'includes/class-frontend.php';
        require_once BUZZHUB_PLUGIN_DIR . 'includes/class-shortcodes.php';
        require_once BUZZHUB_PLUGIN_DIR . 'includes/class-user-reviews.php';
    }
    
    public function activate() {
        // Load database class for activation
        require_once BUZZHUB_PLUGIN_DIR . 'includes/class-database.php';
        BuzzHub_Database::create_tables();
        
        // Set default options
        add_option('buzzhub_version', BUZZHUB_VERSION);
        add_option('buzzhub_display_settings', array(
            'show_photos' => 1,
            'show_dates' => 1,
            'show_platform' => 1,
            'max_reviews' => 10,
            'min_rating' => 1,
            'photo_size' => 'small',
            'redirect_after_review' => home_url(),
            'button_color' => 'blue'
        ));
    }
    
    public function deactivate() {
        // Clean up scheduled events if any
        wp_clear_scheduled_hook('buzzhub_cleanup_temp_files');
    }
}

// Initialize the plugin
new BuzzHub(); 