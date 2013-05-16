<?php

function my_custom_posts() 
{
    $labels = array(
        'name' => _x('Products', 'post type general name') ,
        'singular_name' => _x('Product', 'post type singular name') ,
        'add_new' => _x('Add New', 'book') ,
        'add_new_item' => __('Add New Product') ,
        'edit_item' => __('Edit Product') ,
        'new_item' => __('New Product') ,
        'all_items' => __('All Products') ,
        'view_item' => __('View Product') ,
        'search_items' => __('Search Products') ,
        'not_found' => __('No products found') ,
        'not_found_in_trash' => __('No products found in the Trash') ,
        'parent_item_colon' => '',
        'menu_name' => 'Products'
    );
    $args = array(
        'labels' => $labels,
        'description' => 'Holds our products and product specific data',
        'public' => true,
        'menu_position' => 5,
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
            'excerpt'
        ) ,
        'has_archive' => true,
        'rewrite' => array(
            'slug' => 'product',
        ) ,
    );
    register_post_type('dm_product', $args);
    $labels = array(
        'name' => _x('Orders', 'post type general name') ,
        'singular_name' => _x('Order', 'post type singular name') ,
        'add_new' => _x('Add New', 'book') ,
        'add_new_item' => __('Add New Orders') ,
        'edit_item' => __('Edit Order') ,
        'new_item' => __('New Orders') ,
        'all_items' => __('All Orders') ,
        'view_item' => __('View Order') ,
        'search_items' => __('Search Orders') ,
        'not_found' => __('No orders found') ,
        'not_found_in_trash' => __('No orders found in the Trash') ,
        'parent_item_colon' => '',
        'menu_name' => 'Orders'
    );
    $args = array(
        'labels' => $labels,
        'description' => 'Holds our orders and order specific data',
        'public' => true,
        'menu_position' => 6,
        'supports' => array(
            'title',
            'editor'
        ) ,
        'has_archive' => true,
        'rewrite' => array(
            'slug' => 'order',
        ) ,
    );
    register_post_type('dm_order', $args);
}
add_action('init', 'my_custom_posts');

/*
CUSTOM INTERACTION MESSAGES
*/

function my_updated_messages($messages) 
{
    global $post, $post_ID;
    $messages['product'] = array(
        0 => '',
        1 => sprintf(__('Product updated. <a href="%s">View product</a>') , esc_url(get_permalink($post_ID))) ,
        2 => __('Custom field updated.') ,
        3 => __('Custom field deleted.') ,
        4 => __('Product updated.') ,
        5 => isset($_GET['revision']) ? sprintf(__('Product restored to revision from %s') , wp_post_revision_title((int)$_GET['revision'], false)) : false,
        6 => sprintf(__('Product published. <a href="%s">View product</a>') , esc_url(get_permalink($post_ID))) ,
        7 => __('Product saved.') ,
        8 => sprintf(__('Product submitted. <a target="_blank" href="%s">Preview product</a>') , esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))) ,
        9 => sprintf(__('Product scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview product</a>') , date_i18n(__('M j, Y @ G:i') , strtotime($post->post_date)) , esc_url(get_permalink($post_ID))) ,
        10 => sprintf(__('Product draft updated. <a target="_blank" href="%s">Preview product</a>') , esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))) ,
    );
    $messages['order'] = array(
        0 => '',
        1 => sprintf(__('Order updated. <a href="%s">View Order</a>') , esc_url(get_permalink($post_ID))) ,
        2 => __('Custom field updated.') ,
        3 => __('Custom field deleted.') ,
        4 => __('Order updated.') ,
        5 => isset($_GET['revision']) ? sprintf(__('Order restored to revision from %s') , wp_post_revision_title((int)$_GET['revision'], false)) : false,
        6 => sprintf(__('Order published. <a href="%s">View Order</a>') , esc_url(get_permalink($post_ID))) ,
        7 => __('Order saved.') ,
        8 => sprintf(__('Order submitted. <a target="_blank" href="%s">Preview Order</a>') , esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))) ,
        9 => sprintf(__('Order scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Order</a>') , date_i18n(__('M j, Y @ G:i') , strtotime($post->post_date)) , esc_url(get_permalink($post_ID))) ,
        10 => sprintf(__('Order draft updated. <a target="_blank" href="%s">Preview Order</a>') , esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))) ,
    );
    
    return $messages;
}
add_filter('post_updated_messages', 'my_updated_messages');

/*
Custom Taxonomies
*/

