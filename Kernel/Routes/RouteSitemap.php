<?php

 /*
  * 
  * Sitemap Route :: Generate sitemap
  *
  * v.2.0.0.0
  *
  *
  *
  *
  *               ^
  *              | |
  *            @#####@
  *          (###   ###)-.
  *        .(###     ###) \
  *       /  (###   ###)   )
  *      (=-  .@#####@|_--"
  *      /\    \_|l|_/ (\
  *     (=-\     |l|    /
  *      \  \.___|l|___/
  *      /\      |_|   /
  *     (=-\._________/\
  *      \             /
  *        \._________/
  *          #  ----  #
  *          #   __   #
  *          \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

$sitemap = '<?xml version="1.0" encoding="UTF-8" ?>'. "\n";

$sitemap .= '<?xml-stylesheet type="text/xsl" href="'. $RNV->host .'/Interface/sitemap.xsl" ?>'. "\n";

$sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'. "\n";

foreach( iterator_to_array(

		$RKI->Model::get( 'nodes', [

			'criterion' => 'id::*',

			'bound'		=> [

				0,   // limit

			],

			'course'	=> 'backward', // backward
			'sort' 		=> 'time',

		]),

	)['model::nodes'] as $node => $n) {

	$sitemap .= ' <url>'. "\n";

		$sitemap .= '<loc>'. $RNV->host . $n['route'] .'</loc>'. "\n";
		$sitemap .= '<lastmod>'. $RKI->Calendar::formatTime($n['time']) .'</lastmod>'. "\n";

		$sitemap .= '<changefreq>monthly</changefreq>'. "\n";
		$sitemap .= '<priority>.9</priority>'. "\n";

	$sitemap .= ' </url>'. "\n\n";

}

foreach( iterator_to_array(

		$RKI->Model::get( 'blog_nodes', [

			'criterion' => 'id::*',

			'bound'		=> [

				0,   // limit

			],

			'course'	=> 'backward', // backward
			'sort' 		=> 'time',

		]),

	)['model::blog_nodes'] as $bnode => $n) {

	$sitemap .= ' <url>'. "\n";

		$date = explode('-', 

					str_replace('.', '-', explode(' ', $n['time'])[0])
		);

		$sitemap .= '<loc>'. $RNV->host . $n['route'] .'</loc>'. "\n";
		$sitemap .= '<lastmod>'. $RKI->Calendar::formatTime($n['time']) .'</lastmod>'. "\n";

		$sitemap .= '<changefreq>monthly</changefreq>'. "\n";
		$sitemap .= '<priority>.7</priority>'. "\n";

	$sitemap .= ' </url>'. "\n\n";

}

foreach( iterator_to_array(

		$RKI->Model::get( 'wiki_nodes', [

			'criterion' => 'id::*',

			'bound'		=> [

				0,   // limit

			],

			'course'	=> 'backward', // backward
			'sort' 		=> 'time',

		]),

	)['model::wiki_nodes'] as $node => $n) {

	$sitemap .= ' <url>'. "\n";

		$sitemap .= '<loc>'. $RNV->host . $n['route'] .'</loc>'. "\n";
		$sitemap .= '<lastmod>'. $RKI->Calendar::formatTime($n['time']) .'</lastmod>'. "\n";

		$sitemap .= '<changefreq>monthly</changefreq>'. "\n";
		$sitemap .= '<priority>.8</priority>'. "\n";

	$sitemap .= ' </url>'. "\n\n";

}

foreach( iterator_to_array(

		$RKI->Model::get( 'store_goods', [

			'criterion' => 'id::*',

			'bound'		=> [

				0,   // limit

			],

			'course'	=> 'backward', // backward
			'sort' 		=> 'id',

		]),

	)['model::store_goods'] as $node => $n) {

	$sitemap .= ' <url>'. "\n";

		$sitemap .= '<loc>'. $RNV->host . '/store/goods/'. $n['id'] .'/</loc>'. "\n";
		$sitemap .= '<lastmod>'. date('Y-m-d') .'</lastmod>'. "\n";

		$sitemap .= '<changefreq>monthly</changefreq>'. "\n";
		$sitemap .= '<priority>1</priority>'. "\n";

	$sitemap .= ' </url>'. "\n\n";

}


$sitemap .= '</urlset>';

print $sitemap;

define('serviceOutput', [

  'ctype'     => 'application/xml',
  'route'     => '/search/'

]);

?>
