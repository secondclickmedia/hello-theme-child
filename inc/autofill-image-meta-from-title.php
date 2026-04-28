<?php
function auto_fill_image_meta_from_title($post_ID) {
    // Get the attachment post
    $attachment = get_post($post_ID);

    // Only proceed if it's an image
    if (strpos($attachment->post_mime_type, 'image/') !== false) {

        // Get the title
        $title = $attachment->post_title;

        // Fallback: if no title, use filename
        if (empty($title)) {
            $file = get_attached_file($post_ID);
            $title = pathinfo($file, PATHINFO_FILENAME);
        }

        // Clean title (optional but recommended)
        $clean_title = sanitize_text_field($title);

        // Update ALT text
        update_post_meta($post_ID, '_wp_attachment_image_alt', $clean_title);

        // Update Caption (post_excerpt)
        wp_update_post([
            'ID'           => $post_ID,
            'post_excerpt' => $clean_title,
            'post_content' => $clean_title, // Description
        ]);
    }
}
add_action('add_attachment', 'auto_fill_image_meta_from_title');

function mytheme_set_bulk_image_meta_flag() {
    add_option('mytheme_run_image_meta_update', true);
}
add_action('after_switch_theme', 'mytheme_set_bulk_image_meta_flag');


// 2. Run the bulk process once (admin-safe)
function mytheme_run_bulk_image_meta_update_once() {

    // Only run in admin and if flag exists
    if (!is_admin()) return;

    if (get_option('mytheme_run_image_meta_update')) {

        // Prevent re-entry (important)
        delete_option('mytheme_run_image_meta_update');

        // Run your bulk function
        $args = [
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => -1,
        ];

        $attachments = get_posts($args);

        foreach ($attachments as $attachment) {
            $post_ID = $attachment->ID;
            $title = $attachment->post_title;

            if (empty($title)) {
                $file = get_attached_file($post_ID);
                $title = pathinfo($file, PATHINFO_FILENAME);
            }

            $clean_title = sanitize_text_field($title);

            update_post_meta($post_ID, '_wp_attachment_image_alt', $clean_title);

            wp_update_post([
                'ID'           => $post_ID,
                'post_excerpt' => $clean_title,
                'post_content' => $clean_title,
            ]);
        }
    }
}

add_action('admin_init', 'mytheme_run_bulk_image_meta_update_once');