function my_taxonomies() 
{
    $labels = array(
        'name' => _x('Product Categories', 'taxonomy general name') ,
        'singular_name' => _x('Product Category', 'taxonomy singular name') ,
        'search_items' => __('Search Product Categories') ,
        'all_items' => __('All Product Categories') ,
        'parent_item' => __('Parent Product Category') ,
        'parent_item_colon' => __('Parent Product Category:') ,
        'edit_item' => __('Edit Product Category') ,
        'update_item' => __('Update Product Category') ,
        'add_new_item' => __('Add New Product Category') ,
        'new_item_name' => __('New Product Category') ,
        'menu_name' => __('Product Categories') ,
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
    );
    register_taxonomy('products', 'dm_product', $args);
    $labels = array(
        'name' => _x('Product Tags', 'taxonomy general name') ,
        'singular_name' => _x('Product Tag', 'taxonomy singular name') ,
        'search_items' => __('Search Product Tags') ,
        'all_items' => __('All Product Tags') ,
        'parent_item' => __('Parent Product Tag') ,
        'parent_item_colon' => __('Parent Product Tag:') ,
        'edit_item' => __('Edit Product Tag') ,
        'update_item' => __('Update Product Tag') ,
        'add_new_item' => __('Add New Product Tag') ,
        'new_item_name' => __('New Product Tag') ,
        'menu_name' => __('Product Tags') ,
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
    );
    register_taxonomy('product-tag', 'dm_product', $args);
    $labels = array(
        'name' => _x('Order Categories', 'taxonomy general name') ,
        'singular_name' => _x('Order Category', 'taxonomy singular name') ,
        'search_items' => __('Search Order Categories') ,
        'all_items' => __('All Order Categories') ,
        'parent_item' => __('Parent Order Category') ,
        'parent_item_colon' => __('Parent Order Category:') ,
        'edit_item' => __('Edit Order Category') ,
        'update_item' => __('Update Order Category') ,
        'add_new_item' => __('Add New Order Category') ,
        'new_item_name' => __('New Order Category') ,
        'menu_name' => __('Order Categories') ,
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
    );
    register_taxonomy('orders', 'dm_order', $args);
}
add_action('init', 'my_taxonomies', 0);

/*
Add the price meta data
*/
add_action('add_meta_boxes', 'product_price_box');

function product_price_box() 
{
    add_meta_box('product_price_box', __('Product Price', 'myplugin_textdomain') , 'product_price_box_content', 'dm_product', 'side', 'high');
}

function product_price_box_content($post) 
{
    wp_nonce_field(plugin_basename(__FILE__) , 'product_price_box_content_nonce');
    
    foreach (Products::GetProduct($post->ID)->Prices as $key => $price) 
    {
        echo "<div>";
        echo '<label for="type' . $key . '">Type</label>';
        echo '<input type="text" name="type' . $key . '" value="' . $price->Type . '" size="10" />';
        echo '<label for="quantity' . $key . '">Quantity</label>';
        echo '<input type="text" name="quantity' . $key . '" value="' . $price->Quantity . '" size="5" />';
        echo '<label for="price' . $key . '">Price £</label>';
        echo '<input type="text" name="price' . $key . '" value="' . $price->Price . '" size="5" />';
        echo "</div>";
    }
}
add_action('save_post', 'product_price_box_save');

function product_price_box_save($post_id) 
{
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
    return $post_id;
    
    if ( ! isset($_POST['product_price_box_content_nonce']) )
        return $post_id;
    
    if (!wp_verify_nonce($_POST['product_price_box_content_nonce'], plugin_basename(__FILE__))) 
    return $post_id;
    
    if ('dm_product' != $_POST['post_type']) 
    return $post_id;
    
    if (!current_user_can('edit_post', $post_id)) 
    return $post_id;
    $product = Products::GetProduct($post_id);
    $newprices = $product->Prices;

    //do the update
    
    foreach ($newprices as $key => & $price) 
    {
        $price->Quantity = number_format(floatval(filter_var($_POST['quantity' . $key], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) , 0);
        if ( $price->Quantity == 0 ) unset($price->Quantity);
        
        $price->Price = number_format(floatval(filter_var($_POST['price' . $key], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) , 2);
        if ( $price->Price == 0 ) unset($price->Price);
        
        $price->Type = filter_var($_POST['type' . $key], FILTER_SANITIZE_STRING);
    }
    $product->Prices = $newprices;
    
    return $post_id;
}

// Add the posts and pages columns filter. They can both use the same function.
add_filter('manage_posts_columns', 'tcb_add_post_thumbnail_column', 5);
add_filter('manage_pages_columns', 'tcb_add_post_thumbnail_column', 5);

// Add the column

function tcb_add_post_thumbnail_column($cols) 
{
    $cols['tcb_post_thumb'] = __('Featured');
    
    return $cols;
}

// Hook into the posts an pages column managing. Sharing function callback again.
add_action('manage_posts_custom_column', 'tcb_display_post_thumbnail_column', 5, 2);
add_action('manage_pages_custom_column', 'tcb_display_post_thumbnail_column', 5, 2);

// Grab featured-thumbnail size post thumbnail and display it.

function tcb_display_post_thumbnail_column($col, $id) 
{
    
    switch ($col) 
    {
    case 'tcb_post_thumb':
        
        if (function_exists('the_post_thumbnail')) echo the_post_thumbnail('admin-list-thumb');
        else echo 'Not supported in theme';
        
        break;
    }
}

