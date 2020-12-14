<?php

$RKI->Template::$b[] = '<section class="revolver__advanced-contents">';

$RKI->Template::$b[] = '<h2>'. $RKV->lang['Comments'] .' &hellip;</h2>';

if( ACCESS === 'none' ) {

	$RKI->Template::$b[] = '<div class="revolver__status-notifications revolver__inactive">';

	$RKI->Template::$b[] = '<div class="revolver__statuses-heading">... Please register<i>+</i></div>';

	$RKI->Template::$b[] = $RKV->lang['You can write here as guest with moderation'] .' '. $RKV->lang['Please'];

	$RKI->Template::$b[] = ' <a href="/user/auth/">'. $RKV->lang['confirm your person'] .'</a> ';

	$RKI->Template::$b[] = $RKV->lang['if you have an account or'];

	$RKI->Template::$b[] = ' <a href="/user/register/">'. $RKV->lang['register'] .'</a>';

	$RKI->Template::$b[] = '</div>';

}

// Render comments
if( is_array( $node_comments ) ) {

	foreach( $node_comments as $c ) {

		if( (bool)$c->comment_published ) {

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

		$RKI->Template::$b[] = '<article itemprop="comment" itemscope itemtype="https://schema.org/Comment" id="comment-'. $c->comment_id .'" class="revolver__comments comments-'. $c->comment_id .' '. $class .'">';

		$RKI->Template::$b[] = '<header class="revolver__comments-header">'; 

		$RKI->Template::$b[] = '<h2><a itemprop="url" href="'. $n['route'] .'#comment-'. $c->comment_id .'">&#8226;'. $c->comment_id .'</a> '. $RKV->lang['by'] .' <span>'. $c->comment_user_name .'</span></h2>';

		$RKI->Template::$b[] = '<time itemprop="dateCreated" datetime="'. $RKI->Calendar::formatTime($c->comment_time) .'">'. $c->comment_time .'</time>';

		$RKI->Template::$b[] = '</header>';

		$RKI->Template::$b[] = '<figure itemprop="creator" itemscope itemtype="https://schema.org/Person" class="revolver__comments-avatar">';

		if( $c->comment_user_avatar === 'default') {

			$src = '/public/avatars/default.png';

		}
		else {

			$src = $c->comment_user_avatar;

		}

		$RKI->Template::$b[] = '<img itemprop="image" src="'. $src .'" alt="'. $c->comment_user_name .'" />';

		$RKI->Template::$b[] = '<figcaption itemprop="name">'. $c->comment_user_name .'</figcaption>';

		$RKI->Template::$b[] = '</figure>';

		if( $n['quedit'] ) {

			$quick_edit_attr = ' contenteditable="false"';
			$quick_edit_data = ' data-node="'. $c->comment_id .'" data-type="node-comment" data-user="'. $c->comment_user_name .'"';

		} 
		else {

			$quick_edit_attr = '';
			$quick_edit_data = '';

		}


		$RKI->Template::$b[] = '<div class="revolver__comments-contents"'. $quick_edit_attr . $quick_edit_data .'>'. $RKI->HTML::Markup( 

				htmlspecialchars_decode( 

					html_entity_decode( 

						$RKI->HTML::metaHash( $c->comment_contents )

					)

				), [ 'lazy' => 1 ] ) .'</div>';


		$RKI->Template::$b[] = '<footer class="revolver__comments-footer">';

		if( isset($n['rating']) ) {

			$tpe = PASS[ 1 ] === 'blog' ? 'blog-comment' : 'node-comment';

			$RKI->Template::$b[] = '<div class="revolver-rating">';
			$RKI->Template::$b[] = '<ul class="rated-'. $c->rating .'" data-node="'. $c->comment_id .'" data-user="'. USER['id'] .'" data-type="'. $tpe .'">';

				$RKI->Template::$b[] = '<li data-rated="1">1</li>';
				$RKI->Template::$b[] = '<li data-rated="2">2</li>';
				$RKI->Template::$b[] = '<li data-rated="3">3</li>';
				$RKI->Template::$b[] = '<li data-rated="4">4</li>';
				$RKI->Template::$b[] = '<li data-rated="5">5</li>';

			$RKI->Template::$b[] = '</ul>';

			$RKI->Template::$b[] = '<span>'. $c->rating .'</span> / <span>5</span> #<span class="closest">'. $c->rates .'</span>';
			$RKI->Template::$b[] = '</div>';

		}

		if( $c->editor ) {

			$RKI->Template::$b[] = '<nav><ul>';

			if( $c->quedit ) {

				$RKI->Template::$b[] = '<li class="revolver__quick-edit-handler" title="'. $RKV->lang['qedit'] .'">[ '. $RKV->lang['QEdit'] .' ]</li>';

			}

			$RKI->Template::$b[] = '<li><a title="'. $c->comment_id .' '. $RKV->lang['edit'] .'" href="/comment/'. $c->comment_id .'/edit/">'. $RKV->lang['Edit'] .'</a></li>';

			$RKI->Template::$b[] = '</ul></nav>';

		}

		$RKI->Template::$b[] = '</footer>';

		$RKI->Template::$b[] = '</article>';

	}

}

$RKI->Template::$b[] = '</section>';

?>
