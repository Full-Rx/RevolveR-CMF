<?php

$form_parameters = [

	// Main parameters
	'id' 	  => 'comment-add-form',
	'class'	  => 'revolver__comment-forum-add-form revolver__new-fetch',
	'action'  => '/forum-comments-d/',
	'method'  => 'post',
	'encrypt' => true,
	'captcha' => true,
	'submit'  => 'Submit',

	// Include fieldsets
	'fieldsets' => [

		// fieldset contents parameters
		'fieldset_1' => [

			// wrap fields into label
			'labels' => [

				'label_3' => [

					'title'  =>  'Name',
					'no-label' => true,
					'access' => 'comment',
					'auth'   => 1,

					'fields' => [

						1 => [

							'type' 			=> 'input:hidden',
							'name' 			=> 'revolver_froom_id',
							'value'			=> PASS[ 3 ]

						],

						2 => [

							'type' 			=> 'input:hidden',
							'name' 			=> 'revolver_forum_id',
							'value'			=> PASS[ 2 ]

						],

						3 => [

							'type' 			=> 'input:hidden',
							'name' 			=> 'revolver_comment_user_id',
							'value'			=> USER['id']

						],

						4 => [

							'type' 			=> 'input:hidden',
							'name' 			=> 'revolver_comment_user_name',
							'value'			=> USER['name']

						],

						5 => [

							'type' 			=> 'input:hidden',
							'name' 			=> 'revolver_comment_time',
							'value'			=> date('d.m.Y H:i')

						],

					],

				],

				'label_4' => [

					'title'  => 'Comment',
					'access' => 'comment',
					'auth'	 => 1,

					'fields' => [

						0 => [

							'type' 			=> 'textarea:text',
							'name' 			=> 'revolver_comment_content',
							'placeholder'	=> 'Comment contents',
							'required'		=> true,
							'rows'			=> 10

						],

					],

				],

			],

		],

	],

];

if( FORM_ACCESS !== 'none' ) {

	$form_parameters['fieldsets']['fieldset_1']['title:html'] = $RKV->lang['Add a comment as'] .' '. USER['name'];

}

$render_node .= '<div class="revolver__comments_add">';

$render_node .= $RKI->HTMLForm::build( $form_parameters );

$render_node .= '</div>';

?>
