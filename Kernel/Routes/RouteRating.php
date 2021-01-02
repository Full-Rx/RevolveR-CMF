<?php

 /* 
  * 
  * RevolveR Route Contents Rating Dispatch
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

if( isset(SV['p']) ) {

  $node = $user = $value = $type = null;

  if( isset(SV['p']['revolver_rating_node']) ) {

    if( (bool)SV['p']['revolver_rating_node']['valid'] ) {

      $node = SV['p']['revolver_rating_node']['value'];

    }

  }

  if( isset(SV['p']['revolver_rating_user']) ) {

    if( (bool)SV['p']['revolver_rating_user']['valid'] ) {

      $user = SV['p']['revolver_rating_user']['value'];

    }

  }

  if( isset(SV['p']['revolver_rating_value']) ) {

    if( (bool)SV['p']['revolver_rating_value']['valid'] ) {

      $value = SV['p']['revolver_rating_value']['value'];

    }

  }

  if( isset(SV['p']['revolver_rating_type']) ) {

    if( (bool)SV['p']['revolver_rating_type']['valid'] ) {

      $type = SV['p']['revolver_rating_type']['value'];

    }

  }

  if( $node && $user && $value && $type ) {


    switch( $type ) {

      case 'node':

        $RKI->Model::set('nodes_ratings', [

          'node_id'     => $node,
          'user_id'     => $user,
          'rate'        => $value

        ]);

        break;

      case 'blog':

        $RKI->Model::set('blog_ratings', [

          'node_id'     => $node,
          'user_id'     => $user,
          'rate'        => $value

        ]);

        break;

      case 'store':

        $RKI->Model::set('goods_ratings', [

          'node_id'     => $node,
          'user_id'     => $user,
          'rate'        => $value

        ]);

        break;

      case 'node-comment':

        $RKI->Model::set('comments_ratings', [

          'comment_id'  => $node,
          'user_id'     => $user,
          'rate'        => $value

        ]);

        break;

      case 'blog-comment':

        $RKI->Model::set('blog_comments_ratings', [

          'comment_id'  => $node,
          'user_id'     => $user,
          'rate'        => $value

        ]);

        break;

      case 'store-comment':

        $RKI->Model::set('store_comments_ratings', [

          'comment_id'  => $node,
          'user_id'     => $user,
          'rate'        => $value

        ]);

        break;

      case 'index':

        if( Auth ) {

          $RKI->Model::set('index_ratings', [

            'index_id'    => $node,
            'user_id'     => $user,
            'rate'        => $value

          ]);

        }

        break;


    }

  }

}

print '<!-- route contents rating dispatch -->';

define('serviceOutput', [

  'ctype'     => 'text/html',
  'route'     => '/rating-d/'

]);

?>
