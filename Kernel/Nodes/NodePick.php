<?php

 /*
  * 
  * Search Route
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

$output = '<p>'. $RNV->lang['Welcome Pick networks'] .'!</p>';

$query = null;

if( isset(SV['p']) ) {

  if( isset(SV['p']['revolver_pick_query']) ) {

    if( (bool)SV['p']['revolver_pick_query']['valid'] ) {

      $query = SV['p']['revolver_pick_query']['value'];

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

$form_parameters = [

  // main para
  'id'      => 'pick-query-box',
  'class'   => 'revolver__pick-query-box revolver__new-fetch',
  'method'  => 'post',
  'action'  => $RNV->request,
  'encrypt' => true,
  'captcha' => true,
  'submit'  => 'Pick it',

  // included fieldsets
  'fieldsets' => [

    // fieldset contents parameters
    'fieldset_1' => [

      'title' => 'Pick query box',
      
      // wrap fields into label
      'labels' => [

        'label_1' => [

          'title'  => 'Query phrase',
          'access' => 'comment',
          'auth'   => 'all',

          'fields' => [

            0 => [

              'type'        => 'input:text',
              'name'        => 'revolver_pick_query',
              'placeholder' => 'Query phrase',
              'required'    => true

            ],

          ],

        ],

      ],

    ],

  ],

];

// Picks query box
$output .= $RKI->HTMLForm::build( $form_parameters );

if( defined('form_pass') ) {

  if( form_pass === 'pass' ) {

    if( $query ) {

      $qs = $query;

      $output  .= '<div class="revolver__search_results">';

      $output .= '<p>'. $RNV->lang['Search for'] .' <b>'. $qs .'</b>.</p>';

      $output .= '<ol>';

      function search( string $qs, iterable $v, ?iterable $crating, Model $model ): iterable {

        $ptitle = htmlspecialchars_decode($v['title']);
        $pdescr = htmlspecialchars_decode($v['description']);

        $rgxp = '/[^\p{L}[[:punct:]]\s]+/u';

        if( $pdescr === 'null' ) { // use short snippet of content as description

          $pdescr = preg_replace($rgxp, '', substr(

            html_entity_decode(

                $v['content']

            ), 0, 100)

          ) .'...';

        } 
        else {

          $pdescr = preg_replace($rgxp, '', substr(

            html_entity_decode(

                $pdescr

            ), 0, 100) .'...'

          );

        }

        /* Rating block */
        $crate = 0;

        if( $crating ) {

          foreach( $crating as $r => $rv ) {

            $crate += $rv['rate'];

          }

          $crate /= count( $crating ); 

        }
        else {

          $crating = [];

        }

        $output  = '<li>';

        $output .= '<a target="_blank" href="'. $v['uri'] .'" title="'. $pdescr .'">';
        $output .= str_ireplace( $qs, '<mark>'. $qs .'</mark>', $ptitle) .'</a>';

        $output .= '<em>'. (isset($v['date']) ? $v['date'] : date('d-m-Y h:i')) .'</em>';

        $output .= '<span>'. str_ireplace( $qs, '<mark>'. $qs .'</mark>', $pdescr ) .'</span>';

        $replace = trim(

          preg_replace(

            ['/ +/', '/~\w*~/', '/<[^>]*>/' ],

            [' ', ' ', ''],

            str_replace(

              [ '&nbsp;', "\n", "\r" ], 

              '',

              html_entity_decode(

                $v['content'], ENT_QUOTES, 'UTF-8'

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

        $output .= '<dfn class="revolver__search-snippet">... '. preg_replace($rgxp, '', $highlight_1) . '<mark>'. $qs .'</mark>'. preg_replace($rgxp, '', $highlight_2) .' ...</dfn>';

        $tpe = 'index';

        $output .= '<div class="revolver-rating">';
        $output .= '<ul class="rated-'. floor($crate) .'" data-node="'. $v['id'] .'" data-user="'. USER['id'] .'" data-type="'. $tpe .'">';

          $output .= '<li data-rated="1">1</li>';
          $output .= '<li data-rated="2">2</li>';
          $output .= '<li data-rated="3">3</li>';
          $output .= '<li data-rated="4">4</li>';
          $output .= '<li data-rated="5">5</li>';

        $output .= '</ul>';

        $output .= '<span>'. floor($crate) .'</span> / <span>5</span> #<span class="closest">'. count($crating) .'</span>';
        $output .= '</div>';

        $output .= '</li>';

        return [ floor($crate), $output  ];

      }

      // Picking results
      $results = [];

      // Index picking
      foreach( iterator_to_array(

        $RKI->Model::get( 'index', [

          'criterion' => 'content::'. $qs,

          'bound'   => [

            5000,   // limit

          ],

          'course'  => 'forward', // backward
          'expert'  => true,
          'sort'    => 'id'

        ])

      )['model::index'] as $k => $v ) {

        if( preg_match('/'. $qs .'/i', $v['content']) ) {

          $rating = iterator_to_array(

              $RKI->Model::get( 'index_ratings', [

                'criterion' => 'index_id::'. $v['id'],
                'course'    => 'forward',
                'sort'      => 'id'

              ])

            )['model::index_ratings'];

          $snippet = search( $qs, $v, $rating, $model );

          $results[ $snippet[0] ][] = $snippet[1];

        }

      }

      // Sort results by rating
      ksort($results);

      foreach( array_reverse($results) as $r ) {

        shuffle( $r ); // randomize positions

        foreach( $r as $s ) {

          $output .= $s;

        }

      }

      $output .= '</ol>';

      $output .= '<p>'. $RNV->lang['Search for'] .' <b>'. $qs .'</b>.</p>';

      $output .= '</div>';

    }

  }

}

$node_data[] = [

  'title'     => $RNV->lang['Pick'],
  'id'        => 'pick',
  'route'     => '/pick/',
  'contents'  => $output,
  'teaser'    => null,
  'footer'    => null,
  'published' => 1

];

?>
