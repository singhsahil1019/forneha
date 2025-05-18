<?php
$post = get_post($post_id);
if (!$post) return;

$title   = get_the_title($post_id);
$excerpt = get_the_excerpt($post_id);
$total_votes = get_post_meta($post_id, '_tc_votes', true);

?>
<div class="tc-popup-content" data-id="<?php echo esc_attr($post_id); ?>">
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
    <h2><?php echo esc_html($title); ?></h2>
    <p><?php echo esc_html($excerpt); ?></p>
    <p><strong>Votes:</strong> <?php echo esc_html($total_votes); ?></p>

    <div class="tc-popup-nav">
        <?php if (!empty($prev_id)): ?>
            <button class="tc-popup-prev" data-id="<?php echo esc_attr($prev_id); ?>">← Prev</button>
        <?php endif; ?>
        <?php if (!empty($next_id)): ?>
            <button class="tc-popup-next" data-id="<?php echo esc_attr($next_id); ?>">Next →</button>
        <?php endif; ?>
    </div>
</div>
