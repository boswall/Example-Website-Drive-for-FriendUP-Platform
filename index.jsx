Application.run = function( msg )
{
	var v = new View( {
		title: 'My sample Website app',
		width: 500,
		height: 400
	} );

	v.onClose = function()
	{
		Application.quit();
	}

	var f = new File( 'Progdir:Libraries/template' );

	f.onCall = function( e, d )
	{
		if( e == 'ok' ) v.setContent( d );
	}

	f.call( 'template', { templateFile: 'main' } );
}
