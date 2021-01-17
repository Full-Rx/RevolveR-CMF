<?php

 /*
  * RevolveR Attendance Node
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

if( in_array(ROLE, ['Admin', 'Writer'], true) )  {

	$infoByIP = new Reader('/private/IPDB/');

	// Statistics
	$tracker_stack = [];

	$curent_month_dates = [];

	// Date change
	if( isset(SV['g']['date']['value']) ) {

		$curent_stats_date = htmlspecialchars( SV['g']['date']['value'] );

		$uriSegment = explode('/', $curent_stats_date);

		$highlight	= !empty( $uriSegment[ 2 ] ) ? $calendar::leadingZeroFix( $uriSegment[ 2 ] ) : '01';

		$fzd = $calendar::leadingZeroFix( $uriSegment[ 1 ] );

		$regexp = $uriSegment[ 0 ] .'\/'. $fzd .'\/';

		$filter	= $uriSegment[ 0 ] .'/'. $fzd .'/';

	}
	else {

		$curent_stats_date = date('Y/m/d');

		$uriSegment = explode('/', $curent_stats_date);

		$highlight = $uriSegment[ 2 ];
		
		$regexp = $uriSegment[ 0 ] .'\/'. $uriSegment[ 1 ] .'\/';

		$filter = $uriSegment[ 0 ] .'/'. $uriSegment[ 1 ] .'/';

	}

	$hits = iterator_to_array(

		$RKI->Model::get('statistics', [

			'criterion' => 'date::' . '^'. $regexp .'[0-9]{2}',
			'course'	=> 'forward',
			'sort' 		=> 'id',
			'expert'	=> true

		])

	)['model::statistics'];

	if( isset($hits[ 0 ]) ) {

		foreach( $hits as $s ) {

			$curent_month_render = explode( '/', $s['date'] );

			if( $filter . $highlight === $s['date'] ) { // today

				$ua_data = $uaInfo::getInfo( $s['user_agent'] );

				if( empty($ua_data['platform']) ) {

					$ua_data['platform'] = 'bot';

					if( empty($ua_data['browser']) ) {

						$ua_data = null;

					}

				}

				if( $ua_data ) {

					try {

						if( filter_var( $s['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE | FILTER_FLAG_NO_PRIV_RANGE ) ) {

							$ipInfo = $infoByIP::get( $s['ip'] );

							$location = $ipInfo['country']['names']['en'] .' :: '. ( isset( $ipInfo['city']['names']['en'] ) ? $ipInfo['city']['names']['en'] : 'unknown' );

							$attr = $ipInfo['registered_country']['iso_code'];

						}
						else {

							$location = 'private';

							$attr = 'private';

						}

					}
					catch( AddressNotFoundException $e ) {

						$location = 'unknown';

						$attr = 'unknown';

					}

					if( !in_array( $s['route'], ['/secure/', '/comments-d/', '/contents-d/', '/category-d/', '/rating-d/', '/user-d/', '/favicon.ico', '/apple-touch-icon-precomposed.png', '/apple-touch-icon.png'], true ) ) {

						$tracker_stack[ $s['ip'] ][ $ua_data[ 'platform' ] .'/'. $ua_data[ 'browser' ] .'/'. $location ][] = [

							explode(';', 

								rtrim( 

									ltrim( $os[ 0 ][ 0 ],'(' ), ')'

								)

							)[ 0 ] .'/'. $browser[ 0 ] .'/'. $location  => [

								'time'		=> $s['time'],
								'identify'	=> $s['track'],
								'route'		=> $s['route'],
								'referer'	=> $s['referer'],
								'ip'		=> $s['ip'],

								'iso_code'	=> $attr

							]

						];

					}

				}

			} 

			// Complare only current Month statistics
			if( $calendar::leadingZeroFix( $uriSegment[ 1 ] ) === $curent_month_render[ 1 ] ) {

				$curent_month_dates[ (int)$curent_month_render[ 2 ] ] = true;

			}

		}

	}

	// Close and free
	$infoByIP::close();

	$complite_tracker_stack = [];

	foreach( $tracker_stack as $visitor ) {

		$total_time_h = 0;

		$total_time_m = 0;

		$total_time_s = 0;

		foreach( $visitor as $hit => $a ) {

			$start_time_h = 0;

			$start_time_m = 0;

			$start_time_s = 0;

			$counter = 0;

			$xkey = '';

			$qkey = '';

			$key = '';

			foreach( $a as $f => $v ) {

				$key  = key($v);

				$xkey = $v[ $key ];

				$qkey = key($visitor);

				$time = explode(':', $xkey[ 'time' ]);

				$s_time = explode(':', $a[0][ $key ][ 'time' ]);

				$start_time_h = $s_time[ 0 ];

				$start_time_m = $s_time[ 1 ];

				$start_time_s = $s_time[ 2 ];

				$p_time = explode(':', $a[ $counter - 1 ][ $key ]['time']);

				$prev_time_s = $p_time[ 2 ];

				$prev_time_m = $p_time[ 1 ];

				if( !(bool)$counter ) {

					$total_time_s = (int)$time[ 2 ] - (int)$start_time_s;

					$total_time_m = (int)$time[ 1 ] - (int)$start_time_m;

					$apple_icons = explode('png', $xkey['route']);

					if( explode('ico', $xkey['route'])[ 0 ] !== '/fav' && $apple_icons[ 0 ] !== '/apple-touch-icon-precomposed.' && $apple_icons[ 0 ] !== '/apple-touch-icon.') {

						$complite_tracker_stack[ $xkey[ 'ip' ] ][ $qkey ][] = [

							'stay_time' => [

								'h' => 0,
								'm' => 0,
								's' => 0

							],

							'total_time' => [

								'h' =>  abs((int)$total_time_h),
								'm' =>  abs((int)$total_time_m),
								's' =>  abs((int)$total_time_s)

							],

							'time' 		=> $xkey['time'],
							'identify' 	=> $xkey['identify'],
							'route'		=> $xkey['route'],
							'referer'	=> $xkey['referer'],
							'iso_code'	=> $xkey['iso_code'],
							'ip'		=> $xkey['ip']

						];

					}

				}
				else {

					$total_time_freeze_s = $calendar::timeDiffCalc( (int)$time[ 2 ] - (int)$prev_time_s );
					$total_time_freeze_m = $calendar::timeDiffCalc( (int)$time[ 1 ] - (int)$prev_time_m );
					
					$total_time_s += (int)$time[ 2 ] - (int)$prev_time_s;
					$total_time_m += (int)$time[ 1 ] - (int)$prev_time_m;

					if( (bool)($prev_time_s - $time[ 2 ]) ) {

						$total_time_m++;

					}

					if( (bool)($prev_time_m - $time[ 1 ]) ) {

						$total_time_h++;

					}

					$complite_tracker_stack[ $xkey[ 'ip' ] ][ $qkey ][ $counter - 1 ][ 'stay_time' ][ 'm' ] = $calendar::leadingZeroFix($total_time_freeze_m);
					$complite_tracker_stack[ $xkey[ 'ip' ] ][ $qkey ][ $counter - 1 ][ 'stay_time' ][ 's' ] = $calendar::leadingZeroFix($total_time_freeze_s);

					$route = $xkey['route'] === $complite_tracker_stack[ $xkey['ip']][ $qkey ][$counter - 1]['route'] ? '...' : $xkey['route'];

					$apple_icons = explode('png', $xkey['route']);

					if( explode('ico', $xkey['route'])[ 0 ] !== '/fav' && $apple_icons[ 0 ] !== '/apple-touch-icon-precomposed.' && $apple_icons[ 0 ] !== '/apple-touch-icon.' ) { 

						$add_to_stack = [

							'total_time' => [

								'h' => abs((int)$total_time_h),
								'm' => abs((int)$total_time_m),
								's' => abs((int)$total_time_s)

							],

							'time' 		=> $xkey['time'],
							'identify' 	=> $xkey['identify'],
							'referer'	=> $xkey['referer'],
							'iso_code'	=> $xkey['iso_code'],
							'ip'		=> $xkey['ip'],
							'route'		=> $route

						];

						$complite_tracker_stack[ $xkey[ 'ip' ] ][ $qkey ][] = $add_to_stack;

					}

				}

				$counter++;
			}

		}

	}

	$contents .= '<div class="revolver__form-wrapper">';
	$contents .= '<form class="revolver__new-fetch">';

	$contents .= '<fieldset>';
	$contents .= '<legend style="min-width:40%">'. $RNV->lang['Summary for the day'] .'</legend>';

	// Visitors
	$count_guest = 0;

	$count_bot = 0;

	$count_users = 0;

	// Referers
	$count_referers = 0;

	$count_internal = 0;

	$count_search_engines = 0;

	$referers = [];

	$key = '';

	/* Whois servers list */
	define('wservers', file_get_contents( $_SERVER['DOCUMENT_ROOT'] .'/Kernel/Modules/Extra/whois/servers.txt') );

	/* Whois server handler */
	function getWHOISServers() {

		$whoisservers = explode("\n", wservers);

		foreach( $whoisservers as $value ) {

			$value = explode('|', $value);

			$whoisserver[ trim(strip_tags($value[0])) ] = trim(strip_tags($value[1]));

		}

		return $whoisserver;

	}

	/* Whois handler */
	function lookupDomain( string $sld, string $ext, string $whois_server = 'whois.crsnic.net', int $whois_line = 1 ) {

		$data = '';

		$whoisservers = explode("\n", wservers);

		foreach( $whoisservers as $value ) {

			$value = explode('|', $value);

			$tld = trim(strip_tags($value[ 0 ]));

			$whoisserver[ $tld ] = trim(strip_tags($value[ 1 ]));

			$whoisvalue[ $tld ] = trim(strip_tags($value[ 2 ]));

			$whoisreqprefix[ $tld ] = isset($value[ 3 ]) ? strip_tags($value[ 3 ]) : '';

			continue;

		}

		$server = $whoisserver[ $ext ];

		if( strlen($whois_server) > 10 ) {

			$server = $whois_server;

		}

		$port = '43';

		$return = $whoisvalue[ $ext ];

		$reqprefix = $whoisreqprefix[ $ext ];

		if( $server === '' ) {

			$result['result'] = 'available';

		}
		else {

			$domain = $sld . $ext;

			$fulldomain = $domain;

			if( substr($return, 0, 12) === 'HTTPREQUEST-' ) {

				$ch = curl_init();

				$url = $server . $domain;

				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				$data = curl_exec($ch);

				$data2 = ' ---' . $data;

				if(curl_error($ch)) {

					$result['result'] = 'error';

					if( $_SESSION['adminid'] ) {

						$result['errordetail'] = 'Error: ' . curl_errno($ch) . ' - ' . curl_error($ch);

					}

				}
				else {

					if( !(bool)strpos($data2, substr($return, 12))) {

						$result['whois'] = strip_tags($data);

					}

				}

				curl_close($ch);

			}
			else {

				if(strpos($server, ':')) {

					$port = explode(':', $server, 2);
					$server = $port[ 0 ];
					$port = $port[ 1 ];

				}

				if(substr($return, 0, 6) === 'NOTLD-') {

					$domain = $sld;
					$return = substr($return, 6);

				}

				$fp = fsockopen($server, $port, $errno, $errstr, 10);

				if($fp) {

					fputs($fp, $reqprefix . $domain . "\r\n");

					socket_set_timeout($fp, 10);

					while(!feof($fp)) {

						$data .= fread($fp, 4096);

					}

					fclose($fp);

					$data2 = ' ---' . $data;

					if(!(bool)strpos($data2, $return) === true) {

						$result['whois'] = $data;

					}

				}
				else {

					$result['result'] = 'error';

					if($_SESSION['adminid']) {

						$result['errordetail'] = 'Error: ' . $errno . ' - ' . $errstr;

					}

				}

			}

		}

		return $result;

	}

	foreach( $complite_tracker_stack as $visitor => $v ) {

		$key = $v[ key($v) ];

		$total_hits = count( $key );
		
		$ua_data = explode('/', key($v));

		if( $ua_data[ 0 ] === 'bot' ) {

			$count_bot++;

		}
		else {

			if( $key[ count( $key ) - 1 ]['identify'] !== 'guest' ) {

				$count_users++;

			}
			else {

				$count_guest++;

			}

			foreach( $key as $g ) {

				if( $g['referer'] !== 'straight' ) {

				$host = explode('.', parse_url($g['referer'])['path'] );

					if( !in_array( $host[ 0 ] != 'www' ? $host[ 0 ] : $host[ 1 ], ['yandex', 'yahoo', 'mail', 'rambler', 'bing', 'nigma', 'baidu', 'duckduckgo', 'google'] ) ) {

						$count_referers++;

						$referers[ $g['referer'] ] = $g['referer'];

					}
					else {

						$count_search_engines++;

						$referers[ $g['referer'] ] = $g['referer'];

					}

				}
				else {

					$count_internal++;

				}

			}

		}

	}

	$total_visits =  $count_users + $count_guest + $count_bot;
	$total_visits_counter = $count_referers + $count_search_engines + $count_internal;

	$contents .= '<div class="revolver__attendance-panel">';
	$contents .= '<output class="revolver__stats-total-counters">';
	$contents .= '<dfn class="revolver__stats-counter-hits">';

	$contents .= '<u>';
	$contents .= '<span class="revolver__interface-icon icon-mind-map">❏ '. $RNV->lang['users'] .': <b>'. $count_users .'</b></span> / '; 
	$contents .= '<span class="revolver__interface-icon icon-enter">❐ '. $RNV->lang['guests'] .': ';
	$contents .= '<b>'. $count_guest .'</b>';
	$contents .= '</span> / ';
	$contents .= '<span class="revolver__interface-icon icon-collect">❖ '. $RNV->lang['scanners'] .': <b>'. $count_bot .'</b></span>';
	$contents .= '</u>';

	if( (bool)$total_visits ) {

		$contents .= '<i><b class="revolver__interface-icon icon-bullish">'. $RNV->lang['humans'] .': '. (int)( $count_users + $count_guest / $total_visits * 100 ) .'%</b></i>';

	} 
	else {

		$contents .= '<i>'. $RNV->lang['Insufficient statistics'] .'</i>';

	}

	$contents .= '</dfn>';
	$contents .= '</output>';

	$contents .= '<output class="revolver__stats-total-counters">';
	$contents .= '<dfn class="revolver__stats-counter-hits">';
	
	$contents .= '<u>';
	$contents .= '<span class="revolver__interface-icon icon-enter">⥹ '. $RNV->lang['internal'] .': <b>'. $count_internal .'</b></span> / ';
	$contents .= '<span class="revolver__interface-icon icon-open-in-browser">⥻ '. $RNV->lang['referer'] .': <b>'. $count_referers .'</b></span> / ';
	$contents .= '<span class="revolver__interface-icon icon-find-and-replace">⧝ '. $RNV->lang['search engines'] .': <b>'. $count_search_engines .'</b></span>';
	$contents .= '</u>';

	if( (bool)$total_visits_counter ) {

		$humansPercent = $count_search_engines / $total_visits_counter * 100;
		$externalPercent = $count_referers / $total_visits_counter * 100;

		$contents .= '<i><b class="revolver__interface-icon icon-org-unit">'. $RNV->lang['search engines'] .': '. round($humansPercent) .'%</b> / <b class="revolver__interface-icon icon-open-in-browser">'. $RNV->lang['referer'] .': '. round($externalPercent) .'%</b></i>';

	} 
	else {

		$contents .= '<i>'. $RNV->lang['Insufficient statistics'] .'</i>';

	}

	$contents .= '</dfn>';
	$contents .= '</output>';
	$contents .= '</div>';
	$contents .= '</fieldset>';

	if( count($referers) > 0 ) {

		$contents .= '<fieldset>';

		if( count($referers) >= 5 ) {

			$contents .= '<legend style="min-width:40%;" class="revolver__collapse-form-legend">'. $RNV->lang['External transitions'] .'</legend>';
			$contents .= '<output style="margin-bottom: 40px; overflow: hidden; width: 0; height: 0; line-height: 0; display: inline-block;" class="revolver__collapse-form-contents">';

		}
		else {

			$contents .= '<legend style="min-width:40%;">'. $RNV->lang['External transitions'] .'</legend>';

		}

		$contents .= '<dl class="revolver__stats-list"><dd>';
		$contents .= '<dl class="revolver__referers-list">';

		$list = 0;

		$wd  = '';
		$ext = '';

		foreach( $referers as $r ) {

			if( strlen($r) > 3 ) {

				/* Whois service futures */

				$xn = html_entity_decode(explode('/', $r)[ 0 ]);

				$xd = idn_to_utf8($xn);

				$wd = explode('.', $xn);

				$dc = count($wd);

				if( (int)$dc === 2 ) {

					$ext = '.'. $wd[ 1 ];

				}
				else if( (int)$dc === 3 ) {

					$wd[ 0 ] = $wd[ 1 ];

					$ext = '.'. $wd[ 2 ];

				}

				$wr = explode('>>>', strtolower(lookupDomain($wd[ 0 ], $ext, getWHOISServers()[ $ext ])['whois']))[0];

				$r = rtrim($r, '/');

				$contents .= '<dt>';
				$contents .= '<a target="_blank" href="//'. $r .'" title="'. $xd .'">'. $r .'</a>';
				$contents .= '</dt>';
				$contents .= '<dd><pre class="whois-info">'. $wr .'</pre></dd>';

				$list++;

			}

		}

		$contents .= '</dl>';
		$contents .= '</dd></dl>';

		if( count($referers) >= 5 ) {

			$contents .= '</output>';

		} 

		$contents .= '</fieldset>';

	}

	$contents .= '<fieldset>';
	$contents .= '<legend style="min-width:40%;" class="revolver__collapse-form-legend">'. $RNV->lang['Internal transitions'] .'</legend>';
	$contents .= '<output style="overflow: hidden; width: 0; height: 0; line-height: 0; display: inline-block;" class="revolver__collapse-form-contents">';
	$contents .= '<dl class="revolver__stats-list collapse">';


	$xkey = '';
	$key  = '';

	foreach( $complite_tracker_stack as $visitor => $v ) {

		$key = key($v);

		$timeDiffx = explode(':', $v[$key][ count($v[ $key ]) - 1 ]['time']);
		$timeDiffy = explode(':', $v[$key][ 0 ]['time']);

		$ts = abs((int)$timeDiffx[ 2 ] - (int)$timeDiffy[ 2 ]);
		$tm = abs((int)$timeDiffx[ 1 ] - (int)$timeDiffy[ 1 ]);
		$th = abs((int)$timeDiffx[ 0 ] - (int)$timeDiffy[ 0 ]);

		$xkey = $v[ $key ];

		$total_hits = count( $xkey );

		$ua_data = explode('/',  $key );

		if( $ua_data[0] !== 'bot' ) {

			$contents .= '<dt>';
			$contents .= '<dfn>';

			$contents .= '<div class="revolver__stats-group-identify">';

			$contents .= '<span class="revolver__stats-ua"> 👁 '. $ua_data[ 0 ] .'</span>';
			$contents .= '<span class="revolver__stats-system"> ☸ '. $ua_data[ 1 ] .'</span>&nbsp;';

			$contents .= $RNV->lang['from'] .'<span class="revolver__stats-country"><span class="state-attribution revolver__sa-iso-'. strtolower( str_replace(' ', '-', $xkey[ 0 ]['iso_code']) ) .'"></span> ⛫ '. $ua_data[ 2 ] .'</span>';

			$contents .= '</div>';

			$contents .= '<div class="revolver__stats-group-time">';

			$time_string = $th . ' ' . $RNV->lang['hours'] .' '. $tm .' '. $RNV->lang['minutes'] .' '. $ts .' '. $RNV->lang['seconds'];

			$contents .= '<span class="revolver__stats-time">  ⛶ '. $total_hits .' '. $RNV->lang['total hits'] .' '. $RNV->lang['at'] .' '. $time_string .' </span>';
			
			$contents .= '</div>';

			$contents .= '<div class="revolver__stats-group-ip">';
			$contents .= '<span class="revolver__stats-ip-address revolver__interface-icon icon-collect">'. $visitor .'</span>';
			$contents .= '</div>';

			$contents .= '</dfn>';
			$contents .= '</dt>';
			
			$contents .= '<dd>';
			$contents .= '<ol>';

			$contents .= '<li class="revolver__stats-header"><span>#</span>';
			$contents .= ' <span><b class="revolver__interface-icon icon-expired"> ☆ '. $RNV->lang['visit time'] .'</b></span>';
			$contents .= ' <span><b class="revolver__interface-icon icon-expired"> ★ '. $RNV->lang['residence time'] .'</b></span>';
			$contents .= ' <span><b class="revolver__interface-icon icon-open-in-browser"> ⛓ '. $RNV->lang['route'] .'</b></span>';
			$contents .= ' <span><b class="revolver__interface-icon icon-info-popup"> ☄ '. $RNV->lang['identify'] .'</b></span>';
			$contents .= '</li>';

			$c = 1;

			foreach( $xkey as $g ) {

				$contents .= '<li class="revolver__stats-row">';
				$contents .= '<span class="revolver__stats-counter">'. $c++ .'</span>';
				$contents .= '<span class="revolver__stats-time">'. $g['time'] .'</span>';

				if( isset( $g['stay_time'] ) ) { 

					$contents .= '<span class="revolver__stats-residence-time">'. $RKI->Calendar::leadingZeroFix( $g['stay_time']['m'] ) .':'. $RKI->Calendar::leadingZeroFix( $g['stay_time']['s'] ) .'</span>';

				} 
				else {

					$contents .= '<span class="revolver__stats-residence-time"></span>';

				}

				$contents .= '<span class="revolver__stats-route">'. $g['route'] .'</span>';
				$contents .= '<span class="revolver__stats-identify">'. $g['identify'] .'</span>';
				$contents .= '</li>';

			}

			$contents .= '</ol></dd>';

		}

	}

	$contents .= '</dl>';
	
	// Max Mind attribution requred for usage MMDB with share-alike license
	$contents .= '<p style="font-size:.8vw; text-align: right; padding-right: 1.2vw;">[ This section includes GeoLite2 data created by MaxMind, available from <a href="https://www.maxmind.com" target="_blank">https://www.maxmind.com</a> ]</p>';
	
	$contents .= '</output>';
	$contents .= '</fieldset>';

	// get selected date
	$dateNow = explode('/', $curent_stats_date);

	$contents .= '<fieldset>';
	$contents .= '<legend style="min-width:40%;" class="revolver__collapse-form-legend">';

	$contents .= $RNV->lang[ $RKI->Calendar::monthName( date($dateNow[ 0 ] .'-'. $dateNow[ 1 ])) ] .' '. $dateNow[ 0 ] .', [ '. (int)$highlight .' ]';

	$contents .= '</legend>';

	$contents .= '<output style="overflow: hidden; width: 0; height: 0; line-height: 0; display: inline-block;" class="revolver__collapse-form-contents">';

	/* Extra SQL with Years and Months ranges */
	unset( $STRUCT_STATISTICS['criterion_field'], $STRUCT_STATISTICS['criterion_format'], $STRUCT_STATISTICS['criterion_regexp'], $STRUCT_STATISTICS['criterion_value'] );

	/* Get Years-Months Ranges */
	$STRUCT_STATISTICS['extra_select_sql'] = 'SELECT DISTINCT DATE_FORMAT(`field_date`, \'[%Y%m]\') YearMonth, DATE_FORMAT(`field_date`, \'[%Y]\') Year, DATE_FORMAT(`field_date`, \'[%m]\') Month FROM `revolver__statistics`;';

	$dbx::query('p', 'revolver__statistics', $STRUCT_STATISTICS); // be carefull becuase this query unescaped

	$year_month_r = [];

	$years_r = [];

	$month_r = [];

	// Get ranges
	foreach( $dbx::$result['result'] as $k => $v ) {

		$years = str_replace(['\\', ']', '['], ['', '', ''], $v['Year']);

		$month = str_replace('\\', '', $v['Month']);

		$ym = explode('\\', $v['YearMonth']);

		$years_r[ $years ] = $years;

		$month_r[ $month ] = $month;

		$year_month_r[]    = [ $ym[1], rtrim($ym[2], ']') ];

	}

	$contents .= '<div class="revolver__multiple-selects-wrapper" style="text-align:center;">';
	$contents .= '<label style="width:46% !important" class="styled-select switch-time">';
	$contents .= 'Choose year:';

	$contents .= '<select name="revolver__attendance-year" data-callback="switchAttendanceDate">';

	// Statistics Years list
	foreach( $years_r as $y ) {

		$lastMonthInYear = '';

		foreach( $year_month_r as $ymr ) {

			if( $y === $ymr[ 0 ] ) {

				$lastMonthInYear = $ymr[ 1 ];

			}

		}

		$contents .= '<option value="'. $y .'/'. $lastMonthInYear .'/'. $RKI->Calendar::dayCountInMonth( [0, 0, 0, $lastMonthInYear, 10] ) .'" '. ( $uriSegment[ 0 ] === $y ? 'selected="selected"' : '' ) .'>'. $y .'</option>';

	}

	$contents .= '</select>';
	$contents .= '</label>';

	$contents .= '<label style="width:46% !important" class="styled-select switch-time">';
	$contents .= 'Choose month:';

	// Statistics Month list
	$contents .= '<select name="revolver__attendance-month" data-callback="switchAttendanceDate">';

	foreach( $year_month_r as $ym ) {

		if( $uriSegment[ 0 ] === $ym[ 0 ] ) {

			if( $uriSegment[ 1 ] === $ym[ 1 ] ) {

				$attr = ' selected="selected"';

			} 
			else {

				$attr = '';

			}

			$contents .= '<option value="'.  $ym[ 0 ] .'/'. $ym[ 1 ] .'/'. $RKI->Calendar::dayCountInMonth( [0, 0, 0, $ym[ 1 ], 10] ) .'" '. $attr .'>'. $RKI->Calendar::monthName( [0, 0, 0, $ym[ 1 ], 10] ) .'</option>';

		}

	}

	$contents .= '</select>';
	$contents .= '</label>';
	$contents .= '</div>';

	$yearMonth = explode('/', $curent_stats_date);

	$contents .= $RKI->Calendar::make( $yearMonth[1], $yearMonth[0], $curent_month_dates, $highlight, $ipl );

	$contents .= '</fieldset>';
	$contents .= '</form>';
	$contents .= '</div>';

}

$node_data[] = [

	'title'		=> $RNV->lang['Attendance'],
	'route'		=> '/attendance/',
	'id'		=> 'attendance',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'time'		=> null,
	'published' => 1

];

?>
