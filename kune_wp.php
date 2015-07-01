<?php
/**
 * Plugin Name: Kune in Wordpress
 * Plugin URI:  http://kune.ourproject.org/kune-wp/
 * Description: This is a plugin to embed docs from kune.cc (and similar nodes) in Wordpress pages. Also will allow to use other kune functionalities in Wordpress.
 * Version:     0.1.4
 * Author:      Vicente J. Ruiz Jurado, from Comunes Collective
 * Author URI:  http://comunes.org/
 * License:     GPLv3+
 * Text Domain: kune_wp_plugin
 * Domain Path: /languages
 */

/**
 *
 * Copyright (C) 2007-2014 Licensed to the Comunes Association (CA) under
 * one or more contributor license agreements.
 *
 * The CA licenses this file to you under the GNU General Public
 * License version 3, (the "License"); you may not use this file except in
 * compliance with the License. This file is part of kune.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * Built using grunt-wp-plugin
 * Copyright (c) 2013 10up, LLC
 * https://github.com/10up/grunt-wp-plugin
 */

// Useful global constants
define( 'KUNE_WP_PLUGIN_VERSION', '0.1.4' );
define( 'KUNE_WP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'KUNE_WP_PLUGIN_PATH', dirname( __FILE__ ) . '/' );

define( 'KUNE_DOMAIN', 'kune_wp_plugin');

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */
function kune_wp_plugin_init() {
  $locale = apply_filters( 'plugin_locale', get_locale(), 'kune_wp_plugin' );
  load_textdomain( KUNE_DOMAIN, WP_LANG_DIR . '/kune_wp_plugin/kune_wp_plugin-' . $locale . '.mo' );
  load_plugin_textdomain( KUNE_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * Activate the plugin
 */
function kune_wp_plugin_activate() {
  // First load the init scripts in case any rewrite functionality is being loaded
  kune_wp_plugin_init();

  flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'kune_wp_plugin_activate' );

/**
 * Deactivate the plugin
 * Uninstall routines should be in uninstall.php
 */
function kune_wp_plugin_deactivate() {
}
register_deactivation_hook( __FILE__, 'kune_wp_plugin_deactivate' );

/* Set the admin options page */
if ( is_admin() ) {
  /* add_action('admin_menu', 'kune_menu');
     add_action('admin_init', 'register_kunesettings' ); */
}

function register_kune_settings() { // whitelist options
  /* register_setting( 'kune-option-group', 'new_option_name' );
  register_setting( 'kune-option-group', 'some_other_option' );
  register_setting( 'kune-option-group', 'option_etc' ); */
}

include "includes/KuneUrl.php";

add_option("kune_readOnly", false);
add_option("kune_sitebarRightMargin", 200);
add_option("kune_sitebarTopMargin", 0);
add_option("kune_signInText", "Participate");
add_option("kune_showSignOut", true);
add_option("kune_showSignIn", true);

function kune_menu(){
  /* add_options_page('Kune Setup',"Kune",'manage_options','kune','kune_options'); */
}


if ( is_admin() ) {
  /* include("options_admin_page.php"); */
}

$global_docs_count = 0;
$cache_param = '?v='.time();

/**
 * Function that embed the kune document in the fronted shortcode "kune"
 */
function embebKune($atts){
  global $global_docs_count;

  $default_kune_readOnly = get_option("kune_readOnly");
  $default_kune_sitebarRightMargin = get_option("kune_sitebarRightMargin");
  $default_kune_sitebarTopMargin = get_option("kune_sitebarTopMargin");
  $default_kune_signInText = get_option("kune_signInText");
  $default_kune_showSignOut = get_option("kune_showSignOut");
  $default_kune_showSignIn = get_option("kune_showSignIn");

  $a = shortcode_atts(array(
    'read_only' => $default_kune_readOnly,
    'sitebar_right_margin' => $default_kune_sitebarRightMargin,
    'sitebar_top_margin' => $default_kune_sitebarTopMargin,
    'sign_in_text' => $default_kune_signInText,
    'show_sign_out' => $default_kune_showSignOut,
    'show_sign_in' => $default_kune_showSignIn,
    'width' => "auto",
    'height' => "auto",
    'url' =>null
  ),$atts,'kune');

  // https://wordpress.stackexchange.com/questions/119294/pass-boolean-value-in-shortcode
  //$a['read_only'] = false; // filter_var( $a['read_only'], FILTER_VALIDATE_BOOLEAN );
  //$a['show_sign_in'] = filter_var( $a['show_sign_in'], FILTER_VALIDATE_BOOLEAN );
  //$a['show_sign_out'] = filter_var( $a['show_sign_out'], FILTER_VALIDATE_BOOLEAN );

  $global_docs_count++;

  $kuneUrl = new KuneUrl($a['url']);

  if (!$kuneUrl->isValid()) {
    return "<div>"._e("Wrong kune url: it should be something like 'http://kune.cc/#!somegroup.docs.1.2'", KUNE_DOMAIN)."</div>";
  }

  if ($global_docs_count > 1) {
    return "<div>"._e("Sorry, right now we only can embed one kune document per page/post", KUNE_DOMAIN)."</div>";
  }

  $server = $kuneUrl->getServer();
  $hash = $kuneUrl->getHash();

  $embedConf = '"serverUrl":"'.$server.'",
        "readOnly":'.$a['read_only'].',
        "sitebarRightMargin":"'.$a['sitebar_right_margin'].'",
        "sitebarTopMargin":"'.$a['sitebar_top_margin'].'",
        "signInText":"'.$a['sign_in_text'].'",
        "showSignOut":'.$a['show_sign_out'].',
        "showSignIn":'.$a['show_sign_in'];

  $embScript = '<script>
    var kuneEmbedConf = {'.$embedConf.'};

    // http://stackoverflow.com/questions/8586446/dynamically-load-external-javascript-file-and-wait-for-it-to-load-without-usi
    function loadJS(src, callback) {
        var s = document.createElement(\'script\');
        s.src = src;
        s.async = true;
        s.onreadystatechange = s.onload = function() {
            var state = s.readyState;
            if (!callback.done && (!state || /loaded|complete/.test(state))) {
                callback.done = true;
                callback();
            }
        };
        document.getElementsByTagName(\'head\')[0].appendChild(s);
    }

    function loadLink(src) {
        var s = document.createElement(\'link\');
        s.setAttribute("rel", "import");
        s.setAttribute("href", src);
        document.getElementsByTagName(\'head\')[0].appendChild(s);
    }
    </script>';

  # we load the kune styles & js only if shortcode is used

  if ($global_docs_count == 1) {

    $styles = ['wse/ws.css', 'wse/kune-common.css', 'wse/kune-custom-common.css', 'wse/kune-message.css', 'others/splash/style/permalink.css', 'others/splash/style/stuff.css'];

    $jss = [ 'wse/wse.nocache.js'];

    foreach($jss as $js) {
      $embScript.= '<script type="text/javascript">loadJS(\''.$server.$js.'\', function() {});</script>';
    }

    foreach($styles as $st) {
      $embScript.= '<link rel="stylesheet" href="'.$server.$st.$cache_param.'" type="text/css" media="all">';
    }

  }

  $embScript.= '
         <paper-fab icon="create" id="edit_inbox_fab" style="display: none;" mini class="btn_green"></paper-fab>
         <paper-fab icon="reply" id="reply_inbox_fab" style="display: none;" mini class="btn_green"></paper-fab>
         <paper-fab icon="check" id="editdone_inbox_fab" style="display: none;" mini class="btn_green"></paper-fab>
         <paper-fab icon="create" id="edit_docgroup_fab" style="visibility: hidden; display: none;" mini class="btn_green"></paper-fab>
         <paper-fab icon="reply" id="reply_docgroup_fab" style="visibility: hidden; display: none;" mini class="btn_green"></paper-fab>
         <paper-fab icon="check" id="editdone_docgroup_fab" style="visibility: hidden; display: none;" mini class="btn_green"></paper-fab>
         <div id="kune-embed-hook" class="spin" style="margin:0px 0px 100px 0px;"></div>
         <!-- <div id="kune-embed-hook-\'.$global_docs_count.\'" style="margin:0px 0px 50px 0px;"></div> -->
         <script type="text/javascript">
         loadJS(\''.KUNE_WP_PLUGIN_URL.'/assets/js/kune_wp.js\', function() {
            add_kune_doc("'.$hash.'", "kune-embed-hook-'.$global_docs_count.'",{';

  $embScript .= $embedConf;
  $embScript .='    })});
            jQuery(function($) { $("#kune-embed-hook").removeClass("spin"); });
         </script>';
  return $embScript;
}

/**
 * Kune scripts loaded in frontend
 */
function kuneScriptsAction() {
}

function add_kune_mce_button() {
  if (current_user_can('edit_posts') || current_user_can('edit_pages')) {
    add_filter("mce_external_plugins", "add_kune_tinymce_plugin");
    add_filter('mce_buttons', 'register_kune_button');
  }
}

function register_kune_button($buttons) {
  array_push($buttons, "separator", "kunebutton");
  // array_push($buttons, "separator", "kunebutton_dev");
  return $buttons;
}

// https://codex.wordpress.org/TinyMCE_Custom_Buttons
// Load the TinyMCE plugin : editor_plugin.js
function add_kune_tinymce_plugin($plugin_array) {
  $plugin_array['kunebutton'] = plugins_url('/assets/js/editor_plugin.js?ye2',__FILE__);
  return $plugin_array;
}

wp_enqueue_style('kune-wp-general', KUNE_WP_PLUGIN_URL.'assets/css/kune_wp.min.css'.$cache_param);

// Wireup actions
add_action( 'init', 'kune_wp_plugin_init' );
add_action( 'wp_enqueue_scripts', 'kuneScriptsAction');

// Wireup filters

// Wireup shortcodes

add_shortcode('kune','embebKune');
add_action('init', 'add_kune_mce_button');

add_action( 'wp_ajax_plugin_slug_insert_dialog', 'plugin_slug_insert_gistpen_dialog' );

function plugin_slug_insert_gistpen_dialog() {
  die(include 'form.php');
}

if (!is_admin()) {
  wp_enqueue_script( 'polymer-webcomponentsjs', plugin_dir_url( __FILE__ ) . '/includes/bower_components/webcomponentsjs/webcomponents.min.js', array() );

  add_action('wp_head', 'add_link_in_head');
  function add_link_in_head() {
?><link rel="import" href="<?php echo KUNE_WP_PLUGIN_URL; ?>/includes/bower_components/polymer/polymer.html">
    <link rel="import" href="<?php echo KUNE_WP_PLUGIN_URL; ?>/includes/bower_components/paper-fab/paper-fab.html">
    <link rel="import" href="<?php echo KUNE_WP_PLUGIN_URL; ?>/includes/bower_components/core-icons/core-icons.html"><?php
  }
}
