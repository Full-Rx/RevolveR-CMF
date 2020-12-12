<?php

 /* 
  * 
  * RevolveR Route Talker
  *
  * v.2.0.0.0
  *
  *
  *
  *
  *
  *               ^
  *              | |
  *            @#####@
  *          (###   ###)-.
  *        .(###     ###) \
  *       /  (###   ###)   )
  *      (=-  .@#####@|_--"
  *      /\    \_|l|_/ (\
  *     (=-\     |l|    /
  *      \  \.___|l|___/
  *      /\      |_|   /
  *     (=-\._________/\
  *      \             /
  *        \._________/
  *          #  ----  #
  *          #   __   #
  *          \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

if( !empty($_SERVER['HTTP_REFERER']) && ( $_SERVER['HTTP_HOST'] === parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)) ) {

  if( !empty(SV['p']) ) {

    $id = $name = $message = $audio = null;

    if( isset(SV['p']['revolver_user_id']) ) {

      if( (bool)SV['p']['revolver_user_id']['valid'] ) {

        $id = strip_tags(SV['p']['revolver_user_id']['value']);

      }

    }

    if( isset(SV['p']['revolver_user_nickname']) ) {

      if( (bool)SV['p']['revolver_user_nickname']['valid'] ) {

        $name = strip_tags(SV['p']['revolver_user_nickname']['value']);

      }

    }

    if( isset(SV['p']['revolver_message']) ) {

      if( (bool)SV['p']['revolver_message']['valid'] ) {

        $message = strip_tags(SV['p']['revolver_message']['value']);

      }

    }

    $adata = file_get_contents( $_FILES['revolver_audio']['tmp_name'] );

    $aname = md5( $adata ) .'.wav';

    $f = fopen( $_SERVER['DOCUMENT_ROOT'] .'/public/talk/'. $aname, 'wb' );

    fwrite( $f, $adata );

    fclose( $f );

    if( isset( $_GET['audio'] ) ) {

      if( $id && $name && $message ) {

        $RKI->Model::set('talk', [

          'user_id'       => $id,
          'user_nickname' => $name,
          'message'       => $message,
          'audio'         => $aname

        ]);

      }

    }

  }

  if( isset(SV['g']['abuse']) ) {

    if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

      $file_id = iterator_to_array(

          $RKI->Model::get('talk', [

            'criterion' => 'id::'. (int)SV['g']['abuse']['value'],

            'course'  => 'backward',
            'sort'    => 'id'

          ])

      )['model::talk'][0]['audio'];

      unlink($_SERVER['DOCUMENT_ROOT'] .'/public/talk/'. $file_id);

      $RKI->Model::erase('talk', [

        'criterion' => 'id::'. (int)SV['g']['abuse']['value']

      ]);

    }

  }

  $messages = iterator_to_array(

      $RKI->Model::get('talk', [

        'criterion' => 'id::*',

        'bound'   => [

          50

        ],

        'course'    => 'forward',
        'sort'      => 'id'

      ])

    )['model::talk'];

  $result = [];

  if( $messages ) {

    $c = 0;

    foreach( $messages as $message ) {

      if( $message['message'] === 'Audio' ) {

        $result[ $c ] = [

          'name'    => $message['user_nickname'],
          'message' => $message['message'],
          'audio'   => $message['audio'],

        ];

      } 
      else {

        $result[ $c ] = [

          'name'    => $message['user_nickname'],
          'message' => $message['message'],

        ];

      }

      if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

        $result[ $c ]['id'] = $message['id'];

      }

      $c++;

    }

  }

  print json_encode( $result );

} 
else {

  print json_encode( ['Secure' => 'External host prevented!'] );

}

define('serviceOutput', [

  'ctype'     => 'application/json',
  'route'     => '/talker-s/'

]);

?>
