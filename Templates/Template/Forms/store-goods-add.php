<?php

if( Auth ) {

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
			'id'		=> 'goods-add-form',
			'class'		=> 'revolver__goods-add-form revolver__new-fetch',
			'action'	=> '/store-goods-d/',
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
											'required'		=> true

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
											'required'		=> true

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
											'required'		=> true

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
											'required'		=> true

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
											'required'		=> true

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
											'required'		=> true

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
											'required'		=> true

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
											'rows'			=> 20

										],

									],

								],

								'label_chechboxes_1' => [

									'title' => 'Delivery',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:checkbox:unchecked',
											'name' 			=> 'revolver_node_edit_delivery',
											'value'			=> 1

										],

									],

								],

								'label_chechboxes_2' => [

									'title' => 'Pickup',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:checkbox:unchecked',
											'name' 			=> 'revolver_node_edit_pickup',
											'value'			=> 1

										],

									],

								],

								'label_chechboxes_3' => [

									'title' => 'Service',
									'access' => 'node',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:checkbox:unchecked',
											'name' 			=> 'revolver_node_edit_service',
											'value'			=> 1

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

				'tab_4' => [

					// tab title
					'title' => 'Choose currency current goods',

					// included fieldsets
					'fieldsets' => [

						// fieldset contents parameters
						'fieldset_4' => [

							'title' => 'Choose currency current goods',

							'labels' => [

								'label_9' => [

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

							],

						],

					],

				], // #tab 4

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

			if( !(bool)$c && !(bool)$node_category ) {

				$categories_options_list .= '<option value="'. $v['id'] .'" selected="selected">'. $v['title'] .'</option>';

			} else if( (int)$node_category === (int)$v['id'] ) {

				$categories_options_list .= '<option value="'. $v['id'] .'" selected="selected">'. $v['title'] .'</option>';

			}
			else {

				$categories_options_list .= '<option value="'. $v['id'] .'">'. $v['title'] .'</option>';

			}

			$c++;

		}

		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels']['label_6']['fields'][0]['value:html'] = $categories_options_list;

		// TAB-4 Language
		$labels_count = 8;

		foreach( $RKI->Language::getLanguageData('*') as $country => $c ) {

			if( isset($c['currency_code']) && isset($c['currency_symb']) ) {

				$labels_count++;

				$form_parameters['tabs']['tab_4']['fieldsets']['fieldset_4']['labels']['label_'. $labels_count]['title:html'] = $RKV->lang['Language'] .' <span class="revolver__stats-system">[ '. $c['code_length_3'] .' :: '. $c['code_length_2'] .' :: '. $c['hreflang'] .' ]</span> <span class="state-attribution laguage-list-item revolver__sa-iso-'. strtolower( $c['code_length_2'] ) .'"></span>'. $RKV->lang['exchange currency'] .' <span class="revolver__stats-country">['. $c['currency_code'] .'] \ { '. $c['currency_symb'] .' }</span>';

				$form_parameters['tabs']['tab_4']['fieldsets']['fieldset_4']['labels']['label_'. $labels_count]['access'] = 'node';
				$form_parameters['tabs']['tab_4']['fieldsets']['fieldset_4']['labels']['label_'. $labels_count]['auth'] = 1;

				$form_parameters['tabs']['tab_4']['fieldsets']['fieldset_4']['labels']['label_'. $labels_count]['fields'][ 0 ]['type']  = 'input:radio:'. ( $c['cipher'] === $index_language ? 'checked' : 'unchecked' ); 
				$form_parameters['tabs']['tab_4']['fieldsets']['fieldset_4']['labels']['label_'. $labels_count]['fields'][ 0 ]['name']  = 'revolver_country_code';
				$form_parameters['tabs']['tab_4']['fieldsets']['fieldset_4']['labels']['label_'. $labels_count]['fields'][ 0 ]['value'] = $c['cipher'];

			}

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