<?php

 /*
  * 
  * Preview Route
  *
  * v.2.0.0.0
  *
  *
  *
  *
  *               ^
  *              | |
  *            @#####@
  *          (###   ###)-.
  *        .(###     ###) \
  *       /  (###   ###)   )
  *      (=-  .@#####@|_--"
  *      /\    \_|l|_/ (\
  *     (=-\     |l|    /
  *      \  \.___|l|___/
  *      /\      |_|   /
  *     (=-\._________/\
  *      \             /
  *        \._________/
  *          #  ----  #
  *          #   __   #
  *          \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

if( !empty( SV['p'] ) ) {

	if( isset(SV['p']['revolver_preview_mode']) ) {

		if( (bool)SV['p']['revolver_preview_mode']['valid'] ) {

			switch( SV['p']['revolver_preview_mode']['value'] ) {

				case 'node':

					ob_start();

					$node_id = 0;

					$node_route = '';

					$render = '';

					if( isset(SV['p']['revolver_node_edit_id']) ) {

						if( (bool)SV['p']['revolver_node_edit_id']['valid'] ) {

							$node_id = SV['p']['revolver_node_edit_id']['value'];

						}

					}

					if( isset(SV['p']['revolver_node_edit_title']) ) {

						if( (bool)SV['p']['revolver_node_edit_title']['valid'] ) {

							$node_title = SV['p']['revolver_node_edit_title']['value'];

						}

					}

					if( isset(SV['p']['revolver_node_edit_description']) ) {

						if( (bool)SV['p']['revolver_node_edit_description']['valid'] ) {

							$node_description = SV['p']['revolver_node_edit_description']['value'];

						}

					}

					if( isset(SV['p']['revolver_node_edit_route']) ) {

						if( (bool)SV['p']['revolver_node_edit_route']['valid'] ) {

							$node_route = strip_tags(preg_replace("/\/+/", '/', preg_replace("/ +/", '-', trim( SV['p']['revolver_node_edit_route']['value'] ))));

						}

					}

					if( isset(SV['p']['revolver_node_edit_content']) ) {

						if( (bool)SV['p']['revolver_node_edit_content']['valid'] ) {

							$node_content = SV['p']['revolver_node_edit_content']['value'];

						}

					}

					if( !(bool)$node_id ) {

						foreach( main_nodes as $k => $v ) {

							if( trim($v['route']) === trim($node_route) ) {

								$RKI->Notify::set('notice', 'Route allready defined for system');

								break;

							}

						}

						if( strlen( $node_route ) !== strlen( utf8_decode( $node_route ) ) ) {

							$RKI->Notify::set('notice', 'Route not allow to use non english letters');

						}

						$route_fix = ltrim(

							rtrim(

								$node_route, '/'

							), '/'

						);

						$node = iterator_to_array(

							$RKI->Model::get('nodes', [

								'criterion' => 'route::'.'\/'. str_replace( ['.', '/'], ['\.', '\/'], $route_fix ) .'\/',

								'bound'		=> [

									1

								],

								'course'	=> 'backward',
								'sort' 		=> 'id',
								'expert'	=> true

							])

						)['model::nodes'];

					}
					else {

						$node = null;

					}

					if( $node ) {

						$RKI->Notify::set('notice', 'Node with defined route already exist');

						$RKI->Notify::set('active', '<div><a href="'. $node[0]['route'] .'" title="'. $node[0]['description'] .'">'. $node[0]['title'] .'</a></div>', null);

					}
					else {

						$RKI->Notify::set('active', 'Sense allowed for this node');

						$render .= '<article class="revolver__article revolver__article-preview">';

						$render .= '<header class="revolver__article-header">'; 

						$render .= '<h2>'. $node_title .'</h2>';

						$render .= '<time>'. date('Y.m.d h:i') .'</time>';

						$render .= '</header>';

						$render .= '<div class="revolver__article-contents">'. $RKI->HTML::Markup(

							htmlspecialchars_decode(

								html_entity_decode(

									$node_content 

								)

							)

						) .'</div>';

						$render .= '</article>';

					}

					print $RKI->Notify::Conclude() . $render;

					break;

				case 'topic':

					ob_start();

					$node_id = 0;

					$render = '';

					if( isset(SV['p']['revolver_node_edit_id']) ) {

						if( (bool)SV['p']['revolver_node_edit_id']['valid'] ) {

							$node_id = SV['p']['revolver_node_edit_id']['value'];

						}

					}

					if( isset(SV['p']['revolver_forum_room_title']) ) {

						if( (bool)SV['p']['revolver_forum_room_title']['valid'] ) {

							$node_title = SV['p']['revolver_forum_room_title']['value'];

						}

					}

					if( isset(SV['p']['revolver_forum_room_description']) ) {

						if( (bool)SV['p']['revolver_forum_room_description']['valid'] ) {

							$node_description = SV['p']['revolver_forum_room_description']['value'];

						}

					}

					if( isset(SV['p']['revolver_forum_room_content']) ) {

						if( (bool)SV['p']['revolver_forum_room_content']['valid'] ) {

							$node_content = SV['p']['revolver_forum_room_content']['value'];

						}

					}

					$RKI->Notify::set('active', 'Sense allowed for this node');

					$render .= '<article class="revolver__article revolver__article-preview">';

					$render .= '<header class="revolver__article-header">'; 

					$render .= '<h2>'. $node_title .'</h2>';

					$render .= '<time>'. date('Y.m.d h:i') .'</time>';

					$render .= '</header>';

					$render .= '<div class="revolver__article-contents">'. $RKI->HTML::Markup( 

						htmlspecialchars_decode( 

							html_entity_decode( 

								$node_content 

							)

						)

					) .'</div>';

					$render .= '</article>';

					print $RKI->Notify::Conclude() . $render;

					break;

				case 'topic_edit':

					ob_start();

					$node_id = 0;

					$render = '';

					if( isset(SV['p']['revolver_froom_edit_id']) ) {

						if( (bool)SV['p']['revolver_froom_edit_id']['valid'] ) {

							$node_id = SV['p']['revolver_froom_edit_id']['value'];

						}

					}

					if( isset(SV['p']['revolver_froom_edit_title']) ) {

						if( (bool)SV['p']['revolver_froom_edit_title']['valid'] ) {

							$node_title = SV['p']['revolver_froom_edit_title']['value'];

						}

					}

					if( isset(SV['p']['revolver_froom_edit_description']) ) {

						if( (bool)SV['p']['revolver_froom_edit_description']['valid'] ) {

							$node_description = SV['p']['revolver_froom_edit_description']['value'];

						}

					}

					if( isset(SV['p']['revolver_froom_edit_content']) ) {

						if( (bool)SV['p']['revolver_froom_edit_content']['valid'] ) {

							$node_content = SV['p']['revolver_froom_edit_content']['value'];

						}

					}

					$RKI->Notify::set('active', 'Sense allowed for this node');

					$render .= '<article class="revolver__article revolver__article-preview">';

					$render .= '<header class="revolver__article-header">'; 

					$render .= '<h2>'. $node_title .'</h2>';

					$render .= '<time>'. date('Y.m.d h:i') .'</time>';

					$render .= '</header>';

					$render .= '<div class="revolver__article-contents">'. $RKI->HTML::Markup( 

						htmlspecialchars_decode( 

							html_entity_decode( 

								$node_content 

							)

						)

					) .'</div>';

					$render .= '</article>';

					print $RKI->Notify::Conclude() . $render;

					break;

				case 'blog':

					ob_start();

					$node_id = 0;

					$node_route = '';

					$render = '';

					if( isset(SV['p']['revolver_node_edit_id']) ) {

						if( (bool)SV['p']['revolver_node_edit_id']['valid'] ) {

							$node_id = SV['p']['revolver_node_edit_id']['value'];

						}

					}

					if( isset(SV['p']['revolver_node_edit_title']) ) {

						if( (bool)SV['p']['revolver_node_edit_title']['valid'] ) {

							$node_title = SV['p']['revolver_node_edit_title']['value'];

						}

					}

					if( isset(SV['p']['revolver_node_edit_description']) ) {

						if( (bool)SV['p']['revolver_node_edit_description']['valid'] ) {

							$node_description = SV['p']['revolver_node_edit_description']['value'];

						}

					}

					if( isset(SV['p']['revolver_node_edit_route']) ) {

						if( (bool)SV['p']['revolver_node_edit_route']['valid'] ) {

							$node_route = strip_tags(preg_replace("/\/+/", '/', preg_replace("/ +/", '-', trim( SV['p']['revolver_node_edit_route']['value'] ))));

						}

					}

					if( isset(SV['p']['revolver_node_edit_content']) ) {

						if( (bool)SV['p']['revolver_node_edit_content']['valid'] ) {

							$node_content = SV['p']['revolver_node_edit_content']['value'];

						}

					}

					if( !(bool)$node_id ) {

						foreach( main_nodes as $k => $v ) {

							if( trim($v['route']) === trim($node_route) ) {

								$RKI->Notify::set('notice', 'Route allready defined for system');

								break;

							}

						}

						if( strlen( $node_route ) !== strlen( utf8_decode( $node_route ) ) ) {

							$RKI->Notify::set('notice', 'Route not allow to use non english letters');

						}

						$route_fix = ltrim(

							rtrim(

								$node_route, '/'

							), '/'

						);

						$node = iterator_to_array(

							$RKI->Model::get('blog_nodes', [

								'criterion' => 'route::'.'\/'. str_replace( ['.', '/'], ['\.', '\/'], $route_fix ) .'\/',

								'bound'		=> [

									1

								],

								'course'	=> 'backward',
								'sort' 		=> 'id',
								'expert'	=> true

							])

						)['model::blog_nodes'];

					}
					else {

						$node = null;

					}

					if( $node ) {

						$RKI->Notify::set('notice', 'Node with defined route already exist');

						$RKI->Notify::set('active', '<div><a href="'. $node[0]['route'] .'" title="'. $node[0]['description'] .'">'. $node[0]['title'] .'</a></div>', null);

					}
					else {

						$RKI->Notify::set('active', 'Sense allowed for this node');

						$render .= '<article class="revolver__article revolver__article-preview">';

						$render .= '<header class="revolver__article-header">'; 

						$render .= '<h2>'. $node_title .'</h2>';

						$render .= '<time>'. date('Y.m.d h:i') .'</time>';

						$render .= '</header>';

						$render .= '<div class="revolver__article-contents">'. $RKI->HTML::Markup( 

							htmlspecialchars_decode( 

								html_entity_decode( 

									$node_content 

								)

							)

						) .'</div>';

						$render .= '</article>';

					}

					print $RKI->Notify::Conclude() . $render;

					break;

				case 'blog_edit':

					ob_start();

					$node_id = 0;

					$node_route = '';

					$render = '';

					if( isset(SV['p']['revolver_node_edit_id']) ) {

						if( (bool)SV['p']['revolver_node_edit_id']['valid'] ) {

							$node_id = SV['p']['revolver_node_edit_id']['value'];

						}

					}

					if( isset(SV['p']['revolver_blog_edit_title']) ) {

						if( (bool)SV['p']['revolver_blog_edit_title']['valid'] ) {

							$node_title = SV['p']['revolver_blog_edit_title']['value'];

						}

					}

					if( isset(SV['p']['revolver_blog_edit_description']) ) {

						if( (bool)SV['p']['revolver_blog_edit_description']['valid'] ) {

							$node_description = SV['p']['revolver_blog_edit_description']['value'];

						}

					}

					if( isset(SV['p']['revolver_node_edit_route']) ) {

						if( (bool)SV['p']['revolver_node_edit_route']['valid'] ) {

							$node_route = strip_tags(preg_replace("/\/+/", '/', preg_replace("/ +/", '-', trim( SV['p']['revolver_node_edit_route']['value'] ))));

						}

					}

					if( isset(SV['p']['revolver_blog_edit_content']) ) {

						if( (bool)SV['p']['revolver_blog_edit_content']['valid'] ) {

							$node_content = SV['p']['revolver_blog_edit_content']['value'];

						}

					}

					if( !(bool)$node_id ) {

						foreach( main_nodes as $k => $v ) {

							if( trim($v['route']) === trim($node_route) ) {

								$RKI->Notify::set('notice', 'Route allready defined for system');

								break;

							}

						}

						if( strlen( $node_route ) !== strlen( utf8_decode( $node_route ) ) ) {

							$RKI->Notify::set('notice', 'Route not allow to use non english letters');

						}

						$route_fix = ltrim(

							rtrim(

								$node_route, '/'

							), '/'

						);

						$node = iterator_to_array(

							$RKI->Model::get('blog_nodes', [

								'criterion' => 'route::'.'\/'. str_replace( ['.', '/'], ['\.', '\/'], $route_fix ) .'\/',

								'bound'		=> [

									1

								],

								'course'	=> 'backward',
								'sort' 		=> 'id',
								'expert'	=> true

							])

						)['model::blog_nodes'];

					}
					else {

						$node = null;

					}

					if( $node ) {

						$RKI->Notify::set('notice', 'Node with defined route already exist');

						$RKI->Notify::set('active', '<div><a href="'. $node[0]['route'] .'" title="'. $node[0]['description'] .'">'. $node[0]['title'] .'</a></div>', null);

					}
					else {

						$RKI->Notify::set('active', 'Sense allowed for this node');

						$render .= '<article class="revolver__article revolver__article-preview">';

						$render .= '<header class="revolver__article-header">'; 

						$render .= '<h2>'. $node_title .'</h2>';

						$render .= '<time>'. date('Y.m.d h:i') .'</time>';

						$render .= '</header>';

						$render .= '<div class="revolver__article-contents">'. $RKI->HTML::Markup( 

							htmlspecialchars_decode( 

								html_entity_decode( 

									$node_content 

								)

							)

						) .'</div>';

						$render .= '</article>';

					}

					print $RKI->Notify::Conclude() . $render;

					break;

				case 'comment':

					ob_start();

					$users = iterator_to_array(

						$RKI->Model::get('users', [

							'criterion'	=> 'id::*',
							'course'	=> 'forward',
							'sort'		=> 'id'

						])

					)['model::users'];

					$src = '/public/avatars/default.png';

					$comment_time = date('Y.m.d h:i');

					$edit = null;

					$comment_id = 0;

					if( isset(SV['p']['revolver_comments_action_edit']) ) {

						if( (bool)SV['p']['revolver_comments_action_edit']['valid'] ) {

							$edit = true;

						}

					}

					if( isset(SV['p']['revolver_comment_user_id']) ) {

						if( (bool)SV['p']['revolver_comment_user_id']['valid'] ) {

							if( (int)SV['p']['revolver_comment_user_id']['value'] !== (int)BigNumericX64 ) {

								$user = iterator_to_array(

									$RKI->Model::get('users', [

										'criterion'	=> 'id::'. (int)SV['p']['revolver_comment_user_id']['value'],
										'course'	=> 'forward',
										'sort'		=> 'id'

									])

								)['model::users'];

								if( $user ) {

									$src = $user[0]['avatar'] === 'default' ? '/public/avatars/default.png' : $user[0]['avatar'];

								}

								$RKI->Notify::set('active', 'Sense allowed for this comment');

							}
							else {

								$RKI->Notify::set('inactive', 'Sense for this comment awaiting moderation');

							}

						}

					}

					if( isset(SV['p']['revolver_comment_user_name']) ) {

						if( (bool)SV['p']['revolver_comment_user_name']['valid'] ) {

							$comment_user_name = SV['p']['revolver_comment_user_name']['value'];

							if( $users && !Auth ) {

								foreach( $users as $u ) {

									if( $u['nickname'] === trim( SV['p']['revolver_comment_user_name']['value'] ) ) {

										$RKI->Notify::set('notice', 'User with given name already registered');

										break;

									}

								}

							}

						}

					}

					if( isset(SV['p']['revolver_comment_user_email']) ) {

						if( (bool)SV['p']['revolver_comment_user_email']['valid'] ) {

							if( $users && !Auth ) {

								foreach( $users as $u ) {

									if( $u['email'] === trim( SV['p']['revolver_comment_user_email']['value'] ) ) {

										$RKI->Notify::set('notice', 'User with given email already registered');

										break;

									}

								}

							}

						}

					}

					if( isset(SV['p']['revolver_comment_node_route']) ) {

						if( (bool)SV['p']['revolver_comment_node_route']['valid'] ) {

							$comment_route = SV['p']['revolver_comment_node_route']['value']; 

						}

					}

					if( isset(SV['p']['revolver_comment_content']) ) {

						if( (bool)SV['p']['revolver_comment_content']['valid'] ) {

							$comment_contents = SV['p']['revolver_comment_content']['value'];

						}

					}

					if( isset(SV['p']['revolver_comment_id']) ) {

						if( (bool)SV['p']['revolver_comment_id']['valid'] ) {

							$comment_id = SV['p']['revolver_comment_id']['value']; 

						}

					}

					$cid = !(bool)$comment_id ? 'comment preview' : $comment_id;

					$render .= '<article id="comment-'. $comment_id .'" class="revolver__article-preview revolver__comments comments-'. $comment_id .' published">';

					$render .= '<header class="revolver__comments-header">'; 

					$render .= '<h2><a href="'. $comment_route .'#comment-'. $comment_id .'">&#8226; '. $cid .'</a> '. $RNV->lang['by'] .' <span>'. $comment_user_name .'</span></h2>';

					$render .= '<time datetime="'. $RKI->Calendar::formatTime( $comment_time ) .'">'. $comment_time .'</time>';

					$render .= '</header>';

					$render .= '<figure class="revolver__comments-avatar">';

					$render .= '<img src="'. $src .'" alt="'. $comment_user_name .'" />';

					$render .= '</figure>';

					$render .= '<div class="revolver__comments-contents">'. $RKI->HTML::Markup( 

						htmlspecialchars_decode( 

							html_entity_decode( 

								$comment_contents 

							)

						)

					) .'</div>';

					$render .= '</article>';

					print $RKI->Notify::Conclude() . $render;

					break;

				case 'blog_comment':

					ob_start();

					$users = iterator_to_array(

						$RKI->Model::get('users', [

							'criterion'	=> 'id::*',
							'course'	=> 'forward',
							'sort'		=> 'id'

						])

					)['model::users'];

					$src = '/public/avatars/default.png';

					$comment_time = date('Y.m.d h:i');

					$edit = null;

					$comment_id = 0;

					if( isset(SV['p']['revolver_comments_action_edit']) ) {

						if( (bool)SV['p']['revolver_comments_action_edit']['valid'] ) {

							$edit = true;

						}

					}

					if( isset(SV['p']['revolver_comment_user_id']) ) {

						if( (bool)SV['p']['revolver_comment_user_id']['valid'] ) {

							if( (int)SV['p']['revolver_comment_user_id']['value'] !== (int)BigNumericX64 ) {

								$user = iterator_to_array(

									$RKI->Model::get('users', [

										'criterion'	=> 'id::'. (int)SV['p']['revolver_comment_user_id']['value'],
										'course'	=> 'forward',
										'sort'		=> 'id'

									])

								)['model::users'];

								if( $user ) {

									$src = $user[0]['avatar'] === 'default' ? '/public/avatars/default.png' : $user[0]['avatar'];
									$comment_user_name = $user[0]['nickname'];

								}

								$RKI->Notify::set('active', 'Sense allowed for this comment');

							}
							else {

								$RKI->Notify::set('inactive', 'Sense for this comment awaiting moderation');

							}

						}

					}

					if( isset(SV['p']['revolver_node_route']) ) {

						if( (bool)SV['p']['revolver_node_route']['valid'] ) {

							$comment_route = SV['p']['revolver_comment_node_route']['value']; 

						}

					}

					if( isset(SV['p']['revolver_comment_content']) ) {

						if( (bool)SV['p']['revolver_comment_content']['valid'] ) {

							$comment_contents = SV['p']['revolver_comment_content']['value'];

						}

					}

					if( isset(SV['p']['revolver_comment_id']) ) {

						if( (bool)SV['p']['revolver_comment_id']['valid'] ) {

							$comment_id = SV['p']['revolver_comment_id']['value']; 

						}

					}

					$cid = !(bool)$comment_id ? 'comment preview' : $comment_id;

					$render .= '<article id="comment-'. $comment_id .'" class="revolver__article-preview revolver__comments comments-'. $comment_id .' published">';

					$render .= '<header class="revolver__comments-header">'; 

					$render .= '<h2><a href="'. $comment_route .'#comment-'. $comment_id .'">&#8226; '. $cid .'</a> '. $RNV->lang['by'] .' <span>'. $comment_user_name .'</span></h2>';

					$render .= '<time datetime="'. $RKI->Calendar::formatTime( $comment_time ) .'">'. $comment_time .'</time>';

					$render .= '</header>';

					$render .= '<figure class="revolver__comments-avatar">';

					$render .= '<img src="'. $src .'" alt="'. $comment_user_name .'" />';

					$render .= '</figure>';

					$render .= '<div class="revolver__comments-contents">'. $RKI->HTML::Markup( 

						htmlspecialchars_decode( 

							html_entity_decode( 

								$comment_contents 

							)

						)

					) .'</div>';

					$render .= '</article>';

					print $RKI->Notify::Conclude() . $render;

					break;

				case 'message':

					ob_start();

					$render = '';

					$message = null;

					$author = null;

					$to = null;

					if( isset(SV['p']['revolver_mailto_nickname']) ) {

						if( (bool)SV['p']['revolver_mailto_nickname']['valid'] ) {

							$to = SV['p']['revolver_mailto_nickname']['value'];

						}

					}

					if( isset(SV['p']['revolver_mailto_message']) ) {

						if( (bool)SV['p']['revolver_mailto_message']['valid'] ) {

							$message = $RKI->HTML::Markup( SV['p']['revolver_mailto_message']['value'] );;

						}

					}

					if( isset(SV['p']['revolver_user_name']) ) {

						if( (bool)SV['p']['revolver_user_name']['valid'] ) {

							$author = SV['p']['revolver_user_name']['value'];

						}

					}

					if( $to ) {

						$user = iterator_to_array(

							$RKI->Model::get('users', [

								'criterion' => 'nickname::'. $to,

								'bound'		=> [

									1,

								],

								'course'	=> 'forward',
								'sort' 		=> 'id'

							])

						)['model::users'];

					}

					if( $user && $message ) {

						$RKI->Notify::set('active', 'Sense allowed for wthis message');

						$render .= '<dl class="revolver__messages revolver__article-preview">';
						$render .= '<dd class="revolver__messages-message">';

						if( $user[0]['avatar'] === 'default' ) {

							$avatar = '<img src="/public/avatars/default.png" alt="'. $user[0]['nickname']  .'" />';

						}
						else {

							$avatar = '<img src="'. $user[0]['avatar'] .'" alt="'. $user[0]['nickname'] .'" />';

						}

						$render .= '<figure class="revolver__messages-avatar">'. $avatar .'</figure>';

						$render .= '<div class="revolver__messages-body">';
						$render .= '<header><b>'. $RNV->lang['Message from'] .' '. $author .'</b> <time>'. date('Y.m.d h:i') .'</time></header>';

						$render .= '<div class="revolver__messages-text">'. $message .'</div>';

						$render .= '</div>';
						$render .= '</dd>';

						$render .= '</dl>';

					}

					if( !$user ) {

						$RKI->Notify::set('inactive', 'User to deliver not found');

					}

					print $RKI->Notify::Conclude() . $render;

					break;

			}

		}

	}

}

print '<!-- Service Preview -->';

define('serviceOutput', [

  'ctype'     => 'text/html',
  'route'     => '/preview/'

]);

?>
