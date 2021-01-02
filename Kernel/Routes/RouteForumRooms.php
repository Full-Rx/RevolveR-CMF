<?php

 /*
  * 
  * RevolveR Route Forums Edit
  *
  * v.2.0.0.0
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
  *
  */

if( Auth ) {

	if( in_array(ROLE, ['Admin', 'Writer', 'User'], true) ) {

		if( isset(SV['p']) ) {

			$room_title = $room_content = $room_description = $id = null;

			if( isset(SV['p']['revolver_forum_room_title']) ) {

				if( (bool)SV['p']['revolver_forum_room_title']['valid'] ) {

					$room_title = strip_tags( SV['p']['revolver_forum_room_title']['value'] );

				}

			}

			if( isset(SV['p']['revolver_forum_room_description']) ) {

				if( (bool)SV['p']['revolver_forum_room_description']['valid'] ) {

					$room_description = strip_tags( SV['p']['revolver_forum_room_description']['value'] );

				}

			}

			if( isset(SV['p']['revolver_forum_room_content']) ) {

				if( (bool)SV['p']['revolver_forum_room_content']['valid'] ) {

					$room_content = $RKI->HTML::Markup(

										html_entity_decode(

											htmlspecialchars_decode(

												SV['p']['revolver_forum_room_content']['value']

											)

										)

								);

				}

			}


			if( isset(SV['p']['revolver_forum_room_id']) ) {

				if( (bool)SV['p']['revolver_forum_room_id']['valid'] ) {

					$id = SV['p']['revolver_forum_room_id']['value'];

				}

			}

			if( isset(SV['p']['revolver_captcha']) ) {

				if( (bool)SV['p']['revolver_captcha']['valid'] ) {

					if( $RKI->Captcha::verify(SV['p']['revolver_captcha']['value']) ) {

						define('form_pass', 'pass');

					}

				}

			}

		}

	}

} 
else {

	header('Location: '. $RNV->host .'/forum/');

}

if( defined('form_pass') ) {

	if( $room_title && $room_description && $room_content && $id && form_pass ) {

			$RKI->Model::set('forum_rooms', [

				'title'			=> $room_title,
				'description'	=> $room_description,
				'content'		=> $room_content,
				'user'			=> USER['name'],
				'time'			=> date('d.m.Y h:i'),
				'forum_id'		=> $id

			]);

			//$notify::set('status', 'Forum room created');

			header('Location: '. $RNV->host .'/forum/'. $id .'/');

	} 
	else {

		header('Location: '. $RNV->host .'/forum/');

	}

}
else {

	header('Location: '. $RNV->host .'/forum/');

}

print '<!-- Forum rooms dispatcher -->';

define('serviceOutput', [

  'ctype'     => 'text/html', 
  'route'     => '/forum-room-d/'

]);

?>
