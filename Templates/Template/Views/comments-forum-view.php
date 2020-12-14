<?php

$RKI->Template::$b[] = '<section class="revolver__advanced-contents">';

$comments = iterator_to_array(

				$RKI->Model::get('froom_comments', [

					'criterion' => 'froom_id::'. (int)PASS[ 3 ],
					'course'	=> 'forward',
					'sort'		=> 'id'

				])

			)['model::froom_comments'];

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

		$RKI->Template::$b[] = '<h2><a href="/forum/'. PASS[ 2 ] .'/'. PASS[ 3 ] .'/#comment-'. $c['id'] .'">&#8226;'. $c['id'] .'</a> '. $RKV->lang['by'] .' <span>'. $comment_user['nickname'] .'</span></h2>';

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

		if( in_array(ROLE, ['Admin', 'Writer']) || USER['name'] === $comment_user['nickname'] ) {

				$quick_edit_attr = ' contenteditable="false"';
				$quick_edit_data = ' data-node="'. $c['id'] .'" data-type="forum-comment" data-user="'. $comment_user['nickname'] .'"';

		} 
		else {

			$quick_edit_attr = '';
			$quick_edit_data = '';

		}

		$RKI->Template::$b[] = '<div class="revolver__comments-contents"'. $quick_edit_attr . $quick_edit_data .'>'. $RKI->HTML::Markup( 

				htmlspecialchars_decode( 

					html_entity_decode( 

						$RKI->HTML::metaHash($c['content'])

					)

				), [ 'lazy' => 1 ] ) .'</div>';


		if( $comment_user['id'] === USER['id'] || in_array(ROLE, ['Admin', 'Writer']) ) {

			$RKI->Template::$b[] = '<footer class="revolver__comments-footer"><nav><ul>';
			$RKI->Template::$b[] = '<li class="revolver__quick-edit-handler" title="'. $RKV->lang['qedit'] .'">[ '. $RKV->lang['QEdit'] .' ]</li>';
			$RKI->Template::$b[] = '<li><a title="'. $c['id'] .' '. $RKV->lang['edit'] .'" href="/forum/'. PASS[ 2 ] .'/'. PASS[ 3 ] .'/comment/'.  $c['id'] .'/edit/">'. $RKV->lang['Edit'] .'</a></li>';
			$RKI->Template::$b[] = '</ul></nav></footer>';

		}

		$RKI->Template::$b[] = '</article>';

	}

}

$RKI->Template::$b[] = '</section>';

?>
