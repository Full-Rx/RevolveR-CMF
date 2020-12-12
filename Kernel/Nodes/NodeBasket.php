<?php

 /*
  * 
  * RevolveR Node Basket
  *
  * v.2.0.0.0
  *
  *
  *
  *
  *
  *			          ^
  *			         | |
  *			       @#####@
  *			     (###   ###)-.
  *			   .(###     ###) \
  *			  /  (###   ###)   )
  *			 (=-  .@#####@|_--"
  *			 /\    \_|l|_/ (\
  *			(=-\     |l|    /
  *			 \  \.___|l|___/
  *			 /\      |_|   /
  *			(=-\._________/\
  *			 \             /
  *			   \._________/
  *			     #  ----  #
  *			     #   __   #
  *			     \########/
  *
  *
  *
  * Developer: Maltsev Dmitry
  *
  * License: Apache 2.0
  *
  */

$contents = '';

if( !empty(SV['p']) ) {

	if( isset(SV['p']['revolver_customer_name']) ) {

		if( (bool)SV['p']['revolver_customer_name']['valid'] ) {

			$name = SV['p']['revolver_customer_name']['value'];

		}

	}

	if( isset(SV['p']['revolver_customer_last_name']) ) {

		if( (bool)SV['p']['revolver_customer_last_name']['valid'] ) {

			$last_name = SV['p']['revolver_customer_last_name']['value'];

		}

	}

	if( isset(SV['p']['revolver_customer_email']) ) {

		if( (bool)SV['p']['revolver_customer_email']['valid'] ) {

			$email = SV['p']['revolver_customer_email']['value'];

		}

	}

	if( isset(SV['p']['revolver_customer_telephone']) ) {

		if( (bool)SV['p']['revolver_customer_telephone']['valid'] ) {

			$telephone = SV['p']['revolver_customer_telephone']['value'];

		}

	}

	if( isset(SV['p']['revolver_customer_address']) ) {

		if( (bool)SV['p']['revolver_customer_address']['valid'] ) {

			$address = SV['p']['revolver_customer_address']['value'];

		}

	}

	if( isset(SV['p']['revolver_customer_comment']) ) {

		if( (bool)SV['p']['revolver_customer_comment']['valid'] ) {

			$comment = SV['p']['revolver_customer_comment']['value'];

		}

	}

	if( isset(SV['p']['revolver_captcha']) ) {

		if( (bool)SV['p']['revolver_captcha']['valid'] ) {

			if( $RKI->Captcha::verify(SV['p']['revolver_captcha']['value']) ) {

				define('form_pass', 'pass');

			}

		}

	}

	if( defined('form_pass') ) {

		if( form_pass === 'pass' && (bool)SV['p']['identity']['validity'] ) {

			setcookie('__RevolveR_goods_in_basket', null, -1, '/');

			unset($_COOKIE['__RevolveR_goods_in_basket']);

			$RKI->Model::set('store_orders', [

				'customer_name'			=> $name,
				'customer_last_name'	=> $last_name,
				'customer_email'		=> $email,
				'customer_telephone'	=> $telephone,
				'customer_address'		=> $address,
				'customer_comment'		=> $comment

			]);

			$RKI->Notify::set('status', 'Order processed');

		}

	}

}

$form_parameters = [

	// main parameters
	'id'		=> 'basket-proceed-form',
	'class'		=> 'revolver__basket-proceed-form revolver__new-fetch',
	'method'	=> 'post',
	'action'	=> $RNV->request,
	'encrypt'	=> true,
	'captcha'	=> true,
	'submit'	=> 'Submit',	

	// included fieldsets
	'fieldsets' => [

		// fieldset contents parameters
		'fieldset_1' => [

			'title' => 'Customer info',
			
			// wrap fields into label
			'labels' => [

				'label_1' => [

					'title'  => 'Сustomer name',
					'access' => 'buy',
					'auth'	 => 'all',

					'fields' => [

						0 => [

							'type'			=> 'input:text',
							'name'			=> 'revolver_customer_name',
							'placeholder'	=> 'Сustomer name',
							'required'		=> true

						],

					],

				],

				'label_2' => [

					'title'  => 'Сustomer last name',
					'access' => 'buy',
					'auth'	 => 'all',

					'fields' => [

						0 => [

							'type'			=> 'input:text',
							'name'			=> 'revolver_customer_last_name',
							'placeholder'	=> 'Сustomer last name',
							'required'		=> true

						],

					],

				],

				'label_3' => [

					'title'  => 'Сustomer email',
					'access' => 'buy',
					'auth'	 => 'all',

					'fields' => [

						0 => [

							'type'			=> 'input:email',
							'name'			=> 'revolver_customer_email',
							'placeholder'	=> 'Сustomer email',
							'required'		=> true

						],

					],

				],

				'label_4' => [

					'title'  => 'Сustomer telephone',
					'access' => 'buy',
					'auth'	 => 'all',

					'fields' => [

						0 => [

							'type'			=> 'input:tel',
							'name'			=> 'revolver_customer_telephone',
							'placeholder'	=> 'Сustomer telephone',
							'required'		=> true

						],

					],

				],

				'label_5' => [

					'title'  => 'Сustomer address',
					'access' => 'buy',
					'auth'	 => 'all',

					'fields' => [

						0 => [

							'type'			=> 'input:text',
							'name'			=> 'revolver_customer_address',
							'placeholder'	=> 'Сustomer address',
							'required'		=> true

						],

					],

				],

				'label_6' => [

					'title'  => 'Сustomer comment',
					'access' => 'buy',
					'auth'	 => 'all',

					'fields' => [

						0 => [

							'type'			=> 'textarea:text',
							'name'			=> 'revolver_customer_comment',
							'placeholder'	=> 'Сustomer comment',
							'rows'			=> 20,
							'required'		=> true

						],

					],

				],

			],

		],

	],

];

