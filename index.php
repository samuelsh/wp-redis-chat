<?php
/*
Plugin Name: WP Plugin Template
Plugin URI: https://github.com/fyaconiello/wp_plugin_template
Description: A simple wordpress plugin template
Version: 1.0
Author: Francis Yaconiello
Author URI: http://www.yaconiello.com
License: GPL2
*/
/*
Copyright 2012  Francis Yaconiello  (email : francis@yaconiello.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!class_exists('WP_Plugin_Template'))
{
	class WP_Plugin_Template
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// Initialize Settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$WP_Plugin_Template_Settings = new WP_Plugin_Template_Settings();

			// Register custom post types
			require_once(sprintf("%s/post-types/post_type_template.php", dirname(__FILE__)));
			$Post_Type_Template = new Post_Type_Template();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate

		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=wp_plugin_template">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}

        // Shortcodes
        function show_chat() {
            return '
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <meta name="viewport" content="width=device-width">
  <!-- load JQuery -->
<script type="text/javascript" src="'.plugins_url("lib/jquery-1.8.3.min.js",__FILE__).'"></script>
  <script type="text/javascript" src="'.plugins_url("lib/jquery.ba-bbq.min.js",__FILE__).'"></script>
  <script type="text/javascript" src="'.plugins_url("lib/jquery-ui.js",__FILE__).'"></script>
  <script type="text/javascript" src="'.plugins_url("lib/jquery-url.js",__file__).'"></script>
  <link rel="stylesheet" type="text/css" href="'.plugins_url("lib/jquery-ui.css",__FILE__).'">
  
  <!-- Fix $.ajax for IE8,9 -->
  <script type="text/javascript" src=".'.plugins_url("lib/xdomainrequest.js",__FILE__).'"></script>

  <!-- Fix placeholders for several Browsers -->
  <script type="text/javascript" src="'.plugins_url("lib/jquery.placeholder.js",__FILE__).'"></script>
  
  <!-- Application scripts -->
  <script src="'.plugins_url("js/langs.js",__FILE__).'"></script>
  <script src="'.plugins_url("js/conf.js",__FILE__).'"></script>
  <script src="'.plugins_url("js/app.js",__FILE__).'"></script>
  <script src="'.plugins_url("js/chat_app.js",__FILE__).'"></script>
  <script src="'.plugins_url("js/plugins.js",__file__).'"></script>
  <!-- Emotions -->
  <link href="'.plugins_url("lib/kof-emoticons/emoticons.css",__FILE__).'" rel="stylesheet" type="text/css"/>
  <script type="text/javascript" src="'.plugins_url("lib/kof-emoticons/emoticons.js",__FILE__).'"></script>

  <link rel="stylesheet" type="text/css" href="'.plugins_url("style.css",__FILE__).'">
  <script>window["_GOOG_TRANS_EXT_VER"] = "1";</script>
</head>
<body dir="ltr">
  <div class="btns">
    <button class="btn " id="askBtn" data-tr="askBtn" data-tr-place-tag="span">***</button>
    <button class="btn " id="helpBtn" data-tr="helpBtn" data-tr-place-tag="span">:) ?</button>
  </div>
  <div id="askForm">
    <input type="text" placeholder="***" id="name" class="ui-widget ui-corner-all ui-button-text-only"  data-tr="nameInput" data-tr-place-attr="placeholder" />
    <input type="text" placeholder="***" id="from" class="ui-widget ui-corner-all ui-button-text-only" data-tr="fromInput" data-tr-place-attr="placeholder"/>
    <textarea placeholder="***" id="message" class="ui-widget ui-corner-all ui-button-text-only" data-tr="messageInput" data-tr-place-attr="placeholder"></textarea>
    <div class="btns">
      <button class="btn sendBtn" data-tr="sendBtn" data-tr-place-tag="span">***</button>
      <button class="btn cancelBtn" data-tr="cancelBtn" data-tr-place-tag="span">***</button>
    </div>
  </div>
  <div id="questionsList">
  </div>
  <div id="helpDialog" title="Help"></div> 
</body>
</html>';
     }


	} // END class WP_Plugin_Template
} // END if(!class_exists('WP_Plugin_Template'))

if(class_exists('WP_Plugin_Template'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('WP_Plugin_Template', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_Plugin_Template', 'deactivate'));

    add_shortcode( 'chat', array('WP_Plugin_Template','show_chat' ));

    // instantiate the plugin class
	$wp_plugin_template = new WP_Plugin_Template();

}
