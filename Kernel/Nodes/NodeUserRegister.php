<?php

 /* 
  * 
  * RevolveR Node User Register
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

$radio_group_1 = [];

$user_data_password = '';
$user_data_password_confirm = '';

$lng = $ipl;

if( isset(SV['p']) ) {

	if( isset(SV['p']['revolver_registration_name']) ) {

		if( (bool)SV['p']['revolver_registration_name']['valid'] ) {

			$user_data_name = SV['p']['revolver_registration_name']['value'];

		}

	}

	if( isset(SV['p']['revolver_registration_email']) ) {

		if( (bool)SV['p']['revolver_registration_email']['valid'] ) {

			$user_data_email = SV['p']['revolver_registration_email']['value'];

		}

	}

	if( isset(SV['p']['revolver_registration_password']) ) {

		if( (bool)SV['p']['revolver_registration_password']['valid'] ) {

			$user_data_password = SV['p']['revolver_registration_password']['value'];

		}

	}

	if( isset(SV['p']['revolver_registration_password_confirm']) ) {

		if( (bool)SV['p']['revolver_registration_password_confirm']['valid'] ) {

			$user_data_password_confirm = SV['p']['revolver_registration_password_confirm']['value'];

		}

	}

	if( isset(SV['p']['revolver_generate_password']) ) {

		if( (bool)SV['p']['revolver_generate_password']['valid'] ) {

			if( SV['p']['revolver_generate_password']['value'] === 'on' ) {

				define('allow_generate_password', 'true');

			}

		}

	}

	if( isset(SV['p']['revolver_interface_code']) ) {

		if( (bool)SV['p']['revolver_interface_code']['valid'] ) {

			$lng = SV['p']['revolver_interface_code']['value'];

		}

	}

	if( isset(SV['p']['revolver_captcha']) ) {

		if( (bool)SV['p']['revolver_captcha']['valid'] ) {

			if( $RKI->Captcha::verify(SV['p']['revolver_captcha']['value']) ) {

				define('form_pass', 'pass');

			}

		}

	}

}

$c = 0;

$ipl = $lng;

foreach( TRANSLATIONS as $i => $l ) {

	foreach( $RKI->Language::getLanguageData('*') as $cn ) {

		if( $cn['code_length_2'] === $i || $cn['code_length_2'] === 'US' ) {

			$radio_group_1['label_radio_'. $c]['title:html'] = TRANSLATIONS[ $i ]['Language'] .' <span class="revolver__stats-system">[ '. $cn['code_length_3'] .' :: '. $cn['code_length_2'] .' :: '. $cn['hreflang'] .' ]</span> <i class="state-attribution laguage-list-item revolver__sa-iso-'. strtolower( $cn['code_length_2'] ) .'"></i>'. TRANSLATIONS[ $i ]['country'] .' <span class="revolver__stats-country">['. $cn['name'] .']</span>';

		}

	}

	$radio_group_1[ 'label_radio_'. $c ]['access'] = 'register';
	$radio_group_1[ 'label_radio_'. $c ]['auth'] = 0;

	$radio_group_1[ 'label_radio_'. $c ]['fields'] = [

		0 => [

			'type'  => 'input:radio:'. ( $i === $lng ? 'checked' : 'unchecked' ),
			'name' 	=> 'revolver_interface_code',
			'value' => $i

		]

	];

	$c++;

}

$form_parameters = [

	'id'		=> 'register-form',
	'class'		=> 'revolver__register-form revolver__new-fetch',
	'action'	=> '/user/register/',
	'encrypt'	=> true, 
	'captcha'	=> true,
	'method'	=> 'post',
	'submit'	=> 'Submit',

	'fieldsets' => [

		'fieldset_1' => [

			'title' => 'Account registration',

			'labels' => [

				'label_1' => [

					'title'  => 'User Name',
					'access' => 'register',
					'auth'	 => 0,

					'fields' => [

						0 => [

							'type' 			=> 'input:text',
							'name' 			=> 'revolver_registration_name',
							'placeholder'	=> 'User Name',
							'required'		=> true,
							'value'			=> $user_data_name

						],

					],

				],

				'label_2' => [

					'title'  => 'User Email',
					'access' => 'register',
					'auth'	 => 0,

					'fields' => [

						0 => [

							'type' 			=> 'input:email',
							'name' 			=> 'revolver_registration_email',
							'placeholder'	=> 'User Email',
							'required'		=> true,
							'value'			=> $user_data_email

						],

					],

				],

			],

		],

		// fieldset contents parameters
		'fieldset_2' => [

			'title' => 'Website interface language',
			'collapse' => true,

			// wrap fields into label
			'labels' => $radio_group_1

		],

		// fieldset contents parameters
		'fieldset_3' => [

			'title' => 'Choose account password',
			'collapse' => true,

			// wrap fields into label
			'labels' => [

				'label_4' => [

					'title'  =>  'User password',
					'access' => 'register',
					'auth'	 => 0,

					'fields' => [

						0 => [

							'type' 			=> 'input:password',
							'name' 			=> 'revolver_registration_password',
							'placeholder'	=> 'User password',
							'value'			=> $user_data_password

						],

					],

				],

				'label_5' => [

					'title'  =>  'Confirm password',
					'access' => 'register',
					'auth'	 => 0,

					'fields' => [

						0 => [

							'type' 			=> 'input:password',
							'name' 			=> 'revolver_registration_password_confirm',
							'placeholder'	=> 'Confirm password',
							'value'			=> $user_data_password_confirm

						],

					],

				],

				'label_6' => [

					'title'  =>  'Generate password',
					'no-collapse' => true,
					'access' => 'register',
					'auth'	 => 0,

					'fields' => [

						0 => [

							'type' 	=> 'input:checkbox:unchecked',
							'name' 	=> 'revolver_generate_password'

						],

					],

				],

			],

		],

	]

];

$title = $RNV->lang['Account registration'];

$register_form = new HTMLFormBuilder($ipl);

$contents = $register_form::build( $form_parameters );

if( defined('form_pass') ) {

	if( defined('allow_generate_password') ) {

		if( allow_generate_password === 'true' ) {

			$randomPassword = str_ireplace('qq', 'ls', 

				bin2hex(

					random_bytes(5)

				)

			);

			$user_data_password = $randomPassword;

			$user_data_password_confirm = $randomPassword;

		}

	}

	if( strlen($user_data_name) >= 5 && strlen($user_data_password) >= 8 && strlen($user_data_password_confirm) >= 5 ) {

		if( $user_data_password === $user_data_password_confirm ) {

			// Is user account exist
			$passed = true;

			$users_list = iterator_to_array(

					$RKI->Model::get('users', [

						'criterion' => 'id::*',
						'course'	=> 'forward',
						'sort' 		=> 'id'

					])

				)['model::users'];

			foreach( $users_list as $u ) {

				if( $u['email'] === $user_data_email || $u['nickname'] === $user_data_name ) {

					$passed = null;

					$RKI->Notify::set('notice', 'User with given email already registered');

					break;

				}

			}

			if( $passed && form_pass === 'pass' && SV['p']['identity']['validity_count'] >= 5 ) {

				$RKI->Model::set('users', [

					'nickname'  		 => $user_data_name,
					'email' 			 => $user_data_email,
					'password' 			 => $cipher::crypt( 'encrypt', $user_data_password_confirm ),
					'permissions'		 => 'User',
					'session_id'		 => 'no-id',
					'avatar'			 => 'default',
					'telephone'			 => '',
					'interface_language' => $lng

				]);

				$user = iterator_to_array(

					$RKI->Model::get( 'users', [

						'criterion' => 'email::'. $user_data_email,

						'bound'		=> [

							1

						],

						'course'	=> 'backward', // backward
						'sort'		=> 'id'

					])

				)['model::users'];

				if( $user ) {

					// Send welcome message to new user
					$RKI->Model::set('messages', [

						'user_id'	=> $user[0]['id'],
						'to'		=> $user[0]['nickname'],
						'from'		=> $user[0]['nickname'],
						'time'		=> date('d.m.Y h:m'),

						'message'	=> $RKI->HTML::Markup(

							'<p>'. $RNV->lang['Welcome'] .', '. $user_data_name .' '. $RNV->lang['Now you join in User group of this site'] .'!</p>', [ 'xhash' => 1 ] 

						)

					]);

				}

				// Login now 
				$RKI->Auth::login( $user_data_email .'|'. $RKI->Cipher::crypt( 'encrypt', $user_data_password_confirm ) .'|'. $user_data_name, $user[0]['id'] );

				$notification = '<p>'. $RNV->lang['Hello'] .', '. $user_data_name .'! '. $RNV->lang['You join now in User group at'] .' <a href="'. site_host .'">'. site_host .'</p>';

				// Send welcome email
				$RKI->Email::send(

					$user_data_email, $RNV->lang['Account Created'], $notification

				);

				foreach( $users_list as $u ) {

					if( $u['permissions'] === 'Admin' ) {

						// Send notifications to admins
						$RKI->Model::set('messages', [

							'user_id'	=> $u['id'],
							'to'		=> $u['nickname'],
							'from'		=> $u['nickname'],
							'time'		=> date('d.m.Y h:m'),

							'message'	=> $RKI->HTML::Markup(

								'<p>Detected new account '. $user_data_name .'</p>', [ 'xhash' => 1 ]

							)

						]);

					}

				}

				$title = $RNV->lang['Account Created'];

				$RKI->Notify::set('status', 'Account created');

				$RKI->Notify::set('status', 'Registration complite');

				$RKI->Notify::set('active', 'User group permissions granted for you');

				$RKI->Notify::set('active', 'Update page to enter profile and read welcome messages');

				$RKI->Notify::set('inactive', 'You can restore your account password any time to email');

				$contents = '<p>'. $user_data_name .', '. $RNV->lang['welcome! Now you can'] .' <a href="/user/?notification=authorized">'. $RNV->lang['login'] .'</a>! '. $RNV->lang['User password'] .' '. $user_data_password_confirm .'</p>';

			}
			else {

				$title = $RNV->lang['Account not created'] .'!';

				$RKI->Notify::set('notice', 'Security check not pass');

				$RKI->Notify::set('inactive', 'Account not created');

			}

		}
		else {

			$title = $RNV->lang['Account not created'] .'!';

			$RKI->Notify::set('notice', 'Passwords not match');

			$RKI->Notify::set('inactive', 'Account not created');

		}

	}
	else {

		$title = $RNV->lang['Account not created'] .'!';

		$RKI->Notify::set('notice', 'Choosen email, password or name length to small');

		$RKI->Notify::set('inactive', 'Account not created');

	}

}

$node_data[] = [

	'title'		=> $title,
	'id'		=> 'user-register',
	'route'		=> '/user/register/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'published' => 1

];

?>
