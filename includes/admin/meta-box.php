<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class PopBlocks_Meta_Box {

  protected static $instance;

  public $file = __FILE__;
  public $plugin;

  public static function get_instance() {
    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof PopBlocks_Meta_Box ) ) {
      self::$instance = new PopBlocks_Meta_Box();
    }
    return self::$instance;
  }

  public function __construct() {
    $this->plugin = PopBlocks::get_instance();

    add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
    
    add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
    
    add_action( 'save_post_popblocks-popup', [ $this, 'save_post' ] );
  }

  //

  public function admin_enqueue_scripts( $hook_suffix ) {
    if ( in_array( $hook_suffix, [ 'post.php', 'post-new.php' ] ) ) {
      $screen = get_current_screen();

      if ( is_object( $screen ) && $screen->post_type == 'popblocks-popup' ) {
        wp_enqueue_style( 'wp-components' );
        // https://inspir.test/wp-admin/load-styles.php?c=1&dir=ltr&load%5Bchunk_0%5D=wp-components&ver=6.8.3
        
        // wp_register_style( 'popblocks-admin', $this->plugin->get_url() . 'assets/css/admin.css', '',  $this->plugin->version );
        // wp_enqueue_style( 'popblocks-admin' );

        wp_register_script( 'popblocks-admin', $this->plugin->get_url() . 'assets/js/admin.js', '', $this->plugin->version, true );
        wp_enqueue_script( 'popblocks-admin' );
      }
    }
  }
  
  function add_meta_box() {
    add_meta_box( 'popblocks_popup_meta_box', 'Popup Settings', [ $this, 'draw_meta_box' ], 'popblocks-popup' );
  }
  
  function draw_meta_box( $post ) {
    $popup_data = get_post_meta( $post->ID, 'popblocks_data', true );
    $popup_json = json_decode( $popup_data, true );
    
    wp_nonce_field( 'popblocks_meta_box_nonce', 'popblocks_meta_box_nonce' );

    include $this->plugin->get_path() . 'includes/admin/templates/meta-box.php';
  }
  
  function save_post( $post_id ) {
    $data = sanitize_text_field( $_POST['popblocks_data'] ?? '' );
    
    update_post_meta( $post_id, 'popblocks_data', $data );
    
    PopBlocks_Cache::cache( true );
  }
  
}

PopBlocks_Meta_Box::get_instance();