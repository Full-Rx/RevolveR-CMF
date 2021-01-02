<?php

 /*
  * 
  * RevolveR Node Talk 
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
  * Developer: Maltsev Dmitry
  *
  * License: Apache 2.0
  *
  */

$title = $RNV->lang['Talk'];

$contents  = '<p>'. $RNV->lang['Welcome talk'] .'!</p>';

$contents .= '<div class="revolver-talk">';

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

	foreach( $messages as $message ) {

		$contents .= '<div class="revolver__talk-message">';
		$contents .= '<b>'. $message['user_nickname'] .':</b>';
		$contents .= '<p>'. $message['message'];

		if( $message['message'] === 'Audio' ) {

			$contents .= '<a data-audio='. $message['audio'] .' class="talk-play">[ ► ]</a>';

		}

		if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

			$contents .= '<a class="talk-abuse" data-abuse="'. $message['id'] .'">[ X ]</a>';

		}

		$contents .= '</p>';
		$contents .=' </div>';

	}

}

$contents .= '</div>';

if( isset(SV['p']) ) {

	$message = null;

	$nickname = USER['name'];

	if( isset(SV['p']['revolver_talk_shell']) ) {

		if( (bool)SV['p']['revolver_talk_shell']['valid'] ) {

			$message = strip_tags(SV['p']['revolver_talk_shell']['value']);

		}

	}

	if( isset(SV['p']['revolver_talk_nickname']) ) {

		if( (bool)SV['p']['revolver_talk_nickname']['valid'] ) {

			$nickname = strip_tags(SV['p']['revolver_talk_nickname']['value']);

		}

	}

	if( isset(SV['p']['revolver_captcha']) ) {

		if( (bool)SV['p']['revolver_captcha']['valid'] ) {

			if( $RKI->Captcha::verify(SV['p']['revolver_captcha']['value']) ) {

				define('form_pass', 'pass');

			}

		}

	}

	if( defined('form_pass') ) {

		if( form_pass === 'pass' ) {

			$RKI->Model::set('talk', [

				'user_id'		=> USER['id'],
				'user_nickname'	=> $nickname,
				'message'		=> $message,
				'audio'			=> 0

			]);

		}

	}

}

// Talk Form Structure
$form_parameters = [

	// main parameters
	'id'		=> 'talk-form',
	'class'		=> 'revolver__talk revolver__new-fetch',
	'action'	=> '/talk/',
	'method'	=> 'post',
	'encrypt'	=> true,
	'captcha'	=> true,
	'submit'	=> 'Send',

	'fieldsets' => [

		// fieldset contents parameters
		'fieldset_1' => [

			'title' => 'Talk',

			// wrap fields into label
			'labels' => []

		],

	]

];

if( !Auth ) {

	$form_parameters['fieldsets']['fieldset_1']['labels']['label_2'] = [

		'title'  => 'Talk nickname',
		'access' => 'comment',
		'auth'	 => 0,

		'fields' => [

			0 => [

				'type' 			=> 'input:text',
				'name' 			=> 'revolver_talk_nickname',
				'placeholder'	=> 'Type nickname',
				'value'			=> $nickname,
				'required'		=> true,

			],

		]

	];

}

$contents .= '<div class="revolver__user-data" style="display: none;">';
$contents .= '<span id="revolver__user_id">'. USER['id'] .'</span>';
$contents .= '<span id="revolver__user_name">'. ((bool)USER['name'] ? USER['name'] : 'Guest') .'</span>';
$contents .= '</div>';

$form_parameters['fieldsets']['fieldset_1']['labels']['label_1'] = [

	'title'  => 'Talk message',
	'access' => 'comment',
	'auth'	 => 'all',

	'fields' => [

		0 => [

			'type' 			=> 'input:text',
			'name' 			=> 'revolver_talk_shell',
			'placeholder'	=> 'Type message',
			'required'		=> true,

		],

	]

];

$contents .= '<div class="revolver__record-handler">';
$contents .= '<span>[ • record ]</span>';
$contents .= '</div>';

$contents .= $RKI->HTMLForm::build( $form_parameters );

$node_data[] = [

	'title'		=> $title,
	'id'		=> 'talk',
	'route'		=> '/talk/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'published' => 1

];

?>
