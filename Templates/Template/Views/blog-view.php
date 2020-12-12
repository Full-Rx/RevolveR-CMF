<?php

	$nodeLoaded = true;

	if( !isset($n['editor']) ) {

		$n['editor'] = null;

	}

	if( !isset($n['quedit']) ) {

		$n['quedit'] = null;

	}

	if( !isset( $n['time'] ) ) {

		$n['time'] = null;

	}

	$render_node .= '<article itemscope itemtype="https://schema.org/BlogPosting" class="revolver__article article-id-'. $n['id'] .' '. $class .'">';

	$render_node .= '<header class="revolver__article-header">'; 

	if( empty( PASS[ 2 ] ) ) {

		$render_node .= '<h2 itemprop="headline"><a itemprop="url" href="'. $n['route'] .'" rel="bookmark">'. $n['title'] .'</a></h2>';

	}
	else {

		$render_node .= '<h2 itemprop="headline">'. $n['title'] .'</h2>';

	}

	if( $n['time'] ) {

		$render_node .= '<time itemprop="datePublished dateModified" datetime="'. $RKI->Calendar::formatTime($n['time']) .'">'. $n['time'] .'</time>';

	}

	$render_node .= '</header>';

	if( $RKV->request === '/blog/' ) {

		$render_node .= '<div class="revolver__article-contents" itemprop="articleBody mainEntityOfPage">'. $n['contents'] .'</div>';

	}

	if( $n['footer'] ) {

		$render_node .= '<footer class="revolver__article-footer">';

		if( isset($n['rating']) ) {

			$tpe = PASS[ 1 ] === 'blog' ? 'blog' : 'node';

			$render_node .= '<div class="revolver-rating">';
			$render_node .= '<ul class="rated-'. $n['rating'] .'" data-node="'. $n['id'] .'" data-user="'. USER['id'] .'" data-type="'. $tpe .'">';
				$render_node .= '<li data-rated="1">1</li>';
				$render_node .= '<li data-rated="2">2</li>';
				$render_node .= '<li data-rated="3">3</li>';
				$render_node .= '<li data-rated="4">4</li>';
				$render_node .= '<li data-rated="5">5</li>';
			$render_node .= '</ul>';

			$render_node .= '<span>'. $n['rating'] .'</span> / <span>5</span> #<span class="closest">'. $n['rates'] .'</span>';
			$render_node .= '</div>';

		}

			$render_node .= '<div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">';
			$render_node .= '<meta itemprop="height" content="435">';
			$render_node .= '<meta itemprop="width" content="432">';
			$render_node .= '<meta itemprop="url" content="'. $RKV->host .'/Interface/ArticlePostImage.png">';
			$render_node .= '</div>';

			$render_node .= '<div class="meta" itemprop="author publisher" itemscope itemtype="http://schema.org/Organization">';

			$render_node .= '<div itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">';
			$render_node .= '<meta itemprop="url" content="'. $RKV->host .'/Interface/ArticlePostImage.png" />';
			$render_node .= '</div>';
			$render_node .= '<span itemprop="name">'. $n['author'] .'</span>';

			$render_node .= '</div>';

		$render_node .= '<nav>';

		$render_node .= '<ul>';

		if( $n['editor'] ) {

			$render_node .= '<li><a title="'. $n['title'] .' '. $RKV->lang['edit'] .'" href="'. $n['route'] .'edit/' .'">'. $RKV->lang['Edit'] .'</a></li>';

		}
		else {


			$render_node .= '<li><a title="'. $n['title'] .'" href="'. $n['route'] .'">'. $RKV->lang['Read More'] .' &rArr;</a></li>';

		}

		$render_node .= '</ul></nav>';

		$render_node .= '<div itemprop="author publisher" itemscope itemtype="http://schema.org/Organization"><span itemprop="name">'. $n['author'] .'</span></div>';

		$render_node .= '</footer>';

	}

	$render_node .= '</article>';


?>
