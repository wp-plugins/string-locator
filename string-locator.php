<?php
/**
 * Plugin Name: String Locator
 * Plugin URI: http://www.mrstk.net/wordpress-string-locator/
 * Description: Scan through theme and plugin files looking for text strings
 * Version: 1.3
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
	 * @var string $string_locator_language The code language used for the editing page
	 * @var string $version String Locator version number
	 * @var array  $notice Array containing all notices to display
	 * @var bool   $failed_edit Has there been a failed edit
	 * @var string $plugin_url The URL to the plugins directory
	 */
	public  $string_locator_language = '';
	public  $version                 = '1.2.1';
	public  $notice                  = array();
	public  $failed_edit             = false;
	private $plugin_url              = '';

    /**
     * Construct the plugin
     */
    function __construct()
    {
		/**
		 * Define class variables requiring expressions
		 */
		$this->plugin_url = plugin_dir_url( __FILE__ );

		add_action( 'admin_menu', array( $this, 'populate_menu' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 11 );

		add_action( 'plugins_loaded', array( $this, 'load_i18n' ) );

		add_action( 'admin_init', array( $this, 'editor_save' ) );
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
    }

	/**
	 * Check if a file path is valid for editing
	 *
	 * @param string $path Path to file
	 * @return bool
	 */
	function is_valid_location( $path ) {
		$valid = true;

		if ( empty( $path ) ) {
			$valid = false;
		}
		if ( stristr( $path, '..' ) ) {
			$valid = false;
		}

		return $valid;
	}

	/**
	 * Set the text domain for translated plugin content
	 */
	function load_i18n() {
		$i18n_dir = 'string-locator/languages/';
		load_plugin_textdomain( 'string-locator-plugin', false, $i18n_dir );
	}

	/**
	 * Load up JavaScript and CSS for our plugin on the appropriate admin pages
	 */
	function admin_enqueue_scripts( $hook ) {
		if ( 'tools_page_string-locator' == $hook && isset( $_GET['edit-file'] ) ) {
			$filename = explode( '.', $_GET['edit-file'] );
			$filext = end( $filename );
			switch( $filext ) {
				case 'js':
					$this->string_locator_language = 'javascript';
					break;
				case 'php':
					$this->string_locator_language = 'application/x-httpd-php';
					break;
				case 'css':
					$this->string_locator_language = 'css';
					break;
				default:
					$this->string_locator_language = 'htmlmixed';
			}

			/**
			 * CodeMirror Styles
			 */
			wp_register_style( 'codemirror', plugin_dir_url( __FILE__ ) . '/resources/css/codemirror.css', array( 'codemirror-lint' ), $this->version );
			wp_register_style( 'codemirror-twilight', plugin_dir_url( __FILE__ ) . '/resources/css/codemirror/twilight.css', array( 'codemirror' ), $this->version );
			wp_register_style( 'codemirror-lint', plugin_dir_url( __FILE__ ) . '/resources/js/codemirror/addon/lint/lint.css', array(), $this->version );

			/**
			 * String Locator Styles
			 */
			wp_register_style( 'string-locator', plugin_dir_url( __FILE__ ) . '/resources/css/string-locator.css', array( 'codemirror' ), $this->version );

			/**
			 * CodeMirror Scripts
			 */
			wp_register_script( 'codemirror-addon-edit-closebrackets', $this->plugin_url . '/resources/js/codemirror/addon/edit/closebrackets.js', array( 'codemirror' ), $this->version, true );
			wp_register_script( 'codemirror-addon-edit-matchbrackets', $this->plugin_url . '/resources/js/codemirror/addon/edit/matchbrackets.js', array( 'codemirror' ), $this->version, true );
			wp_register_script( 'codemirror-addon-selection-active-line', $this->plugin_url . '/resources/js/codemirror/addon/selection/active-line.js', array( 'codemirror' ), $this->version, true );

			wp_register_script( 'codemirror-addon-lint-css', $this->plugin_url . '/resources/js/codemirror/addon/lint/lint.js', array( 'codemirror' ), $this->version, true );

			wp_register_script( 'codemirror-mode-javascript', $this->plugin_url . '/resources/js/codemirror/mode/javascript/javascript.js', array( 'codemirror' ), $this->version, true );
			wp_register_script( 'codemirror-mode-htmlmixed', $this->plugin_url . '/resources/js/codemirror/mode/htmlmixed/htmlmixed.js', array( 'codemirror' ), $this->version, true );
			wp_register_script( 'codemirror-mode-clike', $this->plugin_url . '/resources/js/codemirror/mode/clike/clike.js', array( 'codemirror' ), $this->version, true );
			wp_register_script( 'codemirror-mode-xml', $this->plugin_url . '/resources/js/codemirror/mode/xml/xml.js', array( 'codemirror' ), $this->version, true );
			wp_register_script( 'codemirror-mode-css', $this->plugin_url . '/resources/js/codemirror/mode/css/css.js', array( 'codemirror' ), $this->version, true );
			wp_register_script( 'codemirror-mode-php', $this->plugin_url . '/resources/js/codemirror/mode/php/php.js', array( 'codemirror' ), $this->version, true );
			wp_register_script( 'codemirror', $this->plugin_url . '/resources/js/codemirror/lib/codemirror.js', array(), $this->version, true );

			/**
			 * String Locator Scripts
			 */
			wp_register_script( 'string-locator-editor', $this->plugin_url . '/resources/js/string-locator.js', array( 'codemirror' ), $this->version, true );

			/**
			 * CodeMirror Enqueue
			 */
			wp_enqueue_style( 'codemirror-twilight' );

			wp_enqueue_script( 'codemirror-addon-edit-closebrackets' );
			wp_enqueue_script( 'codemirror-addon-edit-matchbrackets' );
			wp_enqueue_script( 'codemirror-addon-selection-active-line' );
			wp_enqueue_script( 'codemirror-addon-lint' );

			wp_enqueue_script( 'codemirror-mode-javascript' );
			wp_enqueue_script( 'codemirror-mode-htmlmixed' );
			wp_enqueue_script( 'codemirror-mode-clike' );
			wp_enqueue_script( 'codemirror-mode-xml' );
			wp_enqueue_script( 'codemirror-mode-css' );
			wp_enqueue_script( 'codemirror-mode-php' );

			/**
			 * String Locator Enqueue
			 */
			wp_enqueue_style( 'string-locator' );

			wp_enqueue_script( 'string-locator-editor' );
		}
	}

    /**
     * Add our plugin to the 'Tools' menu
     */
    function populate_menu()
    {
        $page_title  = __( 'String Locator', 'string-locator-plugin' );
        $menu_title  = __( 'String Locator', 'string-locator-plugin' );
        $capability  = 'edit_themes';
        $parent_slug = 'tools.php';
        $menu_slug   = 'string-locator';
        $function    = array( $this, 'options_page' );

        add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
    }

    /**
     * Function for including the actual plugin Admin UI page
     */
    function options_page()
    {
		/**
		 * Don't load anything if the user can't edit themes any way
		 */
		if ( ! current_user_can( 'edit_themes' ) ) {
			return false;
		}

		/**
		 * Show the edit page if;
		 * - The edit file path query var is set
		 * - The edit file path query var isn't empty
		 * - The edit file path query var does not contains double dots (used to traverse directories)
		 */
		if ( isset( $_GET['string-locator-path'] ) && $this->is_valid_location( $_GET['string-locator-path'] ) ) {
			include_once( dirname( __FILE__ ) . '/editor.php' );
		}
		else {
			include_once( dirname( __FILE__ ) . '/options.php' );
		}
    }

	/**
	 * @param string $start Start delimited
	 * @param string $end End delimiter
	 * @param string $string The string to scan
	 * @return array
	 */
	function SmartScan( $start, $end, $string ) {
		$opened = array();

		$lines = explode( "\n", $string );
		for ( $i = 0; $i < count( $lines ); $i++ ) {
			if ( stristr( $lines[$i], $start ) ) {
				$opened[] = $i;
			}
			if ( stristr( $lines[$i], $end ) ) {
				array_pop( $opened );
			}
		}

		return $opened;
	}

	/**
	 * Handler for storing the content of the code editor
	 * Also runs over the Smart-Scan if enabled
	 */
	function editor_save() {
		if ( isset( $_POST['string-locator-editor-content'] ) && check_admin_referer( 'string-locator-edit_' . $_GET['edit-file'] ) && current_user_can( 'edit_themes' ) ) {

			if ( $this->is_valid_location( $_GET['string-locator-path'] ) ) {
				$path = urldecode( $_GET['string-locator-path'] );
				$content = stripslashes( $_POST['string-locator-editor-content'] );

				/**
				 * Send an error notice if the file isn't writable
				 */
				if ( ! is_writeable( $path ) ) {
					$this->notice[] = array(
						'type'    => 'error',
						'message' => __( 'The file could not be written to, please check file permissions or edit it manually.', 'string-locator-plugin' )
					);
					$this->failed_edit = true;
					return;
				}

				/**
				 * If enabled, run the Smart-Scan on the content before saving it
				 */
				if ( isset( $_POST['string-locator-smart-edit'] ) ) {
					$open_brace = substr_count( $content, '{' );
					$close_brace = substr_count( $content, '}' );
					if ( $open_brace != $close_brace ) {
						$this->failed_edit = true;

						$opened = $this->SmartScan( '{', '}', $content );

						foreach( $opened AS $line ) {
							$this->notice[] = array(
								'type'    => 'error',
								'message' => sprintf( __( 'There is an inconsistency in the opening and closing braces, { and }, of your file on line %s', 'string-locator-plugin' ), '<a href="#" class="string-locator-edit-goto" data-gogo-line="' . ( $line + 1 ). '">' . ( $line + 1 ) . '</a>' )
							);
						}
					}

					$open_bracket = substr_count( $content, '[' );
					$close_bracket = substr_count( $content, ']' );
					if ( $open_bracket != $close_bracket ) {
						$this->failed_edit = true;

						$opened = $this->SmartScan( '[', ']', $content );

						foreach( $opened AS $line ) {
							$this->notice[] = array(
								'type'    => 'error',
								'message' => sprintf( __( 'There is an inconsistency in the opening and closing braces, [ and ], of your file on line %s', 'string-locator-plugin' ), '<a href="#" class="string-locator-edit-goto" data-gogo-line="' . ( $line + 1 ). '">' . ( $line + 1 ) . '</a>' )
							);
						}
					}

					$open_parenthesis  = substr_count( $content, '(' );
					$close_parenthesis = substr_count( $content, ')' );
					if ( $open_parenthesis != $close_parenthesis ) {
						$this->failed_edit = true;

						$opened = $this->SmartScan( '(', ')', $content );

						foreach( $opened AS $line ) {
							$this->notice[] = array(
								'type'    => 'error',
								'message' => sprintf( __( 'There is an inconsistency in the opening and closing braces, ( and ), of your file on line %s', 'string-locator-plugin' ), '<a href="#" class="string-locator-edit-goto" data-gogo-line="' . ( $line + 1 ). '">' . ( $line + 1 ) . '</a>' )
							);
						}
					}

					if ( $this->failed_edit ) {
						return;
					}
				}

				$file = fopen( $path, "w" );
				$lines = explode( "\n", str_replace( array( "\r\n", "\r" ), "\n", $content ) );
				for ( $i = 0; $i < count( $lines ); $i++ ) {
					fwrite( $file, $lines[$i] . PHP_EOL );
				}
				fclose( $file );
				$this->notice[] = array(
					'type'    => 'updated',
					'message' => __( 'The file has been saved', 'string-locator-plugin' )
				);
			}
		}
	}

	/**
	 * Hook the admin notices and loop over any notices we've registered in the plugin
	 */
	function admin_notice() {
		if ( ! empty( $this->notice ) ) {
			foreach( $this->notice AS $note ) {
				echo '
					<div class="' . $note['type'] . '">
						<p>' . $note['message'] . '</p>
					</div>
				';
			}
		}
	}

	function scan_path( $path, $string, $type, $slug ) {
		$output = "";

		/**
		 * We use the PHP Iterator class to recursively check for files
		 */
		$paths = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $path ),
			RecursiveIteratorIterator::SELF_FIRST
		);

		foreach ( $paths AS $name => $location )
		{
			$linenum = 0;

			/**
			 * If it's a directory, skip this run through, we can't read a directory line by line
			 */
			if ( is_dir( $location->getPathname() ) ) {
				continue;
			}

			/**
			 * Start reading the file
			 */
			$readfile = fopen( $location->getPathname(), "r" );
			if ( $readfile )
			{
				while ( ( $readline = fgets( $readfile ) ) !== false )
				{
					$linenum++;
					/**
					 * If our string is found in this line, output the line number and other data
					 */
					if ( stristr( $readline, $string ) )
					{
						/**
						 * Prepare the visual path for the end user
						 * Removes path leading up to WordPress root and ensures consistent directory separators
						 */
						$relativepath = str_replace( array( ABSPATH, '/' ), array( '', DIRECTORY_SEPARATOR ), $location->getPathname() );

						/**
						 * Create the URL to take the user to the editor
						 */
						$editurl = admin_url( 'tools.php?page=string-locator&file-type=' . $type . '&file-reference=' . urlencode( $slug ) . '&edit-file=' . $location->getFilename() . '&string-locator-line=' . $linenum . '&string-locator-path=' . urlencode( $location->getPathname() ) );

						$output .=  '
                            <tr>
                                <td>' . $linenum . '</td>
                                <td>
                                    <a href="' . $editurl . '">' . $relativepath . '</a>
                                </td>
                                <td>' . str_ireplace( $string, '<strong>' . $string . '</strong>', htmlentities( $readline ) ) . '</td>
                            </tr>
                        ';
					}
				}
			}
			else {
				/**
				 * The file was unreadable, give the user a friendly notification
				 */
				$output .= '
                    <tr>
                        <td colspan="3">
                            <strong>
                                ' . __( 'Could not read file: ', 'string-locator-plugin' ) . $location->getFilename() . '
                            </strong>
                        </td>
                    </tr>
                ';
			}
		}

		if ( ! empty( $output ) ) {
			return $output;
		}

		return false;
	}
}

/**
 * Instantiate the plugin
 */
$string_locator = new string_locator();