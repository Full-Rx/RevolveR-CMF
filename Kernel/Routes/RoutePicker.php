<?php

 /* 
  * 
  * RevolveR Route Picker
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

if( isset(SV['g']['host']) && in_array(ROLE, ['Admin', 'Writer']) ) {

	$url = filter_var('https://'. SV['g']['host']['value'], FILTER_VALIDATE_URL);

	$indexed = [];

	function getRobotsTxt( string $url ): ?iterable {

		// location of robots.txt file, only pay attention to it if the server says it exists
		$hrobots = curl_init($url .'/robots.txt');

		curl_setopt($hrobots,  CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($hrobots);

		$httpCode = curl_getinfo($hrobots, CURLINFO_HTTP_CODE);

		if( (int)$httpCode === 200 ) {

			$robots = explode("\n", $response);

		}
		else {

			$robots = null;

		}

		curl_close($hrobots);

		return array_filter(

			preg_replace([

					'/#.*/m',  // 1 :: trim single lines comments excepts quoted strings
					'!\s+!',   // 2 :: trim multiple spaces
					'/\t/'     // 3 :: trim tabulations

				], 

				[

					'', 
					' ', 
					''

				], $robots

			)

		);

	}

	function indexingAllowed( ?iterable $robots, string $xurl ): ?bool {

		if( !$robots ) {

			return null;

		}

		// Parse url to retrieve host and path
		$parsed = parse_url($xurl);

		$rules = [];

		$ruleApplies = null;

		foreach( $robots as $line ) {

			// Following rules only apply if User-agent matches $useragent or '*'
			if( preg_match('/^\s*User-agent: (.*)/i', $line, $match) ) {

				$ruleApplies = preg_match('/(\*)/i', $match[ 1 ]);

				continue;

			}

			if( $ruleApplies ) {

				[$type, $rule] = explode(':', $line, 2);

				$type = trim(strtolower($type));

				// add rules that apply to array for testing
				$rules[] = [

					'type' => $type, 'match' => preg_quote(trim($rule), '/')

				];

			}

		}

		$isAllowed = true;

		$currentStrength = 0;

		foreach( $rules as $rule ) {

			// Check if page hits on a rule
			if( preg_match('/^'. $rule['match'] .'/', $parsed['path']) ) {

				// Prefer longer (more specific) rules and Allow trumps Disallow if rules same length
				$strength = strlen($rule['match']);

				if( $currentStrength < $strength ) {

					$currentStrength = $strength;

					$isAllowed = $rule['type'] === 'allow' ? true : null;

				} 
				else if( $currentStrength === $strength && $rule['type'] === 'allow' ) {

					$currentStrength = $strength;

					$isAllowed = true;

				}

			}

		}

		return $isAllowed;

	}


	$robotstxt = getRobotsTxt($url);

	function getUri( string $url ): iterable {

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.183 Safari/537.36 Picker/1.0');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FILETIME, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);

		$data = curl_exec($ch);

		if( !curl_errno($ch) ) {

			$i = curl_getinfo($ch);

			$ssl_pass = (int)$i['ssl_verify_result']; 

			if( !(bool)$ssl_pass ) {

				$ok = true;

			}

			switch( $i['http_code'] ) {

				case 200:
				case 301:
				case 302:

					$ok = true;

					break;

				default:

					$ok = null;

					break;

			}

			switch( explode(';', $i['content_type'])[0] ) {

				case 'application/xhtml+xml':
				case 'text/html':

					$ok = true;

					break;

				default:

					$ok = null;

					break;

			}

			[$hdr, $body] = explode("\r\n\r\n", $data, 2);

			$headers = explode("\r\n", $hdr);

			$xh = [];

			foreach( $headers as $h ) {

				$r = explode(':', $h, 2);

				$xh[ $r[ 0 ] ] = trim($r[1]);

			}

			if( isset( $xh['Content-Encoding'] ) ) {

				switch( $xh['Content-Encoding'] ) {

					case 'gzip':
					case 'deflate':
					case 'compress':

						$data = gzuncompress($body);

						break;

				}

			}
			else {

				$data = $body;

			}

			if( isset( $xh['Date'] ) ) {

				$date = DateTime::createFromFormat('D, d M Y H:i:s O', $xh['Date']);

				$date = $date->format('h:i d-m-Y');

			}

			if( $data && $ok ) {

				curl_close($ch);

				return [ $data,  $date ];

			}
			else {

				curl_close($ch);

				return [ null, null ];

			}

		}
		else {

			curl_close($ch);

			return [ null, null ];

		}

	}

	function getMetaTags( string $str ): iterable {

		$pattern = '~<\s*meta\s

		# using lookahead to capture type to $1
			(?=[^>]*?
			\b(?:name|property|http-equiv)\s*=\s*
			(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
			([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
		)

		# capture content to $2
		[^>]*?\bcontent\s*=\s*
			(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
			([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
		[^>]*>

		~ix';

		if( preg_match_all($pattern, $str, $out) ) {

			return array_combine( $out[1], $out[2] );

		}

		return [];

	}

	function getHost( string $uri, string $url ): ?string {

		$segments = parse_url(

			str_ireplace('.www', '', $uri)

		);

		$r = null;

		if( isset($segments['host']) ) {

			$r = $segments['host'];

		} 
		else {

			$r = parse_url(

			str_ireplace('.www', '', $url)

		);

			$r = $r['host'];

		}

		return $r;

	}

	function parse( string $html, string $url ): ?iterable {

		$host_links = [];

		// Perform title
		preg_match_all('#<title>(.+?)</title>#su', $html, $meta_title);

		// Perform body
		preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $meta_body);

		// Perform links only for host
		preg_match_all("/<a\s[^>]*href\s*=\s*([\"\']??)([^\"\' >]*?)\\1[^>]*>(.*)<\/a>/siU", $html, $prelinks, PREG_SET_ORDER);

		$meta_links = [];

		foreach( $prelinks as $plnk ) {

			$meta_links[] = $plnk[2]; // $plnk[3] - title

		}

		foreach( $meta_links as $l ) {

			$flnk = getHost($l, $url);

			if( getHost($url, $url) === $flnk ) {

				$lnk  = parse_url($l);

				$xlnk = parse_url($url)['scheme'] .'://'. getHost($url, $url);

				if( isset($lnk['path']) ) {

					$xlnk .= $lnk['path'];

				}

				if( isset($lnk['query']) ) {

					$xlnk .= '?'. $lnk['query'];

				}

				$host_links[] = $xlnk;

			}

		}


		$usefull_text = trim(

			html_entity_decode(

				preg_replace([

						'/<.+?>/mi', 
						'/\s*$^\s*/m', 
						'/[\r\n]+/', 
						'/\s+/',
						'/&(quot|#34);/i',
						'/&(amp|#38);/i',
						'/&(lt|#60);/i',
						'/&(gt|#62);/i',
						'/&(nbsp|#160);/i',
						'/&(iexcl|#161);/i',
						'/&(cent|#162);/i',
						'/&(pound|#163);/i',
						'/&(copy|#169);/i',

					], 

					[

						'',
						"\n",
						"\n",
						' ',
						'"',
						'&',
						'<',
						'>',
						' ',
						chr(161),
						chr(162),
						chr(163),
						chr(169),

					], 

					preg_replace(


						[

							'/\s?<style[^>]*?>.*?<\/style>\s?/si',
							'/\s?<script[^>]*?>.*?<\/script>\s?/si',
							'/\s?<a[^>]*?>.*?<\/a>\s?/si',

							'/<(header|footer|time).+?(style|script|header|footer|time)>/miU',
							'/\s?<nav[^>]*?>.*?<\/nav>\s?/si',
							'/\s?<form[^>]*?>.*?<\/form>\s?/si',
							'/<!--(.|\s)*?-->/', 
							'/s(w+s)1/i', 
							'#(\.|\?|!|\(|\)){3,}#', 
							'/"b([^"x84x93x94rn]+)b"/'

						], 


						[

							'',
							'',
							'',
							'',
							'',
							'',
							'', 
							'', 
							'', 
							'$1', 
							'\1\1\1', 
							'«1»'

						], $meta_body)

				)[ 0 ]

			)

		);

		if( strlen( $usefull_text) >= 120 ) {

			return [

				'title' => $meta_title[ 1 ][ 0 ],
				'meta'  => getMetaTags($html),
				'href'  => array_unique($host_links),
				'text'  => $usefull_text,
				'body'  => $meta_body

			];

		}
		else {

			return null;

		}

	}

	function setIndex( ?iterable $robotstxt, string $url, Model $model, iterable &$indexed ) {

		if( !in_array($url, $indexed) ) {

			$indexed[] = $url;

			$info = getUri($url);

			$xdata = $info[ 0 ];
			$xdate = $info[ 1 ];

			if( $xdata ) {

				$meta_data = parse(

					$xdata, $url

				);

				if( $meta_data ) {

					foreach( $meta_data['href'] as $uri ) {

						$testIndex = iterator_to_array(

						$model::get('index', [

								'criterion' => 'uri::'. $uri,
								'course'    => 'backward',
								'sort'      => 'id'

							])

						)['model::index'];

						if( $testIndex ) {

							$testIndex = $testIndex[ 0 ];

							if( indexingAllowed( $robotstxt, $uri ) ) {

								if( !in_array($uri, $indexed) ) {

									$xinfo = getUri($uri);

									$udata = $xinfo[ 0 ];
									$udate = $xinfo[ 1 ];

									if( $udata ) {

										$xmeta_data = parse(

											$udata, $uri

										);

										if( $xmeta_data ) {

											$hash = md5($xmeta_data['text']);

											$double_check = iterator_to_array(

												$model::get('index', [

														'criterion' => 'hash::'. $hash,
														'course'    => 'forward',
														'sort'      => 'id'

													])

												)['model::index'];

											$adate = date('d-m-Y');
											$idate = explode(' ', $testIndex['date'])[ 1 ]; 

											if( $hash !== $testIndex['hash'] && !$double_check ) {

												if( $adate !== $idate ) {

													// Intelligent update when uri exist and expired
													$model::erase('index', [

														'criterion'   => 'uri::'. $uri 

													]);

													// Intelligent update when uri exist and expired
													$model::set('index', [

														'uri'         => $uri,
														'host'        => getHost($url, $url),
														'hash'        => $hash,
														'date'		  => $udate,
														'title'       => $xmeta_data['title'],
														'description' => $xmeta_data['meta']['og:description'] ?? $xmeta_data['meta']['description'] ?? 'null',
														'content'     => $xmeta_data['text'],
														'criterion'   => 'uri'

													]);

												}

												foreach( $xmeta_data['href'] as $xlnk ) {

													if( indexingAllowed( $robotstxt, $uri) ) {

														setIndex( $robotstxt, $uri, $model, $indexed );

													}

												}

											}


										}

									}

								}

							}

						} 
						else {

							if( indexingAllowed( $robotstxt, $url) ) {

								if( !in_array($uri, $indexed) ) {

									$xinfo = getUri($uri);

									$udata = $xinfo[0];
									$udate = $xinfo[1];

									if( $udata ) {

										$xmeta_data = parse(

											$udata, $uri

										);

										if( $xmeta_data ) {

											$hash = md5($xmeta_data['text']);

											$double_check = iterator_to_array(

											$model::get('index', [

													'criterion' => 'hash::'. $hash,
													'course'    => 'forward',
													'sort'      => 'id'

												])

											)['model::index'];

											if( !$double_check ) {

												// Intelligent insert when uri not indexed
												$model::set('index', [

													'id'          => 0,
													'uri'         => $uri,
													'host'        => getHost($url, $url),
													'date'		  => $udate,
													'hash'        => $hash,
													'title'       => $xmeta_data['title'],
													'description' => $xmeta_data['meta']['og:description'] ?? $xmeta_data['meta']['description'] ?? 'null',
													'content'     => $xmeta_data['text'],

												]);

												foreach( $xmeta_data['href'] as $xlnk ) {

													if( indexingAllowed( $robotstxt, $uri) ) {

														setIndex( $robotstxt, $uri, $model, $indexed );

													}

												}

											}

										}

									}

								}

							}

						}

						sleep(.5);

					}

				}

			}

		}

	}

	if( indexingAllowed( $robotstxt, $url ) ) {

		setIndex( $robotstxt, $url, $RKI->Model, $indexed );

	}

}

define('serviceOutput', [

	'ctype'     => 'text/html',
	'route'     => '/picker/'

]);

?>
