<?php
/**
 * Plugin Name: ACF Units Field
 * Description: Adds units select menu to text field.
 * Version: 1.1.0
 * Author: Tolga Koçak
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Bağımlılık kontrolü
add_action( 'admin_init', function() {
    if ( ! class_exists( 'ACF' ) && ! class_exists( 'acf' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>ACF Units Field:</strong> Advanced Custom Fields (ACF) eklentisi yüklü ve aktif olmalıdır.</p></div>';
        });
    }
});

add_action( 'acf/include_field_types', function() {
    require_once __DIR__ . '/fields/class-custom-acf-field-units.php';
});
