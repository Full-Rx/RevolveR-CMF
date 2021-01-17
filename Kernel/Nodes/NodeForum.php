<?php

 /*
  * 
  * RevolveR Node Forum 
  *
  * v.2.0.1.4
  *
  * Developer: Maltsev Dmitry
  *
  * License: Apache 2.0
  *
  */

$title = $RNV->lang['Forum manage'];

if( is_numeric( PASS[ 2 ] ) && empty( PASS[ 3 ] ) ) {

	$forum = iterator_to_array(

		$RKI->Model::get('forums', [

			'criterion' => 'id::'. (int)PASS[ 2 ],
			'course'	=> 'forward',
			'sort'		=> 'id'

		])

	)['model::forums'][ 0 ];

	$title = $forum['title'];

	$site_description = $forum['description'];


} 
else if( is_numeric( PASS[ 2 ] ) && is_numeric( PASS[ 3 ] ) ) {

	$forum_room = iterator_to_array(

		$RKI->Model::get('forum_rooms', [

			'criterion' => 'id::'. (int)PASS[ 3 ],
			'course'	=> 'forward',
			'sort'		=> 'id'

		])

	)['model::forum_rooms'][ 0 ];

	$title = $forum_room['title'];

	$site_description = $forum_room['description'];

}

$contents = '';

if( ROLE !== 'none' ) {

	if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

		if( isset(SV['p']) ) {

			if( isset(SV['p']['revolver_forum_container_title']) ) {

				if( (bool)SV['p']['revolver_forum_container_title']['valid'] ) {

					$title = SV['p']['revolver_forum_container_title']['value'];

				}

			}

			if( isset(SV['p']['revolver_forum_container_description']) ) {

				if( (bool)SV['p']['revolver_forum_container_description']['valid'] ) {

					$description = SV['p']['revolver_forum_container_description']['value'];

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

					$RKI->Model::set('forums', [

						'title'			=> $title,
						'description'	=> $description

					]);

					$RKI->Notify::set('status', 'Forum container created');

				}

			}

		}

		$form_parameters = [

			// main parameters
			'id'		=> 'forum-add-container-form',
			'class'		=> 'revolver__forum-add-container revolver__new-fetch',
			'method'	=> 'post',
			'action'	=> $RNV->request,
			'encrypt'	=> true,
			'captcha'	=> true,
			'submit'	=> 'Submit',

			// included fieldsets
			'fieldsets' => [

				// fieldset contents parameters
				'fieldset_1' => [

					'title' => 'Add forum container',
					
					// wrap fields into label
					'labels' => [

						'label_1' => [

							'title'  => 'Container title',
							'access' => 'forum',
							'auth'	 => 1,

							'fields' => [

								0 => [

									'type'			=> 'input:text',
									'name'			=> 'revolver_forum_container_title',
									'placeholder'	=> 'Type container name',
									'required'		=> true

								],

							],

						],

						'label_2' => [

							'title'	 => 'Container description',
							'access' => 'forum',
							'auth'	 => 1,

							'fields' => [

								0 => [

									'type'			=> 'input:text',
									'name'			=> 'revolver_forum_container_description',
									'placeholder'	=> 'Type container description',
									'required'		=> true

								],

							],

						],

					],

				],

			],

		];


		$contents .= '<h2 class="revolver__collapse-form-legend revolver__collapse-form-legend-form-free">'. $RNV->lang['Add forum container'] .'</h2>';

		$contents .= '<output class="revolver__collapse-form-contents" style="overflow: hidden; width: 0px; height: 0px; line-height: 0px; display: inline-block; min-height: 0px; opacity: 0; transform: scaleX(1) scaleY(1) scaleZ(1);">';
		$contents .= $RKI->HTMLForm::build( $form_parameters );
		$contents .= '</output>';


	}

}

$contents .= '<dl class="revolver__categories">';

$forums = iterator_to_array(

		$RKI->Model::get('forums', [

			'criterion' => 'id::*',
			'course'	=> 'forward',
			'sort'		=> 'id'

		])

	)['model::forums'];

