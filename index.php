<?php
/*
Plugin Name: WP Redis Chat
Plugin URI: https://github.com/samuelsh/wp-redis-chat.git
Description: A simple redis chat plugin
Version: 1.0
Author: Dogen
Author URI: https://github.com/samuelsh
License: GPL2
*/
/*
Copyright 2014  Dogen  (email : samuel.sh79@gmail.com)

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
        function show_chat($atts, $content=null) {

            extract(shortcode_atts(array(
                  'room' => "testroom"
               ), $atts));

            $room = get_option('global_chat_room');
            return '<div style="display:block;"><iframe width="310" height="500" frameborder="0" src="http://we.kab.tv/?label='.$room.'&auto_approve=true&static_form=true" scrolling="yes" marginwidth="0" marginheight="0"></iframe></div>';
       }


	} // END class WP_Plugin_Template
} // END if(!class_exists('WP_Plugin_Template'))

if(class_exists('WP_Plugin_Template'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('WP_Plugin_Template', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_Plugin_Template', 'deactivate'));

    if(!get_option('global_chat_room'))
        add_option('global_chat_room', 'chatroom'.rand());

    add_shortcode( 'chat', array('WP_Plugin_Template','show_chat' ));

    // instantiate the plugin class
	$wp_plugin_template = new WP_Plugin_Template();

}
