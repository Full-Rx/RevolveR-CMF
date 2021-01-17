<?php

 /* 
  * 
  * RevolveR Markup Class
  *
  * Makes markup valid and secure
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

final class Markup {

	// Lazy list
	public static $lazyList = [];

	function __construct() {


	}

	// Make markup valid and secure
	public static function Markup(string $s, ?array $opts = null): string {

		$default = [

			'length' => 0,
			'lazy'	 => 0

		];

		if( $opts ) {

			$options = array_merge( $default, $opts );

		}
		else {

			$options = $default;

		}

		// Prepare given markup, makes it's valid and secure
		return self::tidy_html5( self::crop( $s, $options['length'] ), $options['lazy'] );

	}

	public static function tidy_html5( string $html, int $lazy, iterable $config = [], string $encoding = 'utf8' ): string {

		$config += [

			'clean'						=> 1,
			'doctype'					=> 'omit',
			'indent'					=> 2,
			'output-html'				=> 1,
			'tidy-mark'					=> 0,
			'fix-bad-comments'			=> 1,
			'show-body-only'			=> 1,
			'accessibility-check'		=> 0,
			'drop-empty-paras'			=> 1,
			'escape-scripts'			=> 1,
			'fix-backslash'				=> 1,
			'strict-tags-attributes'	=> 1,
			'logical-emphasis'			=> 1,
			'escape-scripts'			=> 1,
			'strict-tags-attributes'	=> 1,
			'indent-with-tabs'			=> 1,
			'keep-tabs'					=> 1,
			'tab-size'					=> 4,
			'wrap'						=> 0,
			'vertical-space'			=> 1,

			// Filter
			'omit-optional-tags'	=> 'html, body, head, title, iframe, script',
			'priority-attributes'	=> 'src',

			// HTML5 tags
			'new-blocklevel-tags'	=> 'article aside audio bdi canvas details dialog figcaption figure footer header hgroup main menu menuitem nav section source summary template track video',
			'new-empty-tags'		=> 'command embed keygen source track wbr',
			'new-inline-tags'		=> 'audio command datalist embed keygen mark menuitem meter output progress source time video wbr'

		];

		$output = tidy_repair_string( $html, $config);

		$output = tidy_parse_string( $html, $config, $encoding )->value;

		preg_match_all('@src="([^"]+)"@', $output, $src);

		$preload_list = array_pop($src);

		foreach( $preload_list as $s ) {

			self::$lazyList[] = $s;


		}

		$output = self::cleanXSS(

			preg_replace( 

				[

					'/\s?<iframe[^>]*?>.*?<\/iframe>\s?/si',
					'/\s?<style[^>]*?>.*?<\/style>\s?/si',
					'/\s?<script[^>]*?>.*?<\/script>\s?/si',
					'#\son\w*="[^"]+"#',

				],

				[
					'',
					'',
					''
				],

				$output

			)

		);

		if( (bool)$lazy ) {

			$output = str_ireplace(

				[
					' src', 
					'<img '

				], 

				[
					' data-src', 
					'<img src="/Interface/preloader.svg" '

				], $output

			);

		}

		return $output;

	}

	// Conver #hash to metahash link
	public static function metaHash( string $s ): string {

		return preg_replace('/\#(.*?)((\s|[[:punct:]])|$)/', '<a class="metahash" data-hash="$1">#$1</a>$2', $s);

	}

	// Convert meta link to hash tag
	public static function processMeta( string $s ): string {

		return preg_repalce('/(?<=class=\"metahash\"\>).*(?=\<\/a\>)/','', $s);

	}

	// Crop snippets and returns correct markup
	protected static function crop(string $s, int $l): string {

		if( !(bool)$l ) {

			return $s;

		}

		$xtext = substr( $s, $l );

		return strpos( $xtext, '>' ) !== null ? substr( $s, 0, $l + strpos( $xtext, '>' ) + 1 ) : substr( $s, 0, $l );

	}

	protected static function isHTML( ?string $string ): ?bool {

		return $string != strip_tags( $string ) ? true : null;

	}

	protected static function hexToSymbols( string $s ): string {

		return html_entity_decode($s, ENT_XML1, 'UTF-8');

	}

	protected static function escape( string $s, string $m = 'attr' ): string {

		preg_match_all('/data:\w+\/([a-zA-Z]*);base64,(?!_#_#_)([^)\'"]*)/mi', $s, $b64, PREG_OFFSET_CAPTURE);

		if( count( array_filter( $b64 ) ) > 0 ) {

			switch( $m ) {

				case 'attr': 

					$xclean = self::cleanXSS( 

										urldecode( 

											base64_decode(

												$b64[ 2 ][ 0 ][ 0 ]

											)

										)

								);

					break;

				case 'tag':

					$xclean = self::cleanTagInnerXSS( 

										urldecode(

											base64_decode(

												$b64[ 2 ][ 0 ][ 0 ]

											)

										)

								);

					break;

			}

			return substr_replace(

				$s,

				'_#_#_'. base64_encode( $xclean ), 

				$b64[ 2 ][ 0 ][ 1 ],

				strlen( $b64[ 2 ][ 0 ][ 0 ] )

			);

		}
		else {

			return $s;

		}

	}

	protected static function cleanXSS( string $s ): string {

		// base64 injection prevention
		$st = self::escape( $s, 'attr' );

		return preg_replace([

				// JSON unicode
				'/\\\\u?{?([a-f0-9]{4,}?)}?/mi',												 // [1] unicode JSON clean

				// Data b64 safe
				'/\*\w*\*/mi',																	 // [2] unicode simple clean

				// Malware payloads
				'/:?e[\s]*x[\s]*p[\s]*r[\s]*e[\s]*s[\s]*s[\s]*i[\s]*o[\s]*n[\s]*(:|;|,)?\w*/mi', // [3]  (:expression) evalution
				'/l[\s]*i[\s]*v[\s]*e[\s]*s[\s]*c[\s]*r[\s]*i[\s]*p[\s]*t[\s]*(:|;|,)?\w*/mi',	 // [4]  (livescript:) evalution
				'/j[\s]*s[\s]*c[\s]*r[\s]*i[\s]*p[\s]*t[\s]*(:|;|,)?\w*/mi', 					 // [5]  (jscript:) evalution
				'/j[\s]*a[\s]*v[\s]*a[\s]*s[\s]*c[\s]*r[\s]*i[\s]*p[\s]*t[\s]*(:|;|,)?\w*/mi', 	 // [6]  (javascript:) evalution
				'/b[\s]*e[\s]*h[\s]*a[\s]*v[\s]*i[\s]*o[\s]*r[\s]*(:|;|,)?\w*/mi',				 // [7]  (behavior:) evalution
				'/v[\s]*b[\s]*s[\s]*c[\s]*r[\s]*i[\s]*p[\s]*t[\s]*(:|;|,)?\w*/mi',				 // [8]  (vsbscript:) evalution
				'/v[\s]*b[\s]*s[\s]*(:|;|,)?\w*/mi',											 // [9]  (vbs:) evalution
				'/e[\s]*c[\s]*m[\s]*a[\s]*s[\s]*c[\s]*r[\s]*i[\s]*p[\s]*t*(:|;|,)?\w*/mi',		 // [10] (ecmascript:) possible ES evalution
				'/b[\s]*i[\s]*n[\s]*d[\s]*i[\s]*n[\s]*g*(:|;|,)?\w*/mi',						 // [11] (-binding) payload
				'/\+\/v(8|9|\+|\/)?/mi',														 // [12] (UTF-7 mutation)

				// Some entities
				'/&{\w*}\w*/mi',																 // [13] html entites clenup
				'/&#\d+;?/m', 																	 // [14] html entites clenup

				// Script tag encoding mutation issue 
				'/\¼\/?\w*\¾\w*/mi', 															 // [21] mutation KOI-8
				'/\+ADw-\/?\w*\+AD4-\w*/mi',													 // [22] mutation old encodings

				'/\/*?%00*?\//m',

				// base64 escaped
				'/_#_#_/mi',																	 // [23] base64 escaped marker cleanup
				
			],

			// Replacements steps :: 23
			['&#x$1;', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''], 

			str_ireplace(

				['\u0', '&colon;', '&tab;', '&newline;'], 
				['\0', ':', '', ''], 

			// U-HEX prepare step
			self::hexToSymbols( $st ))

			);

	}

}

?>
