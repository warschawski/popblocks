<?php 

// print_r( $post );
// print_r( $popup );

$modal_options = apply_filters( 'popblock_popup_options', [
  // 'customClass' => 'popblocks-modal',
] );

?>
<a href="#popblocks-<?php echo $post->ID; ?>" class="popblocks-trigger"
  data-popblocks-id="<?php echo $post->ID; ?>"
  data-popblocks-options="<?php echo htmlentities( json_encode( $modal_options ) ); ?>"
  data-popblocks-data="<?php echo htmlentities( json_encode( $popup['data'] ?? [] ) ); ?>"
>
  Open Popup <?php echo get_the_title( $post->ID ); ?>
</a>
<div id="popblocks-<?php echo $post->ID; ?>" class="popblocks-popup-container">
  <div class="popblocks-popup <?php echo $class; ?>">
    <?php include $path; ?>
  </div>
</div>