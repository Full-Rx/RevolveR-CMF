<?php

 /* 
  * 
  * RevolveR Route Contents Dispatch
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

if( $RNV->auth ) {

  if( in_array( ROLE, ['Admin', 'Writer', 'User'] ) ) {

    if( isset(SV['p']) ) {

      $token_explode = explode('|', $cipher::crypt('decrypt', SV['c']['usertoken']));

      $files_to_delete = [];

      $action = null;

      $published = 0;

      // Files
      $f_limit = 0;

      while( $f_limit <= 100 ) {

        if( isset(SV['p'][ 'revolver_delete_attached_file_'. $f_limit ]) ) {

          $files_to_delete[ $f_limit ] = SV['p'][ 'revolver_delete_attached_file_'. $f_limit ]['value'];

        }

        $f_limit++;

      }

      if( isset(SV['p']['revolver_node_edit_id']) ) {

        if( (bool)SV['p']['revolver_node_edit_id']['valid'] ) {

          $blog_id = SV['p']['revolver_node_edit_id']['value'];

        }

      }

      if( isset(SV['p']['revolver_node_published']) ) {

        if( (bool)SV['p']['revolver_node_published']['valid'] ) {

          $published = SV['p']['revolver_node_published']['value'];

        }

      }

      if( isset(SV['p']['revolver_node_user']) ) {

        if( (bool)SV['p']['revolver_node_user']['valid'] ) {

          $blog_user = SV['p']['revolver_node_user']['value'];

        }

      }

      if( isset(SV['p']['revolver_node_route']) ) {

        if( (bool)SV['p']['revolver_node_route']['valid'] ) {

          $blog_route = SV['p']['revolver_node_route']['value'];

        }

      }

      if( isset(SV['p']['revolver_blog_edit_title']) ) {

        if( (bool)SV['p']['revolver_blog_edit_title']['valid'] ) {

          $blog_title = strip_tags( SV['p']['revolver_blog_edit_title']['value'] );

        }

      }

      if( isset(SV['p']['revolver_blog_edit_description']) ) {

        if( (bool)SV['p']['revolver_blog_edit_description']['valid'] ) {

          $blog_description = strip_tags( SV['p']['revolver_blog_edit_description']['value'] );

        }

      }

      if( isset(SV['p']['revolver_blog_edit_content']) ) {

        if( (bool)SV['p']['revolver_blog_edit_content']['valid'] ) {

          $blog_content = $RKI->HTML::Markup(

                  html_entity_decode(

                    htmlspecialchars_decode(

                      SV['p']['revolver_blog_edit_content']['value']

                    )

                  )

                );

        }

      }

      if( isset(SV['p']['revolver_blog_edit_delete']) ) {

        if( (bool)SV['p']['revolver_blog_edit_delete']['valid'] ) {

          $action = 'delete';

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
  else {

    header('Location: '. $RNV->host .'/user/auth/?notification=authorization-reazon', true, 301);

  }

  if( $token_explode[ 2 ] === $blog_user || in_array(ROLE, ['Admin', 'Writer'], true)  ) {

    if( $action !== 'delete' ) {

      $RKI->Model::set('blog_nodes', [

        'id'          => $blog_id,
        'title'       => $blog_title,
        'content'     => $blog_content,
        'route'       => $blog_route,
        'time'        => date('d-m-Y h:i'),
        'description' => $blog_description,
        'published'   => $published,
        'criterion'   => 'id'

      ]);

      file_get_contents('http://www.google.com/ping?sitemap='. $RNV->host .'/sitemap/');

      // Files
      if( count($files_to_delete) > 0 ) {

        foreach( $files_to_delete as $file_to_delete ) {

          // Delete from database
          $model::erase('blog_files', [

            'criterion' => 'id::'. explode(':', $file_to_delete)[ 0 ]

          ]);

          // Delete file from filesystem
          unlink( $_SERVER['DOCUMENT_ROOT'] . '/public/bfiles/' . explode(':', $file_to_delete)[ 1 ] );

        }

      }

		if( isset( SV['f'] ) ) {

	      if( count(SV['f']) > 0 ) {

	        foreach( SV['f'] as $file ) {

	          foreach( $file as $f ) {

	            $upload_allow = null;

	            if( !is_readable($_SERVER['DOCUMENT_ROOT'] .'/public/bfiles/'. $f['name']) ) {

	              if( (bool)$f['valid'] ) {

	                $upload_allow = true;

	              }

	            }

	            if( $upload_allow ) {

	              $RKI->Model::set('blog_files', [

	                'node'      => $blog_route,
	                'name'      => $f['name'],
	                'criterion' => 'node'

	              ]);

	              move_uploaded_file( $f['temp'], $_SERVER['DOCUMENT_ROOT'] .'/public/bfiles/'. $f['name'] );

	            }

	          }

	        }

	      }

	    }

      header( 'Location: '. $RNV->host . $blog_route, true, 301 );

    }
    else if( $action === 'delete' ) {

      $files = iterator_to_array(

        $RKI->Model::get('blog_files', [

          'criterion' => 'node::'. $blog_route,
          'course'    => 'forward',
          'sort'      => 'id'

        ])

      )['model::blog_files'];

      if( $files ) {

        foreach( $files as $f ) {   

          unlink( $_SERVER['DOCUMENT_ROOT'] .'/public/bfiles/'. $f['name'] );

        }

        // Delete from database
        $RKI->Model::erase('blog_files', [

          'criterion' => 'node::'. $blog_route

        ]);

      }

      $RKI->Model::erase('blog_comments', [

        'criterion' => 'node_id::'. $blog_id

      ]);

      $RKI->Model::erase('blog_nodes', [

        'criterion' => 'id::'. $blog_id

      ]);

      header( 'Location: '. $RNV->host .'/blog/?notification=node-erased^'. $blog_id, true, 301 );

    }

  }

}
else {

  header('Location: '. $RNV->host .'?notification=no-changes');

}

print '<!-- blog dispatch -->';

define('serviceOutput', [

  'ctype'     => 'text/html',
  'route'     => '/blog-d/'

]);

?>
