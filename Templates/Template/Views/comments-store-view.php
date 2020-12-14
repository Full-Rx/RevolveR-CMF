<?php

$RKI->Template::$b[] = '<section class="revolver__advanced-contents">';

$comments = iterator_to_array(

	$RKI->Model::get( 'store_comments', [

		'criterion' => 'node_id::'. (int)PASS[ 3 ],
		'course'	=> 'forward',
		'sort' 		=> 'id'

	])

)['model::store_comments'];

// Render comments
if( $comments ) {

	foreach( $comments as $c ) {

		/* Comments rating */
		$crating = iterator_to_array(

				$RKI->Model::get('store_comments_ratings', [

					'criterion'	=> 'comment_id::'. $c['id'],
					'course'	=> 'backward',
					'sort'		=> 'id'

				])

			)['model::store_comments_ratings'];

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

		$RKI->Template::$b[] = '<article itemprop="review" itemtype="http://schema.org/Review" itemscope id="comment-'. $c['id'] .'" class="revolver__comments comments-'. $c['id'] .' '. $class .'">';

		$RKI->Template::$b[] = '<header class="revolver__comments-header">';

		$RKI->Template::$b[] = '<h2><a itemprop="url" href="'. $RKV->request .'#comment-'. $c['id'] .'">&#8226;'. $c['id'] .'</a> '. $RKV->lang['by'] .' <span>'. $comment_user['nickname'] .'</span></h2>';

		$RKI->Template::$b[] = '<time itemprop="dateCreated" datetime="'. $RKI->Calendar::formatTime($c['time']) .'">'. $c['time'] .'</time>';

		$RKI->Template::$b[] = '</header>';

		$RKI->Template::$b[] = '<figure itemprop="author" itemtype="http://schema.org/Person" itemscope class="revolver__comments-avatar">';

		if( $comment_user['avatar'] === 'default') {

			$src = '/public/avatars/default.png';

		}
		else {

			$src = $comment_user['avatar'];

		}

		$RKI->Template::$b[] = '<img itemprop="image" src="'. $src .'" alt="'. $comment_user['nickname'] .'" />';

		$RKI->Template::$b[] = '<figcaption itemprop="name">'. $comment_user['nickname'] .'</figcaption>';

		$RKI->Template::$b[] = '</figure>';

		if( in_array(ROLE, ['Admin', 'Writer']) || USER['name'] === $comment_user['nickname'] ) {

				$quick_edit_attr = ' contenteditable="false"';
				$quick_edit_data = ' data-node="'. $c['id'] .'" data-type="store-comment" data-user="'. $comment_user['nickname'] .'"';

		} 
		else {

			$quick_edit_attr = '';
			$quick_edit_data = '';

		}

		$RKI->Template::$b[] = '<div class="revolver__comments-contents"'. $quick_edit_attr . $quick_edit_data .'>'. $RKI->HTML::Markup( 

					htmlspecialchars_decode( 

						html_entity_decode( 

							$RKI->HTML::metaHash($c['content'])

						), 

					), [ 'lazy' => 1 ] ) .'</div>';


		$RKI->Template::$b[] = '<footer class="revolver__comments-footer">';

		$tpe = 'store-comment';

		$RKI->Template::$b[] = '<div class="revolver-rating" itemprop="reviewRating" itemtype="http://schema.org/Rating" itemscope>';
		$RKI->Template::$b[] = '<ul class="rated-'. floor($crate) .'" data-node="'. $c['id'] .'" data-user="'. USER['id'] .'" data-type="'. $tpe .'">';

			$RKI->Template::$b[] = '<li data-rated="1">1</li>';
			$RKI->Template::$b[] = '<li data-rated="2">2</li>';
			$RKI->Template::$b[] = '<li data-rated="3">3</li>';
			$RKI->Template::$b[] = '<li data-rated="4">4</li>';
			$RKI->Template::$b[] = '<li data-rated="5">5</li>';

		$RKI->Template::$b[] = '</ul>';

		$RKI->Template::$b[] = '<span itemprop="ratingValue">'. floor($crate) .'</span> / <span itemprop="bestRating">5</span> #<span class="closest">'. count($crating) .'</span>';
		$RKI->Template::$b[] = '</div>';

		if( $comment_user['id'] === USER['id'] || in_array(ROLE, ['Admin', 'Writer']) ) {

			$RKI->Template::$b[] = '<nav><ul>';

			$RKI->Template::$b[] = '<li class="revolver__quick-edit-handler" title="'. $RKV->lang['qedit'] .'">[ '. $RKV->lang['QEdit'] .' ]</li>';

			$RKI->Template::$b[] = '<li><a title="'. $c['id'] .' '. $RKV->lang['edit'] .'" href="'. $RKV->request .'comment/'.  $c['id'] .'/edit/">'. $RKV->lang['Edit'] .'</a></li>';

			$RKI->Template::$b[] = '</ul></nav>';

		}

		$RKI->Template::$b[] = '</footer>';

		$RKI->Template::$b[] = '</article>';

	}

}

$RKI->Template::$b[] = '</section>';

$RKI->Template::$b[] = '</div>'

?>
