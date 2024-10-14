<?php
/* Template Name: Balance */
get_header(); ?>

<div id="tabs" style="display: flex;">
    <nav class="tab-list" style="flex: 0 0 200px; margin-right: 20px; border-right: 1px solid #ccc;">
        <ul>
            <?php
            // Get the related posts in the ACF-defined order
            $related_posts = get_field('select_courses'); // Replace with your actual field name
            $first_post = null; // Variable to store the first post

            if ($related_posts):
                foreach ($related_posts as $post):
                    setup_postdata($post);
                    // Store the first post for later use
                    if (!$first_post) {
                        $first_post = $post; // Keep a reference to the first post
                    }

                    $terms = get_the_terms($post->ID, 'courses_category'); // Replace with your actual taxonomy

                    if ($terms) {
                        // If the post has taxonomy terms, group it under the first term
                        foreach ($terms as $term) {
                            if (!isset($grouped_posts[$term->term_id])) {
                                // Print the taxonomy term once as the parent list item
                                echo '<li class="taxonomy-term" data-term="' . esc_attr($term->term_id) . '" style="cursor: pointer; padding: 10px; background: #f0f0f0; margin-bottom: 5px;">';
                                echo esc_html($term->name);
                                echo '</li>';
                                //echo '<ul style="list-style-type: none; padding-left: 20px;">';
                                $grouped_posts[$term->term_id] = true; // Mark term as printed
                            }
                            // Display the post inside the taxonomy term's <ul>
                            echo '<ul style="list-style-type: none; padding-left: 20px;">';
                            echo '<li data-tab="' . esc_attr($post->ID) . '" style="cursor: pointer; padding: 10px; background: #e0e0e0; margin-bottom: 5px;">';
                            echo esc_html(get_the_title($post));
                            echo '</li>';
                            echo '</ul>';
                            break; // Only group under the first term
                        }
                    } else {
                        // If no taxonomy terms, display it as an independent <li>
                        echo '<li data-tab="' . esc_attr($post->ID) . '" style="cursor: pointer; padding: 10px; background: #f0f0f0; margin-bottom: 5px;">';
                        echo esc_html(get_the_title($post));
                        echo '</li>';
                    }
                endforeach;
                wp_reset_postdata();
            endif;
            ?>
        </ul>
    </nav>
    <div class="tab-content" style="flex: 1; padding: 20px;">
        <div class="content" id="content">
            <p>Select a tab or taxonomy term to load content.</p>
            <?php
            // Check if the first post has a taxonomy term or not
            if ($first_post) {
                setup_postdata($first_post);
                $first_terms = get_the_terms($first_post->ID, 'courses_category'); // Replace with your actual taxonomy

                if ($first_terms) {
                    // Fetch and display taxonomy term data
                    foreach ($first_terms as $first_term) {
                        echo '<h2>' . esc_html($first_term->name) . '</h2>';
                        echo '<div>' . esc_html($first_term->description) . '</div>';
                        echo '<div>' .get_field('category_full_editor', $first_term->taxonomy . '_' . $first_term->term_id).'</div>';
                        // You can add more term data fetching logic here if needed
                    }
                } else {
                    // Fetch and display post title and content
                    echo '<h2>' . esc_html(get_the_title($first_post)) . '</h2>';
                    echo '<div>' . apply_filters('the_content', $first_post->post_content) . '</div>';
                }
                wp_reset_postdata();
            }
            ?>
        </div>
    </div>
</div>


<style>
#tabs {
    display: flex;
}

.tab-list {
    flex: 0 0 200px; /* Fixed width for tab list */
    list-style-type: none;
    padding: 0;
    margin-right: 20px; /* Space between tab list and content */
}

.tab-list li {
    cursor: pointer;
    padding: 10px;
    background: #f0f0f0;
    margin-bottom: 5px;
    transition: background 0.3s;
}

.tab-list li:hover {
    background: #e0e0e0; /* Highlight on hover */
}

.tab-content {
    flex: 1; /* Take the remaining space */
    border: 1px solid #ccc;
    padding: 20px;
    background: #fff; /* Background for content area */
}

.content {
    /* Add styles for the content if needed */
}
.tab-list li.active, .tab-list .taxonomy-term.active {
    background: #f1a7a7 !important; /* Change to your desired highlight color */
    font-weight: 700; /* Optional: Make the text bold */
}
</style>

<?php get_footer(); ?>
