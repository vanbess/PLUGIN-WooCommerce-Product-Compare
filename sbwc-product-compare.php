<?php

/** 
 *  Plugin Name: SBWC Product Compare 
 *  Description: Compare similar products and allow user to add to cart preferred product. Shorcode to use: <strong>[sbwc_pc]</strong> or <strong>[sbwc_pc ids="1234,5678,9012"]</strong> for specific products.
 *  Version: 1.0.0 
 *  Author: WC Bessinger
 */

if (!defined('ABSPATH')) :
    exit();
endif;

add_action('init', 'sbwc_pc_init');
function sbwc_pc_init()
{
    // constants
    define('SBWC_PC_PATH', plugin_dir_path(__FILE__));
    define('SBWC_PC_URL', plugin_dir_url(__FILE__));

    // shortcode
    include SBWC_PC_PATH . 'functions/product-compare-shortcode.php';

    // css + js
    add_action('wp_enqueue_scripts', 'sbwc_pc_frontend_scripts');
    function sbwc_pc_frontend_scripts()
    {
        wp_enqueue_style('sbwc-pc-front-', SBWC_PC_URL . 'assets/pc.css');
        wp_enqueue_script('sbwc-pc-front-', SBWC_PC_URL . 'assets/pc.js', ['jquery']);
    }

    // product comparison data tab - product edit screen
    include SBWC_PC_PATH . 'functions/product-compare-tab.php';
}
