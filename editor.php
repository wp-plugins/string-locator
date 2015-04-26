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
			<div class="string-locator-details">
				<div class="string-locator-theme-details">
					<h2><?php echo $details['name']; ?> <small>v. <?php echo $details['version']; ?></small></h2>
					<p>
						<?php _e( 'By', 'string-locator-plugin' ); ?> <a href="<?php echo $details['author']['uri']; ?>" target="_blank"><?php echo $details['author']['name']; ?></a>
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
							<?php _e( 'Enable a smart-scan of your code to help detect bracket mismatches before saving.', 'string-locator-plugin' ); ?>
						</label>
					</p>

					<p class="submit">
						<input type="submit" name="submit" class="button button-primary" value="<?php _e( 'Save changes', 'string-locator-plugin' ); ?>">
					</p>
				</div>
			</div>

			<div class="string-locator-details">
				<div class="string-locator-theme-details">
					<h2><?php _e( 'Functions in use', 'string-locator' ); ?></h2>

					<?php
						$function_info = get_defined_functions();

						foreach( $function_info['user'] AS $user_func ) {
							if ( strstr( $editor_content, $user_func ) ) {
								$function_object = new ReflectionFunction( $user_func );
								$attrs = $function_object->getParameters();

								$attr_strings = array();

								foreach( $attrs AS $attr ) {
									$arg = '';

									if ( $attr->isPassedByReference() ) {
										$arg .= '&';
									}

									if ( $attr->isOptional() ) {
										$arg = sprintf(
											'[ %s$%s ]',
											$arg,
											$attr->getName()
										);
									} else {
										$arg = sprintf(
											'%s%s',
											$arg,
											$attr->getName()
										);
									}

									$attr_strings[] = $arg;
								}

								printf(
									'<p><a href="%s">%s</a></p>',
									esc_url( sprintf( 'https://developer.wordpress.org/reference/functions/%s/', $user_func ) ),
									$user_func . '( ' . implode( ', ', $attr_strings ) . ' )'
								);
							}
						}
					?>
				</div>
			</div>
		</div>
	</form>
</div>