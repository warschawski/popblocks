<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class PopBlocks_Cache {

  protected static $instance;

  public $file = __FILE__;
  public $plugin;
  
  public static $key = 'popblocks_popup_cache';

  public static function get_instance() {
    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof PopBlocks_Cache ) ) {
      self::$instance = new PopBlocks_Cache();
    }
    return self::$instance;
  }

  public function __construct() {
    $this->plugin = PopBlocks::get_instance();

    add_action( 'init', [ $this, 'init' ], 999 );
  }

  //

  public function init() {
    add_action( 'save_post_popblocks-popup', [ $this, 'cache' ], 999 );
  }
  
  // 
  
  public static function get_all( $force = false ) {
    $cached = get_transient( self::$key );
    
    if ( $force || empty( $cached ) ) {
      $cached = [];
      
      $popups = get_posts( [
        'post_type' => 'popblocks-popup'
      ] );
      
      foreach ($popups as $popup) {
        $data = json_decode( get_post_meta( $popup->ID, 'popblocks_data', true ), true );
        
        $cached[] = [
          'ID' => $popup->ID,
          'title' => $popup->title,
          'data' => $data,
        ];
      }
      
      set_transient( self::$key, $cached, DAY_IN_SECONDS * 7 );
    }
    
    return $cached;
  }
  
  // 
  
  public static function cache( $post = false ) {
    self::get_all( true );
  }
  
  public static function flush() {
    delete_transient( self::$key );
  }
  
}

PopBlocks_Cache::get_instance();