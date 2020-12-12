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

	$render_node .= '<article itemscope itemtype="http://schema.org/Article"'. $xlang .' class="revolver__article article-id-'. $n['id'] .' '. $class .'">';

	$render_node .= '<header class="revolver__article-header">';

	if( $n['teaser'] ) {

		$render_node .= '<h2 itemprop="headline"><a itemprop="url"'. $xalang .' href="'. $n['route'] .'" rel="bookmark">'. $n['title'] .'</a></h2>';

	}
	else {

		$render_node .= '<h2 itemprop="headline name">'. $n['title'] .'</h2>';

	}

	if( $n['time'] ) {

		$render_node .= '<time itemprop="datePublished dateModified" datetime="'. $RKI->Calendar::formatTime( $n['time'] ) .'">'. $n['time'] .'</time>';

	}

	$render_node .= '</header>';

	if( RQST === '/' ) {

		$render_node .= '<div itemprop="articleBody mainEntityOfPage" class="revolver__article-contents">'. $RKI->HTML::Markup( $RKI->HTML::metaHash($n['contents']), [ 'length' => 2000, 'lazy' => 1 ] ) .'</div>';

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

				$render_node .= '<figure itemprop="creator" itemscope itemtype="https://schema.org/Person" class="revolver__comments-avatar">';

				if( $topic_author['avatar'] === 'default') {

					$src = '/public/avatars/default.png';

				}
				else {

					$src = $topic_author['avatar'];

				}

				$render_node .= '<img itemprop="image" src="'. $src .'" alt="'. $topic_author['nickname'] .'" />';

				$render_node .= '<figcaption itemprop="name">'. $topic_author['nickname'] .'</figcaption>';

				$render_node .= '</figure>';

				$render_node .= '<div itemprop="articleBody mainEntityOfPage" class="revolver__article-contents" data-node="'. $n['id'] .'" data-type="forum-node" data-user="'. $n['author'] .'">';

				$render_node .= $RKI->HTML::Markup( $RKI->HTML::metaHash($n['contents']), [ 'lazy' => 1 ] );

				$render_node .= '</div>';

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

				$render_node .= '<div itemprop="articleBody mainEntityOfPage" class="revolver__article-contents"'. $quick_edit_attr . $quick_edit_data .'>'. $n['contents'] .'</div>';

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

			$render_node .= '<div itemprop="articleBody mainEntityOfPage" class="revolver__article-contents"'. $quick_edit_attr . $quick_edit_data .'>';

			$render_node .= $RKI->HTML::Markup( $RKI->HTML::metaHash($n['contents']), [ 'lazy' => 1 ] );

			$render_node .='</div>';

		}

	}


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

	if( $n['footer'] ) {

		$render_node .= '<nav>';

		$render_node .= '<ul>';

		if( $n['quedit'] ) {

			$render_node .= '<li class="revolver__quick-edit-handler" title="'. $n['title'] .' '. $RKV->lang['qedit'] .'">[ '. $RKV->lang['QEdit'] .' ]</li>';

		}

		if( $n['editor'] ) {

			$render_node .= '<li><a title="'. $n['title'] .' '. $RKV->lang['edit'] .'" href="'. $n['route'] .'edit/' .'">'. $RKV->lang['Edit'] .'</a></li>';

		}
		else {

			if( PASS[ 1 ] !== 'blog' && PASS[ 1 ] !== 'wiki' ) {

				$render_node .= '<li><a itemprop="url"'. $xalang .' title="'. $n['title'] .'" href="'. $n['route'] .'">'. $RKV->lang['Read More'] .' &rArr;</a></li>';

			}

		}

		$render_node .= '</ul></nav>';

	}

	$render_node .= '</footer>';

	$render_node .= '</article>';

	// Similar contents links next \ prev 
	if( isset($n['similar']) && $RKV->request !== '/' && !(bool)pagination['offset'] ) {

		$render_node .= '<nav class="revolver__similar-links">';

		$render_node .= '<ul>';

		if( !empty( $n['similar']['prev']['route'] ) ) {

			$render_node .= '<li class="revolver__similar-links--previous">';

			$render_node .= '<a hreflang="'. $n['language']['hreflang'] .'" rel="prev" href="'. $n['similar']['prev']['route'] .'" title="'. $RKV->lang['previous'] .' :: '. $n['similar']['prev']['title'].'">';

			$render_node .= '<span>'. $n['similar']['prev']['title'] .'</span>';

			$render_node .= '</a>';

			$render_node .= '</li>';

		}

		if( !empty( $n['similar']['next']['route'] ) ) { 

			$render_node .= '<li class="revolver__similar-links--next">';

			$render_node .= '<a hreflang="'. $n['language']['hreflang'] .'" rel="next" href="'. $n['similar']['next']['route'] .'" title="'. $RKV->lang['next'] .' :: '. $n['similar']['next']['title'] .'">';

			$render_node .= '<span>'. $n['similar']['next']['title'] .'</span>';

			$render_node .= '</a>';

			$render_node .= '</li>';

		}

		$render_node .= '</ul>';

		$render_node .= '</nav>';

	}

?>
