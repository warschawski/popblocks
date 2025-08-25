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
        'labels'              => [
            'menu_name'         => 'Popups',
            'all_items'         => 'All Popups',
            'name'              => 'Popups',
            'singular_name'     => 'Popup',
            'add_new'           => 'Add Popup',
            'add_new_item'      => 'Add New Popup',
            'edit_item'         => 'Edit Popup',
        ],
        'description'         => '',
        'menu_icon'           => 'dashicons-admin-comments',
        'menu_position'       => 50,
        'public'              => false,
        'show_ui'             => true,
        'has_archive'         => false,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'capabilities'        => [],
        'supports'            => [ 'title', 'editor', 'revisions' ],
        'map_meta_cap'        => true,
        'hierarchical'        => false,
        'query_var'           => true,
        'show_in_rest'        => true,
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
    }

    return apply_filters( 'popblocks_allowed_block_types', $allowed_blocks, $post );
  }

}

PopBlocks_Post_Types::get_instance();
