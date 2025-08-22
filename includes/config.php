<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class PopBlocks_Config {

  protected static $instance;

  public $file = __FILE__;
  public $plugin;
  
  public $triggers = [];
  public $behaviors = [];
  public $opperators = [];

  public static function get_instance() {
    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof PopBlocks_Config ) ) {
      self::$instance = new PopBlocks_Config();
    }
    return self::$instance;
  }

  public function __construct() {
    $this->plugin = PopBlocks::get_instance();

    add_action( 'init', [ $this, 'init' ], 999 );
  }

  //

  public function init() {
    $this->behaviors = $this->get_json( $this->plugin->get_path() . '/config/behaviors.json' );
    
    $this->opperators = $this->get_json( $this->plugin->get_path() . 'config/opperators.json' );
    
    $this->triggers = $this->get_json( $this->plugin->get_path() . 'config/triggers.json' );
  }
  
  public function get_json( $path = '' ) {
    return json_decode( file_get_contents( $path ), true );
  }
  
  // 
  
  public static function admin_settings() {
    return json_encode( [
      'behaviors' => self::behaviors(),
      'opperators' => self::opperators(),
      'triggers' => self::triggers(),
    ] );
  }
  
  public static function behaviors() {
    $instance = self::get_instance();
    
    return apply_filters( 'popblocks_config_behaviors', $instance->behaviors );
  }
  
  public static function opperators() {
    $instance = self::get_instance();
    
    return apply_filters( 'popblocks_config_opperators', $instance->opperators );
  }
  
  public static function triggers() {
    $instance = self::get_instance();
    
    return apply_filters( 'popblocks_config_triggers', $instance->triggers );
  }
  
  
}

PopBlocks_Config::get_instance();