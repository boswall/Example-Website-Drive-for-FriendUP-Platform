<?php

// Output request data to a log file for development
file_put_contents('../logs/friend_access.log', 'SERVER: ' . print_r($_SERVER, true) . 'REQUEST: ' . print_r($_REQUEST, true) . 'POST: ' . print_r($_POST, true) . 'GET: ' . print_r($_GET, true), FILE_APPEND);


// Check commands from Friend Core
if( isset( $_REQUEST['command'] ) ) {
  switch( $_REQUEST['command'] ) {

    // Library listing?
    case 'libraries':
      $libraries = glob( 'lib/*' , GLOB_ONLYDIR);
      $o = array();
      foreach ($libraries as $library) {
        $o[] = array(
          'Filename' => end( explode( '/', $library ) ) . '.library',
          'Filesize' => '16b',
          'DateModified' => date( 'Y-m-d H:i:s' ),
          'DateCreated' => $o->DateModified,
          'IconClass' => 'TypeLibrary',
          'Permissions' => '-r-e-',
        );
      }
      // Return a json encoded array of the Libraries
      die( 'ok<!--separate-->' . json_encode( $o ) );
      break;

    // A library call?
    case 'call':
      if( !isset( $_REQUEST[ 'path' ] ) )
        die( 'fail<!--separate-->{"response":"no library specified"}' );
      $library = end( explode( '/', $_REQUEST[ 'path' ] ) );

      // Template library?
      if( $library == 'template' ) {
        if( !isset( $_REQUEST['args']['query'] ) )
          die( 'fail<!--separate-->{"response":"no library call specified"}' );
        switch( $_REQUEST['args']['query'] )
        {
          // We want a template
          case 'template':
          // No traversal
          $templatefile = isset( $_REQUEST[ 'args']['templateFile' ] ) ? $_REQUEST[ 'args'][ 'templateFile' ] : '';
          if( !$templatefile || strstr( $templatefile, '..' ) )
            die( 'fail' );
          if( file_exists( 'lib/template/' . $templatefile . '.html' ) )
          {
            die( 'ok<!--separate-->' . file_get_contents( 'lib/template/' . $templatefile . '.html' ) );
          }
          break;
        }
      }

      break;

    // Unknown command
    default:
      die( 'fail<!--separate-->{"response":"no command specified"}' );
  }
}
// Nothing was triggered check for a Browser
$browserfile = 'default_index.html';
if ( isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) && file_exists( $browserfile ) ) {
  die( file_get_contents( $browserfile ) );
}

// Otherwise - fail
die( 'fail' );
