<?php

$render_node .= '<section class="revolver__advanced-contents">';

$render_node .= '<h2>'. $RKV->lang['Comments'] .' &hellip;</h2>';

if( ACCESS === 'none' ) {

	$render_node .= '<div class="revolver__status-notifications revolver__inactive">';

	$render_node .= '<div class="revolver__statuses-heading">... Please register<i>+</i></div>';

	$render_node .= $RKV->lang['You can write here as guest with moderation'] .' '. $RKV->lang['Please'];

	$render_node .= ' <a href="/user/auth/">'. $RKV->lang['confirm your person'] .'</a> ';

	$render_node .= $RKV->lang['if you have an account or'];

	$render_node .= ' <a href="/user/register/">'. $RKV->lang['register'] .'</a>';

	$render_node .= '</div>';

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

		$render_node .= '<article itemprop="comment" itemscope itemtype="https://schema.org/Comment" id="comment-'. $c->comment_id .'" class="revolver__comments comments-'. $c->comment_id .' '. $class .'">';

		$render_node .= '<header class="revolver__comments-header">'; 

		$render_node .= '<h2><a itemprop="url" href="'. $n['route'] .'#comment-'. $c->comment_id .'">&#8226;'. $c->comment_id .'</a> '. $RKV->lang['by'] .' <span>'. $c->comment_user_name .'</span></h2>';

		$render_node .= '<time itemprop="dateCreated" datetime="'. $RKI->Calendar::formatTime($c->comment_time) .'">'. $c->comment_time .'</time>';

		$render_node .= '</header>';

		$render_node .= '<figure itemprop="creator" itemscope itemtype="https://schema.org/Person" class="revolver__comments-avatar">';

		if( $c->comment_user_avatar === 'default') {

			$src = '/public/avatars/default.png';

		}
		else {

			$src = $c->comment_user_avatar;

		}

		$render_node .= '<img itemprop="image" src="'. $src .'" alt="'. $c->comment_user_name .'" />';

		$render_node .= '<figcaption itemprop="name">'. $c->comment_user_name .'</figcaption>';

		$render_node .= '</figure>';

		if( $n['quedit'] ) {

			$quick_edit_attr = ' contenteditable="false"';
			$quick_edit_data = ' data-node="'. $c->comment_id .'" data-type="node-comment" data-user="'. $c->comment_user_name .'"';

		} 
		else {

			$quick_edit_attr = '';
			$quick_edit_data = '';

		}


		$render_node .= '<div class="revolver__comments-contents"'. $quick_edit_attr . $quick_edit_data .'>'. $RKI->HTML::Markup( 

				htmlspecialchars_decode( 

					html_entity_decode( 

						$RKI->HTML::metaHash( $c->comment_contents )

					)

				), [ 'lazy' => 1 ] ) .'</div>';


		$render_node .= '<footer class="revolver__comments-footer">';

		if( isset($n['rating']) ) {

			$tpe = PASS[ 1 ] === 'blog' ? 'blog-comment' : 'node-comment';

			$render_node .= '<div class="revolver-rating">';
			$render_node .= '<ul class="rated-'. $c->rating .'" data-node="'. $c->comment_id .'" data-user="'. USER['id'] .'" data-type="'. $tpe .'">';

				$render_node .= '<li data-rated="1">1</li>';
				$render_node .= '<li data-rated="2">2</li>';
				$render_node .= '<li data-rated="3">3</li>';
				$render_node .= '<li data-rated="4">4</li>';
				$render_node .= '<li data-rated="5">5</li>';

			$render_node .= '</ul>';

			$render_node .= '<span>'. $c->rating .'</span> / <span>5</span> #<span class="closest">'. $c->rates .'</span>';
			$render_node .= '</div>';

		}

		if( $c->editor ) {

			$render_node .= '<nav><ul>';

			if( $c->quedit ) {

				$render_node .= '<li class="revolver__quick-edit-handler" title="'. $RKV->lang['qedit'] .'">[ '. $RKV->lang['QEdit'] .' ]</li>';

			}

			$render_node .= '<li><a title="'. $c->comment_id .' '. $RKV->lang['edit'] .'" href="/comment/'. $c->comment_id .'/edit/">'. $RKV->lang['Edit'] .'</a></li>';

			$render_node .= '</ul></nav>';

		}

		$render_node .= '</footer>';

		$render_node .= '</article>';

	}

}

$render_node .= '</section>';

?>
