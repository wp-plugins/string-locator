<?php
	global $string_locator;
	$editor_content = "";
	$file = $_GET['string-locator-path'];
	$themedata = wp_get_theme( $_GET['theme'] );

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

	<form action="" id="string-locator-edit-form" method="post">
		<div class="string-locator-edit-wrap">
			<textarea name="string-locator-editor-content" class="string-locator-editor" id="code-editor" data-editor-goto-line="<?php echo $_GET['string-locator-line']; ?>" data-editor-language="<?php echo $string_locator->string_locator_language; ?>" autofocus="autofocus"><?php echo $editor_content; ?></textarea>
		</div>

		<div class="string-locator-sidebar-wrap">
			<div class="string-locator-theme-details">
				<h2><?php echo $themedata->get( 'Name' ); ?> <small>v. <?php echo $themedata->get( 'Version' ); ?></small></h2>
				<p>
					By <a href="<?php echo $themedata->get( 'AuthorURI' ); ?>" target="_blank"><?php echo $themedata->get( 'Author' ); ?></a>
				</p>
				<p>
					<?php echo $themedata->get( 'Description' ); ?>
				</p>
			</div>

			<div class="string-locator-actions">
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