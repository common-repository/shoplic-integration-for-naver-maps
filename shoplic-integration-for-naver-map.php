<?php
/**
 * Plugin Name:       Shoplic Integration for Naver Map
 * Plugin URI:        https://shoplic.kr
 * Description:       A map plugin for WordPress integrating Naver Maps with WordPress, powered by Shoplic. Register many places you want and display them on your Naver maps.
 * Version:           1.0.5
 * Requires PHP:      7.2
 * Author:            Shoplic
 * Author URI:        https://shoplic.kr
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       shoplic-integration-for-naver-map
 * Domain Path:       /languages
 */

/* ABSPATH check */
if ( ! defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

const NM_MAIN    = __FILE__;
const NM_VERSION = '1.0.5';

nm();
