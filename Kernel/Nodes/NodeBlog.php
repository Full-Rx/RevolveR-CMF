<?php

 /*
  * 
  * RevolveR Node Blog
  *
  * v.2.0.0.5
  *
  *
  *
  *
  *
  *			          ^
  *			         | |
  *			       @#####@
  *			     (###   ###)-.
  *			   .(###     ###) \
  *			  /  (###   ###)   )
  *			 (=-  .@#####@|_--"
  *			 /\    \_|l|_/ (\
  *			(=-\     |l|    /
  *			 \  \.___|l|___/
  *			 /\      |_|   /
  *			(=-\._________/\
  *			 \             /
  *			   \._________/
  *			     #  ----  #
  *			     #   __   #
  *			     \########/
  *
  *
  *
  * Developer: Maltsev Dmitry
  *
  * License: Apache 2.0
  *
  */

$contents = '';

if( ROLE !== 'none' ) {

	if( in_array(ROLE, ['Admin', 'Writer', 'User'], true) ) {

		if( !empty(SV['p']) ) {

			$node_title = $node_content = $node_description = $node_route = '';

			$node_category = 0;

			if( isset(SV['p']['revolver_node_edit_title']) ) {

				if( (bool)SV['p']['revolver_node_edit_title']['valid'] ) {

					$node_title = strip_tags( SV['p']['revolver_node_edit_title']['value'] );

				}

			}

			if( isset(SV['p']['revolver_node_edit_content']) ) {

				if( (bool)SV['p']['revolver_node_edit_content']['valid'] ) {

					$node_content = $RKI->HTML::Markup(

						htmlspecialchars_decode(

							html_entity_decode(

								SV['p']['revolver_node_edit_content']['value']

							)

						)

					);

				}

			}

			if( isset(SV['p']['revolver_node_edit_description']) ) {

				if( (bool)SV['p']['revolver_node_edit_description']['valid'] ) {

					$node_description = strip_tags( SV['p']['revolver_node_edit_description']['value'] );

				}

			}


			if( isset(SV['p']['revolver_node_edit_route']) ) {

				if( (bool)SV['p']['revolver_node_edit_route']['valid'] ) {

					$node_route = preg_replace("/\/+/", '/', preg_replace("/ +/", '-', trim( SV['p']['revolver_node_edit_route']['value'] )));

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

					$upload_allow = true;

					$passed = true;

					if( strlen( $node_route ) !== strlen( utf8_decode( $node_route ) ) ) {

						$passed = null;

					}

					$route_fix = ltrim(

						rtrim(

							$node_route, '/'

						), '/'

					);

					$nodes = iterator_to_array(

						$RKI->Model::get( 'blog_nodes', [

							'criterion' => 'route::'. '/blog/'. $route_fix .'/',

							'bound'		=> [

								1

							],

							'course'	=> 'backward',
							'sort' 		=> 'id'

						])

					)['model::blog_nodes'];

					if( $nodes ) {

						$passed = null;

					}

					if( $passed ) {

						$RKI->Model::set('blog_nodes', [

							'title'			=> $node_title,
							'content'		=> $node_content,
							'description'	=> $node_description,
							'user'			=> USER['name'],
							'route'			=> '/blog/'. $route_fix .'/',
							'time'			=> date('d.m.Y h:i'),
							'published'		=> 0,

						]);

						$node_route = '/blog/'. urlencode( $route_fix ) .'/';

						if( count(SV['f']) > 0 ) {

							foreach( SV['f'] as $file ) {

								foreach( $file as $f ) {

									$upload_allow = null;

									if( !is_readable($_SERVER['DOCUMENT_ROOT'] .'/public/bfiles/'. $f['name']) ) {

										if( (bool)$f['valid'] ) {

											$upload_allow = true;

										}

									}

									if( $upload_allow ) {

										$RKI->Model::set('blog_files', [

											'node'	=> $node_route,
											'name'	=> $f['name']

										]);

										move_uploaded_file( $f['temp'], $_SERVER['DOCUMENT_ROOT'] .'/public/bfiles/'. $f['name'] );

									}

								}

							}

						}

						header( 'Location: '. $RNV->host . $node_route .'?notification=node-created' );

					} 
					else {

						$RKI->Notify::set('notice', 'Route exist or security check not pass');

					}

				}
				else {

					$RKI->Notify::set('notice', 'Route exist or security check not pass');

				}

			}
			else {

				$RKI->Notify::set('notice', 'Security check not pass');

			}

		}

		$form_parameters_html_help .= '<ul class="revolver__allowed-files-description-table">';
		$form_parameters_html_help .= '<li class="revolver__table-header">';
		$form_parameters_html_help .= '<span class="revolver__allowed-files-description">'. $RNV->lang['File description'] .'</span>';
		$form_parameters_html_help .= '<span class="revolver__allowed-files-extension">'. $RNV->lang['Extension'] .'</span>';
		$form_parameters_html_help .= '<span class="revolver__allowed-files-size">'. $RNV->lang['Maximum allowed file size'] .'</span>';
		$form_parameters_html_help .= '<li>';

		foreach( $D::$file_descriptors as $allowed_files ) {

			$form_parameters_html_help .= '<li>';
			$form_parameters_html_help .= '<span class="revolver__allowed-files-description">'. $allowed_files['description'] .'</span>';
			$form_parameters_html_help .= '<span class="revolver__allowed-files-extension">'. $allowed_files['extension'] .'</span>';
			$form_parameters_html_help .= '<span class="revolver__allowed-files-size">'. round((int)$allowed_files['max-size'] / 1024, 1, PHP_ROUND_HALF_ODD) .' Kb</span>';
			$form_parameters_html_help .= '</li>';

		}

		$form_parameters_html_help .= '</ul>';

		// User Profile Form Structure
		$form_parameters = [

			// main parameters
			'id'		=> 'blog-create-form',
			'class'		=> 'revolver__blog-create-form revolver__new-fetch',
			'action'	=> '/blog/',
			'method'	=> 'post',
			'encrypt'	=> true,
			'captcha'	=> true,
			'submit'	=> 'Submit',

			// tabs
			'tabs' => [

				'tab_1' => [

					// tab title
					'title'  => 'Node editor',
					'active' => true,

					// included fieldsets
					'fieldsets' => [

						// fieldset contents parameters
						'fieldset_1' => [

							'title' => 'New node editor',

							// wrap fields into label
							'labels' => [

								'label_1' => [

									'title'  => 'Node title',
									'access' => 'blog',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:text',
											'name' 			=> 'revolver_node_edit_title',
											'placeholder'	=> 'Node title',
											'required'		=> true,
											'value'			=> $node_title

										],

									],

								],

								'label_2' => [

									'title'  => 'Node description',
									'access' => 'blog',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:text',
											'name' 			=> 'revolver_node_edit_description',
											'placeholder'	=> 'Node description',
											'required'		=> true,
											'value'			=> $node_description

										],

									],

								],

								'label_3' => [

									'title'  => 'Node route',
									'access' => 'blog',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'input:text',
											'name' 			=> 'revolver_node_edit_route',
											'placeholder'	=> 'Node address',
											'required'		=> true,
											'value'			=> $node_route

										],

									],

								],

								'label_4' => [

									'title'  => 'Node contents',
									'access' => 'blog',
									'auth'   => 1,

									'fields' => [

										0 => [

											'type' 			=> 'textarea:text',
											'name' 			=> 'revolver_node_edit_content',
											'placeholder'	=> 'Node contents',
											'required'		=> true,
											'rows'			=> 20,
											'value:html'	=> $node_content

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
									'access' => 'blog',
									'auth'	 => 1,

									'fields' => [

										0 => [

											'type' 		=> 'input:file',
											'name' 		=> 'revolver_node_files',
											'multiple'	=> true

										],

									],

								],

								'label_7' => [

									'title'  => 'Allowed files',
									'access' => 'blog',
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

				], // #tab 

			]

		];


		$contents .= '<h2 class="revolver__collapse-form-legend revolver__collapse-form-legend-form-free">'. $RNV->lang['Add blog item'] .'</h2>';

		$contents .= '<output class="revolver__collapse-form-contents" style="overflow: hidden; width: 0px; height: 0px; line-height: 0px; display: inline-block; min-height: 0px; opacity: 0; transform: scaleX(1) scaleY(1) scaleZ(1);">';
		$contents .= $RKI->HTMLForm::build( $form_parameters, true );
		$contents .= '</output>';

	}

}

