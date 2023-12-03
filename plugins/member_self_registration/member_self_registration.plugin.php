<?php
/**
 * Plugin Name: member_self_registration
 * Plugin URI: https://github.com/drajathasan/member_self_registration
 * Description: Plugin for online registration
 * Version: 1.0.0
 * Author: Drajat Hasan
 * Author URI: Drajat Hasan
 * Modify by: Jessie Soliman
 */

// get plugin instance
$plugin = \SLiMS\Plugins::getInstance();

// registering menus
$plugin->registerMenu('membership', 'Online Registration Settings', __DIR__ . '/index.php');
$plugin->registerMenu('opac', 'Daftar Online', __DIR__ . '/daftar_online.inc.php');
