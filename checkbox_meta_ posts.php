<?php 
// Add custom checkbox meta box to posts
function add_featured_meta_box() {
    add_meta_box(
        'featured_meta_box',         
        'Featured',                 
        'display_featured_meta_box', 
        'post',                   
        'side',                  
        'default'               
    );
}
add_action('add_meta_boxes', 'add_featured_meta_box');

function display_featured_meta_box($post) {
    $checkbox_value = get_post_meta($post->ID, '_featured_value', true);
    wp_nonce_field(basename(__FILE__), 'featured_nonce');
    ?>
    <label for="featured_field">
        <input type="checkbox" name="featured_field" id="featured_field" value="1" <?php checked($checkbox_value, '1'); ?> />
        Mark this post as Featured
    </label>
    <?php
}
function save_featured_meta($post_id) {
    // Verify the nonce for security
    if (!isset($_POST['featured_nonce']) || !wp_verify_nonce($_POST['featured_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Check if the user can edit the post
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Save the checkbox value: 1 if checked, 0 if not
    $new_value = isset($_POST['featured_field']) ? 1 : 0;
    update_post_meta($post_id, '_featured_value', $new_value);
}
add_action('save_post', 'save_featured_meta');