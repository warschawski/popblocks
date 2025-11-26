<?php

require_once 'vendor/pw-updater.php';

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class PopBlocks_Updater extends PW_GitHub_Updater {

  public $username = 'warschawski';
  public $repository = 'popblocks';
  public $requires = '6.0';
  public $tested = '6.8.3';

  public function __construct() {
    $this->parent = PopBlocks::get_instance();

    parent::__construct();
  }

}

// Instance

PopBlocks_Updater::get_instance();
