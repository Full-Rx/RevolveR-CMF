<?php

	if( !isset($n['editor']) ) {

		$n['editor'] = null;

	}

	if( !isset($n['language']['hreflang']) ) {

		$n['language']['hreflang'] = $primary_language; 

	}

	$render_node .= '<article lang="'. $n['language']['hreflang'] .'" class="revolver__article article-id-'. $n['id'] .' '. $class .'">';

	$render_node .= '<header class="revolver__article-header">'; 

	if( $n['teaser'] ) {

		$render_node .= '<h2><a hreflang="'. $n['language']['hreflang'] .'" href="'. $n['route'] .'" rel="bookmark">'. $n['title'] .'</a></h2>';

	}
	else {

		$render_node .= '<h2>'. $n['title'] .'</h2>';

	}

	$render_node .= '</header>';

	if( RQST === '/' ) {

		$render_node .= '<div class="revolver__article-contents">'. $RKI->HTML::Markup( 

					htmlspecialchars_decode( 

						html_entity_decode( 

							$RKI->HTML::metaHash( $n['contents'] )

						)

					), [ 'length' => 2000, 'lazy' => 1 ] ) .'</div>';

	}
	else {

		if( $flag_main_node ) {

			$render_node .= '<div class="revolver__article-contents">'. $n['contents'] .'</div>';	

		}
		else {

			$render_node .= '<div class="revolver__article-contents">'. $RKI->HTML::Markup( 

					htmlspecialchars_decode( 

						html_entity_decode( 

							$RKI->HTML::metaHash( $n['contents'] )

						)

					), ['lazy' => 1] ) .'</div>';

		}

	}

	if( $n['footer'] ) {

		$render_node .= '<footer class="revolver__article-footer"><nav>';

		$render_node .= '<ul>';

		if( $n['editor'] ) {

			$render_node .= '<li><a title="'. $n['title'] .' '. $RKV->lang['edit'] .'" href="'. $n['route'] .'edit/' .'">'. $RKV->lang['Edit'] .'</a></li>';

		}

		$render_node .= '</ul></nav></footer>';

	}

	$render_node .= '</article>';

?>
