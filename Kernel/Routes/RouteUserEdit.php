<?php

 /* 
  * 
  * RevolveR Node User Edit
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

if( isset(SV['p']) && ROLE === 'Admin' ) { 

	if( isset(SV['p']['revolver_user_edit_id']) ) {

		if( (bool)SV['p']['revolver_user_edit_id']['valid'] ) {

			$user_id = SV['p']['revolver_user_edit_id']['value'];

		}

	}

	if( isset(SV['p']['revolver_user_edit_role']) ) {

		if( (bool)SV['p']['revolver_user_edit_role']['valid'] ) {

			$user_permissions = SV['p']['revolver_user_edit_role']['value'][0];

		}

	}

	if( isset(SV['p']['revolver_captcha']) ) {

		if( (bool)SV['p']['revolver_captcha']['valid'] ) {

			if( $RKI->Captcha::verify( SV['p']['revolver_captcha']['value'] ) ) {

				define('form_pass', 'pass');

			}

		}

	}

  if( defined('form_pass') ) {

    if( form_pass === 'pass' ) {

      $RKI->Model::set('users', [

        'id'          => $user_id,
        'permissions' => $user_permissions,
        'criterion'   => 'id'

      ]);

      header('Location: '. $RNV->host .'/user/'. $user_id .'/edit/?notification=profile-updated^'. $user_id .'-'. $user_permissions);

    }

  } 
  else {

    header('Location: '. $RNV->host .'/user/'. $user_id .'/edit/?notification=profile-not-updated');

  }

}

print '<!-- User dispatcher service -->';

define('serviceOutput', [

  'ctype'     => 'text/html', 
  'route'     => '/user-d/'

]);

?>