if( $forums ) {

	foreach( $forums as $forum ) {

		if( ROLE !== 'none' ) {

			if( ROLE === 'Admin') {

				$contents .= '<dt>&#8226; <a href="/forum/'. $forum['id'] .'/">'. $forum['title'] .'</a> &#8226; <span style="float:right">[ <a href="/forum/'. $forum['id'] .'/edit/">'. $RNV->lang['Edit'] .'</a> ]</span></dt>';

			} 
			else {

				$contents .= '<dt>&#8226; <a href="/forum/'. $forum['id'] .'/">'. $forum['title'] .'</a></dt>';

			}

		}
		else {

			$contents .= '<dt>&#8226; <a href="/forum/'. $forum['id'] .'/">'. $forum['title'] .'</a></dt>';

		}

		$contents .= '<dd><p>'. $forum['description'] .'</p>';
		$contents .= '<ul>';

		$language_segments = [];

		foreach( $all_nodes as $node ) {

			$language = $RKI->Language::getLanguageData( $node['country'] );

			if( $node['forum'] === $forum['id'] && $language['cipher'] === $node['country'] ) {

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


		$contents .= '</ul></dd>';

	}

}


$contents .= '</dl>'; 

if( ROUTE['node'] === '#forum' && is_numeric( PASS[ 2 ] ) ) {

	if( PASS[ 3 ] !== 'edit' && empty( PASS[ 3 ] ) ) {

		// Forum inside

		$form_parameters = [

			// main parameters
			'id'		=> 'forum-add-room-form',
			'class'		=> 'revolver__forum-add-room revolver__new-fetch',
			'method'	=> 'post',
			'action'	=> '/forum-room-d/',
			'encrypt'	=> true,
			'captcha'	=> true,
			'submit'	=> 'Submit',

			// included fieldsets
			'fieldsets' => [

				// fieldset contents parameters
				'fieldset_1' => [

					'title' => 'Add forum room',
					
					// wrap fields into label
					'labels' => [

						'label_1' => [

							'title'  => 'Room title',
							'access' => 'comment',
							'auth'	 => 1,

							'fields' => [

								0 => [

									'type'			=> 'input:text',
									'name'			=> 'revolver_forum_room_title',
									'placeholder'	=> 'Type room title',
									'required'		=> true

								],

							],

						],

						'label_2' => [

							'title'	 => 'Room description',
							'access' => 'comment',
							'auth'	 => 1,

							'fields' => [

								0 => [

									'type'			=> 'input:text',
									'name'			=> 'revolver_forum_room_description',
									'placeholder'	=> 'Type room description',
									'required'		=> true

								],

							],

						],

						'label_3' => [

							'title'  => 'Room contents',
							'access' => 'comment',
							'auth'   => 1,

							'fields' => [

								0 => [

									'type' 			=> 'textarea:text',
									'name' 			=> 'revolver_forum_room_content',
									'placeholder'	=> 'Type room contents',
									'required'		=> true,
									'rows'			=> 20,

								],

								1 => [

									'type' 			=> 'input:hidden',
									'name'			=> 'revolver_forum_room_id',
									'required'		=> true,
									'value'			=> PASS[ 2 ]

								],

							],

						],

					],

				],

			],

		];

		if( Auth ) {

			$forum_room_contents .= '<h2 class="revolver__collapse-form-legend revolver__collapse-form-legend-form-free">'. $RNV->lang['Add forum room'] .'</h2>';

			$forum_room_contents .= '<output class="revolver__collapse-form-contents" style="overflow: hidden; width: 0px; height: 0px; line-height: 0px; display: inline-block; min-height: 0px; opacity: 0; transform: scaleX(1) scaleY(1) scaleZ(1);">';
			$forum_room_contents .= $RKI->HTMLForm::build( $form_parameters );
			$forum_room_contents .= '</output>';

		}

		$forum = iterator_to_array(

			$RKI->Model::get('forums', [

				'criterion' => 'id::'. (int)PASS[ 2 ],
				'course'	=> 'forward',
				'sort'		=> 'id'

			])

		)['model::forums'];

		if( !$forum ) {

			define('NF', true);

		}

		$forum = $forum[0];

		$forum_rooms = iterator_to_array(

			$RKI->Model::get('forum_rooms', [

				'criterion' => 'forum_id::'. (int)PASS[ 2 ],
				'course'	=> 'backward',
				'sort'		=> 'id'

			])

		)['model::forum_rooms'];

		if( $forum_rooms ) {

			foreach( $forum_rooms as $froom ) {

				$datetime = explode( '.', str_replace( '-', '.', explode(' ', $froom['time'])[ 0 ] ) );

				$forum_room_contents .= '<dl class="revolver__forum-topic-list">';
				
				$forum_room_contents .= '<dt>';
				$forum_room_contents .= '<span>#'. $froom['id'] .' :: <a href="/forum/'. $forum['id'] .'/'. $froom['id'] .'/" title="Forum topic '. $froom['title'] .' by '. $froom['user'] .'">'. $froom['title'] . '</a></span> <span>by '. $froom['user'] .' at <time datetime="'. $datetime[ 2 ] .'-'. $datetime[ 1 ] .'-'. $datetime[ 0 ] .'">'. $froom['time'] .'</time></span>';
				$forum_room_contents .= '</dt>';
				
				$forum_room_contents .= '<dd>';
				$forum_room_contents .= '<div>'. $RKI->HTML::Markup( 

					htmlspecialchars_decode( 

						html_entity_decode( 

							$froom['content'] 

						)

					)

				) .'</div>';
				
				$comments = iterator_to_array(

							$RKI->Model::get('froom_comments', [

								'criterion' => 'froom_id::'. (int)$froom['id'],
								'course'	=> 'backward',
								'sort'		=> 'id'

							])

						)['model::froom_comments'];


				if( $comments ) {

						$user_info = iterator_to_array(

							$RKI->Model::get('users', [

								'criterion' => 'id::'. (int)$comments[0]['user_id'],
								'course'	=> 'backward',
								'sort'		=> 'id'

							])

						)['model::users'][ 0 ];


					$forum_room_contents .= '<div>';
					$forum_room_contents .= '<span>'. $comments[0]['time'] .'</span>';
					$forum_room_contents .= '<em>by '. $user_info['nickname'] .'</em>';
					$forum_room_contents .= '<span><a href="/forum/'. PASS[ 2 ] .'/'. $froom['id'] .'/#comment-'. $comments[0]['id'] .'">'. count( $comments ) .'</a></span>';
					$forum_room_contents .= '</div>';

				}

				$forum_room_contents .= '</dd>';
				$forum_room_contents .='</dl>';

			}

		}

		$node_data[] = [

			'title'		=> '#'. $forum['id'] .' :: '. $forum['title'],
			'contents'  => '<p>'. $forum['description'] .'</p>' . $forum_room_contents,
			'id'		=> 'forum-room-container',
			'route'		=> '/forum/',
			'teaser'	=> null,
			'footer'	=> null,
			'published' => 1

		];

	} 

	else if( is_numeric( PASS[ 3 ] ) && empty( PASS[ 4 ] ) ) {

		// Forum room inner(topic)

		$forum = iterator_to_array(

			$RKI->Model::get('forums', [

				'criterion' => 'id::'. (int)PASS[ 2 ],
				'course'	=> 'forward',
				'sort'		=> 'id'

			])

		)['model::forums'];

		if( !$forum ) {

			define('NF', true);

		}

		$forum = $forum[0];

		$forum_rooms = iterator_to_array(

			$RKI->Model::get('forum_rooms', [

				'criterion' => 'forum_id::'. (int)PASS[ 2 ],
				'course'	=> 'backward',
				'sort'		=> 'id'

			])

		)['model::forum_rooms'];

		if( $forum_rooms ) {

			$not_found = true;

			foreach( $forum_rooms as $fr ) {

				if( (int)$fr['id'] === (int)PASS[ 3 ] ) {

					$not_found = null;

				}

			}

			$room_user		  = $forum_rooms[ 0 ]['user'];
			$room_time 		  = $forum_rooms[ 0 ]['time'];
			$room_id 	      = $forum_rooms[ 0 ]['id'];
			$room_title       = $forum_rooms[ 0 ]['title'] .' '. $RNV->lang['by'] .' '. $room_user;
			$room_description = $forum_rooms[ 0 ]['description'];
			$room_content 	  = $RKI->HTML::Markup(

									html_entity_decode(

										htmlspecialchars_decode(

											$forum_rooms[ 0 ]['content']

										)

									)

								);

			define('NF', $not_found);

		} 
		else {

			define('NF', true);

			header('Location: '. $RNV->host .'/forum/');

		}

		$node_data[] = [

			'title'		  => $room_title,
			'description' => $room_description,
			'contents'	  => $room_content,
			'id'		  => $room_id,
			'route'		  => '/forum/'. $forum['id'] .'/'. $room_id .'/',
			'time'		  => $room_time,
			'teaser'	  => null,
			'footer'	  => USER['name'] === $room_user || in_array(ROLE, ['Admin', 'Writer']) ? true : null,
			'published'	  => 1,
			'editor'	  => USER['name'] === $room_user || in_array(ROLE, ['Admin', 'Writer']) ? true : null,
			'quedit'	  => USER['name'] === $room_user || in_array(ROLE, ['Admin', 'Writer']) ? true : null,
			'author'	  => $room_user,
			'editor_mode' => null,

		];

	} else if( is_numeric( PASS[ 2 ] ) && is_numeric( PASS[ 3 ] ) && PASS[ 4 ] === 'edit' ) {

		$forum = iterator_to_array(

			$RKI->Model::get('forums', [

				'criterion' => 'id::'. (int)PASS[ 2 ],
				'course'	=> 'forward',
				'sort'		=> 'id'

			])

		)['model::forums'][ 0 ];

		$forum_rooms = iterator_to_array(

			$RKI->Model::get('forum_rooms', [

				'criterion' => 'forum_id::'. (int)PASS[ 2 ],
				'course'	=> 'backward',
				'sort'		=> 'id'

			])

		)['model::forum_rooms'];

		if( $forum_rooms ) {

			$room_id 	      = $forum_rooms[ 0 ]['id'];
			$room_title       = $forum_rooms[ 0 ]['title'];
			$room_time		  = $forum_rooms[ 0 ]['time'];
			$room_description = $forum_rooms[ 0 ]['description'];

			$room_content 	  = $RKI->HTML::Markup(

									html_entity_decode(

										htmlspecialchars_decode(

											$forum_rooms[ 0 ]['content']

										)

									)

								);

			$room_user		  = $forum_rooms[ 0 ]['user'];
			$room_forum_id	  = $forum_rooms[ 0 ]['forum_id'];


		}

		$title = $room_title;

		$node_data[] = [

			'title'		  => $room_title,
			'description' => $room_description,
			'contents'	  => $room_content,
			'id'		  => $room_id,
			'route'		  => '/forum/'. $forum['id'] .'/'. $room_id .'/',
			'time'		  => $room_time,
			'forum'		  => $room_forum_id,
			'teaser'	  => null,
			'footer'	  => true,
			'published'	  => 1,
			'author'	  => $room_user,
			'editor'	  => true,
			'editor_mode' => true,

		];

	}

} 
else {

	$title = $RNV->lang['Forum manage'];

	$node_data[] = [

		'title'		=> $title,
		'contents'  => $contents,
		'id'	    => 'forum-manage',
		'route'     => '/forum/',
		'teaser'    => null,
		'footer'    => null,
		'published' => 1

	];

}


?>
