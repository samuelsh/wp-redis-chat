<?php
/*
Plugin Name: WP Redis Chat
Plugin URI: https://github.com/samuelsh/wp-redis-chat.git
Description: A simple redis chat client plugin
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

        public $username = null;
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

            $userlist="";
            $username = wp_get_current_user()->user_login;
            $users = WP_Plugin_Template::get_all_logged_in_users();

            if(!$username)
                $username = "User".rand(1,1000);

            extract(shortcode_atts(array(
                  'room' => "testroom"
               ), $atts));

            foreach( $users as $user) {

                $userlist .= '<span>' . esc_html( $user->display_name ) . '</span>';

            }

            $room = get_option('global_chat_room');
            $users_iframe = '<iframe width="150" height="500" frameborder="1" src="http://we.kab.tv/users.html?label='.$room.'&link_pattern=http://www.google.com%2Fsearch%3Fq%3DX" scrolling="yes" marginwidth="10px" marginheight="10px"></iframe>';
            return '<div style="display:block;"><div>'.$users_iframe.'<iframe width="310" height="500" frameborder="0" src="http://we.kab.tv/?label='.$room.'&auto_approve=true&static_form=true&from_text='.$username.'&name_text='.$username.'" scrolling="yes" marginwidth="0" marginheight="0"></iframe></div><div>'.$userlist.'</div></div>';
       }


        function set_user_logged_in($user_login, $user) {

            update_user_meta($user->ID, 'logged_in', true);
        }


        function set_user_logged_out() {

            $user = wp_get_current_user();
            update_user_meta($user->ID, 'logged_in', false);
        }


        public static function get_all_logged_in_users() {

            $users_list = get_users( array('meta_query' => array( 'relation', array('meta_key' => 'logged_in', 'meta_value' => 'true'))));
        //$users_list = get_users();


            return $users_list;
        }
    } // END class WP_Plugin_Template
} // END if(!class_exists('WP_Plugin_Template'))

if(class_exists('WP_Plugin_Template'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('WP_Plugin_Template', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_Plugin_Template', 'deactivate'));

    if(!get_option('global_chat_room'))
        add_option('global_chat_room', 'bb_dating_website_chatroom'.rand());
    if(strpos(get_option('global_chat_room'),'bb_dating_website_chatroom') === false )
        update_option('global_chat_room', 'bb_dating_website_chatroom'.rand());

    add_shortcode( 'chat', array('WP_Plugin_Template','show_chat' ));
    add_action('clear_auth_cookie', 'set_user_logged_out', 10);
    add_action('wp_login', 'set_user_logged_in', 10, 2);

    // instantiate the plugin class
	$wp_plugin_template = new WP_Plugin_Template();

}
