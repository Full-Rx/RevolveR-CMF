<?php

if( in_array(ACCESS['role'], ['Admin', 'Writer'], true) ) { 

	$forum = iterator_to_array(

		$RKI->Model::get('forums', [

			'criterion' => 'id::'. PASS[ 2 ]

		])

	)['model::forums'][0];

	$form_parameters = [

		// main parameters
		'id'	  => 'forum-edit-container-form',
		'class'   => 'forum-containers-edit-form revolver__new-fetch',
		'action'  => '/forum-d/',
		'method'  => 'post',
		'encrypt' => true,
		'captcha' => true,
		'submit'  => 'Submit',

		// included fieldsets
		'fieldsets' => [

			// fieldset contents parameters
			'fieldset_1' => [

				'title' => 'Edit forum container',

				// wrap fields into label
				'labels' => [

					'label_1' => [

						'title'  => 'Container title',
						'access' => 'forum',
						'auth'	 => 1,

						'fields' => [

							0 => [

								'type' 			=> 'input:text',
								'name'			=> 'revolver_forum_container_title',
								'placeholder' 	=> 'Type container name',
								'required'		=> true,
								'value'			=> $forum['title']

							],

						],

					],

					'label_2' => [

						'title'  => 'Container description',
						'access' => 'forum',
						'auth'	 => 1,

						'fields' => [

							0 => [

								'type' 			=> 'input:text',
								'name'			=> 'revolver_forum_container_description',
								'placeholder' 	=> 'Type container description',
								'required'		=> true,
								'value'			=> $forum['description']

							],

							1 => [

								'type' 			=> 'input:hidden',
								'name'			=> 'revolver_forum_container_edit',
								'required'		=> true,
								'value'			=> $forum['id']

							],

						],

					],

					'label_3' => [

						'title'  => 'Delete forum container',
						'access' => 'forum',
						'auth'	 => 1,

						'fields' => [

							0 => [

								'type' 			=> 'input:checkbox:unchecked',
								'name'			=> 'revolver_forum_container_action_delete',
								'placeholder' 	=> 'Type category description',
								'value'			=> 1

							],

						],

					],

				],

			],

		],

	];

	$render_node .= '<article class="revolver__article article-forum-edit">';
	$render_node .= '<header class="revolver__article-header">'; 
	$render_node .= '<h1>'. $RKV->lang['Forum manage'] .' #'. $forum['id'] .'</h1>';
	$render_node .= '</header>';

	$render_node .= $RKI->HTMLForm::build( $form_parameters );

	$render_node .= '</article>';

}

?>
