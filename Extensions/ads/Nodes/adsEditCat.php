<?php

/** 
  * 
  * RevolveR Ads Extension ads logic
  *
  * v.2.0.0.0
  *
  */

$contents = '';

if( ROLE !== 'none' ) {

	if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

		$id = null;

		if( isset(SV['p']) ) {

			if( isset(SV['p']['revolver_ads_category_edit']) ) {

				if( (bool)SV['p']['revolver_ads_category_edit']['valid'] ) {

					$id = strip_tags( SV['p']['revolver_ads_category_edit']['value'] );

				}

			}

		}

		if( isset(SV['g'])) {

			if( isset(SV['g']['id']) ) {

				$id = (int)SV['g']['id']['value'];

				$cat = iterator_to_array(

						$RKI->Model::get('ads_categories', [

							'criterion' => 'id::'. $id,
							'course'	=> 'forward',
							'sort'		=> 'id'

						])

					)['model::ads_categories'];

				if( $cat ) {

					$category_title = $cat[ 0 ]['title'];
					$category_description = $cat[ 0 ]['description'];

				} 
				else {

					header('Location: /ads/');

				}

			}

		}

		if( !$id ) {

			header('Location: /ads/');

		}

		if( isset(SV['p']) ) {

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

				if( form_pass === 'pass' && (bool)SV['p']['identity']['validity'] && $id ) {

					$RKI->Model::set('ads_categories', [

						'id'			=> $id,
						'title'			=> $category_title,
						'description'	=> $category_description

					]);

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
									'value'			=> $category_title,
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
									'value'			=> $category_description,
									'required'		=> true

								],

								1 => [

									'type' 			=> 'input:hidden',
									'name'			=> 'revolver_ads_category_edit',
									'required'		=> true,
									'value'			=> $id

								],

							],

						],

					],

				],

			],

		];

		$contents .= $RKI->HTMLForm::build( $form_parameters, null, etranslations );

	}
	else {

		header('Location: /ads/');

	}

}
else {

	header('Location: /ads/');

}

$node_data[] = [

	'title'		=> etranslations[ $ipl ]['Edit ads category'],
	'id'		=> 'ads',
	'route'		=> '/ads/editcat/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'time'		=> null,
	'published'	=> 1

];

?>
