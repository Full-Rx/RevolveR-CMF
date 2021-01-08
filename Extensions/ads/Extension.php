<?php

/**
  *
  * Extension :: Feedback
  *
  * v.2.0.0.0
  *
  */

// Feedback routing
$extensionsRoutes[ etranslations[ $ipl ]['Ads'] ] = [

	'title' => etranslations[ $ipl ]['Ads'],
	'descr'	=> etranslations[ $ipl ]['Ads view'],

	'param_check'	=> [

		'installed'	=> 1,
		'menu'		=> 1

	],

	'route'	=> '/ads/',
	'node'	=> '#ads',
	'type'	=> 'node',
	'id'	=> 'ads',
	'ext'	=> 1

];

$extensionsRoutes[ etranslations[ $ipl ]['Add ads category'] ] = [

	'title' => etranslations[ $ipl ]['Add ads category'],
	'descr'	=> etranslations[ $ipl ]['Add ads category'],

	'param_check'	=> [

		'menu'		=> 0

	],

	'route'	=> '/ads/addcat/',
	'node'	=> '#addcat',
	'type'	=> 'node',
	'id'	=> 'addcat',
	'ext'	=> 1

];

$extensionsRoutes[ etranslations[ $ipl ]['Edit ads category'] ] = [

	'title' => etranslations[ $ipl ]['Edit ads category'],
	'descr'	=> etranslations[ $ipl ]['Edit ads category'],

	'param_check'	=> [

		'menu'		=> 0

	],

	'route'	=> '/ads/editcat/',
	'node'	=> '#editcat',
	'type'	=> 'node',
	'id'	=> 'editcat',
	'ext'	=> 1

];

$extensionsRoutes[ etranslations[ $ipl ]['Add ads'] ] = [

	'title' => etranslations[ $ipl ]['Add ads'],
	'descr'	=> etranslations[ $ipl ]['Add ads'],

	'param_check'	=> [

		'menu'		=> 0

	],

	'route'	=> '/ads/additem/',
	'node'	=> '#additem',
	'type'	=> 'node',
	'id'	=> 'additem',
	'ext'	=> 1

];

if( PASS[ 1 ] === 'ads' && PASS[ 2 ] === 'view' ) {

	$title = etranslations[ $ipl ]['View ads'];
	$descr = etranslations[ $ipl ]['View ads'];

	if( isset( SV['g']['id'] ) ) {

		$c = iterator_to_array(

				$model::get('ads_categories', [

					'criterion' => 'id::'. (int)SV['g']['id']['value'],
					'course'	=> 'forward',
					'sort'		=> 'id'

				])

			)['model::ads_categories'];

		if( $c) {

			$title = $c[ 0 ]['title'];
			$descr = $c[ 0 ]['description'];

		}

	}

$extensionsRoutes[ etranslations[ $ipl ]['View ads'] ] = [

	'title' => $title,
	'descr'	=> $descr,

	'param_check'	=> [

		'menu'		=> 0

	],

	'route'	=> '/ads/view/',
	'node'	=> '#adsview',
	'type'	=> 'node',
	'id'	=> 'adsview',
	'ext'	=> 1

];

}

$extensionsRoutes[ etranslations[ $ipl ]['Delete ads'] ] = [

	'title' => etranslations[ $ipl ]['Delete ads'],
	'descr'	=> etranslations[ $ipl ]['delete ads'],

	'param_check'	=> [

		'menu'		=> 0

	],

	'route'	=> '/ads/delete/',
	'node'	=> '#adsdelete',
	'type'	=> 'node',
	'id'	=> 'adsdelete',
	'ext'	=> 1

];

?>
