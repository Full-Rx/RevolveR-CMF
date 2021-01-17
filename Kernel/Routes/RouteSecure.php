<?php

 /*
  * 
  * Secure Route :: Generates Captcha
  * 
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

$keys = json_encode(['key' => base64_encode('0*A-0:0:0|A-0:00:0|A-0:0:0|A-0:0:0|A-0:0:0|A-0:0:0|A-0:0:00|0-0:0:00|A-0:0:0|A-0:0:0|A-0:0:0|A-0:0:0|A-0:0:0|A-0:0:0|O-0:0:0|A-0:0:0*00000000000000000000')]);

if( isset( SV['g']['route'] ) ) {

  if( isset($_SERVER['HTTP_REFERER']) ) { 

    if( $_SERVER['HTTP_HOST'] === parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) ) {

        $dsp = null;

        $psw = explode('/', SV['g']['route']['value'] );

        if( $psw[ 1 ] === 'terminal' ) {

          $dsp = '/terminal-s/';

        }

        if( $psw[ count($psw) - 2 ] === 'edit' ) {

          if( $psw[ 1 ] === 'comment' && is_numeric( $psw[ count($psw) - 3 ] ) ) {

            $dsp = '/comments-d/';

          }

          if( $psw[ 1 ] === 'store' && $psw[ count($psw) - 4 ] === 'category' && is_numeric( $psw[ count($psw) - 3 ] ) ) {

            $dsp = '/store-category-d/';

          }

          if( $psw[ 1 ] === 'store' && $psw[ 2 ] === 'goods' && $psw[ 3 ] === 'add' ) {

            $dsp = '/store-goods-d/';

          }

          if( $psw[ 1 ] === 'store' && $psw[ 2 ] === 'goods' && is_numeric($psw[ 3 ]) && $psw[ 4 ] === 'edit' ) {

            $dsp = '/store-goods-edit-d/';

          }

          if( $psw[ 1 ] === 'categories' && is_numeric( $psw[ count($psw) - 3 ] ) ) {

            $dsp = '/category-d/';

          }

          if( $psw[ 1 ] === 'wiki' && is_numeric( $psw[ count($psw) - 3 ] ) ) {

            $dsp = '/wiki-d/';

          }

          if( $psw[ 1 ] === 'wiki' && $psw[ 4 ] === 'edit' ) {

            $dsp = '/wiki-node-d/';

          }

          if( $psw[ 1 ] === 'forum' && is_numeric( $psw[ count($psw) - 3 ] ) ) {

            $dsp = '/forum-d/';

          }

          if( $psw[ 1 ] === 'forum' && is_numeric( $psw[ 2 ] ) && empty( $psw[ 3 ] ) ) {

            $dsp = '/forum-room-d/';

          }

          if( $psw[ 1 ] === 'forum' && is_numeric( $psw[ 2 ] ) && is_numeric( $psw[ 3 ] ) ) {

            $dsp = '/forum-comments-d/';

          }

          if( !is_numeric( $psw[ count($psw) - 3 ] ) ) {

            $dsp = '/contents-d/';

          }

          if( $psw[ 1 ] === 'user' && is_numeric( $psw[ count($psw) - 3 ] ) ) {

            $dsp = '/user-d/';

          }

        }

        $keys = json_encode([

          'key' => $RKI->Captcha::randomize(

              base64_encode( SV['g']['route']['value'] ), $dsp 

            )

        ]);

    }

  }

}

if( !isset( SV['g']['policy'] ) ) {

	print $keys;

} 
else {

  if( isset(SV['c']['accepted-privacy-policy']) ) {

    if( SV['c']['accepted-privacy-policy'] === session_id() ) {

      print json_encode([

        'privacy' => base64_encode( 'accepted::'. session_id() )

      ]);

    }
    else {

      print json_encode([

        'privacy' => base64_encode('null')

      ]);

    }

  }
  else {

      print json_encode([

        'privacy' => base64_encode('null')

      ]);

  }

}

define('serviceOutput', [

  'ctype'     => 'application/json',
  'route'     => '/secure/'

]);

?>
