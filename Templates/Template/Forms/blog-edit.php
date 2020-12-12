<?php

if( in_array(ACCESS['role'], ['Admin', 'Writer', 'User'], true) ) { 

	$blog_node = iterator_to_array(

		$RKI->Model::get( 'blog_nodes', [

			'criterion' => 'route::'. explode('/edit/', $RKV->request)[0] .'/',

			'bound'		=> [

				1

			],

			'course'	=> 'backward',
			'sort' 		=> 'id'

		])

	)['model::blog_nodes'][0];

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
		'id' 	  => 'node-edit-blog-form',
		'class'	  => 'revolver__node-edit-blog-form revolver__new-fetch',
		'enctype' => 'multipart/form-data',
		'action'  => '/blog-d/',
		'method'  => 'post',
		'encrypt' => true,
		'captcha' => null,
		'submit'  => 'Submit',

		// tabs
		'tabs' => [

			'tab_1' => [

				// tab title
				'title'  => 'Blog item editor',
				'active' => true,

				// included fieldsets
				'fieldsets' => [

					// fieldset contents parameters
					'fieldset_1' => [

						'title:html' => $RKV->lang['Editor'] .' &#8226; '. $blog_node['id'],

						// wrap fields into label
						'labels' => [

							'label_1' => [

								'title'  => 'Blog item title',
								'access' => 'blog',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type' 			=> 'input:text',
										'name' 			=> 'revolver_blog_edit_title',
										'placeholder'	=> 'Blog item title',
										'required'		=> true,
										'value'			=> $blog_node['title']

									],

								],

							],

							'label_2' => [

								'title'  => 'Blog item description',
								'access' => 'blog',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type' 			=> 'input:text',
										'name' 			=> 'revolver_blog_edit_description',
										'placeholder'   => 'Blog item description',
										'required'		=> true,
										'value'			=> $blog_node['description']

									],

								],

							],

							'label_4' => [

								'title'  => 'Blog item contents',
								'access' => 'blog',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type' 			=> 'textarea:text',
										'name' 			=> 'revolver_blog_edit_content',
										'placeholder'	=> 'Blog item contents',
										'required'		=> true,
										'rows'			=> 20,
										'value:html'	=> $RKI->HTML::Markup(

											html_entity_decode(

												htmlspecialchars_decode(

													$blog_node['content']

												)

											)

										),

									],

								],

							],

							'label_hiddens_0' => [

								'no-label' => true,
								'access' => 'blog',
								'auth' => 1,

								'fields' => [

									0 => [

										'type' 			=> 'input:hidden',
										'name' 			=> 'revolver_node_edit_id',
										'required'		=> true,
										'readonly'		=> true,
										'value'			=> $blog_node['id']

									],

									1 => [

										'type' 			=> 'input:hidden',
										'name' 			=> 'revolver_node_route',
										'required'		=> true,
										'readonly'		=> true,
										'value'			=> $blog_node['route']

									],

									2 => [

										'type' 			=> 'input:hidden',
										'name' 			=> 'revolver_node_user',
										'required'		=> true,
										'readonly'		=> true,
										'value'			=> $blog_node['user']

									],

								],

							],

							'label_chechboxes_1' => [

								'title'  => 'Publish node',
								'access' => 'blog',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type' 			=> 'input:checkbox:'. ((bool)$blog_node['published'] ? 'checked' : 'unchecked'),
										'name' 			=> 'revolver_node_published',
										'value'			=> 1

									],

								],

							],

							'label_chechboxes_2' => [

								'title'  => 'Delete blog item',
								'access' => 'topic',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type' 			=> 'input:checkbox:unchecked',
										'name' 			=> 'revolver_blog_edit_delete',
										'value'			=> 'delete',

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

		]

	];

	$render_node_html_files .= '<dl>';

	$file_limit = 0;
	$file_count = 0;

	$files_list = iterator_to_array(

			$model::get('blog_files', [

				'criterion' => 'node::'. $blog_node['route'],
				'course'	=> 'forward',
				'sort' 		=> 'id'

			])

		)['model::blog_files'];

	if( $files_list ) {

		foreach( $files_list as $file ) {

			$attached_file = '/public/bfiles/'. $file['name'];

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
	$render_node .= '<article class="revolver__article article-id-'. $n['id'] .'-edit">';
	$render_node .= '<header class="revolver__article-header">'; 
	$render_node .= '<h2>'. $title .'</h2>';
	$render_node .= '</header>';

	$render_node .= $RKI->HTMLForm::build( $form_parameters, true );

	$render_node .= '</article>';

}

?>
