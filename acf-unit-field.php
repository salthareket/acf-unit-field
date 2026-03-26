<?php
/*
Plugin Name: ACF Units Field
Description: Adds units select menu to text field..
Version: 1.0.3
Author: Tolga Koçak
*/


if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('acf/include_field_types', 'include_custom_acf_field_types');
function include_custom_acf_field_types( $version ) {
    include_once('fields/class-custom-acf-field-units.php');
}

?>
