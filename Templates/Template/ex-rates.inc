<?php

	$rates = iterator_to_array(

		$RKI->Model::get( 'rates', [

			'criterion' => 'date::'. date('d/m/Y'),

			'bound'		=> [

				2

			],

			'course'	=> 'backward',
			'sort' 		=> 'id'

		])

	)['model::rates'];

	$xrates = iterator_to_array(

		$RKI->Model::get( 'rates', [

			'criterion' => 'date::'. date('d/m/Y', strtotime('previous day')),

			'bound'		=> [

				2

			],

			'course'	=> 'backward',
			'sort' 		=> 'id'

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

	$vdata = '';

	if( $rates ) {

		foreach( $rates as $r ) {

			$xfc = ( $r['value'] - $xcur[ $r['currency'] ] >= 0 ) ? 1 : 0;

			$vdata .= '<div class="'. strtolower( $r['currency'] ) .'"><span class="exchange-currency">'. $r['currency'] .'</span> <span class="exchange-value">'. $r['value'] .'</span> <span>'. ((bool)$xfc ? '⬆' : '⬇') .' RUB</span></div>';

		}

	} 
	else {

		$cdata = curl_init('http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d/m/Y'));

		curl_setopt($cdata,  CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($cdata);

		curl_close($cdata);

		$cdata = simplexml_load_string($response);

		if( !empty($cdata) ) {

			foreach( $cdata->Valute as $v ) {

				if( $v['ID'] == 'R01235' || $v['ID'] == 'R01239' ) {

					$val = round(str_replace(',', '.', $v->Value), 2);

					$xfc = ( $val - $xcur[ $v->CharCode ] >= 0 ) ? 1 : 0; 

					$vdata .= '<div class="'. strtolower( $v->CharCode ) .'"><span class="exchange-currency">'. $v->CharCode .'</span> <span class="exchange-value">'. $val .'</span> <span>'. ((bool)$xfc ? '⬆' : '⬇') .' RUB</span></div>';

					$model::set('rates', [

						'currency'	=> $v->CharCode,
						'value'		=> $val,
						'date'		=> date('d/m/Y'),

					]);

				}

			}

		}

	}

	print $vdata;

?>