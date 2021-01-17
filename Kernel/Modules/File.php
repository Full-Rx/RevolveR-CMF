<?php

 /* 
  * 
  * RevolveR File futures
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

final class File {

	public static function getDir( string $s ): ?iterable {

		return array_diff(

			scandir( $s, 1 ), [ '..', '.' ]

		);

	}

	public static function saveFile( string $f, string $d ): void {

		$r = fopen( $f, 'w' );

		fwrite( $r, $d );

		fclose( $r );

	}

	public static function readFile( string $f ): ?string {

		$r = @fopen( $f, 'r' );

		$c = null;

		if( $r ) {

			$c = fread( $r, filesize($f) );

			fclose( $r );

		}

		return $c;

	}

}

?>
