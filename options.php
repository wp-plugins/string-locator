<?php
    /**
     * Get theme and plugin lists
     */
    $string_locate_themes = wp_get_themes();
    $string_locate_plugins = get_plugins();

	$this_url = admin_url( ( is_multisite() ? 'network/admin.php' : 'tools.php' ) . '?page=string-locator' );
?>
<div class="wrap">
    <h2>
        <?php _e( 'String Locator', 'string-locator' ); ?>
    </h2>

    <form action="<?php echo esc_url( $this_url ); ?>" method="post">
        <label for="string-locator-search"><?php _e( 'Search through', 'string-locator' ); ?></label>
        <select name="string-locator-search" id="string-locator-search">
	        <optgroup label="<?php _e( 'Core', 'string-locator' ); ?>">
		        <option value="core"><?php _e( 'The whole WordPress directory', 'string-locator' ); ?></option>
	        </optgroup>
            <optgroup label="<?php _e( 'Themes', 'string-locator' ); ?>">
	            <option value="t--"<?php echo ( isset( $_POST['string-locator-search'] ) && 't--' == $_POST['string-locator-search'] ? ' selected="selected"' : '' ); ?>>&mdash; <?php _e( 'All themes', 'string-locator' ); ?> &mdash;</option>
                <?php
                    /**
                     * Loop through themes for our dropdown list
                     */
                    foreach( $string_locate_themes AS $string_locate_theme_slug => $string_locate_theme )
                    {
                        $string_locate_theme_data = wp_get_theme( $string_locate_theme_slug );
                        $string_locate_value = 't-' . $string_locate_theme_slug;
                        echo '
                            <option value="' . $string_locate_value . '"' . ( isset( $_POST['string-locator-search'] ) && $_POST['string-locator-search'] == $string_locate_value ? ' selected="selected"' : '' ) . '>' . $string_locate_theme_data->Name . '</option>
                        ';
                    }
                ?>
            </optgroup>
            <optgroup label="<?php _e( 'Plugins', 'string-locator' ); ?>">
	            <option value="p--"<?php echo ( isset( $_POST['string-locator-search'] ) && 'p--' == $_POST['string-locator-search'] ? ' selected="selected"' : '' ); ?>>&mdash; <?php _e( 'All plugins', 'string-locator' ); ?> &mdash;</option>
                <?php
                    /**
                     * Loop through plugins for our dropdown list
                     */
                    foreach( $string_locate_plugins AS $string_locate_plugin_path => $string_locate_plugin )
                    {
                        $string_locate_value = 'p-' . $string_locate_plugin_path;
                        echo '
                            <option value="' . $string_locate_value . '"' . ( isset( $_POST['string-locator-search'] ) && $_POST['string-locator-search'] == $string_locate_value ? ' selected="selected"' : '' ) . '>' . $string_locate_plugin['Name'] . '</option>
                        ';
                    }
                ?>
            </optgroup>
        </select>

        <label for="string-locator-string"><?php _e( 'Search string', 'string-locator' ); ?></label>
        <input type="text" name="string-locator-string" id="string-locator-string" value="<?php echo ( isset( $_POST['string-locator-string'] ) ? $_POST['string-locator-string'] : '' ); ?>" />

	    <p>
	        <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Search', 'string-locator' ); ?>">
	        <a href="<?php echo esc_url( $this_url . '&restore=true' ); ?>" class="button button-primary"><?php _e( 'Restore last search', 'string-locator' ); ?></a>
        </p>
    </form>

    <?php
        if ( isset( $_POST['string-locator-search'] ) || isset( $_GET['restore'] ) )
        {
    ?>

    <form action="" method="post">
        <table class="wp-list-table widefat fixed">
            <thead>
                <tr>
                    <th scope="col" style="width: 3.2em;"><?php _e( 'Line', 'string-locator' ); ?></th>
                    <th scope="col" style=""><?php _e( 'File', 'string-locator' ); ?></th>
                    <th scope="col" style=""><?php _e( 'String', 'string-locator' ); ?></th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th scope="col" style=""><?php _e( 'Line', 'string-locator' ); ?></th>
                    <th scope="col" style=""><?php _e( 'File', 'string-locator' ); ?></th>
                    <th scope="col" style=""><?php _e( 'String', 'string-locator' ); ?></th>
                </tr>
            </tfoot>

            <tbody>
            <?php
                $found = '';
                $path = ABSPATH . 'wp-content/';

                global $string_locator;

                $theme = false;
                $plugin = false;

                if ( isset( $_GET['restore'] ) ) {
		            $found = $string_locator->restore_scan_path();
	            }
                else {
	                if ( 3 == strlen( $_POST['string-locator-search'] ) && '-' == substr( $_POST['string-locator-search'], 2, 1 ) ) {
		                /**
		                 * We are doing a search of all themes or plugins
		                 */
		                if ( substr( $_POST['string-locator-search'], 0, 2 ) == 't-' ) {
			                $path .= 'themes/';

			                foreach ( $string_locate_themes AS $string_locate_theme_slug => $string_locate_theme ) {
				                $locate = $string_locator->scan_path( $path . $string_locate_theme_slug, $_POST['string-locator-string'], 'theme', $string_locate_theme_slug );

				                if ( $locate ) {
					                $found .= '
					                    <tr>
					                        <td colspan="3">
					                            <strong>
					                                ' . ucfirst( $string_locate_theme ) . '
				                                </strong>
					                        </td>
				                        </tr>
					                ';

					                $found .= $locate;
				                }
			                }
		                } else {
			                $path .= 'plugins/';

			                foreach ( $string_locate_plugins AS $string_locate_plugin_path => $string_locate_plugin ) {
				                $plugin = explode( '/', $string_locate_plugin_path );

				                $locate = $string_locator->scan_path( $path . $plugin[0], $_POST['string-locator-string'], 'plugin', $plugin[0] );

				                if ( $locate ) {
					                $found .= '
					                    <tr>
					                        <td colspan="3">
					                            <strong>
					                                ' . ucfirst( $plugin[0] ) . '
				                                </strong>
					                        </td>
				                        </tr>
					                ';

					                $found .= $locate;
				                }
			                }
		                }
	                } else {
		                /**
		                 * We are searching through an individual item
		                 */

		                /**
		                 * Check what we are search through: WordPress core, a theme or a plugin
		                 */
		                if ( 'core' == $_POST['string-locator-search'] ) {
			                $path = ABSPATH;
			                $type = 'core';
			                $slug = '';
		                } elseif ( substr( $_POST['string-locator-search'], 0, 2 ) == 't-' ) {
			                $theme = substr( $_POST['string-locator-search'], 2 );
			                $path .= 'themes/' . $theme;
			                $type = 'theme';
			                $slug = $theme;
		                } else {
			                $plugin = explode( '/', substr( $_POST['string-locator-search'], 2 ) );
			                $path .= 'plugins/' . $plugin[0];
			                $type = 'plugin';
			                $slug = $plugin[0];
		                }

		                $found .= $string_locator->scan_path( $path, $_POST['string-locator-string'], $type, $slug );
	                }
                }

                /**
                 * Give the user feedback if the string was not found anywhere
                 */
                if ( empty( $found ) ) {
	                echo '
                        <tr>
                            <td colspan="3">
                                ' . __( 'Your string was not present in any of the available files.', 'string-locator' ) . '
                            </td>
                        </tr>
                    ';
                }
	            else {
		            echo $found;

		            if ( ! isset( $_GET['restore'] ) ) {
			            error_log( 'Updating string results' );
			            error_log( var_export( $found, true ) );
			            update_option( 'string-locator-results', $found, false );
		            }
	            }
            ?>
            </tbody>
        </table>
    </form>

    <?php
        }
    ?>
</div>