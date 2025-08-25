<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class PopBlocks_Display {

  protected static $instance;

  public $file = __FILE__;
  public $plugin;
  
  public $triggers = [];
  public $behaviors = [];
  public $opperators = [];

  public static function get_instance() {
    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof PopBlocks_Display ) ) {
      self::$instance = new PopBlocks_Display();
    }
    return self::$instance;
  }

  public function __construct() {
    $this->plugin = PopBlocks::get_instance();

    add_action( 'init', [ $this, 'init' ], 999 );
  }

  //

  public function init() {
    
    add_action( 'wp_footer', [ $this, 'footer' ] );
    
  }

  // 
  
  public function footer() {
    
    // check front end cache
    
    $all = PopBlocks_Cache::get_all();
    
    // 
    
    wp_enqueue_style( 'popblocks-popup', $this->plugin->get_url() . 'assets/css/popup.css', [], $this->plugin->version, 'all' );

    wp_enqueue_script( 'popblocks-popup', $this->plugin->get_url() . 'assets/js/popup.js', [ 'jquery' ], $this->plugin->version, true );
    
    // 
    
    // determine popups to show + draw
    
    foreach ( $all as $popup ) {
        // print_r($popup);
        
        $this->render( $popup );
    }
    
  } 
  
  public function render( $popup ) {
    // $popup = get_post( $popup ); 
    global $post;
    
    $post = get_post( $popup['ID'] );
    setup_postdata( $post );
    
     // Customizable via hooks
    $path = apply_filters( 'popblocks_popup_template_path', $this->plugin->get_path() . 'templates/popup-content.php', $popup );
    $class = apply_filters( 'popblocks_popup_template_class', '', $popup );

    // include apply_filters( 'popblocks_popup_template_path', $path, $popup );
    include $this->plugin->get_path() . 'templates/popup-base.php';
    
    wp_reset_postdata();
  }
  
}

PopBlocks_Display::get_instance();