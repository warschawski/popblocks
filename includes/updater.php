<?php

require_once 'vendor/pw-updater.php';

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class PopBlocks_Updater extends PW_GitHub_Updater {

  public $username = 'benplum';
  public $repository = 'Gravity-Forms-Lead-Source-Tracker';
  public $requires = '5.0';
  public $tested = '5.0.2';

  public function __construct() {
    $this->parent = PopBlocks::get_instance();

    parent::__construct();
  }

}

// Instance

PopBlocks_Updater::get_instance();
