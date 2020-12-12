<?php

	if( isset($node_data[ 0 ]) ) {

		$nodeLoaded = true;

		$n = $node_data[ 0 ];

		if( !isset($n['editor']) ) {

			$n['editor'] = null;

		}

		if( !isset($n['quedit']) ) {

			$n['quedit'] = null;

		}

		if( !isset($n['language']['hreflang']) ) {

			$n['language']['hreflang'] = $primary_language; 

		}

		if( !isset( $n['time'] ) ) {

			$n['time'] = null;

		}

		$render_node .= '<article itemscope itemtype="http://schema.org/Article" lang="'. $n['language']['hreflang'] .'" class="revolver__article article-id-'. $n['id'] .' '. $class .'">';

		$render_node .= '<header class="revolver__article-header">'; 

		if( $n['teaser'] ) {

			$render_node .= '<h2 itemprop="name"><a itemprop="url" hreflang="'. $n['language']['hreflang'] .'" href="'. $n['route'] .'" rel="bookmark">'. $n['title'] .'</a></h2>';

		}
		else {

			$render_node .= '<h2 itemprop="name">'. $n['title'] .'</h2>';

		}

		if( $n['time'] ) {

			$render_node .= '<time itemprop="datePublished dateModified" datetime="'. $RKI->Calendar::formatTime( $n['time'] ) .'">'. $n['time'] .'</time>';

		}

		$render_node .= '</header>';

		if( $n['quedit'] ) {

			$quick_edit_attr = ' contenteditable="false"';
			$quick_edit_data = ' data-node="'. $n['id'] .'" data-type="wiki"';

		} 
		else {

			$quick_edit_attr = '';
			$quick_edit_data = '';

		}

		$render_node .= '<div itemprop="articleBody mainEntityOfPage" class="revolver__article-contents"'. $quick_edit_attr . $quick_edit_data .'>'. $markup::Markup( $n['contents'], [ 'lazy' => 1 ] ) .'</div>';


		if( $n['footer'] ) {

			$render_node .= '<footer class="revolver__article-footer">';

			$render_node .= '<div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">';
			$render_node .= '<meta itemprop="height" content="435">';
			$render_node .= '<meta itemprop="width" content="432">';
			$render_node .= '<meta itemprop="url" content="'. site_host .'/Interface/ArticlePostImage.png">';
			$render_node .= '</div>';

			$render_node .= '<div class="meta" itemprop="author publisher" itemscope itemtype="http://schema.org/Organization">';

			$render_node .= '<div itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">';
			$render_node .= '<meta itemprop="url" content="'. site_host .'/Interface/ArticlePostImage.png" />';
			$render_node .= '</div>';
			$render_node .= '<span itemprop="name">'. $n['author'] .'</span>';

			$render_node .= '</div>';


			$render_node .= '<nav>';

			$render_node .= '<ul>';

			if( $n['quedit'] ) {

				$render_node .= '<li class="revolver__quick-edit-handler" title="'. $n['title'] .' '. TRANSLATIONS[ $ipl ]['qedit'] .'">[ '. TRANSLATIONS[ $ipl ]['QEdit'] .' ]</li>';

			}

			if( $n['editor'] ) {

				$render_node .= '<li><a title="'. $n['title'] .' '. TRANSLATIONS[ $ipl ]['edit'] .'" href="'. $n['route'] .'edit/' .'">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a></li>';

			}

			$render_node .= '</ul></nav></footer>';

		}

		$render_node .= '</article>';

	}

?>
