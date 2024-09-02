<?php
// Hook into the admin menu to add our custom dropdown
add_action('restrict_manage_posts', 'add_file_filter_dropdown');

// Function to get file IDs and associated products
function get_file_id_product_pairs() {
    $file_ids = [];
    $products = wc_get_products([
        'limit' => -1,
        'status' => 'publish',
        'type' => 'downloadable'
    ]);

    foreach ($products as $product) {
        if ($product->is_downloadable()) {
            $downloads = $product->get_downloads();

            foreach ($downloads as $download) {
                $file_id = $download['id'];
                if (!isset($file_ids[$file_id])) {
                    $file_ids[$file_id] = [];
                }
                $file_ids[$file_id][] = [
                    'product_id' => $product->get_id(),
                    'product_name' => $product->get_name()
                ];
            }
        }
    }

    return $file_ids;
}

// Add a filter dropdown for file IDs and associated products
function add_file_filter_dropdown() {
    global $typenow;

    if ($typenow == 'your_custom_post_type') { // Replace with your custom post type slug
        $file_ids = get_file_id_product_pairs();

        echo '<select name="file_filter" id="file_filter">';
        echo '<option value="">Select a file</option>';

        foreach ($file_ids as $file_id => $products) {
            foreach ($products as $product) {
                $product_name = esc_html($product['product_name']);
                $display_text = 'File ID ' . $file_id . ' - ' . $product_name;
                $value = esc_attr($file_id . '|' . $product['product_id']); // Unique value combining file ID and product ID
                $selected = isset($_GET['file_filter']) && $_GET['file_filter'] == $value ? ' selected="selected"' : '';
                echo '<option value="' . $value . '"' . $selected . '>' . $display_text . '</option>';
            }
        }

        echo '</select>';
    }
}

// Filter posts by file ID and product ID
add_filter('pre_get_posts', 'filter_posts_by_file_id_and_product');

function filter_posts_by_file_id_and_product($query) {
    global $pagenow;
    $typenow = isset($_GET['post_type']) ? $_GET['post_type'] : '';

    if ($pagenow == 'edit.php' && $typenow == 'your_custom_post_type') { // Replace with your custom post type slug
        if (isset($_GET['file_filter']) && $_GET['file_filter'] != '') {
            // Split the value into file ID and product ID
            list($file_id, $product_id) = explode('|', sanitize_text_field($_GET['file_filter']));
            
            $meta_query = [
                'relation' => 'AND',
                [
                    'key'     => '_file_id',
                    'value'   => $file_id,
                    'compare' => '='
                ]
            ];

            // Add product ID filter if present
            if ($product_id) {
                $meta_query[] = [
                    'key'     => '_product_id',
                    'value'   => $product_id,
                    'compare' => '='
                ];
            }

            $query->set('meta_query', $meta_query);
        }
    }
}

// AJAX callback to record file downloads
add_action('wp_ajax_record_file_download', 'record_file_download');

function record_file_download() {
    // Check nonce for security
    check_ajax_referer('download_nonce', 'security');

    // Get parameters from AJAX request
    $product_id = intval($_POST['product_id']);
    $file_id = intval($_POST['file_id']);
    $file_name = sanitize_text_field($_POST['file_name']);
    $user_id = get_current_user_id();

    // Validate parameters
    if (!$product_id || !$file_id || !$file_name || !$user_id) {
        wp_send_json_error('Invalid parameters');
    }

    // Check if record already exists
    $existing_post = get_posts(array(
        'post_type'   => 'download',
        'meta_query'  => array(
            'relation' => 'AND',
            array(
                'key'     => '_file_id',
                'value'   => $file_id,
                'compare' => '='
            ),
            array(
                'key'     => '_product_id',
                'value'   => $product_id,
                'compare' => '='
            ),
            array(
                'key'     => '_user_id',
                'value'   => $user_id,
                'compare' => '='
            )
        )
    ));

    if (!empty($existing_post)) {
        wp_send_json_error('File already recorded');
    }

    // Insert new post
    $post_id = wp_insert_post(array(
        'post_title'  => $file_name,
        'post_type'   => 'download',
        'post_status' => 'publish',
        'post_author' => $user_id
    ));

    // Add metadata
    update_post_meta($post_id, '_file_id', $file_id);
    update_post_meta($post_id, '_product_id', $product_id);
    update_post_meta($post_id, '_user_id', $user_id);

    wp_send_json_success('Download recorded');
}