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
    
    add_action( 'wp_footer', [ $this, 'footer' ], 5 );
  }

  // 
  
  public function setup() {
    $all = PopBlocks_Cache::get_all();
    
    foreach ( $all as $popup ) {
      $behaviors = $this->filterBehaviors( $popup['data']['behaviors'] );
      
      if ( ! empty( $behaviors ) ) {
        $popup['data']['behaviors'] = $behaviors;
        
        $this->active[] = $popup;
      }
    }
    
    if ( count( $this->active ) > 0 ) {
      wp_enqueue_style( 'popblocks-popup', $this->plugin->get_url() . 'assets/css/popup.css', [], $this->plugin->version, 'all' );

      wp_enqueue_script( 'popblocks-popup', $this->plugin->get_url() . 'assets/js/popup.js', [ 'jquery' ], $this->plugin->version, true );
    }
  }
  
  public function footer() {
    ob_start();
    
    foreach ( $this->active as $popup ) {
      $this->render( $popup );
    }
    
    $content = ob_get_clean();
    
    include $this->plugin->get_path() . 'templates/popups.php';
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
  //  CHECK IN HERE BEFORE THE COOKIE? 
  // Need to rework to pass back a 'filtered' copy of the groups contain only the groups that pass server checks

  public function filterBehaviors( $groups ) {
    $filteredGroups = [];
    
    foreach ( $groups as $group ) {
      $group_pass = true;

      foreach ( $group['rules'] as $rule ) {
        print_r($rule);
        $rule_pass = false;

        // All
        if ( $rule['type'] == 'all' ) {
          $rule_pass = true;
        }
        
        // Post Object
        if ( $rule['type'] == 'post' ) {
          if ( $rule['operator'] == 'equals' ) {
            $rule_pass = ( get_the_ID() == $rule['value'] );
          } else {
            $rule_pass = ( get_the_ID() != $rule['value'] );
          }
        }
        
        // Post Type
        if ( $rule['type'] == 'post_type' ) {
          if ( $rule['operator'] == 'equals' ) {
            $rule_pass = ( is_singular( $rule['value'] ) || is_post_type_archive( $rule['value'] ) );
          } else {
            $rule_pass = ( ! is_singular( $rule['value'] ) && ! is_post_type_archive( $rule['value'] ) );
          }
        }
        
        // Taxonomy Term
        if ( $rule['type'] == 'category' ) { // 'taxonomy_term'
          $term = get_term( $rule['value'] );
          
          if ( ! empty( $term ) ) {
            if ( $rule['operator'] == 'equals' ) {
              $rule_pass = has_term( $rule['value'], $term->taxonomy );
            } else {
              $rule_pass = ( ! has_term( $rule['value'], $term->taxonomy ) );
            }
          }
        }
        
        // URL 
        // Needs to be moved to front end, cant repliably check url on backend
        if ( $rule['type'] == 'url' ) {
          // $domain = $_SERVER['HTTP_HOST'];
          // $path = $_SERVER['SCRIPT_NAME'];
          // $qs = $_SERVER['QUERY_STRING'];
          
          // $url = 'http://' . $domain . $path . "?" . $queryString;
          
          // if ( $rule['operator'] == 'contains' ) {
          //   $rule_pass = ( strpos($url, $rule['value']) !== false );
          // } else {
          //   $rule_pass = ( ! strpos($url, $rule['value']) === false );
          // }

          $rule_pass = true;
        }

        // Browser Language
        if ( $rule['type'] == 'browser_language' ) {
          // if (isset($_COOKIE['user-lang'])) {
          //   $user_lang = $_COOKIE['user-lang'];
          //   $user_lang = stripslashes($user_lang);
          //   $user_lang = trim($user_lang, '"');

          //   if ( $rule['operator'] == 'equals' ) {
          //     $group_pass = ( $user_lang == $rule['value'] );
          //   } else {
          //     $group_pass = ( $user_lang != $rule['value'] );
          //   }
          // }

          $rule_pass = true;
        }
        
        // Browser Location
        // if ( $rule['type'] == 'browser_location' ) {
        //   if (isset($_COOKIE['user-geo'])) {
        //     $user_location = $_COOKIE['user-geo'];

        //     var_dump($user_location);

        //     if ( $rule['operator'] == 'equals' ) {
        //       $rule_pass = ( $user_location == $rule['value'] );
        //     } else {
        //       $rule_pass = ( $user_location != $rule['value'] );
        //     }
        //   }
        // }

        if ( ! $rule_pass ) {
          $group_pass = false;
          break;
        }
        
      }
      
      if ( $group_pass ) {
        $filteredGroups[] = $group;
      }
    }

    //print_r($filteredGroups);
    
    return $filteredGroups;
  }

}

PopBlocks_Display::get_instance();