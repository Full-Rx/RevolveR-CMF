<?php

if( Auth ) {

	$node = iterator_to_array(

		$RKI->Model::get('store_goods', [

			'criterion' => 'id::'. (int)PASS[ 3 ],
			'course'	=> 'forward',
			'sort'		=> 'id'

		])

	)['model::store_goods'];

	if( !$node ) {

		header('Location: '. $RKV->host .'/store/' );

	} 
	else {

		$node = $node[0];

	}

	if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

		$index_language = '840';

		$title = $RKV->lang['Create Node'];

		$form_parameters_html_help .= '<ul class="revolver__allowed-files-description-table">';
		$form_parameters_html_help .= '<li class="revolver__table-header">';
		$form_parameters_html_help .= '<span class="revolver__allowed-files-description">'. $RKV->lang['File description'] .'</span>';
		$form_parameters_html_help .= '<span class="revolver__allowed-files-extension">'. $RKV->lang['Extension'] .'</span>';
		$form_parameters_html_help .= '<span class="revolver__allowed-files-size">'. $RKV->lang['Maximum allowed file size'] .'</span>';
		$form_parameters_html_help .= '<li>';

		foreach( $D::$file_descriptors as $allowed_files ) {

			if( in_array($allowed_files['extension'], ['jpg', 'jpeg', 'png', 'webp']) ) {

				$form_parameters_html_help .= '<li>';
				$form_parameters_html_help .= '<span class="revolver__allowed-files-description">'. $allowed_files['description'] .'</span>';
				$form_parameters_html_help .= '<span class="revolver__allowed-files-extension">'. $allowed_files['extension'] .'</span>';
				$form_parameters_html_help .= '<span class="revolver__allowed-files-size">'. round((int)$allowed_files['max-size'] / 1024, 1, PHP_ROUND_HALF_ODD) .' Kb</span>';
				$form_parameters_html_help .= '</li>';

			}

		}

		$form_parameters_html_help .= '</ul>';

		// Node create Form Structure
		$form_parameters = [

			// main parameters
			'id'		=> 'goods-edit-form',
			'class'		=> 'revolver__goods-add-form revolver__new-fetch',
			'action'	=> '/store-goods-edit-d/',
			'method'	=> 'post',
			'encrypt'	=> true,
			'captcha'	=> true,
			'submit'	=> 'Submit',

			// tabs
			'tabs' => [

				'tab_1' => [

					// tab title
					'title'  => 'Goods editor',
					'active' => true,

					// included fieldsets
					'fieldsets' => [

						// fieldset contents parameters
						'fieldset_1' => [

							'title' => 'New goods editor',

							// wrap fields into label
							'labels' => [

								'label_1' => [

									'title'  => 'Goods title',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:text',
											'name' 			=> 'revolver_node_edit_title',
											'placeholder'	=> 'Goods title',
											'required'		=> true,
											'value'			=> $node['title'],

										],

									],

								],

								'label_2' => [

									'title'  => 'Goods description',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:text',
											'name' 			=> 'revolver_node_edit_description',
											'placeholder'	=> 'Goods description',
											'required'		=> true,
											'value'			=> $node['description'],

										],

									],

								],

								'label_vendor' => [

									'title'  => 'Goods vendor',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:text',
											'name' 			=> 'revolver_node_edit_vendor',
											'placeholder'	=> 'Goods vendor',
											'required'		=> true,
											'value'			=> $node['vendor'],

										],

									],

								],

								'label_quantity' => [

									'title'  => 'Goods quantity',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:number',
											'name' 			=> 'revolver_node_edit_quantity',
											'placeholder'	=> 'Goods quantity',
											'required'		=> true,
											'value'			=> $node['quantity'],

										],

									],

								],

								'label_3' => [

									'title'  => 'Goods price',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:number',
											'name' 			=> 'revolver_node_edit_price',
											'placeholder'	=> 'Goods price',
											'required'		=> true,
											'value'			=> $node['price'],

										],

									],

								],

								'label_tax' => [

									'title'  => 'Tax %',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:number',
											'name' 			=> 'revolver_node_edit_tax',
											'placeholder'	=> 'Tax %',
											'required'		=> true,
											'value'			=> $node['tax'],

										],

									],

								],

								'label_4' => [

									'title'  => 'Goods % rebate',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:number',
											'name' 			=> 'revolver_node_edit_rebate',
											'placeholder'	=> 'Goods % rebate',
											'required'		=> true,
											'value'			=> $node['rebate'],

										],

									],

								],

								'label_5' => [

									'title'  => 'Goods full description',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'textarea:text',
											'name' 			=> 'revolver_node_edit_content',
											'placeholder'	=> 'Goods full description',
											'required'		=> true,
											'rows'			=> 20,
											'value:html'	=> $node['content'],

										],

									],

								],

								'label_hiddens_0' => [

									'no-label' => true,
									'access' => 'node',
									'auth' => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:hidden',
											'name' 			=> 'revolver_node_edit_id',
											'required'		=> true,
											'readonly'		=> true,
											'value'			=> $node['id']

										],

									],

								],

								'label_chechboxes_3' => [

									'title' => 'Delivery',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:checkbox:'. ((bool)$node['delivery'] ? 'checked' : 'unchecked'),
											'name' 			=> 'revolver_node_edit_delivery',
											'value'			=> 1

										],

									],

								],

								'label_chechboxes_4' => [

									'title' => 'Pickup',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:checkbox:'. ((bool)$node['pickup'] ? 'checked' : 'unchecked'),
											'name' 			=> 'revolver_node_edit_pickup',
											'value'			=> 1

										],

									],

								],

								'label_chechboxes_5' => [

									'title' => 'Service',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:checkbox:'. ((bool)$node['service'] ? 'checked' : 'unchecked'),
											'name' 			=> 'revolver_node_edit_service',
											'value'			=> 1

										],

									],

								],

								'label_chechboxes_1' => [

									'title' => 'Publish node',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:checkbox:'. ((bool)$node['published'] ? 'checked' : 'unchecked'),
											'name' 			=> 'revolver_node_published',
											'value'			=> 1

										],

									],

								],

								'label_chechboxes_2' => [

									'title' => 'Delete node',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:checkbox:unchecked',
											'name' 			=> 'revolver_node_edit_delete',
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
					'title' => 'Goods category',

					// included fieldsets
					'fieldsets' => [

						// fieldset contents parameters
						'fieldset_2' => [

							'title' => 'Goods category',

							// wrap fields into label
							'labels' => [

								'label_6' => [

									'title'  => 'Choose goods category',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 		=> 'select',
											'name' 		=> 'revolver_node_edit_category',
											'required'	=> true

										],

									],

								],

							],

						],

					],

				], // #tab 2

				'tab_3' => [

					// tab title
					'title' => 'Goods cover files',

					// included fieldsets
					'fieldsets' => [

						// fieldset contents parameters
						'fieldset_3' => [

							'title' => 'Goods cover files',

							'labels' => [

								'label_7' => [

									'title'  => 'Choose files to upload',
									'access' => 'node',
									'auth'	 => 1,

									'fields' => [

										0 => [

											'type' 		=> 'input:file',
											'name' 		=> 'revolver_node_files',
											'multiple'	=> true

										],

									],

								],

								'label_8' => [

									'title'  => 'Allowed files',
									'access' => 'node',
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

				], // #tab 3

			]

		];

		// TAB-2 Category Choose
		$categories_options_list = '';

		$c = 0;

		foreach( iterator_to_array(

				$RKI->Model::get('store_categories', [

					'criterion' => 'id::*',
					'course'	=> 'forward',
					'sort' 		=> 'id'

				])

			)['model::store_categories'] as $k => $v ) {

			if( !(bool)$c && !(bool)$node['category'] ) {

				$categories_options_list .= '<option value="'. $v['id'] .'" selected="selected">'. $v['title'] .'</option>';

			} else if( (int)$node['category'] === (int)$v['id'] ) {

				$categories_options_list .= '<option value="'. $v['id'] .'" selected="selected">'. $v['title'] .'</option>';

			}
			else {

				$categories_options_list .= '<option value="'. $v['id'] .'">'. $v['title'] .'</option>';

			}

			$c++;

		}

		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels']['label_6']['fields'][ 0 ]['value:html'] = $categories_options_list;

		$render_node_html_files .= '<dl>';

		$file_limit = 0;
		$file_count = 0;

		$files_list = iterator_to_array(

				$RKI->Model::get( 'store_goods_files', [

					'criterion' => 'node::'. $node['id'],
					'course'	=> 'forward',
					'sort' 		=> 'id'

				])

			)['model::store_goods_files'];

		if( isset( $files_list[ 0 ] ) ) {

			foreach( $files_list as $file ) {

				$attached_file = '/public/sfiles/'. $file['name'];

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

			$form_parameters['tabs']['tab_3']['fieldsets']['fieldset_3']['labels']['label_9']['title'] = 'Attachements';
			$form_parameters['tabs']['tab_3']['fieldsets']['fieldset_3']['labels']['label_9']['access'] = 'node';
			$form_parameters['tabs']['tab_3']['fieldsets']['fieldset_3']['labels']['label_9']['auth'] = 1;
			$form_parameters['tabs']['tab_3']['fieldsets']['fieldset_3']['labels']['label_9']['fields'][ 0 ]['html:contents'] = $render_node_html_files;	

		}


		$RKI->Template::$b[] = '<article class="revolver__article article-id-store-goods-add">';
		$RKI->Template::$b[] = '<header class="revolver__article-header">';
		$RKI->Template::$b[] = '<h1>'. $RKV->lang['Add goods'] .'</h1>';
		$RKI->Template::$b[] = '</header>';

		$RKI->Template::$b[] = '<div class="revolver__article-contents">';

		$RKI->Template::$b[] = $RKI->HTMLForm::build( $form_parameters, true );
		
		$RKI->Template::$b[] = '</div>';

		$RKI->Template::$b[] = '</article>';


	}

}

?>