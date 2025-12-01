<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class PopBlocks_Post_Types {

  protected static $instance;

  public $file = __FILE__;
  public $plugin;

  public static function get_instance() {
    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof PopBlocks_Post_Types ) ) {
      self::$instance = new PopBlocks_Post_Types();
    }
    return self::$instance;
  }

  public function __construct() {
    $this->plugin = PopBlocks::get_instance();

    add_action( 'init', [ $this, 'init' ], 999 );
    
    add_filter( 'allowed_block_types', [ $this, 'allowed_block_types' ], 10, 2 );
  }

  //

  public function init() {
    register_post_type( 'popblocks-popup', [
      'labels' => [
          'menu_name' => 'Popups',
          'all_items' => 'All Popups',
          'name' => 'Popups',
          'singular_name' => 'Popup',
          'add_new' => 'Add Popup',
          'add_new_item' => 'Add New Popup',
          'edit_item' => 'Edit Popup',
      ],
      'description' => '',
      //'menu_icon' => 'dashicons-admin-comments',
      'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(%23clip0_771_2373)"><path d="M12.5844 1.12417C12.6863 1.00585 12.8533 0.967229 12.9965 1.02944C13.1395 1.09162 13.2252 1.23961 13.2084 1.39467C13.0713 2.6504 13.85 3.99427 15.1293 5.0021C16.4013 6.00404 18.0847 6.60255 19.602 6.42397C19.7685 6.40452 19.9265 6.50363 19.9809 6.66225C20.0352 6.82097 19.9712 6.99646 19.8275 7.08315C17.1064 8.72453 16.9238 11.9953 19.0678 13.6193C19.1999 13.7197 19.2453 13.8986 19.1772 14.0499C19.1089 14.2011 18.9453 14.2854 18.7826 14.2531C17.4104 13.9785 16.1367 14.1691 15.2191 14.7609C14.3153 15.3438 13.7144 16.3413 13.7143 17.7882C13.7142 17.9434 13.6141 18.081 13.4662 18.1281C13.3184 18.1751 13.1574 18.1209 13.0678 17.9943C12.1501 16.697 10.5209 15.8058 8.81485 15.4826C7.10385 15.1585 5.40349 15.4213 4.32657 16.3136C4.20073 16.4177 4.01977 16.4229 3.88809 16.3263C3.75634 16.2293 3.70777 16.0548 3.76993 15.9035C4.74994 13.5197 3.42393 9.93931 0.262114 9.0646L0.258208 9.06362C0.0997966 9.01796 -0.00736357 8.86925 0.000395377 8.70425C0.00838605 8.53807 0.130525 8.39934 0.294341 8.37026C1.88621 8.08832 2.995 7.22786 3.54532 6.10268C4.09675 4.97479 4.1094 3.53572 3.41739 2.06069C3.34827 1.91338 3.38761 1.73847 3.51309 1.63491C3.63861 1.53143 3.81816 1.52545 3.94961 1.62124C6.84002 3.73136 10.7781 3.22158 12.5844 1.12417Z" fill="%239CA2A7"/></g><defs><clipPath id="clip0_771_2373"><rect width="20" height="20" fill="white"/></clipPath></defs></svg>'),
      'menu_position' => 50,
      'public' => false,
      'show_ui' => true,
      'has_archive' => false,
      'show_in_menu' => true,
      'show_in_nav_menus' => false,
      'exclude_from_search' => true,
      'publicly_queryable' => false,
      'capability_type' => 'post',
      'capabilities' => [],
      'supports' => [ 'title', 'editor', 'revisions' ],
      'map_meta_cap' => true,
      'hierarchical' => false,
      'query_var' => true,
      'show_in_rest' => true,
    ] );
  }
  
  function allowed_block_types( $allowed_blocks, $post ) {
    if ( $post->post_type == 'popblocks-popup' ) {
      $allowed_blocks =[
        'core/image',
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/group'
      ];
      
      $allowed_blocks = apply_filters( 'popblocks_allowed_block_types', $allowed_blocks, $post );
    }
    
    return $allowed_blocks;
  }

}

PopBlocks_Post_Types::get_instance();
