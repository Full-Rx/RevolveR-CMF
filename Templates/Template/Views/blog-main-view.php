<?php

$RKI->Template::$b[] = '<section class="revolver__advanced-contents">';

$blog_id = iterator_to_array(

	$RKI->Model::get( 'blog_nodes', [

		'criterion' => 'route::'. $RKV->request,

		'bound' 	=> [

			1

		],

		'course'	=> 'backward',
		'sort' 		=> 'id'

	])

)['model::blog_nodes'][0]['id'];

$comments = iterator_to_array(

	$RKI->Model::get( 'blog_comments', [

		'criterion' => 'node_id::'. $blog_id,
		'course'	=> 'forward',
		'sort' 		=> 'id'

	])

)['model::blog_comments'];

// Render comments
if( $comments ) {

	foreach( $comments as $c ) {

		$comment_user = iterator_to_array(

				$RKI->Model::get('users', [

					'criterion' => 'id::'. $c['user_id'],
					'course'	=> 'forward',
					'sort'		=> 'id'

				])

			)['model::users'][0];

		if( (bool)$c['published'] ) {

			$class = 'published';

		}
		else {

			$class = 'unpublished';

			if( isset( ACCESS['role'] ) ) {

				if( in_array( ACCESS['role'], ['none', 'User'], true) ) {

					continue;

				}

			}

			if( ACCESS === 'none' ) {

				continue;

			}

		}

		$RKI->Template::$b[] = '<article id="comment-'. $c['id'] .'" class="revolver__comments comments-'. $c['id'] .' '. $class .'">';

		$RKI->Template::$b[] = '<header class="revolver__comments-header">'; 

		$RKI->Template::$b[] = '<h2><a href="'. $RKV->request .'#comment-'. $c['id'] .'">&#8226;'. $c['id'] .'</a> '. $RKV->lang['by'] .' <span>'. $comment_user['nickname'] .'</span></h2>';

		$RKI->Template::$b[] = '<time datetime="'. $RKI->Calendar::formatTime($c['time']) .'">'. $c['time'] .'</time>';

		$RKI->Template::$b[] = '</header>';

		$RKI->Template::$b[] = '<figure class="revolver__comments-avatar">';

		if( $comment_user['avatar'] === 'default') {

			$src = '/public/avatars/default.png';

		}
		else {

			$src = $comment_user['avatar'];

		}

		$RKI->Template::$b[] = '<img src="'. $src .'" alt="'. $comment_user['nickname'] .'" />';

		$RKI->Template::$b[] = '</figure>';

		$RKI->Template::$b[] = '<div class="revolver__comments-contents">'. $markup::Markup( 

					htmlspecialchars_decode( 

						html_entity_decode( 

							$RKI->HTML::metaHash($c['content'])

						), 

					), [ 'lazy' => 1 ] ) .'</div>';


		if( $n['editor'] ) {

			$RKI->Template::$b[] = '<footer class="revolver__comments-footer"><nav><ul>';

			if( $comment_user['id'] === USER['id'] || in_array(ROLE, ['Admin', 'Writer']) ) {

				$RKI->Template::$b[] = '<li><a title="'. $c['id'] .' '. $RKV->lang['edit'] .'" href="'. $RKV->request .'comment/'.  $c['id'] .'/edit/">'. $RKV->lang['Edit'] .'</a></li>';

			}

			$RKI->Template::$b[] = '</ul></nav></footer>';

		}

		$RKI->Template::$b[] = '</article>';

	}

}

$RKI->Template::$b[] = '</section>';

?>
