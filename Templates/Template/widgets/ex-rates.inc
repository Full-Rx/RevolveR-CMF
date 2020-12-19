<?php

	$RKI->Template::$b[] = '<ul>';

	$rates = iterator_to_array(

		$RKI->Model::get( 'rates', [

			'criterion' => 'date::'. date('d/m/Y'),

			'bound'		=> [

				0

			],

			'course'	=> 'forward',
			'sort' 		=> 'currency'

		])

	)['model::rates'];

	$xrates = iterator_to_array(

		$RKI->Model::get( 'rates', [

			'criterion' => 'date::'. date('d/m/Y', strtotime('previous day')),

			'bound'		=> [

				0

			],

			'course'	=> 'forward',
			'sort' 		=> 'currency'

		])

	)['model::rates'];

	if( $xrates ) {

		$xcur = [];

		foreach( $xrates as $xr ) {

			$xcur[ $xr['currency'] ] = $xr['value'];

		}

	} 
	else {

		$xcur = [

			'USD' => 0, 
			'EUR' => 0

		];

	}

	if( $rates ) {

		foreach( $rates as $r ) {

			$xfc = ( $r['value'] - $xcur[ $r['currency'] ] >= 0 ) ? 1 : 0;

			$RKI->Template::$b[] = '<li title="↻ USD x1 '. $r['name'] .'" class="'. strtolower( $r['currency'] ) .'"><span class="exchange-currency">'. $r['currency'] .'</span> <span class="exchange-value">'. $r['value'] .'</span>';
			$RKI->Template::$b[] = ' <span>'. ((bool)$xfc ? '⬆' : '⬇') .'</span></li>';

		}

	} 
	else {

		$cdata = curl_init('http://www.floatrates.com/daily/usd.xml');

		curl_setopt($cdata,  CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($cdata);

		curl_close($cdata);

		$cdata = @simplexml_load_string($response);

		if( !empty($cdata) ) {

			foreach( $cdata->item as $v ) {

				switch( $v->targetCurrency ) {

					case 'EUR':
					case 'GBP':
					case 'JPY':
					case 'CHF':
					case 'AUD':
					case 'CAD':
					case 'CRC':
					case 'BRL':
					case 'TMT':
					case 'DOP':
					case 'COP':
					case 'ARS':
					case 'JMD':
					case 'TRY':
					case 'RUB':
					case 'TWD':
					case 'BYN':
					case 'HKD':
					case 'XCD':
					case 'CUP':
					case 'MXN':
					case 'EGP':

						$val = round($v->exchangeRate, 3);

						$xfc = ( $val - $xcur[ $v->targetCurrency ] >= 0 ) ? 1 : 0; 

						$RKI->Template::$b[] = '<li title="↻ USD x1 '. $v->targetName .'" class="'. strtolower( $v->targetCurrency ) .'"><span class="exchange-currency">'. $v->targetCurrency .'</span> <span class="exchange-value">'. $val .'</span>';
						$RKI->Template::$b[] = ' <span>'. ((bool)$xfc ? '⬆' : '⬇') .' </span></li>';

						$model::set('rates', [

							'currency'	=> $v->targetCurrency,
							'name'		=> $v->targetName,
							'value'		=> $val,
							'date'		=> date('d/m/Y'),

						]);

						break;

				}

			}

		}

	}

	if( !$rates ) {

		$cbase = 'https://api.cryptonator.com/api/ticker/';

		$cstack = ['btc-usd', 'eth-usd', 'xmr-usd', 'etc-usd', 'ltc-usd', 'zec-usd'];

		foreach( $cstack as $s ) {

			$cdata = curl_init($cbase . $s);

			curl_setopt($cdata,  CURLOPT_RETURNTRANSFER, TRUE);

			$response = curl_exec($cdata);

			curl_close($cdata);

			$cdata = json_decode($response, true);

			switch( $cdata['ticker']['base'] ) {

				case 'BTC':

					$bname = 'Bitcoin';

					break;

				case 'ETH':

					$bname = 'Ethereum';

					break;

				case 'XMR':

					$bname = 'Monero';

					break;

				case 'ETC':

					$bname = 'Ethereum Classic';

					break;

				case 'LTC':

					$bname = 'Litecoin';

					break;

				case 'ZEC':

					$bname = 'zCash';

					break;

			}

			$RKI->Template::$b[] = '<li title="↻ USD x1 '. $bname .'" class="'. strtolower( $cdata['ticker']['base'] ) .'"><span class="exchange-currency">'. $cdata['ticker']['base'] .'</span>';
			$RKI->Template::$b[] = ' <span class="exchange-value">'. round($cdata['ticker']['price'], 3) .'</span>';
			$RKI->Template::$b[] = ' <span>'. ( (int)$cdata['ticker']['change'] > 0 ? '⬆' : '⬇') .' </span></li>';

			$model::set('rates', [

				'currency'	=> $cdata['ticker']['base'],
				'name'		=> $bname,
				'change'	=> round($cdata['ticker']['change'], 3),
				'value'		=> round($cdata['ticker']['price'], 3),
				'date'		=> date('d/m/Y'),

			]);

		}

	}

	$RKI->Template::$b[] = '</ul>';

	print implode("\n", $RKI->Template::$b);

	$RKI->Template::$b = [];

?>
