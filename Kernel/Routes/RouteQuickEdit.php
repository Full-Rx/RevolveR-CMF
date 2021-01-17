<?php

 /* 
  * 
  * RevolveR Route Contents Quick Edit Dispatch
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

if( isset(SV['p']) ) {

  $node = $data = $type = $user = null;

  if( isset(SV['p']['revolver_quedit_node']) ) {

    if( (bool)SV['p']['revolver_quedit_node']['valid'] ) {

      $node = SV['p']['revolver_quedit_node']['value'];

    }

  }

  if( isset(SV['p']['revolver_quedit_user']) ) {

    if( (bool)SV['p']['revolver_quedit_user']['valid'] ) {

      $user = SV['p']['revolver_quedit_user']['value'];

    }

  }

  if( isset(SV['p']['revolver_quedit_data']) ) {

    if( (bool)SV['p']['revolver_quedit_data']['valid'] ) {

      $data = $RKI->HTML::Markup(

              htmlspecialchars_decode(

                html_entity_decode(

                  SV['p']['revolver_quedit_data']['value']

                )

              )

            );

    }

  }

  if( isset(SV['p']['revolver_quedit_type']) ) {

    if( (bool)SV['p']['revolver_quedit_type']['valid'] ) {

      $type = SV['p']['revolver_quedit_type']['value'];

    }

  }

  if( $node && $data && $type && $user ) {

    if( in_array(ROLE, ['Admin', 'Writer']) || USER['name'] === $user ) {

      switch( $type ) {

        case 'node':

          $RKI->Model::set('nodes', [

            'id'          => $node,
            'content'     => $data,
            'time'        => date('d.m.Y h:i'),

            'criterion'   => 'id'

          ]);

          break;

        case 'wiki':

          $RKI->Model::set('wiki_nodes', [

            'id'          => $node,
            'content'     => $data,
            'time'        => date('d.m.Y h:i'),

            'criterion'   => 'id'

          ]);

          break;

        case 'blog':

          $RKI->Model::set('blog_nodes', [

            'id'          => $node,
            'content'     => $data,
            'time'        => date('d.m.Y h:i'),

            'criterion'   => 'id'

          ]);

          break;

        case 'forum-node':

          $RKI->Model::set('forum_rooms', [

            'id'          => $node,
            'content'     => $data,
            'time'        => date('d.m.Y h:i'),

            'criterion'   => 'id'

          ]);

          break;

        case 'node-comment':

          $RKI->Model::set('comments', [

            'id'          => $node,
            'content'     => $data,
            'time'        => date('d.m.Y h:i'),

            'criterion'   => 'id'

          ]);

          break;

        case 'blog-comment':

          $RKI->Model::set('blog_comments', [

            'id'          => $node,
            'content'     => $data,
            'time'        => date('d.m.Y h:i'),

            'criterion'   => 'id'

          ]);

          break;

        case 'forum-comment':

          $RKI->Model::set('froom_comments', [

            'id'          => $node,
            'content'     => $data,
            'time'        => date('d.m.Y h:i'),

            'criterion'   => 'id'

          ]);

          break;

        case 'store-comment':

          $RKI->Model::set('store_comments', [

            'id'          => $node,
            'content'     => $data,
            'time'        => date('d.m.Y h:i'),

            'criterion'   => 'id'

          ]);

          break;

      }

    }

  }

}

print '<!-- route contents quick edit dispatch -->';

define('serviceOutput', [

  'ctype'     => 'text/html',
  'route'     => '/quedit-d/'

]);

?>
