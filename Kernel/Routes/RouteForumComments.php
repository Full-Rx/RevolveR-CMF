<?php

 /* 
  * 
  * RevolveR Route Comment Dispatch
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

if( in_array(ROLE, ['Admin', 'Writer', 'User'], true) ) {

	if( isset(SV['p']) ) {

		$advanced_action = 'update';

		$action = null;

		$published = 0;

		$contents = '';

		if( in_array(ROLE, ['Admin', 'Writer', 'User'], true) ) {

			if( isset(SV['p']['revolver_comment_id']) ) {

				if( (bool)SV['p']['revolver_comment_id']['valid'] ) {

					$comment_id = SV['p']['revolver_comment_id']['value'];

				}

			}

			if( isset(SV['p']['revolver_comments_action_edit']) ) {

				if( (bool)SV['p']['revolver_comments_action_edit']['valid'] ) {

					$action = 'edit';

				}

			}

			if( isset(SV['p']['revolver_comments_action_delete']) ) {

				if( (bool)SV['p']['revolver_comments_action_delete']['valid'] ) {

					$advanced_action = 'delete';

				}

			}

			if( isset(SV['p']['revolver_comments_published']) ) {

				if( (bool)SV['p']['revolver_comments_published']['valid'] ) {

					$published = 1;

				}

			}

		}

		if( isset(SV['p']['revolver_comment_content']) ) {

			if( (bool)SV['p']['revolver_comment_content']['valid'] ) {

				$contents = $RKI->HTML::Markup(

								html_entity_decode(

									htmlspecialchars_decode(

										SV['p']['revolver_comment_content']['value']

									)

								)

							);

			}

		}

		if( isset(SV['p']['revolver_comment_time']) ) {

			if( (bool)SV['p']['revolver_comment_time']['valid'] ) {

				$time = SV['p']['revolver_comment_time']['value'];

			}

		}

		if( isset(SV['p']['revolver_comment_user_id']) ) {

			if( (bool)SV['p']['revolver_comment_user_id']['valid'] ) {

				$user_id = SV['p']['revolver_comment_user_id']['value'];

			}

		}

		if( isset(SV['p']['revolver_froom_id']) ) {

			if( (bool)SV['p']['revolver_froom_id']['valid'] ) {

				$node_id = SV['p']['revolver_froom_id']['value'];

			}

		}

		if( isset(SV['p']['revolver_forum_id']) ) {

			if( (bool)SV['p']['revolver_forum_id']['valid'] ) {

				$forum_id = SV['p']['revolver_forum_id']['value'];

			}

		}

	}

	if( $action === 'edit' ) {

		if( $advanced_action === 'delete' ) {

			// Delete comment
			$RKI->Model::erase('froom_comments', [

				'criterion' => 'id::'. $comment_id

			]);

			header('Location: '. $RNV->host . '/forum/'. $forum_id .'/'. $node_id .'/?notification=comment-erased^'. $comment_id);

		}
		else {

			if( strlen( $contents ) > 0 ) {

				$RKI->Model::set('froom_comments', [

					'id'		=> $comment_id,
					'user_id'	=> $user_id,
					'froom_id'	=> $node_id,
					'content'	=> $contents,
					'time'		=> date('d.m.Y h:m'),
					'published'	=> $published,

					'criterion'	=> 'id'

				]);

				header('Location: '. $RNV->host . '/forum/'. $forum_id .'/'. $node_id .'/?notification=comment-updated^'. $comment_id .'#comment-'. $comment_id );

			}
			else {

				header('Location: '. $RNV->host . '/forum/?notification=no-changes' );

			}

		}

	}
	else {

		$RKI->Model::set('froom_comments', [

			'froom_id'	=> $node_id,
			'user_id'	=> $user_id,
			'content'	=> $contents,
			'time'		=> date('d.m.Y h:m'),
			'published'	=> 1

		]);

		header( 'Location: '. $RNV->host . '/forum/'. $forum_id .'/'. $node_id .'/?notification=comment-added^' . 'not-subscribed');

	}

}

print '<!-- Forum comments dispatch -->';

define('serviceOutput', [

	'ctype'	=> 'text/html',
	'route'	=> '/forum-comments-d/'

]);

?>
