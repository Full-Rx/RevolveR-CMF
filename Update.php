<?php

 /*
  * 
  * RevolveR CMF Update future 
  *
  * v.2.0.1.5
  *
  * Developer: Maltsev Dmitry
  *
  * License: Apache 2.0
  *
  */

// Prevent update process blocking by time
ignore_user_abort(true);

set_time_limit(0);

// Main parameters
$root = $_SERVER['DOCUMENT_ROOT'];

$remote_host = 'https://xcmf.net/';

// Kernel version
$local_version = $root .'/private/version';

$remote_version = $remote_host . 'repository/version.txt';

// Remote data downloader
function download( string $url ): ?string {

  return file_get_contents( $url, null, stream_context_create( [ 'http' => [ 'timeout' => 5 ] ] ) );

}

// Update Kernel
$rv = download( $remote_version );

$lv = file_get_contents( $local_version );

if( (int)$rv > 0 ) {

  $xmp = explode('::', $rv);

  if( (int)$lv < (int)$xmp[ 0 ] ) {

    $mp = str_split( $xmp[ 0 ] );

    $mp = $remote_host . 'repository/v.'. $mp[ 0 ] .'.'. $mp[ 1 ] .'.'. $mp[ 2 ] .'.'. $mp[ 3 ] .'.zip';

    $update_package = download( $mp );

    if( strlen($update_package) > 200 ) {

      if( !is_dir( $root . '/update/' ) ) {

        mkdir( $root . '/update/', 0775, true );

      }

      if( md5($update_package) === $xmp[ 1 ] ) {

        file_put_contents( $root .'/update/update.zip', $update_package );

        $zip = new ZipArchive;

        $res = $zip->open( $root .'/update/update.zip' );

        if( $res === TRUE ) {

          $zip->extractTo( $root .'/' );

          $zip->close();

        }

        unlink( $root . '/update/update.zip' );

        rmdir( $root . '/update/' );

        print 'RevolveR CMF version update success! First Kernel run perform upgrade ...';

      } 
      else {

        print 'Something went wrong! Rollback performed.';

      }

    }

  }

}
else {

  print 'Something went wrong! Rollback performed.'; 

}

?>
