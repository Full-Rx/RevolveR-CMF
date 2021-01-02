<?php

	$nodeLoaded = true;

	$goods = iterator_to_array(

			$RKI->Model::get( 'store_goods', [

				'criterion' => 'id::'. (int)PASS[ 3 ],

				'bound'		=> [

					1

				],

				'course'	=> 'backward',
				'sort' 		=> 'id'

			])

		)['model::store_goods'][0];

	$language = $RKI->Language::getLanguageData( $goods['country'] );

	$RKI->Template::$b[] = '<div itemtype="http://schema.org/Product" itemscope>';

	$RKI->Template::$b[] = '<article class="revolver__article article-id-'. $goods['id'] .' revolver__store-goods">';

	$RKI->Template::$b[] = '<header class="revolver__article-header">';

	$RKI->Template::$b[] = '<h2 itemprop="name">'. $goods['title'] .'</h2>';

	$RKI->Template::$b[] = '<div itemprop="offers" style="display:block; float:right;" itemtype="http://schema.org/Offer" itemscope>';

	$RKI->Template::$b[] = '<link itemprop="url" href="'. $RKV->host .'/store/goods/'. PASS[ 3 ] .'/" />';

	$RKI->Template::$b[] = '<meta itemprop="availability" content="https://schema.org/InStock" />'; // make iterable for items count

	$RKI->Template::$b[] = '<meta itemprop="itemCondition" content="https://schema.org/UsedCondition" />';

	$RKI->Template::$b[] = '<meta itemprop="priceCurrency" content="'. $language['currency_code'] .'" />';

	$RKI->Template::$b[] = '<meta itemprop="priceValidUntil" content="'. date('Y-m-d') .'" />';

	if( (int)$goods['rebate'] > 0 ) {

		$formula1 = ((int)$goods['price'] - ( ( (int)$goods['price'] / 100 ) * (int)$goods['rebate'] ) + (((int)$goods['price'] / 100) * (int)$goods['tax'] ));

		$RKI->Template::$b[] = '<dfn> <span itemprop="price">'. $formula1 .'</span> <b>'. $language['currency_symb'] .'</b></dfn>';

		$formula2 = ((int)$goods['price'] + (((int)$goods['price'] / 100) * (int)$goods['tax'] ) );

		$RKI->Template::$b[] = '<dfn> <s>'. $formula2 .' <b>'. $language['currency_symb'] .'</b></s></dfn>';

	}
	else {

		$formula = (int)$goods['price'] + (((int)$goods['price'] / 100) * (int)$goods['tax'] );

		$RKI->Template::$b[] = '<dfn> <span itemprop="price">'. $formula .'</span> <b>'. $language['currency_symb'] .'</b></dfn>';

	}

	$RKI->Template::$b[] = '</div>';

	$RKI->Template::$b[] = '</header>';

	$RKI->Template::$b[] = '<div itemprop="description" class="revolver__article-contents">';

	$RKI->Template::$b[] = '<div class="revolver__store-goods-cover">';

    $files = iterator_to_array(

        $RKI->Model::get('store_goods_files', [

          'criterion' => 'node::'. $goods['id'],
          'course'    => 'forward',
          'sort'      => 'id'

        ])

      )['model::store_goods_files'];

	if( $files ) {

		$RKI->Template::$b[] = '<figure>';

		foreach( $files as $f ) {   

			$RKI->Template::$b[] = '<img itemprop="image" src="/public/sfiles/'. $f['name'] .'" />';

		}

		$RKI->Template::$b[] = '</figure>';

	} 
	else {

		$RKI->Template::$b[] = '<figure>';

		$RKI->Template::$b[] = '<img src="/Interface/store-default.png" alt="Goods have no cover" />';

		$RKI->Template::$b[] = '</figure>';

	}

	if( (int)$goods['rebate'] > 0 ) {

		$RKI->Template::$b[] = '<div class="rebate-container">';
		$RKI->Template::$b[] = '<div class="guilloche"></div>';
		$RKI->Template::$b[] = '<span class="revolver__store_rebate-span">'. $RKV->lang['rebate'] .'</span>';
		$RKI->Template::$b[] = '<b class="revolver__store_rebate">'. $goods['rebate'] .'%</b>';
		$RKI->Template::$b[] = '</div>';

	}

	$RKI->Template::$b[] = '</div>';

	$RKI->Template::$b[] = '<div class="revolver__store-good-description">';

	$RKI->Template::$b[] = '<h2 itemprop="brand" itemtype="http://schema.org/Brand" itemscope>'. $RKV->lang['Goods vendor'] .': <span itemprop="name">'. $goods['vendor'] .'</span></h2>';

	$RKI->Template::$b[] = $RKI->HTML::Markup(

						htmlspecialchars_decode(

							html_entity_decode(

								$RKI->HTML::metaHash( $goods['content'] )

							)

						), ['lazy' => 1]);

	$RKI->Template::$b[] = '<ul>';


	if((bool)$goods['service']) {

		$RKI->Template::$b[] = '<li>'. $RKV->lang['Service'] .'</li>';

	} 
	else {

		$RKI->Template::$b[] = '<li><s>'. $RKV->lang['Service'] .'</s></li>';

	}

	if((bool)$goods['delivery']) {

		$RKI->Template::$b[] = '<li>'. $RKV->lang['Delivery'] .'</li>';

	} 
	else {

		$RKI->Template::$b[] = '<li><s>'. $RKV->lang['Delivery'] .'</s></li>';

	}

	if((bool)$goods['pickup']) {

		$RKI->Template::$b[] = '<li>'. $RKV->lang['Pickup'] .'</li>';

	} 
	else {

		$RKI->Template::$b[] = '<li><s>'. $RKV->lang['Pickup'] .'</s></li>';

	}

	$RKI->Template::$b[] = '</ul>';

	$RKI->Template::$b[] = '<h4>'. $RKV->lang['Tax'] .': <span>'. $goods['tax'] .'%</span></h4>';

	$RKI->Template::$b[] = '<h4>'. $RKV->lang['Quantity'] .': <span>'. $goods['quantity'] .'</span></h4>';

	$RKI->Template::$b[] = '</div>';

	$RKI->Template::$b[] = '</div>';

	$RKI->Template::$b[] = '<footer class="revolver__article-footer">';

	$goods_rating = iterator_to_array(

			$RKI->Model::get( 'goods_ratings', [

				'criterion' => 'node_id::'. (int)PASS[ 3 ],
				'course'	=> 'backward',
				'sort' 		=> 'id'

			])

		)['model::goods_ratings'];


	$grate = 0;

	if( $goods_rating ) {

		foreach( $goods_rating as $r => $rv ) {

			$grate += $rv['rate'];

		}

		$grate /= count( $goods_rating ); 

	}
	else {

		$goods_rating = [];

	}

	$tpe = 'store';

	$RKI->Template::$b[] = '<div class="revolver-rating" itemprop="aggregateRating" itemtype="http://schema.org/AggregateRating" itemscope>';
	$RKI->Template::$b[] = '<ul class="rated-'. floor($grate) .'" data-node="'. $goods['id'] .'" data-user="'. USER['id'] .'" data-type="'. $tpe .'">';
		$RKI->Template::$b[] = '<li data-rated="1">1</li>';
		$RKI->Template::$b[] = '<li data-rated="2">2</li>';
		$RKI->Template::$b[] = '<li data-rated="3">3</li>';
		$RKI->Template::$b[] = '<li data-rated="4">4</li>';
		$RKI->Template::$b[] = '<li data-rated="5">5</li>';
	$RKI->Template::$b[] = '</ul>';

	$RKI->Template::$b[] = '<span itemprop="ratingValue">'. floor( $grate ) .'</span> / <span>5</span> #<span class="closest" itemprop="reviewCount">'. count( $goods_rating ) .'</span>';
	$RKI->Template::$b[] = '</div>';

	$RKI->Template::$b[] = '<div class="socialize">';
	$RKI->Template::$b[] = '<ul data-type="'. $tpe .'">';
	$RKI->Template::$b[] = '<li class="fb" data-share="https://www.facebook.com/sharer.php?src='. $RKV->brand .'&u='. $RKV->host . $n['route'] .'&t='. $n['title'] .'"></li>';
	$RKI->Template::$b[] = '<li class="vk" data-share="https://vk.com/share.php?url='. $RKV->host . $n['route'] .'&title='. $n['title'] .'&description'. $n['description'] .'=&image='. $RKV->host .'/Interface/ArticlePostImage.png"></li>';
	$RKI->Template::$b[] = '<li class="tw" data-share="https://twitter.com/intent/tweet?url=&text='. $n['title'] .' :: '. $n['description'] .'"></li>';
	$RKI->Template::$b[] = '</ul>';
	$RKI->Template::$b[] = '</div>';

	$RKI->Template::$b[] = '<nav>';

	$RKI->Template::$b[] = '<ul>';

	$RKI->Template::$b[] = '<li class="revolver__in-basket-handler" data-goods="'. $goods['id'] .'">[ <span>'. $RKV->lang['In basket'] .'</span> ]</li>';

	if( $RKV->auth ) {

		if( in_array(ROLE, ['Admin', 'Writer']) ) {
	
			$RKI->Template::$b[] = '<li><a title="'. $n['title'] .' '. $RKV->lang['edit'] .'" href="/store/goods/'. (int)PASS[ 3 ] .'/edit/' .'">'. $RKV->lang['Edit'] .'</a></li>';
		
		}

	}

	$RKI->Template::$b[] = '</ul>';

	$RKI->Template::$b[] = '</nav>';

	$RKI->Template::$b[] = '</footer>';

	$RKI->Template::$b[] = '</article>';

?>
