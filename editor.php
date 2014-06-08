<?php
	global $string_locator;
	$editor_content = "";
	$file = $_GET['string-locator-path'];
	$details = array();

	if ( $_GET['file-type'] == 'theme' ) {
		$themedata = wp_get_theme( $_GET['file-reference'] );

		$details = array(
			'name'        => $themedata->get( 'Name' ),
			'version'     => $themedata->get( 'Version' ),
			'author'      => array(
				'uri'     => $themedata->get( 'AuthorURI' ),
				'name'    => $themedata->get( 'Author' )
			),
			'description' => $themedata->get( 'Description' )
		);
	}
	else {
		$plugins = get_plugins();

		foreach( $plugins AS $pluginname => $plugindata ) {
			$pluginref = explode( '/', $pluginname );

			if ( $pluginref[0] == $_GET['file-reference'] ) {
				$details = array(
					'name'        => $plugindata['Name'],
					'version'     => $plugindata['Version'],
					'author'      => array(
						'uri'     => $plugindata['AuthorURI'],
						'name'    => $plugindata['Author']
					),
					'description' => $plugindata['Description']
				);
			}
		}
	}

	if ( ! $string_locator->failed_edit ) {
		$readfile = fopen( $file, "r" );
		if ( $readfile )
		{
			while ( ( $readline = fgets( $readfile ) ) !== false )
			{
				$editor_content .= $readline;
			}
		}
	}
	else {
		$editor_content = stripslashes( $_POST['string-locator-editor-content'] );
	}
?>
<div class="wrap">
	<h2>
		<?php _e( 'String Locator - Code Editor', 'string-locator-plugin' ); ?>
	</h2>

	<form action="<?php echo ( is_ssl() ? 'http://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" id="string-locator-edit-form" method="post">
		<div class="string-locator-edit-wrap">
			<textarea name="string-locator-editor-content" class="string-locator-editor" id="code-editor" data-editor-goto-line="<?php echo $_GET['string-locator-line']; ?>" data-editor-language="<?php echo $string_locator->string_locator_language; ?>" autofocus="autofocus"><?php echo esc_html( $editor_content ); ?></textarea>
		</div>

		<div class="string-locator-sidebar-wrap">
			<div class="string-locator-theme-details">
				<h2><?php echo $details['name']; ?> <small>v. <?php echo $details['version']; ?></small></h2>
				<p>
					By <a href="<?php echo $details['author']['uri']; ?>" target="_blank"><?php echo $details['author']['name']; ?></a>
				</p>
				<p>
					<?php echo $details['description'] ?>
				</p>
			</div>

			<div class="string-locator-actions">
				<?php wp_nonce_field( 'string-locator-edit_' . $_GET['edit-file'] ); ?>
				<p>
					<label>
						<input type="checkbox" name="string-locator-smart-edit" checked="checked">
						Enable a smart-scan of your code to help detect bracket mismatches before saving.
					</label>
				</p>

				<p class="submit">
					<input type="submit" name="submit" class="button button-primary" value="<?php _e( 'Save changes', 'string-locator-plugin' ); ?>">
				</p>
			</div>
		</div>
	</form>
</div>