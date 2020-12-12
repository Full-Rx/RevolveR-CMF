<?php

$render_node .= '<nav class="revolver__pagination">';
$render_node .= '<ul>';

// Links 
$render_node_links = '';

// Bind link root
$link_root = PASS[ 1 ] === 'blog' ? '/blog/' : '/';

// Limit pages per section
$limit = 5;

for( $i = 1; $i <= $pages_count; $i++ ) {

	$link = $i === 1 ? $link_root : $link_root . '?page='. $i; 

	if( $i >= 1 ) {

		if( (int)pagination['curent'] + 1 === 1 ) {

			$prev_link = $link_root;

		}
		else {

			$prev_link = ((int)pagination['curent'] <= 1 ? $link_root : (int)pagination['curent'] - 1 === 1) ? $link_root : $link_root . '?page='. ((int)pagination['curent'] - 1);

		}

		$render_node_prev = (int)pagination['curent'] <= 1 ? '' : '<li><a href="'. $prev_link .'"><span>⇐</span></a></li>';

	}

	if( $i <= ((int)$pages_count - 1) ) {

		if( (int)pagination['curent'] + 1 === 1 ) {

			$nextFixed = (int)pagination['curent'] + 2;

		}
		else {

			$nextFixed = (int)pagination['curent'] + 1;

		}

		$next_link = pagination['curent'] >= $pages_count ? $link_root : $link_root . '?page='. $nextFixed;

		$render_node_next = pagination['curent'] >= $pages_count ? '' : '<li><a href="'. $next_link .'"><span>⇒</span></a></li>';

	}

	if( (int)pagination['curent'] === $i ) {

		$render_node_links .= '<li class="pagination_current pagination_link" data-number="'. $i .'"><span><i>'. $i .'</i></span></li>';

	}
	else {

		if( $i <= $pages_count - 1  ) {

			if( $i <= $limit ) {

				$render_node_links .= !(bool)pagination['curent'] && $i === 1 ? '<li class="pagination_current pagination_link" data-number="'. $i .'"><span><i>'. $i .'</i></span></li>' : '<li class="pagination_link" data-number="'. $i .'"><a href="'. $link .'"><span>'. $i .'</span></a></li>';

			}

			if( $i == $pages_count - 1 ) {

				$render_node_links .= '<li><span><i>&hellip;</i></span></li>';

			}

		}
		else if( $i == $pages_count ) {

			$render_node_links .= !(bool)pagination['curent'] && $i === 1 ? '<li class="pagination_current pagination_link" data-number="'. $i .'"><span><i>'. $i .'</i></span></li>' : '<li class="pagination_link" data-number="'. $i .'"><a href="'. $link .'"><span>'. $i .'</span></a></li>';

		}

	}

}

$render_node .= $render_node_prev . $render_node_links . $render_node_next;

$render_node .= '</ul>';

$render_node .= '</nav>';

?>
