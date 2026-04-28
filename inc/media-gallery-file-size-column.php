<?php
if ( ! defined( 'ABSPATH' ) ) die( 'Invalid access' );

// Add File Size Column in WordPress Media Library
// Prefix: hello_elementor

// 1. Add a sortable 'File Size' column to the Media Library table
function hello_elementor_media_columns_file_size( $columns ) {
    // Add the new column to the array of existing columns
    $columns['hello_elementor_file_size'] = __( 'File Size', 'hello-elementor' );
    return $columns;
}
// Filter to add the new column to the list view
add_filter( 'manage_media_columns', 'hello_elementor_media_columns_file_size' );


// 2. Register the custom column as sortable
function hello_elementor_sortable_columns( $columns ) {
    $columns['hello_elementor_file_size'] = 'size'; // The key 'size' is used internally by WP_Query for ordering
    return $columns;
}
// Filter to make the new column sortable
add_filter( 'manage_upload_sortable_columns', 'hello_elementor_sortable_columns' );


// 3. Display the actual file size data for each media item
function hello_elementor_media_column_file_size_data( $column_name, $attachment_id ) {
    if ( 'hello_elementor_file_size' === $column_name ) {
        // Get the absolute path to the file
        $file_path = get_attached_file( $attachment_id );
        if ( file_exists( $file_path ) ) {
            $bytes = filesize( $file_path );
            // Use WordPress's built-in function to format bytes into readable format (KB, MB, etc.)
            echo size_format( $bytes, 2 );
        } else {
            echo 'N/A';
        }
    }
}
// Action hook to populate the data in the custom column
add_action( 'manage_media_custom_column', 'hello_elementor_media_column_file_size_data', 10, 2 );


// 4. (Optional) Add some CSS to format the column width nicely
function hello_elementor_media_styles() {
    echo '<style>.column-hello_elementor_file_size { width: 100px; }</style>';
}
// Action hook to add custom CSS to the admin head section
add_action( 'admin_head', 'hello_elementor_media_styles' );