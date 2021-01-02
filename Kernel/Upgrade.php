<?php

$log_file = $_SERVER['DOCUMENT_ROOT'] .'/private/version';

$current_version = file_get_contents( $log_file );

$actual_version = str_replace('.', '', rr_version);

if( !$current_version ) {

	file_put_contents($log_file, $actual_version);

}
else {

	if( (int)$current_version < (int)$actual_version ) {

		// Fix Files and directories permissons

		exec('find '. $_SERVER['DOCUMENT_ROOT'] .' -type d -exec chmod 0775 {} +'); // for sub directory
		exec('find '. $_SERVER['DOCUMENT_ROOT'] .' -type f -exec chmod 0644 {} +'); // for files inside directory

		// Total clean

		foreach( $file::getDir($_SERVER['DOCUMENT_ROOT'] . '/cache/dbcache/') as $f ) {

			unlink( $_SERVER['DOCUMENT_ROOT'] . '/cache/dbcache/' . $f );

		}

		rmdir($_SERVER['DOCUMENT_ROOT'] . '/cache/dbcache');
		mkdir($_SERVER['DOCUMENT_ROOT'] . '/cache/dbcache', 0775);

		foreach( $file::getDir($_SERVER['DOCUMENT_ROOT'] . '/cache/tplcache/') as $f ) {

			unlink( $_SERVER['DOCUMENT_ROOT'] . '/cache/tplcache/' . $f );

		}

		rmdir($_SERVER['DOCUMENT_ROOT'] . '/cache/tplcache');
		mkdir($_SERVER['DOCUMENT_ROOT'] . '/cache/tplcache', 0775);

		foreach( $file::getDir($_SERVER['DOCUMENT_ROOT'] . '/cache/scripts/') as $f ) {

			unlink( $_SERVER['DOCUMENT_ROOT'] . '/cache/scripts/' . $f );

		}

		foreach( $file::getDir($_SERVER['DOCUMENT_ROOT'] . '/cache/styles/') as $f ) {

			unlink( $_SERVER['DOCUMENT_ROOT'] . '/cache/styles/' . $f );

		}

		rmdir($_SERVER['DOCUMENT_ROOT'] . '/public/cache/scripts');
		rmdir($_SERVER['DOCUMENT_ROOT'] . '/public/cache/styles');

		mkdir($_SERVER['DOCUMENT_ROOT'] . '/public/cache/scripts', 0775);
		mkdir($_SERVER['DOCUMENT_ROOT'] . '/public/cache/styles', 0775);

		// update db struct

		if( (int)$current_version <= 2007 ) {

			// Recreate table rates

			$dbx::query('d', 'revolver__rates', $STRUCT_RATES);
			$dbx::query('c', 'revolver__rates', $STRUCT_RATES);

		}

		if( (int)$current_version <= 2000 ) {

			// Update table talk

			$dbx::query('d', 'revolver__talk', $STRUCT_TALK);
			$dbx::query('c', 'revolver__talk', $STRUCT_TALK);

		}

		// update success

		file_put_contents($log_file, $actual_version);

	}


}


?>
