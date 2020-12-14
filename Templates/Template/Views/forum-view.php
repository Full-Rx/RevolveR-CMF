<?php

	if( !isset($n['editor']) ) {

		$n['editor'] = null;

	}

	if( !isset($n['language']['hreflang']) ) {

		$n['language']['hreflang'] = $primary_language; 

	}

	$RKI->Template::$b[] = '<article lang="'. $n['language']['hreflang'] .'" class="revolver__article article-id-'. $n['id'] .' '. $class .'">';

	$RKI->Template::$b[] = '<header class="revolver__article-header">'; 

	if( $n['teaser'] ) {

		$RKI->Template::$b[] = '<h2><a hreflang="'. $n['language']['hreflang'] .'" href="'. $n['route'] .'" rel="bookmark">'. $n['title'] .'</a></h2>';

	}
	else {

		$RKI->Template::$b[] = '<h2>'. $n['title'] .'</h2>';

	}

	$RKI->Template::$b[] = '</header>';

	if( RQST === '/' ) {

		$RKI->Template::$b[] = '<div class="revolver__article-contents">'. $RKI->HTML::Markup( 

					htmlspecialchars_decode( 

						html_entity_decode( 

							$RKI->HTML::metaHash( $n['contents'] )

						)

					), [ 'length' => 2000, 'lazy' => 1 ] ) .'</div>';

	}
	else {

		if( $flag_main_node ) {

			$RKI->Template::$b[] = '<div class="revolver__article-contents">'. $n['contents'] .'</div>';	

		}
		else {

			$RKI->Template::$b[] = '<div class="revolver__article-contents">'. $RKI->HTML::Markup( 

					htmlspecialchars_decode( 

						html_entity_decode( 

							$RKI->HTML::metaHash( $n['contents'] )

						)

					), ['lazy' => 1] ) .'</div>';

		}

	}

	if( $n['footer'] ) {

		$RKI->Template::$b[] = '<footer class="revolver__article-footer"><nav>';

		$RKI->Template::$b[] = '<ul>';

		if( $n['editor'] ) {

			$RKI->Template::$b[] = '<li><a title="'. $n['title'] .' '. $RKV->lang['edit'] .'" href="'. $n['route'] .'edit/' .'">'. $RKV->lang['Edit'] .'</a></li>';

		}

		$RKI->Template::$b[] = '</ul></nav></footer>';

	}

	$RKI->Template::$b[] = '</article>';

?>
