<?php
    /**
     * Get theme and plugin lists
     */
    $string_locate_themes = wp_get_themes();
    $string_locate_plugins = get_plugins();
?>
<div class="wrap">
    <h2>
        <?php _e( 'String Locator', 'string-locator' ); ?>
    </h2>

    <form action="" method="post">
        <label for="string-locator-search"><?php _e( 'Search through', 'string-locator' ); ?></label>
        <select name="string-locator-search" id="string-locator-search">
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

        <?php submit_button( __( 'Search', 'string-locator' ) ); ?>
    </form>

    <?php
        if ( isset( $_POST['string-locator-search'] ) )
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
                $found = false;
                $path = ABSPATH . 'wp-content/';

                global $string_locator;

                $theme = false;
                $plugin = false;

                if ( 3 == strlen( $_POST['string-locator-search'] ) && '-' == substr( $_POST['string-locator-search'], 2, 1 ) ) {
	                /**
	                 * We are doing a search of all themes or plugins
	                 */
	                if ( substr( $_POST['string-locator-search'], 0, 2 ) == 't-' ) {
		                $path .= 'themes/';

		                foreach( $string_locate_themes AS $string_locate_theme_slug => $string_locate_theme )
		                {
			                $locate = $string_locator->scan_path( $path . $string_locate_theme_slug, $_POST['string-locator-string'], 'theme', $string_locate_theme_slug );

			                if ( $locate ) {
				                $found = true;

				                echo '
				                    <tr>
				                    	<td colspan="3">
				                    		<strong>
				                    			' . ucfirst( $string_locate_theme ) . '
			                                </strong>
				                    	</td>
			                        </tr>
				                ';

				                echo $locate;
			                }
		                }
	                }
	                else {
		                $path .= 'plugins/';

		                foreach( $string_locate_plugins AS $string_locate_plugin_path => $string_locate_plugin )
		                {
			                $plugin = explode( '/', $string_locate_plugin_path );

			                $locate = $string_locator->scan_path( $path . $plugin[0], $_POST['string-locator-string'], 'plugin', $plugin[0] );

			                if ( $locate ) {
				                $found = true;

				                echo '
				                    <tr>
				                    	<td colspan="3">
				                    		<strong>
				                    			' . ucfirst( $plugin[0] ) . '
			                                </strong>
				                    	</td>
			                        </tr>
				                ';

				                echo $locate;
			                }
		                }
	                }
                }
                else {
	                /**
	                 * We are searching through an individual item
	                 */

	                /**
	                 * Check what we are search through, a theme or a plugin
	                 */
	                if ( substr( $_POST['string-locator-search'], 0, 2 ) == 't-' ) {
		                $theme = substr( $_POST['string-locator-search'], 2 );
		                $path .= 'themes/' . $theme;
	                } else {
		                $plugin = explode( '/', substr( $_POST['string-locator-search'], 2 ) );
		                $path .= 'plugins/' . $plugin[0];
	                }

	                $found = $string_locator->scan_path( $path, $_POST['string-locator-string'], ( $theme ? 'theme' : 'plugin' ), ( $theme ? $theme : $plugin[0] ) );
	                if ( $found ) {
		                echo $found;
	                }
                }

                /**
                 * Give the user feedback if the string was not found anywhere
                 */
                if ( ! $found )
                    echo '
                        <tr>
                            <td colspan="3">
                                ' . __( 'Your string was not present in any of the available files.', 'string-locator' ) . '
                            </td>
                        </tr>
                    ';
            ?>
            </tbody>
        </table>
    </form>

    <?php
        }
    ?>
</div>