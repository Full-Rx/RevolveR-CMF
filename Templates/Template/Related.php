<?php 

if( defined('ROUTE') ):

	if( ROUTE['node'] !== '#user' && ROUTE['node'] !== '#categories' ):

?>

<section class="revolver__related">

<?php 

if( $RKV->installed ) {

	$related = $related_comments = '';

	$nodesByCategories = iterator_to_array(

		$RKI->Model::get('category->node', [

			'criterion' => 'categories::id::*'

		])

	)['model::category->node'];

	if( $nodesByCategories ) {

		// Sort
		$nbc = [];

		foreach( $nodesByCategories as $r ) {

			$nbc[ $r['categories']['id'] ]['id'] = $r['categories']['id']; 
			$nbc[ $r['categories']['id'] ]['title'] = $r['categories']['title'];
			$nbc[ $r['categories']['id'] ]['items'][] = $r['nodes'];

		}

		// Make templated
		foreach( $nbc as $c ) {

			$RKI->Template::$b[] = '<div class="revolver__related-group-category-'. $c['id'] .'">';
			$RKI->Template::$b[] = '<h4>'. $c['title'] .'</h4>';

			$RKI->Template::$b[] = '<ul>';		

			foreach( $c['items'] as $n ) {

				if( $n['country'] === LANGUAGE ) {

					if( (bool)$n['published'] ) {

						$RKI->Template::$b[] = '<li><a hreflang="'. $RKI->Language::getLanguageData(LANGUAGE)['hreflang'] .'" title="'. $n['description'] .'" href="'. $n['route'] .'">'. $n['title'] .'</a></li>';

					}
					else {

						$RKI->Template::$b[] = '<li>'. $n['title'] .'</li>';

					}

				}
			
			}

			$RKI->Template::$b[] = '</ul>';
			$RKI->Template::$b[] = '</div>';

		}

	}

	$blogNodes = iterator_to_array(

		$RKI->Model::get( 'blog_nodes', [

			'criterion' => 'id::*',

			'bound'		=> [

				10

			],

			'course'	=> 'backward',
			'sort' 		=> 'id'

		])

	)['model::blog_nodes'];

	if( $blogNodes ) {

		$RKI->Template::$b[] = '<div class="revolver__related-group-category-blog">';
		$RKI->Template::$b[] = '<h4>'. $RKV->lang['Blog']  .'</h4>';

		$RKI->Template::$b[] = '<ul>';		

		foreach( $blogNodes as $n ) {

			if( (bool)$n['published'] ) {

				$RKI->Template::$b[] = '<li><a title="'. $n['description'] .'" href="'. $n['route'] .'">'. $n['title'] .'</a></li>';

			}

		}

		$RKI->Template::$b[] = '</ul>';
		$RKI->Template::$b[] = '</div>';

	}

    print implode("\n", $RKI->Template::$b);

    $RKI->Template::$b = [];

	$comments = iterator_to_array(

		$RKI->Model::get( 'node->comment', [

			'criterion' => 'comments::id::*'

		])

	)['model::node->comment'];

	if( $comments ) {

		$RKI->Template::$b[] = '<div class="revolver__related-group-comments">';
		$RKI->Template::$b[] = '<h4>'. $RKV->lang['Latest comments'] .'</h4>';
		$RKI->Template::$b[] = '<ul>';

		$show_comments = null;

		foreach( $comments as $c => $v ) {

			$comment = mb_substr(

							strip_tags(

								html_entity_decode(

									htmlspecialchars_decode( 

										$v['comments']['content']

									)

								)

							), 0, 26, 'utf-8'

						);

			if( (bool)$v['comments']['published'] ) {

				$show_comments = true;

				$class = 'published';

			}
			else {

				$class = 'unpublished';

				if( isset( ACCESS['role'] ) ) {

					if( in_array( ACCESS['role'], ['none', 'User'], true ) ) {

						continue;

					}

				}

				if( ACCESS === 'none' ) {

					continue;

				}

			}

			$date = explode(' ',  $v['comments']['time']);

			$datetime = explode('.', $date[0]);

			$datetime = $datetime[ 2 ] .'-'. $datetime[ 1 ] .'-'. $datetime[ 0 ] .'T'. ( (bool)strlen( $date[ 1 ] ) ? $date[ 1 ] : '12:00' );

			$RKI->Template::$b[] = '<li class="'. $class .'">#'. $v['comments']['id'] .' :: ';
			$RKI->Template::$b[] = '<a hreflang="'. $RKI->Language::getLanguageData( $v['nodes']['country'] )['hreflang'] .'" title="'. $v['comments']['time'] .'" href="'. $v['nodes']['route'] .'#comment-'. $v['comments']['id'] .'">'. $comment .'</a>';
			$RKI->Template::$b[] = '<time datetime="'. $datetime .'">'. $v['comments']['time'] .'</time>';
			$RKI->Template::$b[] = '<span>'. $RKV->lang['by'] .' '. $v['comments']['user_name'] .'</span>';
			$RKI->Template::$b[] = '</li>';

		}

		$RKI->Template::$b[] = '</ul></div>';

		if( !$RKV->auth && $show_comments || $RKV->auth ) {

			print implode("\n", $RKI->Template::$b);

			$RKI->Template::$b = [];

		}

	}

}

?>

</section>

<?php

	endif;

endif;

?>
