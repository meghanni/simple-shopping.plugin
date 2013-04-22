<?php

/*
   Plugin Name: Simple-Shopping Version 1.10
   Plugin URI: http://megnicholas.co.uk/plugins
   Description: A plugin that provides a product database, product pages, order history and a simple shopping cart
   Version: 1.10
   Author: Meghan Nicholas
   Author URI: http://megnicholas.co.uk
   License: GPL2
*/
include ('simple-shopping-custom-post-types.php');
include ('shortcodes/product-list.php');
include ('shortcodes/cart.php');
include ('shortcodes/cart-menu.php');
//include ('shortcodes/cart-orderdetails.php');
//include ('shortcodes/cart-confirmandplace.php');
include ('class.product.php');
include ('class.cart.php');
include ('class.pluginsettings.php');
include ('class.view.php');
include ('simple-shopping-settings.php');
include ('ajax.php');

if (!defined('SCC_THEME_DIR')) define('SCC_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

if (!defined('SCC_PLUGIN_NAME')) define('SCC_PLUGIN_NAME', 'simple-shopping');

if (!defined('SCC_PLUGIN_DIR')) define('SCC_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . SCC_PLUGIN_NAME);

if (!defined('SCC_PLUGIN_URL')) define('SCC_PLUGIN_URL', WP_PLUGIN_URL . '/' . SCC_PLUGIN_NAME);

if (!defined('SCC_VERSION_KEY')) define('SCC_VERSION_KEY', 'cff_version');

if (!defined('SCC_VERSION_NUM')) define('SCC_VERSION_NUM', '1.10');

if (!defined('SCC_OPTIONS_KEY')) define('SCC_OPTIONS_KEY', 'cff_options');

/*
Create the products custom type
*/

/*start the session*/
session_start();

/*If attached to an action hook, it should be after_setup_theme.
 The init action hook may be too late for some features.*/
add_action('after_setup_theme', 'ThumbnailSupport');

function ThumbnailSupport() 
{
    add_theme_support('post-thumbnails');
    add_image_size('admin-list-thumb', 100, 100, false);
}

//this will allow shortcodes to be placed in menus

function shortcode_menu($item_output, $item) 
{
    
    if (!empty($item->title)) 
    {
        $output = do_shortcode($item->title);

        //echo $item->title;
        
        if ($output != $item->title) $item_output = $output;
    }
    
    return $item_output;
}
add_filter("walker_nav_menu_start_el", "shortcode_menu", 10, 2);

