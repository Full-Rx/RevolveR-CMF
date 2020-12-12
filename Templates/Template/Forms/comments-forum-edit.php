<?php

if( PASS[ 4 ] === 'comment' && PASS[ 6 ] === 'edit' ) {

	if( in_array( ACCESS['role'], ['Admin', 'Writer', 'User'], true ) ) { 

		$cid = PASS[ 5 ];

		$comment = iterator_to_array(

				$RKI->Model::get( 'froom_comments', [

					'criterion' => 'id::'. PASS[ 5 ]

				])

			)['model::froom_comments'][0];

		$comment_user = iterator_to_array(

				$RKI->Model::get( 'users', [

					'criterion' => 'id::'. $comment['user_id']

				])

			)['model::users'][0];

		$form_parameters = [

			// main parameters
			'id'		=> 'comment-edit-form',
			'class'		=> 'revolver__comment-forum-edit-form revolver__new-fetch',
			'action'	=> '/forum-comments-d/',
			'method'	=> 'post',
			'encrypt'	=> true,
			'captcha'	=> true,	
			'submit'	=> 'Submit',

			// included fieldsets
			'fieldsets' => [

				// fieldset contents parameters
				'fieldset_1' => [

					'title:html' => $RKV->lang['Edit comment'] .' &#8226; '. $comment['id'] .' '. $RKV->lang['by'] .' '. $comment_user['nickname'],

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

												$comment['content']

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
									'name' 			=> 'revolver_froom_id',
									'required'		=> true,
									'value'			=> $comment['froom_id']

								],

								2 => [

									'type' 			=> 'input:hidden',
									'name' 			=> 'revolver_comment_user_id',
									'required'		=> true,
									'value'			=> $comment['user_id']

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
									'value'			=> $comment_user['nickname']

								],

								6 => [

									'type' 			=> 'input:hidden',
									'name' 			=> 'revolver_forum_id',
									'value'			=> PASS[ 2 ]

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

									'type' 			=> 'input:checkbox:'. ( (bool)$comment['published'] ? 'checked' : 'unchecked' ),
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

		$render_node .= '<article class="revolver__article">';
		$render_node .= '<header class="revolver__article-header">'; 
		$render_node .= '<h2>'. $RKV->lang['Edit comment'] .' '. $RKV->lang['by'] .' '. $comment_user['nickname'] .'</h2>';

		$render_node .= '<time datetime="2019-12-31T19:20">'. $comment['time'] .'</time>';
		$render_node .= '</header>';

		$render_node .= $RKI->HTMLForm::build( $form_parameters );

		$render_node .= '</article>';

	}

}

?>
