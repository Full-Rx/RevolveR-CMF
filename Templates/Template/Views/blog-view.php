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

	$RKI->Template::$b[] = '<article itemscope itemtype="https://schema.org/BlogPosting" class="revolver__article article-id-'. $n['id'] .' '. $class .'">';

	$RKI->Template::$b[] = '<header class="revolver__article-header">'; 

	if( empty( PASS[ 2 ] ) ) {

		$RKI->Template::$b[] = '<h2 itemprop="headline"><a itemprop="url" href="'. $n['route'] .'" rel="bookmark">'. $n['title'] .'</a></h2>';

	}
	else {

		$RKI->Template::$b[] = '<h2 itemprop="headline">'. $n['title'] .'</h2>';

	}

	if( $n['time'] ) {

		$RKI->Template::$b[] = '<time itemprop="datePublished dateModified" datetime="'. $RKI->Calendar::formatTime($n['time']) .'">'. $n['time'] .'</time>';

	}

	$RKI->Template::$b[] = '</header>';

	if( $RKV->request === '/blog/' ) {

		$RKI->Template::$b[] = '<div class="revolver__article-contents" itemprop="articleBody mainEntityOfPage">'. $n['contents'] .'</div>';

	}

	if( $n['footer'] ) {

		$RKI->Template::$b[] = '<footer class="revolver__article-footer">';

		if( isset($n['rating']) ) {

			$tpe = PASS[ 1 ] === 'blog' ? 'blog' : 'node';

			$RKI->Template::$b[] = '<div class="revolver-rating">';
			$RKI->Template::$b[] = '<ul class="rated-'. $n['rating'] .'" data-node="'. $n['id'] .'" data-user="'. USER['id'] .'" data-type="'. $tpe .'">';
			$RKI->Template::$b[] = '<li data-rated="1">1</li>';
			$RKI->Template::$b[] = '<li data-rated="2">2</li>';
			$RKI->Template::$b[] = '<li data-rated="3">3</li>';
			$RKI->Template::$b[] = '<li data-rated="4">4</li>';
			$RKI->Template::$b[] = '<li data-rated="5">5</li>';
			$RKI->Template::$b[] = '</ul>';

			$RKI->Template::$b[] = '<span>'. $n['rating'] .'</span> / <span>5</span> #<span class="closest">'. $n['rates'] .'</span>';
			$RKI->Template::$b[] = '</div>';

		}

			$RKI->Template::$b[] = '<div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">';
			$RKI->Template::$b[] = '<meta itemprop="height" content="435">';
			$RKI->Template::$b[] = '<meta itemprop="width" content="432">';
			$RKI->Template::$b[] = '<meta itemprop="url" content="'. $RKV->host .'/Interface/ArticlePostImage.png">';
			$RKI->Template::$b[] = '</div>';

			$RKI->Template::$b[] = '<div class="meta" itemprop="author publisher" itemscope itemtype="http://schema.org/Organization">';

			$RKI->Template::$b[] = '<div itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">';
			$RKI->Template::$b[] = '<meta itemprop="url" content="'. $RKV->host .'/Interface/ArticlePostImage.png" />';
			$RKI->Template::$b[] = '</div>';
			$RKI->Template::$b[] = '<span itemprop="name">'. $n['author'] .'</span>';

			$RKI->Template::$b[] = '</div>';

		$RKI->Template::$b[] = '<nav>';

		$RKI->Template::$b[] = '<ul>';

		if( $n['editor'] ) {

			$RKI->Template::$b[] = '<li><a title="'. $n['title'] .' '. $RKV->lang['edit'] .'" href="'. $n['route'] .'edit/' .'">'. $RKV->lang['Edit'] .'</a></li>';

		}
		else {


			$RKI->Template::$b[] = '<li><a title="'. $n['title'] .'" href="'. $n['route'] .'">'. $RKV->lang['Read More'] .' &rArr;</a></li>';

		}

		$RKI->Template::$b[] = '</ul></nav>';

		$RKI->Template::$b[] = '<div itemprop="author publisher" itemscope itemtype="http://schema.org/Organization"><span itemprop="name">'. $n['author'] .'</span></div>';

		$RKI->Template::$b[] = '</footer>';

	}

	$RKI->Template::$b[] = '</article>';


?>
