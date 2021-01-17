<?php

 /* 
  * 
  * RevolveR Route User
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

/* Terminal */
if( isset( SV['p'] ) && ROLE === 'Admin' ) {

  $command = null;

  if( isset( SV['p']['revolver_command_shell'] ) ) {

    if( (bool)SV['p']['revolver_command_shell']['valid'] ) { 

      $command = SV['p']['revolver_command_shell']['value'];

    }

  }

  if( isset(SV['p']['revolver_captcha']) ) {

    if( (bool)SV['p']['revolver_captcha']['valid'] ) {

      if( $RKI->Captcha::verify( SV['p']['revolver_captcha']['value'] ) ) {

        define('form_pass', 'pass');

      }

    }

  }

}

$result = json_encode([ 

  'command' => $command,
  'output'  => $RNV->lang['Security check not pass']

]);

if( defined('form_pass') ) {

  if( form_pass === 'pass' ) {

    if( $command ) {

      print json_encode([ 

        'command' => $command,
        'output'  => shell_exec($command)

      ]);
 
    } 
    else {

      print $result; 

    }

  } 
  else {

    print $result;

  }

} 
else {

  print $result;

}

define('serviceOutput', [

  'ctype'     => 'application/json',
  'route'     => '/terminal-s/'

]);

?>
