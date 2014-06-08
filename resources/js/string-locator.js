var theEditor = document.getElementById( 'code-editor');

if ( theEditor != null ) {
	var editLine       = theEditor.getAttribute( 'data-editor-goto-line'),
		editorLanguage = theEditor.getAttribute( 'data-editor-language');

	function resizeEditor( editor ) {
		var setEditorSize  = ( Math.max( document.documentElement.clientHeight, window.innerHeight || 0 ) - 177 );


		editor.setSize( null, parseInt( setEditorSize ) );
	}

	var editor = CodeMirror.fromTextArea( document.getElementById( 'code-editor' ), {
		lineNumbers: true,
		mode: {
			name            : editorLanguage,
			globalVars      : true
		},
		styleActiveLine : true,
		matchBrackets   : true,
		indentWithTabs  : true,
		indentUnit      : 4,
		theme           : 'twilight'
	} );

	editor.scrollIntoView( parseInt( editLine ) );
	editor.setCursor( parseInt( editLine - 1 ), 0 );
	resizeEditor( editor );
}