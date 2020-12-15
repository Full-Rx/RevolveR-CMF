<?php

	$nodeLoaded = true;

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

	$xlang  = PASS[ 1 ] !== 'blog' && PASS[ 1 ] !== 'forum' ? ' lang="'. $n['language']['hreflang'] .'"' : '';
	$xalang = PASS[ 1 ] !== 'blog' && PASS[ 1 ] !== 'forum' ? ' hreflang="'. $n['language']['hreflang'] .'"' : '';

	$RKI->Template::$b[] = '<article itemscope itemtype="http://schema.org/Article"'. $xlang .' class="revolver__article article-id-'. $n['id'] .' '. $class .'">';

	$RKI->Template::$b[] = '<header class="revolver__article-header">';

	if( $n['teaser'] ) {

		$RKI->Template::$b[] = '<h2 itemprop="headline"><a itemprop="url"'. $xalang .' href="'. $n['route'] .'" rel="bookmark">'. $n['title'] .'</a></h2>';

	}
	else {

		$RKI->Template::$b[] = '<h2 itemprop="headline name">'. $n['title'] .'</h2>';

	}

	if( $n['time'] ) {

		$RKI->Template::$b[] = '<time itemprop="datePublished dateModified" datetime="'. $RKI->Calendar::formatTime( $n['time'] ) .'">'. $n['time'] .'</time>';

	}

	$RKI->Template::$b[] = '</header>';

	if( RQST === '/' ) {

		$RKI->Template::$b[] = '<div itemprop="articleBody mainEntityOfPage" class="revolver__article-contents">'. $RKI->HTML::Markup( $RKI->HTML::metaHash($n['contents']), [ 'length' => 2000, 'lazy' => 1 ] ) .'</div>';

	}
	else {

		if( $flag_main_node ) {

			if( PASS[ 1 ] === 'forum' && is_numeric(PASS[ 2 ]) && is_numeric(PASS[ 3 ]) ) {

				$topic_author = iterator_to_array(

						$RKI->Model::get('users', [

							'criterion' => 'nickname::'. $n['author'],
							'course'	=> 'forward',
							'sort'		=> 'id'

						])

					)['model::users'][ 0 ];

				$RKI->Template::$b[] = '<figure itemprop="creator" itemscope itemtype="https://schema.org/Person" class="revolver__comments-avatar">';

				if( $topic_author['avatar'] === 'default') {

					$src = '/public/avatars/default.png';

				}
				else {

					$src = $topic_author['avatar'];

				}

				$RKI->Template::$b[] = '<img itemprop="image" src="'. $src .'" alt="'. $topic_author['nickname'] .'" />';

				$RKI->Template::$b[] = '<figcaption itemprop="name">'. $topic_author['nickname'] .'</figcaption>';

				$RKI->Template::$b[] = '</figure>';

				$RKI->Template::$b[] = '<div itemprop="articleBody mainEntityOfPage" class="revolver__article-contents" data-node="'. $n['id'] .'" data-type="forum-node" data-user="'. $n['author'] .'">';

				$RKI->Template::$b[] = $RKI->HTML::Markup( $RKI->HTML::metaHash($n['contents']), [ 'lazy' => 1 ] );

				$RKI->Template::$b[] = '</div>';

			}
			else {

				if( $n['quedit'] ) {

					$quick_edit_attr = ' contenteditable="false"';

					if( PASS[ 1 ] === 'wiki' ) {

						$quick_edit_data = ' data-node="'. $n['id'] .'" data-type="wiki" data-user="'. $n['author'] .'"';

					} 

					if( PASS[ 1 ] === 'blog' ) {

						$quick_edit_data = ' data-node="'. $n['id'] .'" data-type="blog" data-user="'. $n['author'] .'"';


					}

				} 
				else {

					$quick_edit_attr = '';
					$quick_edit_data = '';

				}

				$RKI->Template::$b[] = '<div itemprop="articleBody mainEntityOfPage" class="revolver__article-contents"'. $quick_edit_attr . $quick_edit_data .'>'. $n['contents'] .'</div>';

			}


		}
		else {

			if( $n['quedit'] ) {

				$quick_edit_attr = ' contenteditable="false"';
				$quick_edit_data = ' data-node="'. $n['id'] .'" data-type="node" data-user="'. $n['author'] .'"';

			} 
			else {

				$quick_edit_attr = '';
				$quick_edit_data = '';

			}

			$RKI->Template::$b[] = '<div itemprop="articleBody mainEntityOfPage" class="revolver__article-contents"'. $quick_edit_attr . $quick_edit_data .'>';

			$RKI->Template::$b[] = $RKI->HTML::Markup( $RKI->HTML::metaHash($n['contents']), [ 'lazy' => 1 ] );

			$RKI->Template::$b[] ='</div>';

		}

	}


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

	$RKI->Template::$b[] = '<div class="socialize">';
	$RKI->Template::$b[] = '<ul>';
	$RKI->Template::$b[] = '<li class="fb" data-share="https://www.facebook.com/sharer.php?src='. $RKV->brand .'&u='. $RKV->host . $n['route'] .'&t='. $n['title'] .'"></li>';
	$RKI->Template::$b[] = '<li class="vk" data-share="https://vk.com/share.php?url='. $RKV->host . $n['route'] .'&title='. $n['title'] .'&description'. $n['description'] .'=&image='. $RKV->host .'/Interface/ArticlePostImage.png"></li>';
	$RKI->Template::$b[] = '<li class="tw" data-share="https://twitter.com/intent/tweet?url=&text='. $n['title'] .' :: '. $n['description'] .'"></li>';
	$RKI->Template::$b[] = '</ul>';
	$RKI->Template::$b[] = '</div>';

	if( $n['footer'] ) {

		$RKI->Template::$b[] = '<nav>';

		$RKI->Template::$b[] = '<ul>';

		if( $n['quedit'] ) {

			$RKI->Template::$b[] = '<li class="revolver__quick-edit-handler" title="'. $n['title'] .' '. $RKV->lang['qedit'] .'">[ '. $RKV->lang['QEdit'] .' ]</li>';

		}

		if( $n['editor'] ) {

			$RKI->Template::$b[] = '<li><a title="'. $n['title'] .' '. $RKV->lang['edit'] .'" href="'. $n['route'] .'edit/' .'">'. $RKV->lang['Edit'] .'</a></li>';

		}
		else {

			if( PASS[ 1 ] !== 'blog' && PASS[ 1 ] !== 'wiki' ) {

				$RKI->Template::$b[] = '<li><a itemprop="url"'. $xalang .' title="'. $n['title'] .'" href="'. $n['route'] .'">'. $RKV->lang['Read More'] .' &rArr;</a></li>';

			}

		}

		$RKI->Template::$b[] = '</ul></nav>';

	}

	$RKI->Template::$b[] = '</footer>';

	$RKI->Template::$b[] = '</article>';

	// Similar contents links next \ prev 
	if( isset($n['similar']) && $RKV->request !== '/' && !(bool)pagination['offset'] ) {

		$RKI->Template::$b[] = '<nav class="revolver__similar-links">';

		$RKI->Template::$b[] = '<ul>';

		if( !empty( $n['similar']['prev']['route'] ) ) {

			$RKI->Template::$b[] = '<li class="revolver__similar-links--previous">';

			$RKI->Template::$b[] = '<a hreflang="'. $n['language']['hreflang'] .'" rel="prev" href="'. $n['similar']['prev']['route'] .'" title="'. $RKV->lang['previous'] .' :: '. $n['similar']['prev']['title'].'">';

			$RKI->Template::$b[] = '<span>'. $n['similar']['prev']['title'] .'</span>';

			$RKI->Template::$b[] = '</a>';

			$RKI->Template::$b[] = '</li>';

		}

		if( !empty( $n['similar']['next']['route'] ) ) { 

			$RKI->Template::$b[] = '<li class="revolver__similar-links--next">';

			$RKI->Template::$b[] = '<a hreflang="'. $n['language']['hreflang'] .'" rel="next" href="'. $n['similar']['next']['route'] .'" title="'. $RKV->lang['next'] .' :: '. $n['similar']['next']['title'] .'">';

			$RKI->Template::$b[] = '<span>'. $n['similar']['next']['title'] .'</span>';

			$RKI->Template::$b[] = '</a>';

			$RKI->Template::$b[] = '</li>';

		}

		$RKI->Template::$b[] = '</ul>';

		$RKI->Template::$b[] = '</nav>';

	}

?>
