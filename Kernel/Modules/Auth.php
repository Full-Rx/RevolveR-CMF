<?php

 /* 
  * 
  * RevolveR Authentification
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

final class Auth {

  protected static $model;

  protected static $cipher;

  public static $ssl;

  function __construct( Model $model, Cipher $cipher, ?bool $ssl ) {

    self::$model = $model;
    self::$cipher = $cipher;
    self::$ssl = $ssl;

  }

  public static function login( string $token, string $id ): void {

    $te = explode( '|', $token );

    if( isset($te[ 0 ]) && isset($te[ 1 ]) && isset($te[ 2 ]) ) {

      if( !in_array(session_status(), [ PHP_SESSION_DISABLED, PHP_SESSION_NONE ], true) ) { 

        session_destroy();

      }

      self::setCookie([

        [ 'usertoken', self::$cipher::crypt('encrypt', $token), time() + 86400, '/' ],

        [ 'accepted-privacy-policy', 'accepted', time() + 86400, '/' ],

        [ 'authorization', 1, time() + 86400, '/' ]

      ]);

      session_name('__RevolveR_sessid');

      session_start();

      session_regenerate_id(true);

      $session = md5( uniqid() .'|'. $token[ 0 ] .'|'. $token[ 1 ] .'|'. $token[ 2 ] );

      $_SESSION['session_token'] = $session;

      if( is_numeric($id) ) {

        self::$model::set('users', [

          'id'          => $id,
          'session_id'  => $session,
          'criterion'   => 'id'

        ]);

      }

      self::setCookie([

        [ 'accepted-privacy-policy', session_id(), time() + 86400, '/' ]

      ]);

    }

  }

  public static function logout(): void {

    session_destroy(); 

    if( count( SV['c'] ) > 0 ) {

      foreach( SV['c'] as $n => $v ) {

        self::setCookie([

          [ $n, null, -1, '/' ]

        ]);

      }

    }

  }

  private static function constructCookie( iterable $c ): string {

    $s = 'Set-Cookie: __RevolveR_'. $c[0] .'='. rawurlencode( $c[1] ) .'; Expires='. date('D, d M Y H:i:s', $c[2]) . 'GMT' .'; Path='. $c[3] .'; Domain='. $_SERVER['HTTP_HOST'] .';';

    if( self::$ssl ) {

      $s .= ' SameSite=Strict; Secure; httpOnly;';

    }

    return $s;

  }

  public static function setCookie( iterable $d ): void {

    if( isset($d[ 0 ]) ) {

      foreach( $d as $dc ) {

        if( is_array($dc) ) {

          header(

            self::constructCookie($dc), false

          );

        }

      }

    }

  }

}

?>
