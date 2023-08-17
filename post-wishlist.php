<?php

// add shortcode
function display_save_icon_shortcode($atts)
{
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $post_id = get_the_ID();
        $saved_posts_serialized = get_user_meta($user_id, 'saved-post', true);
        $saved_posts = maybe_unserialize($saved_posts_serialized);

        if (!is_array($saved_posts) || !in_array($post_id, $saved_posts)) {
            $icon_html = '<a href="#" class="save-post-icon" data-post-id="' . $post_id . '"><i class="fa-regular fa-bookmark"></i></a>';
        } else {
            $icon_html = '<a href="#" class="save-post-icon" data-post-id="' . $post_id . '"><i class="fa-solid fa-bookmark"></i></a>';
        }

        return $icon_html;
    }

    return '';
}
add_shortcode('save_icon', 'display_save_icon_shortcode');


// save data by ajax 
function save_post_callback()
{
    if (is_user_logged_in() && isset($_POST['post_id'])) {
        $user_id = get_current_user_id();
        $post_id = intval($_POST['post_id']); // Ensure it's an integer

        $saved_posts = get_user_meta($user_id, 'saved-post', true);

        if (!is_array($saved_posts)) {
            $saved_posts = array();
        }

        // Check if the post ID is in the array
        $index = array_search($post_id, $saved_posts);

        if ($index !== false) {
            // Remove the post ID if it's present
            unset($saved_posts[$index]);
        } else {
            // Add the post ID if it's not present
            $saved_posts[] = $post_id;
        }

        // Reindex the array to ensure keys are consecutive
        $saved_posts = array_values($saved_posts);

        update_user_meta($user_id, 'saved-post', $saved_posts);
    }
    die();
}
add_action('wp_ajax_save_post', 'save_post_callback');
add_action('wp_ajax_save_post', 'save_post_callback');



function display_saved_posts_shortcode($atts)
{
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $saved_posts = get_user_meta($user_id, 'saved-post', true);

        if (!empty($saved_posts)) {
            $args = array(
                'post_type' => 'post', // Adjust to your custom post type if needed
                'post__in' => $saved_posts,
                'orderby' => 'post__in'
            );

            $saved_post_query = new WP_Query($args);

            if ($saved_post_query->have_posts()) {
                $output = '<ul>';
                while ($saved_post_query->have_posts()) {
                    $saved_post_query->the_post();
                    $output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                }
                $output .= '</ul>';
                wp_reset_postdata();
                return $output;
            }
        } else {
            return 'No saved posts found.';
        }
    } else {
        return 'You must be logged in to view saved posts.';
    }
}
add_shortcode('saved_posts', 'display_saved_posts_shortcode');
