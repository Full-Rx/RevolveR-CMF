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

if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

	if( isset(SV['p']) ) {

		$action = 'update';

		if( isset(SV['p']['revolver_forum_container_title']) ) {

			if( (bool)SV['p']['revolver_forum_container_title']['valid'] ) {

				$title = strip_tags( SV['p']['revolver_forum_container_title']['value'] );

			}

		}

		if( isset(SV['p']['revolver_forum_container_description']) ) {

			if( (bool)SV['p']['revolver_forum_container_description']['valid'] ) {

				$description = strip_tags( SV['p']['revolver_forum_container_description']['value'] );

			}

		}


		if( isset(SV['p']['revolver_forum_container_edit']) ) {

			if( (bool)SV['p']['revolver_forum_container_edit']['valid'] ) {

				$id = SV['p']['revolver_forum_container_edit']['value'];

			}

		}

		if( isset(SV['p']['revolver_forum_container_action_delete']) ) {

			$action = 'delete';

		}

		if( isset(SV['p']['revolver_captcha']) ) {

			if( (bool)SV['p']['revolver_captcha']['valid'] ) {

				if( $RKI->Captcha::verify(SV['p']['revolver_captcha']['value']) ) {

					define('form_pass', 'pass');

				}

			}

		}

	}

	if( defined('form_pass') ) {

		if( form_pass === 'pass' ) {

			if( $action === 'update' ) {

				$RKI->Model::set('forums', [

					'id'			=> $id,
					'title'			=> $title,
					'description'	=> $description,
					'criterion'		=> 'id'

				]);

				//header('Location: '. $RNV->host .'/forum/?notification=category-updated^'. $id);

				header('Location: '. $RNV->host .'/forum/');

				//print '<!-- Forum dispatcher #1 -->';

			}
			else {

				$RKI->Model::erase('forums', [

					'criterion' => 'id::'. $id

				]);

				// Delete from database
				$RKI->Model::erase('forum_rooms', [

					'criterion' => 'forum_id::'. $id

				]);

				//header('Location: '. $RNV->host .'/categories/?notification=category-erased^'. $id);

				header('Location: '. $RNV->host .'/forum/');

				//print '<!-- Forum dispatcher #2 -->';

			}

		}
		else {

			//header('Location: '. $RNV->host .'/categories/'. $id .'/edit/?notification=no-changes');

			header('Location: '. $RNV->host .'/forum/');

			//print '<!-- Forum dispatcher #3 -->';

		}

	} 
	else {

		//header('Location: '. $RNV->host .'/categories/'. $id .'/edit/?notification=no-changes');

		header('Location: '. $RNV->host .'/forum/');
		//print '<!-- Forum dispatcher #4 -->';

	}

} 
else {

	//header('Location: '. $RNV->host .'/forum/');

}

print '<!-- Forum dispatcher -->';

define('serviceOutput', [

  'ctype'     => 'text/html', 
  'route'     => '/forum-d/'

]);

?>
