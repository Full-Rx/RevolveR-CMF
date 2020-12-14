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

		$RKI->Template::$b[] = '<article itemscope itemtype="http://schema.org/Article" lang="'. $n['language']['hreflang'] .'" class="revolver__article article-id-'. $n['id'] .' '. $class .'">';

		$RKI->Template::$b[] = '<header class="revolver__article-header">'; 

		if( $n['teaser'] ) {

			$RKI->Template::$b[] = '<h2 itemprop="name"><a itemprop="url" hreflang="'. $n['language']['hreflang'] .'" href="'. $n['route'] .'" rel="bookmark">'. $n['title'] .'</a></h2>';

		}
		else {

			$RKI->Template::$b[] = '<h2 itemprop="name">'. $n['title'] .'</h2>';

		}

		if( $n['time'] ) {

			$RKI->Template::$b[] = '<time itemprop="datePublished dateModified" datetime="'. $RKI->Calendar::formatTime( $n['time'] ) .'">'. $n['time'] .'</time>';

		}

		$RKI->Template::$b[] = '</header>';

		if( $n['quedit'] ) {

			$quick_edit_attr = ' contenteditable="false"';
			$quick_edit_data = ' data-node="'. $n['id'] .'" data-type="wiki"';

		} 
		else {

			$quick_edit_attr = '';
			$quick_edit_data = '';

		}

		$RKI->Template::$b[] = '<div itemprop="articleBody mainEntityOfPage" class="revolver__article-contents"'. $quick_edit_attr . $quick_edit_data .'>'. $markup::Markup( $n['contents'], [ 'lazy' => 1 ] ) .'</div>';


		if( $n['footer'] ) {

			$RKI->Template::$b[] = '<footer class="revolver__article-footer">';

			$RKI->Template::$b[] = '<div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">';
			$RKI->Template::$b[] = '<meta itemprop="height" content="435">';
			$RKI->Template::$b[] = '<meta itemprop="width" content="432">';
			$RKI->Template::$b[] = '<meta itemprop="url" content="'. site_host .'/Interface/ArticlePostImage.png">';
			$RKI->Template::$b[] = '</div>';

			$RKI->Template::$b[] = '<div class="meta" itemprop="author publisher" itemscope itemtype="http://schema.org/Organization">';

			$RKI->Template::$b[] = '<div itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">';
			$RKI->Template::$b[] = '<meta itemprop="url" content="'. site_host .'/Interface/ArticlePostImage.png" />';
			$RKI->Template::$b[] = '</div>';
			$RKI->Template::$b[] = '<span itemprop="name">'. $n['author'] .'</span>';

			$RKI->Template::$b[] = '</div>';


			$RKI->Template::$b[] = '<nav>';

			$RKI->Template::$b[] = '<ul>';

			if( $n['quedit'] ) {

				$RKI->Template::$b[] = '<li class="revolver__quick-edit-handler" title="'. $n['title'] .' '. TRANSLATIONS[ $ipl ]['qedit'] .'">[ '. TRANSLATIONS[ $ipl ]['QEdit'] .' ]</li>';

			}

			if( $n['editor'] ) {

				$RKI->Template::$b[] = '<li><a title="'. $n['title'] .' '. TRANSLATIONS[ $ipl ]['edit'] .'" href="'. $n['route'] .'edit/' .'">'. TRANSLATIONS[ $ipl ]['Edit'] .'</a></li>';

			}

			$RKI->Template::$b[] = '</ul></nav></footer>';

		}

		$RKI->Template::$b[] = '</article>';

	}

?>
