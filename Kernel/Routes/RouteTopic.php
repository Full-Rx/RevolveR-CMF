<?php

 /* 
  * 
  * RevolveR Route Contents Dispatch
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

if( Auth ) {

  if( in_array( ROLE, ['Admin', 'Writer', 'User'] ) ) {

    $forum_room = null;

    if( isset(SV['p']) ) {

      $token_explode = explode('|', $RKI->Cipher::crypt('decrypt', SV['c']['usertoken']));

      $files_to_delete = [];

      $action = null;

      // Files
      $f_limit = 0;

      while( $f_limit <= 100 ) {

        if( isset(SV['p'][ 'revolver_delete_attached_file_'. $f_limit ]) ) {

          $files_to_delete[ $f_limit ] = SV['p'][ 'revolver_delete_attached_file_'. $f_limit ]['value'];

        }

        $f_limit++;

      }

      if( isset(SV['p']['revolver_froom_edit_id']) ) {

        if( (bool)SV['p']['revolver_froom_edit_id']['valid'] ) {

          $froom_id = SV['p']['revolver_froom_edit_id']['value'];

        }

      }

      if( isset(SV['p']['revolver_froom_route']) ) {

        if( (bool)SV['p']['revolver_froom_route']['valid'] ) {

          $froom_route = SV['p']['revolver_froom_route']['value'];

        }

      }

      if( isset(SV['p']['revolver_froom_edit_title']) ) {

        if( (bool)SV['p']['revolver_froom_edit_title']['valid'] ) {

          $froom_title = strip_tags( SV['p']['revolver_froom_edit_title']['value'] );

        }

      }

      if( isset(SV['p']['revolver_froom_edit_description']) ) {

        if( (bool)SV['p']['revolver_froom_edit_description']['valid'] ) {

          $froom_description = strip_tags( SV['p']['revolver_froom_edit_description']['value'] );

        }

      }

      if( isset(SV['p']['revolver_froom_edit_content']) ) {

        if( (bool)SV['p']['revolver_froom_edit_content']['valid'] ) {

          $froom_content = $RKI->HTML::Markup(

                  html_entity_decode(

                    htmlspecialchars_decode(

                      SV['p']['revolver_froom_edit_content']['value']

                    )

                  )

                );

        }

      }

      if( isset(SV['p']['revolver_node_edit_forum']) ) {

        if( (bool)SV['p']['revolver_node_edit_forum']['valid'] ) {

          $forum_id = SV['p']['revolver_node_edit_forum']['value'][0];

        }

      }

      if( isset(SV['p']['revolver_froom_edit_delete']) ) {

        if( (bool)SV['p']['revolver_froom_edit_delete']['valid'] ) {

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

      $forum_room = iterator_to_array(

        $RKI->Model::get('forum_rooms', [

          'criterion' => 'id::'. (int)$froom_id,
          'course'  => 'backward',
          'sort'    => 'id'

        ])

      )['model::forum_rooms'][0];

    }

  } 
  else {

    header('Location: '. $RNV->host .'/user/auth/?notification=authorization-reazon');

  }

  if( $forum_room ) {

    if( $token_explode[ 2 ] === $forum_room['user'] || in_array(ROLE, ['Admin', 'Writer', 'User'], true)  ) {

      if( $action !== 'delete' ) {

        $RKI->Model::set('forum_rooms', [

          'id'          => $froom_id['id'],
          'title'       => $froom_title,
          'content'     => $froom_content,
          'description' => $froom_description,
          'forum_id'    => (int)$forum_id,
          'criterion'   => 'id'

        ]);

        // Files
        if( count($files_to_delete) > 0 ) {

          foreach( $files_to_delete as $file_to_delete ) {

            // Delete from database
            $model::erase('froom_files', [

              'criterion' => 'id::'. explode(':', $file_to_delete)[ 0 ]

            ]);

            // Delete file from filesystem
            unlink( $_SERVER['DOCUMENT_ROOT'] . '/public/tfiles/' . explode(':', $file_to_delete)[ 1 ] );

          }

        }

        if( isset( SV['f'] ) ) {

          if( count(SV['f']) > 0 ) {

            foreach( SV['f'] as $file ) {

              foreach( $file as $f ) {

                $upload_allow = null;

                if( !is_readable($_SERVER['DOCUMENT_ROOT'] .'/public/tfiles/'. $f['name']) ) {

                  if( (bool)$f['valid'] ) {

                    $upload_allow = true;

                  }

                }

                if( $upload_allow ) {

                  $RKI->Model::set('froom_files', [

                    'froom'     => $froom_id,
                    'name'      => $f['name'],
                    'criterion' => 'node'

                  ]);

                  move_uploaded_file( $f['temp'], $_SERVER['DOCUMENT_ROOT'] .'/public/tfiles/'. $f['name'] );

                }

              }

            }

          }

        }

        header( 'Location: '. $RNV->host . '/forum/'. $forum_id .'/'. $froom_id.'/' );

      }
      else if( $action === 'delete' ) {

        $files = iterator_to_array(

          $RKI->Model::get('froom_files', [

            'criterion' => 'froom::'. $froom_id,
            'course'    => 'forward',
            'sort'      => 'id'

          ])

        )['model::files'];

        if( $files ) {

          foreach( $files as $f ) {   

            unlink( $_SERVER['DOCUMENT_ROOT'] .'/public/tfiles/'. $f['name'] );

          }

          // Delete from database
          $RKI->Model::erase('froom_files', [

            'criterion' => 'froom_id::'. $froom_id

          ]);

        }

        $RKI->Model::erase('froom_comments', [

          'criterion' => 'froom_id::'. $froom_id

        ]); 

        // Delete from database
        $RKI->Model::erase('forum_rooms', [

          'criterion' => 'id::'. $froom_id

        ]);

        header( 'Location: '. $RNV->host .'/forum/?notification=node-erased^'. $froom_id );

      }

    }

  } 
  else {

    header('Location: '. $RNV->host .'?notification=no-changes');

  }

}
else {

  header('Location: '. $RNV->host .'?notification=no-changes');

}

print '<!-- forum topic dispatch -->';

define('serviceOutput', [

  'ctype'     => 'text/html',
  'route'     => '/contents-d/'

]);

?>
