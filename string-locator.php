<?php
/**
 * Plugin Name: String Locator
 * Plugin URI: http://www.mrstk.net/wordpress-string-locator/
 * Description: Scan through theme and plugin files looking for text strings
 * Version: 1.1.1
 * Author: Clorith
 * Author URI: http://www.mrstk.net
 * Text Domain: string-locator-plugin
 * License: GPL2
 *
 * Copyright 2013 Marius Jensen (email : marius@jits.no)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

class string_locator
{
    /**
     * Construct the plugin
     */
    function __construct()
    {
        add_action( 'admin_menu', array( $this, 'populate_menu' ) );
        add_action( 'admin_notices', array( $this, 'admin_notice' ) );

		add_action( 'plugins_loaded', array( $this, 'load_i18n' ) );
    }

	/**
	 * Set the text domain for translated plugin content
	 */
	function load_i18n() {
		$i18n_dir = 'string-locator/languages/';
		load_plugin_textdomain( 'string-locator-plugin', false, $i18n_dir );
	}

    /**
     * Add our plugin to the 'Tools' menu
     */
    function populate_menu()
    {
        $page_title  = __( 'String Locator', 'string-locator-plugin' );
        $menu_title  = __( 'String Locator', 'string-locator-plugin' );
        $capability  = 'edit_files';
        $parent_slug = 'tools.php';
        $menu_slug   = 'string-locator';
        $function    = array( $this, 'options_page' );

        add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
    }

    /**
     * Add notice to the top of pages in the admin screen
     */
    function admin_notice()
    {
        /**
         * Make sure we only add our notification if it's a page referenced by the plugin
         */
        if ( isset( $_GET['string-locator-line'] ) ) {
            ?>
                <div class="updated">
                    <h3>
                        String Locator
                    </h3>
                    <p>
                        <?php printf( __( 'You recently searched for <strong>%s</strong> which was located on line <strong>%d</strong>.', 'string-locator-plugin' ), urldecode( $_GET['string-locator-search'] ), $_GET['string-locator-line'] ); ?>
                    </p>
                    <p>
                        <?php _e( 'You can easily locate the line in the text editor by using your browsers search function (CTRL+F / CMD+F).', 'string-locator-plugin' ); ?>
                    </p>
                </div>
            <?php
        }
    }

    /**
     * Function for including the actual plugin Admin UI page
     */
    function options_page()
    {
        include_once( dirname( __FILE__ ) . '/options.php' );
    }
}

//  Initiate the plugin code
$string_locator = new string_locator();