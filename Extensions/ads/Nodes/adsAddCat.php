<?php

/** 
  * 
  * RevolveR Ads Extension ads logic
  *
  * v.2.0.0.0
  *
  */

$contents  = '';

if( ROLE !== 'none' ) {

	if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

		if( isset(SV['p']) ) {

			$category_title = $category_description = null;

			if( isset(SV['p']['revolver_ads_category_title']) ) {

				if( (bool)SV['p']['revolver_ads_category_title']['valid'] ) {

					$category_title = strip_tags( SV['p']['revolver_ads_category_title']['value'] );

				}

			}

			if( isset(SV['p']['revolver_ads_category_description']) ) {

				if( (bool)SV['p']['revolver_ads_category_description']['valid'] ) {

					$category_description = strip_tags( SV['p']['revolver_ads_category_description']['value'] );

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

				if( form_pass === 'pass' && (bool)SV['p']['identity']['validity'] ) {

					$RKI->Model::set('ads_categories', [

						'title'			=> $category_title,
						'description'	=> $category_description

					]);

					//$RKI->Notify::set('status', 'Category created');

					header('Location: /ads/');

				}

			}

		}


		$form_parameters = [

			// main parameters
			'id'		=> 'categories-add-form',
			'class'		=> 'revolver__categories-add-form revolver__new-fetch',
			'method'	=> 'post',
			'action'	=> $RNV->request,
			'encrypt'	=> true,
			'captcha'	=> true,
			'submit'	=> 'Set',

			// included fieldsets
			'fieldsets' => [

				// fieldset contents parameters
				'fieldset_1' => [

					'title' => 'Add category',
					
					// wrap fields into label
					'labels' => [

						'label_1' => [

							'title'  => 'Category title',
							'access' => 'categories',
							'auth'	 => 1,

							'fields' => [

								0 => [

									'type'			=> 'input:text',
									'name'			=> 'revolver_ads_category_title',
									'placeholder'	=> 'Type category name',
									'required'		=> true

								],

							],

						],

						'label_2' => [

							'title'	 => 'Category description',
							'access' => 'categories',
							'auth'	 => 1,

							'fields' => [

								0 => [

									'type'			=> 'input:text',
									'name'			=> 'revolver_ads_category_description',
									'placeholder'	=> 'Type category description',
									'required'		=> true

								],

							],

						],

					],

				],

			],

		];

		$contents .= $RKI->HTMLForm::build( $form_parameters, null, etranslations );

	}

}

$node_data[] = [

	'title'		=> etranslations[ $ipl ]['Add ads category'],
	'id'		=> 'ads',
	'route'		=> '/ads/addcat/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'time'		=> null,
	'published'	=> 1

];

?>