if( isset( SV['c']['goods_in_basket'] ) && isset( $_COOKIE['__RevolveR_goods_in_basket'] ) ) {

	$total_price = 0;

	$goods_count = 0;

	$total_rebate = 0;

	$total_tax = 0;

	$contents .= '<h2>'. $RNV->lang['Goods in basket'] .':</h2>';

	foreach( explode('|', SV['c']['goods_in_basket']) as $g ) {

		if( (bool)strlen($g) ) {

			$gds = iterator_to_array(

				$RKI->Model::get('store_goods', [

				'criterion' => 'id::'. (int)$g,
					'course'	=> 'forward',
					'sort'		=> 'id'

				])

			)['model::store_goods'];

			if( $gds ) {

				$goods_count++;

				$gds = $gds[0];

				$language = $RKI->Language::getLanguageData( $gds['country'] );

				$price = ((int)$gds['price'] - ( ( (int)$gds['price'] / 100 ) * (int)$gds['rebate'] ) + (((int)$gds['price'] / 100) * (int)$gds['tax'] ));

				$total_price += $price;

				$total_rebate += ( (int)$gds['price'] / 100 ) * (int)$gds['rebate'];

				$total_tax += ((int)$gds['price'] / 100) * (int)$gds['tax'];

				$contents .= '<dl class="revolver__basket-items">';

				$contents .= '<dt>'. $gds['title'] .' <a href="/store/goods/'. $gds['id'] .'">⇒</a> <span style="float:right">:: '. $gds['vendor'] .'</span></dt>';

				$contents .= '<dd class="revolver__basket-goods_cover revolver__store-goods-cover">';

				$files = iterator_to_array(

					$RKI->Model::get('store_goods_files', [

						'criterion' => 'node::'. $gds['id'],
						'course'    => 'forward',
						'sort'      => 'id'

					])

				)['model::store_goods_files'];

				if( $files ) {

					$contents .= '<figure>';

					foreach( $files as $f ) {   

						$contents .= '<img itemprop="image" src="/public/sfiles/'. $f['name'] .'" />';

					}

					$contents .= '</figure>';

				} 
				else {

					$contents .= '<figure>';

					$contents .= '<img src="/Interface/store-default.png" alt="Goods have no cover" />';

					$contents .= '</figure>';

				}

				$contents .= '</dd>';

				$contents .= '<dd class="revolver__basket-goods_summary">';
				$contents .= '<p>'. $gds['description'] .'</p>';
				
				$contents .= '<ul>';
				$contents .= '<li>'. $RNV->lang['Rebate'] .': <dfn>'. (int)$gds['rebate'] .'%</dfn></li>';
				$contents .= '<li>'. $RNV->lang['Tax'] .': <dfn>'. (int)$gds['tax'] .'%</dfn></li>';
				$contents .= '<li>'. $RNV->lang['Price'] .': <dfn>'. $price .'<b>'. $language['currency_symb'] .'</b></dfn></li>';
				$contents .= '</ul>';
				
				$contents .= '</dd>';

				$contents .= '</dl>';

			}

		}

	}

	$contents .= '<h2>'. $RNV->lang['Order parameters'] .':</h2>';

	$contents .= '<ul class="revolver__order-summary">';
	$contents .= '<li>'. $RNV->lang['Total goods'] .': <span><b>'. $goods_count .'</b></span></li>';
	$contents .= '<li>'. $RNV->lang['Total price'] .': <span>'. $total_price .'<b>'. $language['currency_symb'] .'</b></span></li>';
	$contents .= '<li>'. $RNV->lang['Total rebate'] .': <span>'. $total_rebate .'<b>'. $language['currency_symb'] .'</b></span></li>';
	$contents .= '<li>'. $RNV->lang['Total tax'] .': <span>'. $total_tax .'<b>'. $language['currency_symb'] .'</b></span></li>';
	$contents .= '</ul>';

	$contents .= '<h2>'. $RNV->lang['Order processing'] .':</h2>';
	$contents .= $RKI->HTMLForm::build( $form_parameters );


} 
else {

	$contents .= '<p>'. $RNV->lang['Your basket is empty'] .'.</p>';

}


$node_data[] = [

	'title'		=> $RNV->lang['Basket'],
	'id'		=> 'basket',
	'route'		=> '/basket/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'published' => 1

];

?>
