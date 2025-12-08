<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class PopBlocks_Utils {

  protected static $instance;

  public $file = __FILE__;
  // public $plugin;
  
  public $counter = 0;
  public $time;

  public static function get_instance() {
    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof PopBlocks_Utils ) ) {
      self::$instance = new PopBlocks_Utils();
    }
    return self::$instance;
  }

  public function __construct() {
    // $this->plugin = PopBlocks::get_instance();
      
    $this->time = time();
  }

  public static function clean_data( $data ) {
    $processed = [];

    foreach ( $data as $key => $value ) {
      if ( $key != 'id' ) {
        if ( is_array( $value ) ) {
          $processed[ $key ] = self::clean_data( $value );
        } else {
          $processed[ $key ] = $value;
        }
      }
    }

    return $processed;
  }

  public static function hydrate_id() {
    $instance = PopBlocks_Utils::get_instance();

    $instance->counter++;

    return 'pb-' . $instance->time . '-' . $instance->counter;
  }

  public static function hydrate_data( $data ) {
    $processed = [];

    foreach ( $data as $key => $value ) {
      if ( is_array( $value ) ) {
        $processed[ $key ] = self::hydrate_data( $value );

        // if ( ! in_array( $key, [ 'triggers', 'behaviors', 'rules' ] ) ) {
        if ( ! is_string( $key ) || in_array( $key, [ 'options' ] ) ) {
          $processed[ $key ]['id'] = self::hydrate_id( $data );
        }
      } else {
        $processed[ $key ] = $value;
      }
    }

    return $processed;
  }

  public static function parse_args_deep( $args, $defaults ) {
    $new_args = (array) $defaults;

    foreach ( $args as $key => $value ) {
      if ( is_array( $value ) && isset( $new_args[ $key ] ) ) {
        $new_args[ $key ] = self::parse_args_deep( $value, $new_args[ $key ] );
      } else {
        $new_args[ $key ] = $value;
      }
    }

    return $new_args;
  }

}

PopBlocks_Utils::get_instance();