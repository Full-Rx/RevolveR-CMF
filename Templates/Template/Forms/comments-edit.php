<?php

if( PASS[ 1 ] === 'comment' && PASS[ 3 ] === 'edit' ) {

	if( in_array( ACCESS['role'], ['Admin', 'Writer', 'User'], true ) ) { 

		$cid = PASS[ 2 ];

		$comment = iterator_to_array(

				$RKI->Model::get( 'node->comment', [

					'criterion' => 'comments::id::'. $cid

				])

			)['model::node->comment'][0];

		$form_parameters = [

			// main parameters
			'id'		=> 'comment-edit-form',
			'class'		=> 'revolver__comment-edit-form revolver__new-fetch',
			'action'	=> '/comments-d/',
			'method'	=> 'post',
			'encrypt'	=> true,
			'captcha'	=> true,	
			'submit'	=> 'Submit',

			// included fieldsets
			'fieldsets' => [

				// fieldset contents parameters
				'fieldset_1' => [

					'title:html' => $RKV->lang['Edit comment'] .' &#8226; '. $comment['comments']['id'] .' '. $RKV->lang['by'] .' '. $comment['comments']['user_name'],

					// wrap fields into label
					'labels' => [

						'label_1' => [

							'title'  => 'Comment',
							'access' => 'comment',
							'auth'	 => 1,

							'fields' => [

								0 => [

									'type' 			=> 'textarea:text',
									'name' 			=> 'revolver_comment_content',
									'placeholder'	=> 'Comment contents',
									'required'		=> true,
									'rows'			=> 5,

									'value:html' 	=> $RKI->HTML::Markup( 

										html_entity_decode(

											htmlspecialchars_decode(

												$comment['comments']['content']

											)

										)

									),

								],

							],

						],

						'label_4' => [

							'title'		=> 'Name',
							'no-label'	=> true,
							'access'	=> 'comment',
							'auth'		=> 1,

							'fields' => [

								1 => [

									'type' 			=> 'input:hidden',
									'name' 			=> 'revolver_node_id',
									'required'		=> true,
									'value'			=> $comment['comments']['node_id']

								],

								2 => [

									'type' 			=> 'input:hidden',
									'name' 			=> 'revolver_comment_user_id',
									'required'		=> true,
									'value'			=> $comment['comments']['user_id']

								],

								3 => [

									'type' 			=> 'input:hidden',
									'name' 			=> 'revolver_comment_time',
									'required'		=> true,
									'value'			=> date('d.m.Y H:i')

								],

								4 => [

									'type' 			=> 'input:hidden',
									'name' 			=> 'revolver_comment_id',
									'required'		=> true,
									'value'			=> $cid

								],

								5 => [

									'type' 			=> 'input:hidden',
									'name' 			=> 'revolver_comment_user_name',
									'required'		=> true,
									'value'			=> $comment['comments']['user_name']

								],

								6 => [

									'type' 			=> 'input:hidden',
									'name' 			=> 'revolver_comment_node_route',
									'required'		=> true,
									'value'			=> $comment['nodes']['route']

								],

								7 => [

									'type' 			=> 'input:hidden',
									'name' 			=> 'revolver_comments_action_edit',
									'required'		=> true,
									'value'			=> 1,

								],

							],

						],

						'label_6' => [

							'title'  => 'Published',
							'access' => 'comment',
							'auth'	 => 1,

							'fields' => [

								0 => [

									'type' 			=> 'input:checkbox:'. ( (bool)$comment['comments']['published'] ? 'checked' : 'unchecked' ),
									'name' 			=> 'revolver_comments_published',
									'value'			=> 1

								],

							],

						],

						'label_7' => [

							'title'  => 'Delete comment',
							'access' => 'comment',
							'auth'	 => 1,

							'fields' => [

								0 => [

									'type' 			=> 'input:checkbox:unchecked',
									'name' 			=> 'revolver_comments_action_delete',
									'value'			=> 1

								],

							],

						],

					],

				],

			]

		];

		$RKI->Template::$b[] = '<article class="revolver__article">';
		$RKI->Template::$b[] = '<header class="revolver__article-header">'; 
		$RKI->Template::$b[] = '<h2>'. $RKV->lang['Edit comment'] .' '. $RKV->lang['by'] .' '. $comment['comments']['user_name'] .'</h2>';

		$RKI->Template::$b[] = '<time datetime="2019-12-31T19:20">'. $comment['comments']['time'] .'</time>';
		$RKI->Template::$b[] = '</header>';

		$RKI->Template::$b[] = $RKI->HTMLForm::build( $form_parameters );

		$RKI->Template::$b[] = '</article>';

	}

}

?>
