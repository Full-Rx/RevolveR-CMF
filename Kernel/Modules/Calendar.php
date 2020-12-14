<?php

 /* 
  * 
  * RevolveR Calendar
  *
  * v.2.0.0.4
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
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

final class Calendar {

	// Simple machine readable HTML time format :: YYYY-MM-DD
	public static function formatTime( string $time ): string {

		$dateTime = explode( '.', str_replace( '-', '.', explode(' ', $time)[0] ) );

		return $dateTime[ 2 ] .'-'. $dateTime[ 1 ] .'-'. $dateTime[ 0 ];

	}

	// Add leading zero to digital clock cipher values
	public static function leadingZeroFix( string $d ): string {

		return strlen( (int)$d ) < 2 ? '0'. (int)$d : $d;

	}

	// Time diff correction
	public static function timeDiffCalc( int $t ): int {

		return $t < 0 ? $t + 60 : $t;

	}

	public static function timeExplode( int $sec ): iterable {

		$d = [];

		$d['d'] = floor($sec / 86400);

		$sec = $sec % 86400;

		$d['h'] = floor($sec / 3600);
		
		$sec = $sec % 3600;

		$d['m'] = floor($sec / 60);
		
		$d['s'] = $sec % 60;

		return $d;

	}

	// Day of week number
	public static function dayNW( ?string $x = null ): string {

		if( $x ) {

			return date('N', strtotime( $x ));

		}
		else {

			return date('N');

		}

	}

	// Day count in the month
	public static function dayCountInMonth( $x = null ): string {

		if( $x ) {

			if( is_array( $x ) ) {

				$int = date('t', mktime( $x[ 0 ], $x[ 1 ], $x[ 2 ], (int)$x[ 3 ], $x[ 4 ] ));

			} 
			else {

				$int = date('t', strtotime( $x ));

			}

			return $int;

		}
		else {

			return date('t');

		}

	}

	// Week of year number
	public static function weekNY( ?int $x = null ): string {

		if( $x ) {

			return date('W', strtotime( $x ));	

		} 
		else {

			return date('W');

		}

	}

	//Name of the month
	public static function monthName( $x = null ): string {

		if( $x ) {

			if( is_array( $x ) ) {

				$str = date('F', mktime( $x[ 0 ], $x[ 1 ], $x[ 2 ], $x[ 3 ], $x[ 4 ] ));

			}
			else {

				$str = date('F', strtotime( $x ));

			}

			return $str;

		}
		else {

			return date('F');

		}

	}

	// Make calendar markup
	public static function make( string $month, string $year, iterable $dates, string $now, string $lng ): string {

		$headlines = [

			TRANSLATIONS[ $lng ]['Monday'], 
			TRANSLATIONS[ $lng ]['Tuesday'],
			TRANSLATIONS[ $lng ]['Wednesday'], 
			TRANSLATIONS[ $lng ]['Thursday'],
			TRANSLATIONS[ $lng ]['Friday'], 
			TRANSLATIONS[ $lng ]['Saturday'],
			TRANSLATIONS[ $lng ]['Sunday']

		];

		$dayNamesOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

		$hn = 0;

		$cellCaptions = '<tr>'; 

		// Void days before and after
		$prev_y = (int)$year - 1;
		$next_y = (int)$year + 1;

		// Highlited days
		$ymd = $year .'-'. $month;

		$asx = self::dayNW(date($ymd .'-'. $now));

		foreach( $headlines as $h ) {

			$cellCaptions .= '<th '. ( (int)$asx !== ++$hn ? '' : 'class="active-scope"') . ' data-scope="day-'. $hn .'" scope="col"> [ '. $h .' ] </th>';

		}

		$cellCaptions .= '</tr>';

		$layout  = '<table class="revolver__calendar" lang="'. main_language .'">';

		$layout .= '<caption>'. TRANSLATIONS[ $lng ]['Calendar'] .' :: '. TRANSLATIONS[ $lng ][ self::monthName( date($ymd) ) ] .' ['. $year .'], '. $headlines[ self::dayNW() - 1 ] .' - [ '. $now .' ], '. TRANSLATIONS[ $lng ]['week'] .' - [ '. self::weekNY() .' ]</caption>';

		$layout .= '<thead>'. $cellCaptions .'</thead>';

		$firstDayNumber = self::dayNW( date($ymd) );

		$lastDayInMonth = self::dayCountInMonth( date($ymd) );

		$day = 1;

		$layout .= '<tbody>';

		$flag = null;

		$layout_next_void = '';

		$newMonthDays = 0;

		for( $w = 0; $w < 6; ++$w ) {

			$layout .= '<tr>';
			$layout_td = '';

			$e_counter = 0;

			for( $d = 1; $d < 8; ++$d ) {

				$mod = --$firstDayNumber < 1 && $day <= $lastDayInMonth ? true : null;

				if( $w + 1 === 6 && !$mod ) {

					break;

				}

				$layout_td .= '<td class="'. ( (int)$asx === $d ? 'active-scope ' : '' ) . ($day == (int)$now ? 'active-day-cell ' : '') . ( $mod ? 'calendar-day' : 'calendar-day-void' ) .'"';

				if( $mod ) {

					$layout_td .= ' data-scope="day-'. $d .'"';

				}

				$layout_td .= '>';

				if( !$mod ) {

					switch( (int)$month ) {

						case 1:

							if( $year !== date('Y') ) {

								$layout_td .= $flag ? ++$newMonthDays : (int)date('d', strtotime( $prev_y .' last '. $dayNamesOfWeek[ $d - 1 ] .' of last month' ) ) + 1;

							}
							else {

								$layout_td .= $flag ? (int)date('d', strtotime($next_y .' first '. $dayNamesOfWeek[ $d - 1 ] .' of next month')) : (int)date( 'd', strtotime($prev_y .' last '. $dayNamesOfWeek[ $d - 1 ] .' of last month'));

							}

							break;

						case 12:

							$layout_td .= $flag ? (int)date('d', strtotime($year .'-'. $month .' +1 year first '. $dayNamesOfWeek[ $d - 1 ] .' of first month' )) : '';

							if( $year !== date('Y') && $d < 6 && $day < self::dayCountInMonth( [ 0, 0, 0, $year, $now, $month ] ) ) {

								$layout_next_void .= '<td class="'. ( '' ) .  'calendar-day-void">';

								$layout_next_void .= '<div class="day-number day-future"><span>';
								$layout_next_void .= $d;
								$layout_next_void .= '</span></div>';


								$layout_next_void .= '</td>';

							}

							break;


					}

				}

				if( $mod ) {

					$dt = $day++;

					$flag = true;

					if( isset($dates[ $dt ]) ) {

						if( $dt === (int)$now ) {

							$layout_td .= '<div class="day-number highlight-date"><span>'. $dt .'</span></div>';

						}
						else {

							$layout_td .= '<div class="day-number"><a href="/attendance/?date='. ( strlen($dt) < 2 ? $year .'/'. $month .'/'. 0 . $dt : $year .'/'. $month .'/'. $dt ) .'"><i>'. $dt .'</i></a></div>';

						}

					}
					else {

						$layout_td .= '<div class="day-number day-future"><span>'. $dt .'</span></div>';

					}

				}
				else {

					$e_counter++;

				}

				$layout_td .= '</td>';

				if( $w > 5 && !$mod ) {

					$layout_td = '';

				}

			}

			if( $e_counter !== 6 || $e_counter > 1 ) {

				$layout .= $layout_td . ( $w === 5 ? $layout_next_void .'</tr>' : '</tr>' );

			}

		}

		$layout .= '</tbody>';
		$layout .= '<tfoot>'. $cellCaptions .'</tfoot>';

		return str_replace( '<tr></tr>', '', $layout ) .'</table>';

	}

}

?>
