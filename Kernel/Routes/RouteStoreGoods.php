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

if( in_array( ROLE, ['Admin', 'Writer'] ) ) {

  if( isset(SV['p']) ) {

    $node_rebate = 0;

    $node_delivery = 0;

    $node_service = 0;

    $node_tax = 0;

    $node_quantity = 0;

    $node_pickup = 0;

    if( isset(SV['p']['revolver_node_edit_title']) ) {

      if( (bool)SV['p']['revolver_node_edit_title']['valid'] ) {

        $node_title = strip_tags( SV['p']['revolver_node_edit_title']['value'] );

      }

    }

    if( isset(SV['p']['revolver_node_edit_description']) ) {

      if( (bool)SV['p']['revolver_node_edit_description']['valid'] ) {

        $node_description = strip_tags( SV['p']['revolver_node_edit_description']['value'] );

      }

    }

    if( isset(SV['p']['revolver_node_edit_vendor']) ) {

      if( (bool)SV['p']['revolver_node_edit_vendor']['valid'] ) {

        $node_vendor = strip_tags( SV['p']['revolver_node_edit_vendor']['value'] );

      }

    }

    if( isset(SV['p']['revolver_node_edit_price']) ) {

      if( (bool)SV['p']['revolver_node_edit_price']['valid'] ) {

        $node_price = strip_tags( SV['p']['revolver_node_edit_price']['value'] );

      }

    }

    if( isset(SV['p']['revolver_node_edit_rebate']) ) {

      if( (bool)SV['p']['revolver_node_edit_rebate']['valid'] ) {

        $node_rebate = strip_tags( SV['p']['revolver_node_edit_rebate']['value'] );

      }

    }

    if( isset(SV['p']['revolver_node_edit_tax']) ) {

      if( (bool)SV['p']['revolver_node_edit_tax']['valid'] ) {

        $node_tax = strip_tags( SV['p']['revolver_node_edit_tax']['value'] );

      }

    }

    if( isset(SV['p']['revolver_node_edit_quantity']) ) {

      if( (bool)SV['p']['revolver_node_edit_quantity']['valid'] ) {

        $node_quantity = strip_tags( SV['p']['revolver_node_edit_quantity']['value'] );

      }

    }

    if( isset(SV['p']['revolver_node_edit_delivery']) ) {

      if( (bool)SV['p']['revolver_node_edit_delivery']['valid'] ) {

        $node_delivery = 1;

      }

    }

    if( isset(SV['p']['revolver_node_edit_pickup']) ) {

      if( (bool)SV['p']['revolver_node_edit_pickup']['valid'] ) {

        $node_pickup = 1;

      }

    }

    if( isset(SV['p']['revolver_node_edit_service']) ) {

      if( (bool)SV['p']['revolver_node_edit_service']['valid'] ) {

        $node_service = 1;

      }

    }

    if( isset(SV['p']['revolver_node_edit_content']) ) {

      if( (bool)SV['p']['revolver_node_edit_content']['valid'] ) {

        $node_content = $RKI->HTML::Markup(

                html_entity_decode(

                  htmlspecialchars_decode(

                    SV['p']['revolver_node_edit_content']['value']

                  )

                )

              );

      }

    }

    if( isset(SV['p']['revolver_node_edit_category']) ) {

      if( (bool)SV['p']['revolver_node_edit_category']['valid'] ) {

        $node_category = SV['p']['revolver_node_edit_category']['value'][0];

      }

    }

    if( isset(SV['p']['revolver_country_code']) ) {

      if( (bool)SV['p']['revolver_country_code']['valid'] ) {

        $country = SV['p']['revolver_country_code']['value'];

      }

    }

    if( isset(SV['p']['revolver_captcha']) ) {

      if( (bool)SV['p']['revolver_captcha']['valid'] ) {

        if( $RKI->Captcha::verify(SV['p']['revolver_captcha']['value']) ) {

          define('form_pass', 'pass');

        }

      }

    }

    define('form_pass', 'pass');

  }

} 
else {

  header('Location: '. $RNV->host .'/user/auth/?notification=authorization-reazon');

}

if( defined('form_pass') ) {

  if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

    if( form_pass === 'pass' ) {

      $RKI->Model::set('store_goods', [

        'title'       => $node_title,
        'content'     => $node_content,
        'description' => $node_description,
        'vendor'      => $node_vendor,
        'price'       => $node_price,
        'rebate'      => $node_rebate,
        'tax'         => $node_tax,
        'quantity'    => $node_quantity,
        'delivery'    => $node_delivery,
        'pickup'      => $node_pickup,
        'service'     => $node_service,
        'category'    => $node_category,
        'user'        => USER['name'],

        'country'     => $country,
        'published'   => 0

      ]);

      if( isset( SV['f'] ) ) {

        if( count(SV['f']) > 0 ) {

          foreach( SV['f'] as $file ) {

            foreach( $file as $f ) {

              $upload_allow = null;

              if( !is_readable($_SERVER['DOCUMENT_ROOT'] .'/public/sfiles/'. $f['name']) ) {

                if( (bool)$f['valid'] ) {

                  $upload_allow = true;

                }

              }

              if( $upload_allow ) {

              $node = iterator_to_array(

                    $RKI->Model::get('store_goods', [

                      'criterion' => 'id::*',
                      'course'    => 'backward',
                      'sort'      => 'id'

                    ])

                  )['model::store_goods'][ 0 ];

                $RKI->Model::set('store_goods_files', [

                  'node'      => $node['id'],
                  'name'      => $f['name'],
                  'criterion' => 'node'

                ]);

                move_uploaded_file( $f['temp'], $_SERVER['DOCUMENT_ROOT'] .'/public/sfiles/'. $f['name'] );

              }

            }

          }

        }

      }

      header( 'Location: '. $RNV->host . '/store/'. '?notification=node-updated');

    }
    else {

      header('Location: '. $RNV->host . '/store/goods/add/?notification=conflict-reason');

    }

  }

}
else {

  header('Location: '. $RNV->host . '/store/goods/add/?notification=no-changes');

}

print '<!-- Store goods dispatch -->';

define('serviceOutput', [

  'ctype'     => 'text/html',
  'route'     => '/store-goods-d/'

]);

?>
