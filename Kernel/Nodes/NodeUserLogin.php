<?php

 /*
  * 
  * RevolveR Node User Auth
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

if( isset(SV['p']) ) {

	if( isset(SV['p']['revolver_login_user_email']) ) {

		if( (bool)SV['p']['revolver_login_user_email']['valid'] ) {

			$email = SV['p']['revolver_login_user_email']['value'];

		}

	}

	if( isset(SV['p']['revolver_login_user_password']) ) {

		if( (bool)SV['p']['revolver_login_user_password']['valid'] ) {

			$password = $RKI->Cipher::crypt('encrypt', SV['p']['revolver_login_user_password']['value']);

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

if( defined('form_pass') ) {

	if( form_pass === 'pass' ) {

		$user = iterator_to_array(

			$RKI->Model::get( 'users', [

				'criterion' => 'email::'. $email,

				'bound'		=> [

					1

				],

				'course'	=> 'backward',
				'sort' 		=> 'id'

			])

		)['model::users'];

		if( $user ) {

			if( $user[ 0 ]['password'] === $password ) {

				// Secure session
				$token = $user[ 0 ]['email'] .'|'. $user[ 0 ]['password'] .'|'. $user[ 0 ]['nickname'];

				if( form_pass === 'pass' && (bool)SV['p']['identity']['validity'] ) {

					$RKI->Auth::login($token, $user[ 0 ]['id']);

					header('Location: '. $RNV->host .'/?notification=authorized');

				}

			}
			else {

				$RKI->Notify::set('warning', 'Passwords not match');

			}

		}
		else {

			$RKI->Notify::set('inactive', 'User with given email not found');

		}

	}

}

$form_parameters = [

	// main parameters
	'id' 	 => 'auth-form',
	'class'	 => 'revolver__auth-form revolver__new-fetch',
	'action' => '/user/auth/',
	'method' => 'POST',
	'captcha' => true,
	'encrypt' => true,
	'submit'  => 'Submit',

	// included fieldsets
	'fieldsets' => [

		// fieldset contents parameters
		'fieldset_1' => [

			'title' => 'Account login',

			// wrap fields into label
			'labels' => [

				'label_1' => [

					'title'  =>  'User Email',
					'access' => 'auth',
					'auth'   => 0,

					'fields' => [

						0 => [

							'type' 			=> 'input:email',
							'name' 			=> 'revolver_login_user_email',
							'placeholder'	=>  'User Email',
							'required'		=> true,
							'value'			=> ''

						],

					],

				],

				'label_2' => [

					'title'  => 'User password',
					'access' => 'auth',
					'auth'   => 0,

					'fields' => [

						0 => [

							'type' 			=> 'input:password',
							'name' 			=> 'revolver_login_user_password',
							'placeholder'	=> 'User password',
							'required'		=> true,
							'value'			=> ''

						],

					],

				],

			],

		],

	]

];

$contents = $RKI->HTMLForm::build($form_parameters);

$node_data[] = [

	'title'		=> $RNV->lang['Account login'],
	'id'		=> 'user-login',
	'route'		=> '/user/auth/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'published' => 1

];

?>
