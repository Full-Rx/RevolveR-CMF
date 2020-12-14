<?php

if( USER['name'] === $n['author'] ) {

	$form_parameters_html_help  = '<ul class="revolver__allowed-files-description-table">';
	$form_parameters_html_help .= '<li class="revolver__table-header">';
	$form_parameters_html_help .= '<span class="revolver__allowed-files-description">'. $RKV->lang['File description'] .'</span>';
	$form_parameters_html_help .= '<span class="revolver__allowed-files-extension">'. $RKV->lang['Extension'] .'</span>';
	$form_parameters_html_help .= '<span class="revolver__allowed-files-size">'. $RKV->lang['Maximum allowed file size'] .'</span>';
	$form_parameters_html_help .= '<li>';

	foreach( $D::$file_descriptors as $allowed_files ) {

		$form_parameters_html_help .= '<li>';
		$form_parameters_html_help .= '<span class="revolver__allowed-files-description">'. $allowed_files['description'] .'</span>';
		$form_parameters_html_help .= '<span class="revolver__allowed-files-extension">'. $allowed_files['extension'] .'</span>';

		$form_parameters_html_help .= '<span class="revolver__allowed-files-size">'. round(

			(int)$allowed_files['max-size'] / 1024, 1, PHP_ROUND_HALF_ODD

		) .' Kb</span>';

		$form_parameters_html_help .= '</li>';

	}

	$form_parameters_html_help .= '</ul>';

	// Node Edit Form Structure
	$form_parameters = [

		// main parameters
		'id' 	  => 'node-edit-topic-form',
		'class'	  => 'revolver__node-edit-topic-form revolver__new-fetch',
		'enctype' => 'multipart/form-data',
		'action'  => '/topic-d/',
		'method'  => 'post',
		'encrypt' => true,
		'captcha' => null,
		'submit'  => 'Submit',

		// tabs
		'tabs' => [

			'tab_1' => [

				// tab title
				'title'  => 'Topic editor',
				'active' => true,

				// included fieldsets
				'fieldsets' => [

					// fieldset contents parameters
					'fieldset_1' => [

						'title:html' => $RKV->lang['Editor'] .' &#8226; topic '. PASS[ 3 ],

						// wrap fields into label
						'labels' => [

							'label_1' => [

								'title'  => 'Topic title',
								'access' => 'topic',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type' 			=> 'input:text',
										'name' 			=> 'revolver_froom_edit_title',
										'placeholder'	=> 'Topic title',
										'required'		=> true,
										'value'			=> $n['title']

									],

								],

							],

							'label_2' => [

								'title'  => 'Topic description',
								'access' => 'topic',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type' 			=> 'input:text',
										'name' 			=> 'revolver_froom_edit_description',
										'placeholder'   => 'Topic description',
										'required'		=> true,
										'value'			=> $n['description']

									],

								],

							],

							'label_4' => [

								'title'  => 'Topic contents',
								'access' => 'topic',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type' 			=> 'textarea:text',
										'name' 			=> 'revolver_froom_edit_content',
										'placeholder'	=> 'Topic contents',
										'required'		=> true,
										'rows'			=> 20,
										'value:html'	=> $RKI->HTML::Markup(

											html_entity_decode(

												htmlspecialchars_decode(

													$n['contents']

												)

											)

										),

									],

								],

							],

							'label_hiddens_0' => [

								'no-label' => true,
								'access' => 'topic',
								'auth' => 1,

								'fields' => [

									0 => [

										'type' 			=> 'input:hidden',
										'name' 			=> 'revolver_froom_edit_id',
										'required'		=> true,
										'readonly'		=> true,
										'value'			=> PASS[ 3 ]

									],

									1 => [

										'type' 			=> 'input:hidden',
										'name' 			=> 'revolver_froom_route',
										'required'		=> true,
										'readonly'		=> true,
										'value'			=> '/forum/'. PASS[ 2 ] .'/'. PASS[ 3 ] .'/'

									],

								],

							],

							'label_chechboxes_2' => [

								'title'  => 'Delete topic',
								'access' => 'topic',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type' 			=> 'input:checkbox:unchecked',
										'name' 			=> 'revolver_froom_edit_delete',
										'value'			=> 'delete'

									],

								],

							],

						],

					],

				],

			], // #tab 1

			'tab_2' => [

				// tab title
				'title' => 'Attachements',

				// included fieldsets
				'fieldsets' => [
					
					// fieldset contents parameters
					'fieldset_3' => [

						'title' => 'Attached Files',
						'labels' => [

							'label_6' => [

								'title'  => 'Choose files to upload',
								'access' => 'topic',
								'auth'	 => 1,
								'fields' => [

									0 => [

										'type' 			=> 'input:file',
										'name' 			=> 'revolver_topic_files',
										'multiple'		=> true

									],

								],

							],

							'label_7' => [

								'title'  => 'Allowed files',
								'access' => 'topic',
								'auth'   => 1,
								'collapse' => true,

								'fields' => [

									0 => [

										'html:contents' => $form_parameters_html_help

									],

								],

							],

						],

					],

				],

			], // #tab 2

			'tab_3' => [

				// tab title
				'title' => 'Forum',

				// included fieldsets
				'fieldsets' => [
					
					// fieldset contents parameters
					'fieldset_2' => [

						'title' => 'Forum',
						
						// wrap fields into label
						'labels' => [

							'label_5' => [

								'title'  => 'Choose forum',
								'access' => 'topic',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type' 			=> 'select',
										'name' 			=> 'revolver_node_edit_forum',
										'required'		=> true

									],

								],

							],

						],

					],

				],

			], // #tab 3

		]

	];


	// TAB-3 Forum Choose
	$forum_options_list = '';

	foreach( iterator_to_array(

			$RKI->Model::get( 'forums', [

				'criterion' => 'id::*',
				'course'	=> 'forward',
				'sort' 		=> 'id'

			])

		)['model::forums'] as $k => $v ) {

		$forum_options_list .= '<option value="'. $v['id'] .'"'. ( $v['id'] === $n['forum'] ? ' selected="selected"' : '') .'>'. $v['title'] .'</option>';

	}

	$form_parameters['tabs']['tab_3']['fieldsets']['fieldset_2']['labels']['label_5']['fields'][0]['value:html'] = $forum_options_list;

	$render_node_html_files .= '<dl>';

	$file_limit = 0;
	$file_count = 0;

	$files_list = iterator_to_array(

			$RKI->Model::get('froom_files', [

				'criterion' => 'froom::'. PASS[ 3 ],
				'course'	=> 'forward',
				'sort' 		=> 'id'

			])

		)['model::froom_files'];

	if( $files_list ) {

		foreach( $files_list as $file ) {

			$attached_file = '/public/tfiles/'. $file['name'];

			foreach( $D::$file_descriptors as $attachement ) {

				if( pathinfo(basename($attached_file), PATHINFO_EXTENSION) === $attachement['extension'] ) {

					$message_files = $attachement['description'] .' .'. $attachement['extension'] .' '. round(((int)filesize(ltrim( $attached_file, '/')) / 1024), 3, PHP_ROUND_HALF_ODD) .' Kb';

					$render_node_html_files .= '<dt><label>'. ($file_limit + 1) .' &#8226; '. $file['name'];
					$render_node_html_files .= '<span>'. $RKV->lang['delete'] .': <input type="checkbox" name="revolver_delete_attached_file_'. $file_limit .'" value="'. $file['id'] .':'. $file['name'] .'" /></span></label>';
					$render_node_html_files .= '</dt>';
					$render_node_html_files .= '<dd style="padding: 0 20px 20px;">';
					$render_node_html_files .= $RKV->lang['file path'];
					$render_node_html_files .= ': <a style="color: #5e5c5d; text-decoration: none; border-bottom: 1px dashed #000; padding-bottom: 3px;" href="'. $attached_file .'" target="_blank">'. $attached_file .'</a> : ';
					$render_node_html_files .= $message_files .'.</dd>';

				}

			}

			$file_limit++;

		}

	}

	$render_node_html_files  .= '</dl>';

	if( (bool)$file_limit ) {

		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels']['label_10']['title'] = 'Attachements';
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels']['label_10']['access'] = 'forum';
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels']['label_10']['auth'] = 1;
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels']['label_10']['fields'][0]['html:contents'] = $render_node_html_files;	

	}

	$render_node  = '';
	$RKI->Template::$b[] = '<article class="revolver__article article-id-'. $n['id'] .'-edit">';
	$RKI->Template::$b[] = '<header class="revolver__article-header">'; 
	$RKI->Template::$b[] = '<h2>'. $n['title'] .'</h2>';
	$RKI->Template::$b[] = '</header>';

	if( isset($n['warning']) ) {

		$RKI->Template::$b[] = $n['warning'];	

	}

	$RKI->Template::$b[] = $RKI->HTMLForm::build( $form_parameters, true );

	$RKI->Template::$b[] = '</article>';

}

?>
