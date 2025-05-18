<?php
if (empty($post_id)) return;

$title   = get_the_title($post_id);
$excerpt = get_the_excerpt($post_id);
$votes   = get_post_meta($post_id, '_tc_votes', true);
?>

<div class="submission tc-submission-card" data-id="<?php echo esc_attr($post_id); ?>">
    <div class="tc-submission-thumb">
    <?php 
    if ( has_post_thumbnail( $post_id ) ) {
        $image_url = get_the_post_thumbnail_url( $post_id, 'medium' );
        ?>
        <img src="<?php echo esc_url( $image_url ); ?>" class="" alt="">
        <?php
    } else {
        // fallback if no featured image
        ?><img class="" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/download.jpg'); ?>" alt="No image" />
        <?php
    }
    
    ?>
    </div>
    <h3><?php echo esc_html($title); ?></h3>
    <div><?php echo wp_kses_post($excerpt); ?></div>
    <p><strong>Votes:</strong> <?php echo esc_html($votes); ?></p>
</div>
