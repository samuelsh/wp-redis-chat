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
			//require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			//$WP_Plugin_Template_Settings = new WP_Plugin_Template_Settings();

			// Register custom post types
			//require_once(sprintf("%s/post-types/post_type_template.php", dirname(__FILE__)));
			//$Post_Type_Template = new Post_Type_Template();

			$plugin = plugin_basename(__FILE__);
			//add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
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

            $user_link = urlencode(/*get_site_url()*/"http://2bb1.net/%D7%97%D7%93%D7%A8-%D7%A6%D7%98-%D7%A4%D7%A8%D7%98%D7%99/?label=LABEL");
            
            extract(shortcode_atts(array(
                  'room' => "testroom"
               ), $atts));
/*
            foreach( $users as $user) {

                $userlist .= '<span>' . esc_html( $user->display_name ) . '<br/></span>';

            }
 */
            $room = get_option('global_chat_room');
            $users_iframe = '<iframe id="users" width="150" height="500" frameborder="1" src="http://we.kab.tv/users.html?label='.$room.
                '&auto_approve=true&from_text='.$username.'&link_pattern='.$user_link.
                '&css=http://2bb1.net/wp-content/themes/lovestory/nedchat16.10/style/chatstyle.css
                " scrolling="yes" marginwidth="10px" marginheight="10px"></iframe>';
            return 	'<div style="display:block;"><div>'.get_avatar(wp_get_current_user()->ID,180).'</div><div>'.$users_iframe.
            		'<iframe id="chat" width="310" height="500" frameborder="0" src="http://we.kab.tv/?label='.$room.
            		'&lang=he&auto_approve=true&static_form=true&from_text='.$username.'&name_text='.$username.
                    '&css=http://2bb1.net/wp-content/themes/lovestory/nedchat16.10/style/chatstyle.css
                    " scrolling="yes" marginwidth="0" marginheight="0"></iframe></div><div>'.$userlist.  '</div></div>'; } 
        function set_user_logged_in($user_login, $user) {

            if(get_user_meta($user->ID, "logged_in", true) !== "true")
                if(!update_user_meta($user->ID, 'logged_in', 'true'))
                    wp_die("Failed to add usermeta ", "Fatal");
        }


        function set_user_logged_out() {

            $user = wp_get_current_user();
            if(get_user_meta($user->ID, "logged_in", true) !== "false")
                if(!update_user_meta($user->ID, 'logged_in', 'false'))
                    wp_die("Failed to add usermeta ", "Fatal");

        }


        public static function get_all_logged_in_users() {

            $users_list = get_users( array('meta_key' => 'logged_in', 'meta_value' => 'true'));

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
    add_action('clear_auth_cookie', array('WP_Plugin_Template','set_user_logged_out'), 10);
    add_action('wp_login', array('WP_Plugin_Template','set_user_logged_in'), 10, 2);

    // instantiate the plugin class
	$wp_plugin_template = new WP_Plugin_Template();

}
