<?php

	$RKI->Template::$b[] = '<ul><li><span>$:</span></li>';

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

			'EUR' => 0,
			'GBP' => 0,
			'JPY' => 0,
			'CHF' => 0,
			'AUD' => 0,
			'CAD' => 0,
			'CRC' => 0,
			'BRL' => 0,
			'TMT' => 0,
			'DOP' => 0,
			'COP' => 0,
			'ARS' => 0,
			'JMD' => 0,
			'TRY' => 0,
			'RUB' => 0,
			'TWD' => 0,
			'BYN' => 0,
			'HKD' => 0,
			'XCD' => 0,
			'CUP' => 0,
			'MXN' => 0,
			'EGP' => 0,
			'USD' => 0

		];

	}

	if( $rates ) {

		foreach( $rates as $r ) {

			$xfc = ( $r['value'] - $xcur[ $r['currency'] ] >= 0 ) ? 1 : 0;

			switch( $r['currency'] ) {

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
				case 'USD':

					$xtitle = 'title="↻ '. $r['name'] .' x1 USD"';

					break;

				default:

					$xtitle = 'title="↻ USD x1 '. $r['name'] .'"';

					break;

			}

			$RKI->Template::$b[] = '<li '. $xtitle .' class="'. strtolower( $r['currency'] ) .'"><span class="exchange-currency">'. $r['currency'] .'</span> <span class="exchange-value">'. $r['value'] .'</span>';
			$RKI->Template::$b[] = ' <span>'. ((bool)$xfc ? '⬆' : '⬇') .'</span></li>';

		}

	} 
	else {

		$cdata = curl_init('http://www.floatrates.com/daily/usd.xml');

		curl_setopt($cdata,  CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($cdata);

		curl_close($cdata);

		$moneys = new SimpleXMLElement($response);

		foreach( $moneys->item as $v ) {

			switch( $v->targetCurrency ) {

				case 'USD':
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

					$xfc = ( trim($r['value']) - (int)$xcur[ $r['currency'] ] ) >= 0 ? 1 : 0;

					$RKI->Template::$b[] = '<li title="↻ '. $v->targetName .' x1 USD" class="'. strtolower( $v->targetCurrency ) .'"><span class="exchange-currency">'. $v->targetCurrency .'</span> <span class="exchange-value">'. $v->exchangeRate .'</span>';

					$RKI->Template::$b[] = ' <span>'. ((bool)$xfc ? '⬆' : '⬇') .'</span></li>';

					$model::set('rates', [

						'currency'	=> $v->targetCurrency,
						'name'		=> $v->targetName,
						'value'		=> $v->exchangeRate,
						'date'		=> date('d/m/Y')

					]);
	
					break;

			}

		}

	}

	if( !$rates ) {

		foreach( $dbx::getCachesList( true ) as $fnumber => $fname ) {

			if( preg_match('/(^homepage|contents|categories|wiki|forum|store|services|blog)\w*.?/is', $fname) ) {

				unlink( TCache . $fname );

			}

		}

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
			$RKI->Template::$b[] = ' <span class="exchange-value">'. $cdata['ticker']['price'] .'</span>';
			$RKI->Template::$b[] = ' <span>'. ( (int)$cdata['ticker']['change'] > 0 ? '⬆' : '⬇') .' </span></li>';

			$model::set('rates', [

				'currency'	=> $cdata['ticker']['base'],
				'name'		=> $bname,
				'change'	=> $cdata['ticker']['change'],
				'value'		=> $cdata['ticker']['price'],
				'date'		=> date('d/m/Y')

			]);

		}

	}

	$RKI->Template::$b[] = '</ul>';

	$RKI->Template::print();


?>
