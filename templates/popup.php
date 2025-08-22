<?php 

// print_r( $post );
// print_r( $popup );

?>
<div id="popblocks-<?php echo $post->ID; ?>">
    <?php echo get_the_title(); ?>
</div>
<a href="#popblocks-<?php echo $post->ID; ?>" class="popblocks-popup">
    Open Popup
</a>