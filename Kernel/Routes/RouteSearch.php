<?php

 /*
  * 
  * Search Route
  *
  * v.2.0.0.4
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

if( isset(SV['g']['query']) ) {

  $qs = SV['g']['query']['value'];

  $output  = '<div class="revolver__search_results">';

  $output .= '<p>'. $RNV->lang['Search for'] .' <b>'. $qs .'</b>.</p>';

  $output .= '<ol>';

  function search( string $qs, iterable $v, string $m ): string {

    if( $m === 'topic' ) {

      $url = '/forum/'. $v['forum_id'] .'/'. $v['id'] .'/';

    } 
    else {

      $url = $v['route'];

    }

      $output = '<li><a href="'. $url .'" title="'. $v['description'] .'">'.  str_ireplace( $qs, '<mark>'. $qs .'</mark>', $v['title'] ) .'</a><em>'. (isset($v['time']) ? $v['time'] : date('d-m-Y h:i')) .'</em><span>'. TRANSLATIONS[ 'EN' ][ $m ] .' › '.  str_ireplace( $qs, '<mark>'. $qs .'</mark>', $v['description'] ) .'</span>';

      $replace = trim(

        preg_replace(

          '/ +/', ' ', 

          preg_replace(

            '/~\w*~/', ' ',

            strip_tags(

              html_entity_decode(

                strip_tags(

                  preg_replace(

                    '/<[^>]*>/', '',

                    str_replace(

                      [ '&nbsp;', "\n", "\r" ], 

                      '',

                      html_entity_decode(

                          $v['content'], ENT_QUOTES, 'UTF-8'

                      )

                    )

                  )

                )

              )

            )

          )

        )

      );


      $snippet = preg_split('/'. $qs .'/i', $replace);

      $c = 1;

      foreach( $snippet as $snip ) {

        $length  = strlen( $snip ) * .3;

        $xlength = strlen( explode( $qs, $snip )[0] ); 


        if( $c % 2 !== 0 ) {

          $highlight_1 = substr( $snip, $xlength * .3, $xlength );

        }
        else {

          $highlight_2 = substr( $snip, 0, $length );

        }

        $c++;

      }

      $rgxp = '/[^\p{L}[[:punct:]]\s]+/u';

      return $output . '<dfn class="revolver__search-snippet">... '. preg_replace($rgxp, '', $highlight_1) . '<mark>'. $qs .'</mark>'. preg_replace($rgxp, '', $highlight_2) .' ...</dfn></li>';

  }


  // Nodes search
  foreach( $all_nodes as $k => $v ) {

    if( preg_match('/'. $qs .'/i', $v['content']) ) {

      $output .= search( $qs, $v, 'node' );

    }

  }

  // Forum search
  foreach( iterator_to_array(

    $RKI->Model::get( 'store_goods', [

      'criterion' => 'id::*',

      'bound'   => [

        200,   // limit

      ],

      'course'  => 'backward', // backward
      'sort'    => 'id',

    ])

  )['model::store_goods'] as $k => $v) {

    if( preg_match('/'. $qs .'/i', $v['content']) ) {

      $output .= search( $qs, $v, 'store' );

    }

  }

  // Blog search
  foreach( iterator_to_array(

    $RKI->Model::get( 'blog_nodes', [

      'criterion' => 'id::*',

      'bound'   => [

        20,   // limit

      ],

      'course'  => 'backward', // backward
      'sort'    => 'time',

    ])

  )['model::blog_nodes'] as $k => $v) {

    if( preg_match('/'. $qs .'/i', $v['content']) ) {

      $output .= search( $qs, $v, 'blog' );

    }

  }

  // Wiki search
  foreach( iterator_to_array(

    $RKI->Model::get( 'wiki_nodes', [

      'criterion' => 'id::*',

      'bound'   => [

        20,   // limit

      ],

      'course'  => 'backward', // backward
      'sort'    => 'time',

    ])

  )['model::wiki_nodes'] as $k => $v) {

    if( preg_match('/'. $qs .'/i', $v['content']) ) {

      $output .= search( $qs, $v, 'Wiki' );

    }

  }

  // Forum search
  foreach( iterator_to_array(

    $RKI->Model::get( 'forum_rooms', [

      'criterion' => 'id::*',

      'bound'   => [

        20,   // limit

      ],

      'course'  => 'backward', // backward
      'sort'    => 'time',

    ])

  )['model::forum_rooms'] as $k => $v) {

    if( preg_match('/'. $qs .'/i', $v['content']) ) {

      $output .= search( $qs, $v, 'topic' );

    }

  }

  $output .= '</ol>';

  $output .= '<p>'. $RNV->lang['Search for'] .' <b>'. $qs .'</b>.</p>';

  $output .= '</div>';

}

print $output;

define('serviceOutput', [

  'ctype'     => 'text/html',
  'route'     => '/search/'

]);

?>
