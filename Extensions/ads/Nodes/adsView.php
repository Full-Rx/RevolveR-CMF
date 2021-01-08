<?php

/** 
  * 
  * RevolveR Ads Extension ads
  *
  * v.2.0.1.3
  *
  */

$contents  = '';

if( INSTALLED ) {

	if( isset( SV['g']['id'] ) ) {

		$id = SV['g']['id']['value'];

	} 
	else {

		header('Location: /ads/');

	}

	$categories = iterator_to_array(

			$RKI->Model::get('ads_categories', [

				'criterion' => 'id::'. $id,
				'course'	=> 'forward',
				'sort'		=> 'id'

			])

		)['model::ads_categories'];

	if( $categories ) {

		$cat = $categories[ 0 ];

		$contents .= '<dl class="revolver__categories">';

		if( ROLE !== 'none' ) {

			if( ROLE === 'Admin' || ROLE === 'Writer' ) {

				$contents .= '<dt>&#8226; '. $cat['title'] .' &#8226; <span style="float:right">[ <a href="/ads/editcat/?id='. $cat['id'] .'">'. etranslations[ $ipl ]['Edit'] .'</a> ]</span></dt>';

			}

		}
		else {

			$contents .= '<dt>&#8226; '. $cat['title'] .'</dt>';

		}

		$title = $cat['title'];

		$contents .= '<dd><p>'. $cat['description'] .'</p>';

		$contents .= '<div style="text-align:center">...[ <a href="/ads/additem/">'. etranslations[ $ipl ]['Add ads'] .'</a> ]...</div>';

		$items = iterator_to_array(

				$RKI->Model::get('ads_items', [

					'criterion' => 'ads_category::'. $cat['id'],
					'course'	=> 'backward',
					'sort'		=> 'id'

				])

			)['model::ads_items'];

		if( $items ) {

			$contents .= '<ul>';

			foreach( $items as $i ) {

				$language = $RKI->Language::getLanguageData( $i['ads_country'] );

				$files = iterator_to_array(

						$RKI->Model::get('ads_files', [

							'criterion' => 'ads_hash::'. $i['ads_hash'],
							'course'	=> 'forward',
							'sort'		=> 'id'

						])

					)['model::ads_files'];

				$contents .= '<li>';
				$contents .= '<h2>'. $i['ads_title'] .' <span style="float:right">'. $i['ads_time'] .'</span></h2>';

				if( ROLE !== 'none' ) {

					if( ROLE === 'Admin' || ROLE === 'Writer' ) {

						$contents .= '<div style="text-align:center">... [ <a href="/ads/edit/?id='. $i['id'] .'">'. etranslations[ $ipl ]['Delete'] .'</a> ] ...</div>';

					}

				}

				$contents .= '<div style="display:table">';
				$contents .= '<figure style="display:table-cell; vertical-align: middle; width: 10%">';

				if( $files ) {

					foreach( $files as $f ) {   

						$contents .= '<img itemprop="image" src="/Extensions/ads/uploads/'. $f['file'] .'" />';

					}

				}
				else {


					$contents .= '<img src="/Extensions/ads/uploads/ads.png" alt="Ads have no cover" />';

				}

				$contents .= '</figure>';

				$contents .= '<div style="display:table-cell; width: 90%">';
				$contents .= '<p style="text-shadow: 0 0 .1vw var(--article-header-text-color);">'. $i['ads_description'] .'</p>';

				$contents .= $RKI->HTML::Markup(

					htmlspecialchars_decode(

						html_entity_decode(

							$RKI->HTML::metaHash( $i['ads_content'] )

						)

					), ['lazy' => 1]);

				$contents .= '<p style="text-align:center; text-shadow: 0 0 .1vw var(--article-header-text-color);">';
				$contents .= '<span style="color:#b00000">'. $i['sender_email'] .'</span>; ';
				$contents .= '<span style="color: #30349e;">'. $i['sender_phone'] .'</span>; ';
				$contents .= '<span style="color: #675716;">'. $i['sender_name'] .'</span> ';
				$contents .= '<span style="float:right; color: #efefef">'. $i['ads_price'] .'<em>'. $language['currency_symb'] .'</em></span>';
				$contents .= '</p>';
				$contents .= '</div>';
				$contents .= '</div>';
				$contents .= '</li>';

			}

			$contents .= '</ul>';

		}

		$contents .= '</dd>';

		$contents .= '</dl>';

	}

}

$node_data[] = [

	'title'		=> $title,
	'id'		=> 'ads',
	'route'		=> '/ads/view/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'time'		=> null,
	'published'	=> 1

];

?>
