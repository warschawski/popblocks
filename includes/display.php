<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class PopBlocks_Display {

  protected static $instance;

  public $file = __FILE__;
  public $plugin;
  
  public $active = [];

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
    add_action( 'template_redirect', [ $this, 'setup' ], 999 );
    
    add_action( 'wp_footer', [ $this, 'footer' ], 999 );
  }

  // 
  
  public function setup() {
    $all = PopBlocks_Cache::get_all();
    
    foreach ( $all as $popup ) {
      $render = $this->checkBehavior( $popup['data']['behaviorGroups'] );
      
      if ( $render ) {
        $this->active[] = $popup;
      }
    }
    
    if ( count( $this->active ) > 0 ) {
      wp_enqueue_style( 'popblocks-popup', $this->plugin->get_url() . 'assets/css/popup.css', [], $this->plugin->version, 'all' );

      wp_enqueue_script( 'popblocks-popup', $this->plugin->get_url() . 'assets/js/popup.js', [ 'jquery' ], $this->plugin->version, true );
    }
  }
  
  public function footer() {
    foreach ( $this->active as $popup ) {
      $this->render( $popup );
    }
  } 
  
  public function render( $popup ) {
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
  
  // 
  
  public function checkBehavior( $groups ) {
    $render = false;
    
    foreach ( $groups as $group ) {
      $pass = false;
      
      foreach ( $group['rules'] as $rule ) {
        
        if ( $rule['type'] == 'post' ) {
          $pass = get_the_ID() == $rule['value'];
        }
        
        if ( $rule['type'] == 'post_type' ) {
          $pass = ( is_singular( $rule['value'] ) || is_post_type_archive( $rule['value'] ) );
        }
      }
      
      if ( $pass ) {
        $render = true;
      }
    }
    
    return $render;
  }
  
}

PopBlocks_Display::get_instance();