$title = $RNV->lang['Blog'];

$blog_items = iterator_to_array(

	$RKI->Model::get( 'blog_nodes', [

		'criterion' => 'id::*',

		'course'	=> 'backward',
		'sort' 		=> 'id'

	])

)['model::blog_nodes'];

if( !$blog_items ) {

	$contents .= '<p>No any blog items for now.</p>';

}

$node_data[] = [

	'title'		  => $title,
	'contents'	  => '<p>'. $RNV->lang['Welcome blogs'] .'!</p>'. $contents,
	'id'		  => 'blog',
	'route'		  => '/blog/',
	'teaser'	  => null,
	'footer'	  => null,
	'published'	  => 1

];

$not_found = $RNV->request === '/blog/' || isset(ROUTE['edit']) ? null : true;

if( $blog_items ) {

	foreach( $blog_items as $bi ) {

		$editor = USER['name'] === $bi['user'] || in_array( ROLE, ['Admin', 'Writer'] ) ? true : null;

		if( PASS[ 1 ] === 'blog' && empty( PASS[ 2 ] ) ) {

			$editor = null;

		}

		if( !empty( PASS[ 2 ] ) ) {

			$item_content = $RKI->HTML::Markup(

					htmlspecialchars_decode( 

						html_entity_decode( 

							$bi['content']

						)

					), [ 'lazy' => 1 ]

				);


		} 
		else {

			$item_content = $RKI->HTML::Markup( 

					htmlspecialchars_decode( 

						html_entity_decode( 				

							$bi['content']

						), 

					), [ 'length' => 1500, 'lazy' => 1 ] 

				);

		}

		if( !isset( ROUTE['edit'] ) ) {

			$rating = iterator_to_array(

						$RKI->Model::get( 'blog_ratings', [

							'criterion'	=> 'node_id::'. $bi['id'],
							'course'	=> 'backward',
							'sort'		=> 'id'

						])

					)['model::blog_ratings'];

			$rate = 0;

			if( $rating ) {

				foreach( $rating as $r => $rv ) {

					$rate += $rv['rate'];

				}

				$rate /= count( $rating ); 

			} 
			else {

				$rating = [];

			}

			if( !empty(PASS[ 2 ]) && $RNV->request === $bi['route'] ) {

				$descr = $bi['description'];

				$node_data[ 0 ] = [

					'title'		  => $bi['title'],
					'id'		  => $bi['id'],
					'description' => $bi['description'],
					'contents'	  => $item_content,
					'author'	  => $bi['user'],
					'route'		  => $bi['route'],
					'time'		  => $bi['time'],
					'rating'	  => floor($rate),
					'rates'		  => count( $rating ),
					'teaser'      => null,
					'footer'      => true,
					'editor'      => $editor,
					'quedit'      => $editor,
					'editor_mode' => null,
					'published'   => 1,

				];

				$not_found = null;


			} 
			else {

				if( $RNV->request === $bi['route'] || $RNV->request === '/blog/' ) {

					$descr = $bi['description'];

					$node_data[] = [

						'title'		  => $bi['title'],
						'id'		  => $bi['id'],
						'description' => $bi['description'],
						'contents'	  => $item_content,
						'author'	  => $bi['user'],
						'route'		  => $bi['route'],
						'time'		  => $bi['time'],
						'rating'	  => floor($rate),
						'rates'		  => count( $rating ),
						'teaser'      => null,
						'footer'      => true,
						'editor'      => $editor,
						'quedit'      => $editor,
						'editor_mode' => null,
						'published'   => 1,

					];

					$not_found = null;

				}

			}

		}

	}

}

define('NF', $not_found);

?>
