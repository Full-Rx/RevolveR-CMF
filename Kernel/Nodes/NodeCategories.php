<?php

 /*
  * 
  * RevolveR Node Categories 
  *
  * v.2.0.1.4
  *
  * Developer: Maltsev Dmitry
  *
  * License: Apache 2.0
  *
  */

$title = $RNV->lang['Contents catergories'];

$contents = '';

if( ROLE !== 'none' ) {

	if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

		if( isset(SV['p']) ) {

			if( isset(SV['p']['revolver_category_title']) ) {

				if( (bool)SV['p']['revolver_category_title']['valid'] ) {

					$title = SV['p']['revolver_category_title']['value'];

				}

			}

			if( isset(SV['p']['revolver_category_description']) ) {

				if( (bool)SV['p']['revolver_category_description']['valid'] ) {

					$description = SV['p']['revolver_category_description']['value'];

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

					$RKI->Model::set('categories', [

						'title'			=> $title,
						'description'	=> $description

					]);

					$RKI->Notify::set('status', 'Category created');

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
			'submit'	=> 'Submit',

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
									'name'			=> 'revolver_category_title',
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
									'name'			=> 'revolver_category_description',
									'placeholder'	=> 'Type category description',
									'required'		=> true

								],

							],

						],

					],

				],

			],

		];


		$contents .= '<h2 class="revolver__collapse-form-legend revolver__collapse-form-legend-form-free">'. $RNV->lang['Add category'] .'</h2>';

		$contents .= '<output class="revolver__collapse-form-contents" style="overflow: hidden; width: 0px; height: 0px; line-height: 0px; display: inline-block; min-height: 0px; opacity: 0; transform: scaleX(1) scaleY(1) scaleZ(1);">';
		$contents .= $RKI->HTMLForm::build( $form_parameters );
		$contents .= '</output>';

	}

}

$contents .= '<dl class="revolver__categories">';

foreach( iterator_to_array(

		$RKI->Model::get('categories', [

			'criterion' => 'id::*',
			'course'	=> 'forward',
			'sort'		=> 'id'

		])

	)['model::categories'] as $category ) {

	if( ROLE !== 'none' ) {

		if( ROLE === 'Admin' || ROLE === 'Writer' ) {

			$contents .= '<dt>&#8226; '. $category['title'] .' &#8226; <span style="float:right">[ <a href="/categories/'. $category['id'] .'/edit/">'. $RNV->lang['Edit'] .'</a> ]</span></dt>';

		}

	}
	else {

		$contents .= '<dt>&#8226; '. $category['title'] .'</dt>';

	}

	$contents .= '<dd><p>'. $category['description'] .'</p>';
	$contents .= '<ul>';

	$language_segments = [];

	foreach( $all_nodes as $node ) {

		$language = $RKI->Language::getLanguageData( $node['country'] );

		if( $node['category'] === $category['id'] && $language['cipher'] === $node['country'] ) {

			if( (bool)$node['published'] ) {

				$layout = '<li>';
				$layout .= '<a hreflang="'. $language['hreflang'] .'" href="'. $node['route'] .'" title="'. $node['description'] .'">'. $node['title'] .'</a>';

				if( ROLE !== 'none' ) {

					if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

						$layout .= '<span style="float:right">[ <a title="'. $RNV->lang['Edit node'] .' '. $node['title'] .'" href="'. $node['route'] .'edit/">'. $RNV->lang['Edit'] .'</a> ]</span>'; 

					}

				}

				$layout .='</li>';

				$language_segments[ $language['cipher'] ][ $language['name'] .'|'. $language['code_length_3'] .'|'. $language['hreflang'] .'|'. $language['code_length_2'] ][] = [

					'layout' => $layout

				];

			}
			else {

				if( in_array(ROLE, ['none', 'User'], true) || ROLE === 'none' ) {

					continue;

				}

				$layout = '<li>';
				$layout .= $node['title'];

				$layout .= in_array(ROLE, ['Admin', 'Writer'], true) ? '<span style="float:right">[ <a title="'. $RNV->lang['Edit node'] .' '. $node['title'] .'" href="'. $node['route'] .'edit/">'. $RNV->lang['Edit'] .'</a> ]</span>' : '';
				$layout .='</li>';

				$language_segments[ $language['cipher'] ][ $language['name'] .'|'. $language['code_length_3'] .'|'. $language['hreflang'] .'|'. $language['code_length_2'] ][] = [

					'layout' => $layout,

				];

			}

		}

	}

	foreach( $language_segments as $lng => $l ) {

		$desc = explode('|', key($l));

		$contents .= '<li>';
		$contents .= '<dl class="revolver__categories-by-country">';
		$contents .= '<dt>'. $RNV->lang['Contents country'] .' &#8226; <span class="state-attribution revolver__sa-iso-'. strtolower( $desc[ 3 ] ) .'"></span><span class="revolver__stats-country">'. $desc[ 0 ] .'</span><span class="revolver__stats-system">[ '. $desc[ 1 ] .' :: '. $desc[ 2 ] .' ]</span></dt>';

		$contents .= '<dd><ul>';

		foreach( $l as $p ) {

			foreach( $p as $xp ) {

				$contents .= $xp['layout'];

			}

		}

		$contents .= '</ul></dd></dl></li>';

	}

	$contents .= '</ul></dd>';

}

$contents .= '</dl>'; 

$node_data[] = [

	'title'		=> $title,
	'id'		=> 'categories',
	'route'		=> '/categories/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'published' => 1

];

?>
