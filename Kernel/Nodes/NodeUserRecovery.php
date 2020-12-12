<?php

 /*
  * 
  * RevolveR Node User Recovery
  *
  * v.2.0.0.0
  *
  *
  *
  *
  *
  *			          ^
  *			         | |
  *			       @#####@
  *			     (###   ###)-.
  *			   .(###     ###) \
  *			  /  (###   ###)   )
  *			 (=-  .@#####@|_--"
  *			 /\    \_|l|_/ (\
  *			(=-\     |l|    /
  *			 \  \.___|l|___/
  *			 /\      |_|   /
  *			(=-\._________/\
  *			 \             /
  *			   \._________/
  *			     #  ----  #
  *			     #   __   #
  *			     \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

$user = $recovered = null;

if( !empty(SV['p']) ) {

	if( isset(SV['p']['revolver_recovery_user_email']) ) {

		if( (bool)SV['p']['revolver_recovery_user_email']['valid'] ) {

			$recovery_email = SV['p']['revolver_recovery_user_email']['value'];

		}

	}

	if( isset(SV['p']['revolver_captcha']) ) {

		if( (bool)SV['p']['revolver_captcha']['valid'] ) {

			if( $RKI->Captcha::verify(SV['p']['revolver_captcha']['value']) ) {

				define('form_pass', 'pass');

			}

		}

	}

	$user = iterator_to_array(

		$RKI->Model::get('users', [

			'criterion' => 'email::'. $recovery_email,

			'bound'		=> [

				1 

			],

			'course'	=> 'backward',
			'sort'		=> 'id'

		])

	)['model::users'];

}

$contents = '';

if( defined('form_pass') ) {

	if( $user ) {

		if( form_pass === 'pass' && (bool)SV['p']['identity']['validity'] ) {

			$recovery_password = $RKI->Cipher::crypt('decrypt', $user[ 0 ]['password']);

			// Send Notification to email
			$RKI->Email::send(

				$recovery_email, 'RevolveR Contents Management Framework :: '. $RNV->lang['Account recovery'], 

				'<p>'. $RNV->lang['Hello'] .', '. $user[0]['nickname'] .'! Use your account email: '. $recovery_email .' and password: '. $recovery_password .' to login <a href="'. site_host .'/user/auth/">here</a>.</p>'

			);

			$RKI->Notify::set('status', 'Your Account recovered! Check your email');

			$recovered = true;

		}
		else {

			$RKI->Notify::set('notice', 'Captcha not passed');

		}

	}
	else {

		$RKI->Notify::set('inactive', 'Account not recovered! Unable to find user with given email');

	}

}

$form_parameters = [

	'id' 	 => 'account-recovery-form',
	'class'	 => 'revolver__acount-recovery-form revolver__new-fetch',
	'action' => '/user/recovery/',
	'method' => 'post',
	'encrypt' => true,
	'captcha' => true,
	'submit'  => 'Submit',

	'fieldsets' => [

		'fieldset_1' => [

			'title' => 'Account recovery',

			'labels' => [

				'label_1' => [

					'title'  => 'User Email',
					'access' => 'recovery',
					'auth'   => 0,

					'fields' => [

						0 => [

							'type'			=> 'input:email',
							'name'			=> 'revolver_recovery_user_email',
							'placeholder'	=> 'User Email',
							'required'		=> true

						],

					],

				],

			],

		],

	]

];

if( !$recovered ) {

	$contents .= '<p>'. $RNV->lang['Lost password? Try to recovery to email'] .'.</p>';

}

$node_data[] = [

	'title'		=> $RNV->lang['Account recovery'],
	'id'		=> 'user-recovery',
	'route'		=> '/user/recovery/',
	'contents'	=> $contents . $RKI->HTMLForm::build( $form_parameters ),
	'teaser'	=> null,
	'footer'	=> null,
	'published' => 1

];

?>
