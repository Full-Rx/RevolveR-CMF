<?php

 /* 
  * RevolveR Node
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
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

if( defined('NODE_ID') ) {

	define('users', iterator_to_array(

			$RKI->Model::get( 'users', [

				'criterion' => 'id::*',
				'course'	=> 'forward',
				'sort' 		=> 'id',

			])

		)['model::users']

	);

	define('NCU', iterator_to_array(

			$RKI->Model::get('node->comment', [

				'criterion' => 'comments::node_id::'. NODE_ID

			])

		)['model::node->comment'] 

	);

} 

// Create empty variables for navigation

$prev_title = '';
$prev_route = '';
$next_title = '';
$next_route = '';

$node_comments = [];

// Make countable
$nc = 0;

if( CONTENTS_FLAG ) {

	foreach( $all_nodes as $node ) {

		// Previous node

		if( isset($all_nodes[ $nc - 1 ]) && $all_nodes[ $nc - 1 ]['country'] === $node['country'] ) {

			if( $all_nodes[ $nc - 1 ]['route'] !== $node['route'] ) {

				$prev_title = $all_nodes[ $nc - 1 ]['title'];
				$prev_route = $all_nodes[ $nc - 1 ]['route'];

			}
			else {

				$prev_title = '';
				$prev_route = '';

			}

		}

		// Next node

		if( isset($all_nodes[ $nc + 1 ]) && $all_nodes[ $nc + 1 ]['country'] === $node['country']) {

			if( $all_nodes[ $nc + 1 ]['route'] !== $node['route'] ) {

				$next_title = $all_nodes[ $nc + 1 ]['title'];
				$next_route = $all_nodes[ $nc + 1 ]['route'];

			}
			else {

				$next_title = '';
				$next_route = '';

			}

		}

		$language = $RKI->Language::getLanguageData( $node['country'] );

		$rating = iterator_to_array(

					$RKI->Model::get( 'nodes_ratings', [

						'criterion'	=> 'node_id::'. $node['id'],
						'course'	=> 'backward',
						'sort'		=> 'id'

					])

				)['model::nodes_ratings'];

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

		// Current item
		$CNODE = [

			'title'       => $node['title'],
			'id'	      => $node['id'],
			'description' => $node['description'],
			'route'       => $node['route'],
			'contents'    => html_entity_decode(

				htmlspecialchars_decode(

					$node['content']

				)

			),

			'teaser'      => true,
			'footer'      => true,
			'category'	  => $node['category'],
			'author'	  => $node['user'],
			'language'	  => $language,
			'rating'	  => floor($rate),
			'rates'		  => count( $rating ),

			'similar'	  => [

				'prev' => [

					'title' => $next_title,
					'route' => $next_route,

				],

				'next' => [

					'title' => $prev_title,
					'route' => $prev_route,

				],

			],

			'published' => $node['published'],
			'mainpage'	=> $node['mainpage'],
			'time'		=> $node['time'],

			'editor'      => null,
			'editor_mode' => null,
			'quedit'	  => null,

		]; 

		if( !defined('ROUTE') ) {

			// Editor allowed

			if( isset( SV['c']['usertoken'] ) ) {

				$token_explode = explode('|', $cipher::crypt('decrypt', SV['c']['usertoken']));

				if( $token_explode[ 2 ] === $node['user'] || (in_array(ROLE, ['Admin', 'Writer'], true) ) ) {

					$CNODE['footer'] = true;
					$CNODE['editor'] = true;
					$CNODE['quedit'] = true;

					if( PASS[ count(PASS) - 2 ] === 'edit' ) {

						$CNODE['editor_mode'] = true;

					}

				}

			}
			else {

				$CNODE['footer'] = null;

			}

			$node_data[ $nc ] = $CNODE;

		}
		else if( ROUTE['route'] === '/' ) {

			if( LANGUAGE === $language['cipher'] || (int)$node['mainpage'] === 1 ) {

				$node_data[ $nc ] = $CNODE;

			}
			else {

				continue;

			}

		}

		if( !defined('ROUTE') ) {

			$node_data[ $nc ]['teaser'] = null;

			if( defined('NODE_ID') ) {

				if( !(bool)$nc && isset( NCU[ 0 ] ) ) {

					foreach( NCU as $c ) {

						$c = $c['comments'];

						/* Comments rating */
						$crating = iterator_to_array(

									$RKI->Model::get( 'comments_ratings', [

										'criterion'	=> 'comment_id::'. $c['id'],
										'course'	=> 'backward',
										'sort'		=> 'id'

									])

								)['model::comments_ratings'];

						$crate = 0;

						if( $crating ) {

							foreach( $crating as $r => $rv ) {

								$crate += $rv['rate'];

							}

							$crate /= count( $crating ); 

						} 
						else {

							$crating = [];

						}

						$guest = true;

						foreach( users as $u => $v ) {

							if( $v['nickname'] === $c['user_name'] ) {

								$node_comments[] = (object)[

									'comment_id'		=> $c['id'],
									'comment_uid'		=> $c['user_id'],
									'comment_time'		=> $c['time'],

									'comment_contents'	=> html_entity_decode(

										htmlspecialchars_decode(

											$c['content']

										)

									),

									'comment_user_name'   => $c['user_name'],
									'comment_user_avatar' => $v['avatar'],
									'comment_published'   => $c['published'],
									'rating'			  => floor( $crate ),
									'quedit'			  => in_array(ROLE, ['Admin', 'Writer']) || USER['name'] === $c['user_name'] ? true : null,
									'editor'			  => in_array(ROLE, ['Admin', 'Writer']) || USER['name'] === $c['user_name'] ? true : null, 
									'rates'				  => count( $crating ),

								];

								$guest = null;

							}

						}

						if( $guest ) {

							$node_comments[] = (object)[

								'comment_id'		=> $c['id'],
								'comment_uid'		=> $c['user_id'],
								'comment_time'		=> $c['time'],

								'comment_contents'	=> html_entity_decode(

									htmlspecialchars_decode(

										$c['content']

									)

								),

								'comment_user_name'   => '[guest] '. $c['user_name'],
								'comment_user_avatar' => 'default',
								'comment_published'   => $c['published'],
								'rating'			  => floor( $crate ),
								'rates'				  => count( $crating ),
								'quedit'			  => null,
								'editor'			  => null

							];

						}

					}

				}

			}

		}

		$nc++;

	}

}

?>
