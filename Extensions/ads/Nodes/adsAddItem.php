<?php

 /* 
  * 
  * RevolveR Create new node
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

$index_language = '840';

if( isset(SV['p']) ) {

	$ads_category = 1;

	if( isset(SV['p']['revolver_ads_title']) ) {

		if( (bool)SV['p']['revolver_ads_title']['valid'] ) {

			$ads_title = strip_tags( SV['p']['revolver_ads_title']['value'] );

		}

	}

	if( isset(SV['p']['revolver_ads_description']) ) {

		if( (bool)SV['p']['revolver_ads_description']['valid'] ) {

			$ads_description = strip_tags( SV['p']['revolver_ads_description']['value'] );

		}

	}

	if( isset(SV['p']['revolver_ads_content']) ) {

		if( (bool)SV['p']['revolver_ads_content']['valid'] ) {

			$ads_content = $markup::Markup(

				SV['p']['revolver_ads_content']['value']

			);

		}

	}

	if( isset(SV['p']['revolver_ads_price']) ) {

		if( (bool)SV['p']['revolver_ads_price']['valid'] ) {

			$ads_price = SV['p']['revolver_ads_price']['value'];

		}

	}

	if( isset(SV['p']['revolver_ads_category']) ) {

		if( (bool)SV['p']['revolver_ads_category']['valid'] ) {

			$ads_category = SV['p']['revolver_ads_category']['value'][0];

		}

	}

	if( isset(SV['p']['revolver_ads_country_code']) ) {

		if( (bool)SV['p']['revolver_ads_country_code']['valid'] ) {

			$ads_country = SV['p']['revolver_ads_country_code']['value'];

		}

	}

	if( isset(SV['p']['revolver_ads_sender_name']) ) {

		if( (bool)SV['p']['revolver_ads_sender_name']['valid'] ) {

			$ads_sender_name = SV['p']['revolver_ads_sender_name']['value'];

		}

	}

	if( isset(SV['p']['revolver_ads_sender_email']) ) {

		if( (bool)SV['p']['revolver_ads_sender_email']['valid'] ) {

			$ads_sender_email = SV['p']['revolver_ads_sender_email']['value'];

		}

	}

	if( isset(SV['p']['revolver_ads_sender_tel']) ) {

		if( (bool)SV['p']['revolver_ads_sender_tel']['valid'] ) {

			$ads_sender_tel = SV['p']['revolver_ads_sender_tel']['value'];

		}

	}

	if( isset(SV['p']['revolver_captcha']) ) {

		if( (bool)SV['p']['revolver_captcha']['valid'] ) {

			if( $RKI->Captcha::verify(SV['p']['revolver_captcha']['value']) ) {

				define('form_pass', 'pass');

			}

		}

	}

	$upload_allow = true;

	if( defined('form_pass') ) {

		if( form_pass === 'pass' ) {

			$hash = md5(date('d.m.Y h:i'));

			$RKI->Model::set('ads_items', [

				'ads_hash'			=> $hash,
				'ads_category'		=> (int)$ads_category,
				'ads_title'			=> $ads_title,
				'ads_description'	=> $ads_description,
				'ads_price'			=> (int)$ads_price,
				'ads_content'		=> $ads_content,
				'ads_time'			=> date('d.m.Y h:i'),
				'ads_country'		=> (int)$ads_country,
				'sender_name'		=> $ads_sender_name,
				'sender_email'		=> $ads_sender_email,
				'sender_phone'		=> $ads_sender_tel

			]);

			if( isset( SV['f'] ) ) {

				if( count(SV['f']) > 0 ) {

					foreach( SV['f'] as $file ) {

						foreach( $file as $f ) {

							$upload_allow = null;

							if( !is_readable($_SERVER['DOCUMENT_ROOT'] .'/Extensions/ads/uploads/'. $f['name']) ) {

								if( (bool)$f['valid'] ) {

									$upload_allow = true;

								}

							}

							if( $upload_allow ) {

								$RKI->Model::set('ads_files', [

									'ads_hash'	=> $hash,
									'file'		=> $f['name']

								]);

								move_uploaded_file( $f['temp'], $_SERVER['DOCUMENT_ROOT'] .'/Extensions/ads/uploads/'. $f['name'] );

							}

						}

					}

				}

			}

			header( 'Location: /ads/' );

		}
		else {

			$RKI->Notify::set('notice', '<div>'. etranslations[ $ipl ]['Security check not pass'] .'.</div>', null);

		}

	}
	else {

		$RKI->Notify::set('notice', '<div>'. etranslations[ $ipl ]['Security check not pass'] .'.</div>', null);

	}

}

$title = etranslations[ $ipl ]['Add ads'];

$form_parameters_html_help .= '<ul class="revolver__allowed-files-description-table">';
$form_parameters_html_help .= '<li class="revolver__table-header">';
$form_parameters_html_help .= '<span class="revolver__allowed-files-description">'. $RNV->lang['File description'] .'</span>';
$form_parameters_html_help .= '<span class="revolver__allowed-files-extension">'. $RNV->lang['Extension'] .'</span>';
$form_parameters_html_help .= '<span class="revolver__allowed-files-size">'. $RNV->lang['Maximum allowed file size'] .'</span>';
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
	'id'		=> 'node-create-form',
	'class'		=> 'revolver__node-create-form revolver__new-fetch',
	'action'	=> $RNV->request,
	'method'	=> 'post',
	'encrypt'	=> true,
	'captcha'	=> true,
	'submit'	=> 'Set',

	// tabs
	'tabs' => [

		'tab_1' => [

			// tab title
			'title'  =>	'Add ads',
			'active' => true,

			// included fieldsets
			'fieldsets' => [

				// fieldset contents parameters
				'fieldset_1' => [

					'title' => 'Ads editor',

					// wrap fields into label
					'labels' => [

						'label_1' => [

							'title'  => 'Ads title',
							'access' => 'comment',
							'auth'	 => 'all',

							'fields' => [

								0 => [

									'type' 			=> 'input:text',
									'name' 			=> 'revolver_ads_title',
									'placeholder'	=> 'Ads title',
									'required'		=> true,
									'value'			=> $ads_title

								],

							],

						],

						'label_2' => [

							'title'  => 'Ads description',
							'access' => 'comment',
							'auth'	 => 'all',

							'fields' => [

								0 => [

									'type' 			=> 'input:text',
									'name' 			=> 'revolver_ads_description',
									'placeholder'	=> 'Ads description',
									'required'		=> true,
									'value'			=> $ads_description

								],

							],

						],

						'label_3' => [

							'title'  => 'Ads price',
							'access' => 'comment',
							'auth'	 => 'all',

							'fields' => [

								0 => [

									'type' 			=> 'input:number',
									'name' 			=> 'revolver_ads_price',
									'placeholder'	=> 'Ads price',
									'required'		=> true,
									'value'			=> $ads_price

								],

							],

						],

						'label_4' => [

							'title'  => 'Ads contents',
							'access' => 'comment',
							'auth'	 => 'all',

							'fields' => [

								0 => [

									'type' 			=> 'textarea:text',
									'name' 			=> 'revolver_ads_content',
									'placeholder'	=> 'Ads contents',
									'required'		=> true,
									'rows'			=> 20,
									'value:html'	=> $ads_content

								],

							],

						],

					],

				],

			],

		], // #tab 1

		'tab_2' => [

			// tab title
			'title' => 'Category',

			// included fieldsets
			'fieldsets' => [

				// fieldset contents parameters
				'fieldset_2' => [

					'title' => 'Category',

					// wrap fields into label
					'labels' => [

						'label_5' => [

							'title'  => 'Choose ads category',
							'access' => 'comment',
							'auth'	 => 'all',

							'fields' => [

								0 => [

									'type' 		=> 'select',
									'name' 		=> 'revolver_ads_category',
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
			'title' => 'Attachements',

			// included fieldsets
			'fieldsets' => [

				// fieldset contents parameters
				'fieldset_3' => [

					'title' => 'Attached Files',

					'labels' => [

						'label_6' => [

							'title'  => 'Choose files to upload',
							'access' => 'comment',
							'auth'	 => 'all',

							'fields' => [

								0 => [

									'type' 		=> 'input:file',
									'name' 		=> 'revolver_ads_files',
									'multiple'	=> true

								],

							],

						],

						'label_7' => [

							'title'   => 'Allowed files',
							'access'  => 'comment',
							'auth'	  => 'all',
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
			'title' => 'Sender info',

			// included fieldsets
			'fieldsets' => [

				// fieldset contents parameters
				'fieldset_4' => [

					'title' => 'Sender info',

					'labels' => [

						'label_8' => [

							'title'  => 'Sender name',
							'access' => 'comment',
							'auth'	 => 'all',

							'fields' => [

								0 => [

									'type' 			=> 'input:text',
									'name' 			=> 'revolver_ads_sender_name',
									'placeholder'	=> 'Sender name',
									'required'		=> true,
									'value'			=> $ads_sender_name

								],

							],

						],

						'label_9' => [

							'title'  => 'Sender email',
							'access' => 'comment',
							'auth'	 => 'all',

							'fields' => [

								0 => [

									'type' 			=> 'input:email',
									'name' 			=> 'revolver_ads_sender_email',
									'placeholder'	=> 'Sender email',
									'required'		=> true,
									'value'			=> $ads_sender_email

								],

							],

						],

						'label_10' => [

							'title'  => 'Sender telephone',
							'access' => 'comment',
							'auth'	 => 'all',

							'fields' => [

								0 => [

									'type' 			=> 'input:tel',
									'name' 			=> 'revolver_ads_sender_tel',
									'placeholder'	=> 'Sender telephone',
									'required'		=> true,
									'value'			=> $ads_sender_tel

								],

							],

						],

					],

				],

			],

		], // #tab 4

		'tab_5' => [

			// tab title
			'title' => 'Choose currency for current ads',

			// included fieldsets
			'fieldsets' => [

				// fieldset contents parameters
				'fieldset_5' => [

					'title' => 'Choose currency for current ads',

					'labels' => [


					],

				],

			],

		], // #tab 5

	]

];

// TAB-2 Category Choose
$categories_options_list = '';

$c = 0;

foreach( iterator_to_array(

		$RKI->Model::get('ads_categories', [

			'criterion' => 'id::*',
			'course'	=> 'forward',
			'sort' 		=> 'id'

		])

	)['model::ads_categories'] as $k => $v ) {

	if( !(bool)$c && !(bool)$ads_category ) {

		$categories_options_list .= '<option value="'. $v['id'] .'" selected="selected">'. $v['title'] .'</option>';

	} 
	else if( (int)$ads_category === (int)$v['id'] ) {

		$categories_options_list .= '<option value="'. $v['id'] .'" selected="selected">'. $v['title'] .'</option>';

	}
	else {

		$categories_options_list .= '<option value="'. $v['id'] .'">'. $v['title'] .'</option>';

	}

	$c++;

}

$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels']['label_5']['fields'][ 0 ]['value:html'] = $categories_options_list;

// TAB-5 Language
$labels_count = 10;

foreach( $RKI->Language::getLanguageData('*') as $country => $c ) {

	if( isset($c['currency_code']) && isset($c['currency_symb']) ) {

		$labels_count++;

		$form_parameters['tabs']['tab_5']['fieldsets']['fieldset_5']['labels']['label_'. $labels_count]['title:html'] = $RKV->lang['Language'] .' <span class="revolver__stats-system">[ '. $c['code_length_3'] .' :: '. $c['code_length_2'] .' :: '. $c['hreflang'] .' ]</span> <span class="state-attribution laguage-list-item revolver__sa-iso-'. strtolower( $c['code_length_2'] ) .'"></span>'. $RKV->lang['exchange currency'] .' <span class="revolver__stats-country">['. $c['currency_code'] .'] \ { '. $c['currency_symb'] .' }</span>';

		$form_parameters['tabs']['tab_5']['fieldsets']['fieldset_5']['labels']['label_'. $labels_count]['access'] = 'comment';
		$form_parameters['tabs']['tab_5']['fieldsets']['fieldset_5']['labels']['label_'. $labels_count]['auth'] = 'all';

		$form_parameters['tabs']['tab_5']['fieldsets']['fieldset_5']['labels']['label_'. $labels_count]['fields'][ 0 ]['type']  = 'input:radio:'. ( $c['cipher'] === $index_language ? 'checked' : 'unchecked' ); 
		$form_parameters['tabs']['tab_5']['fieldsets']['fieldset_5']['labels']['label_'. $labels_count]['fields'][ 0 ]['name']  = 'revolver_ads_country_code';
		$form_parameters['tabs']['tab_5']['fieldsets']['fieldset_5']['labels']['label_'. $labels_count]['fields'][ 0 ]['value'] = $c['cipher'];

	}

}

$contents .= $RKI->HTMLForm::build( $form_parameters, true, etranslations );

$node_data[] = [

	'title'		=> etranslations[ $ipl ]['Add ads'],
	'id'		=> 'create',
	'route'		=> '/ads/additem/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'published' => 1

];

?>
