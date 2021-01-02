<?php

 /* 
  * 
  * RevolveR Secure Variables Dispatch
  *
  * v.2.0.0.0
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

final class SecureVariablesDispatcher {

	public static $file_descriptors = [

		'wav' => [

			'mime'			=> 'audio/wav',
			'extension'		=> 'wav',
			'max-size'		=> '10000000',
			'description'	=> 'Audio file',

			'identity' 		=> [

				'U' => 'ffd8',

			]

		],

		'jpg' => [

			'mime'			=> 'image/jpeg',
			'extension'		=> 'jpg',
			'max-size'		=> '10000000',
			'description'	=> 'Photo file',

			'identity' 		=> [

				'U' => 'ffd8',

			]

		],

		'jpeg' => [

			'mime'			=> 'image/jpeg',
			'extension'		=> 'jpeg',
			'max-size'		=> '10000000',
			'description'	=> 'Photo file',

			'identity' 		=> [

				'U' => 'ffd8',

			]

		],

		'png' => [

			'mime'			=> 'image/png',
			'extension'		=> 'png',
			'max-size'		=> '50000000',
			'description'	=> 'Image with tranceparencity support',

			'identity' 		=> [

				'U' => '8950',

			]

		],

		'webp' => [

			'mime'			=> 'image/webp',
			'extension'		=> 'webp',
			'max-size'		=> '3000000',
			'description'	=> 'Google Image file',

			'identity' 		=> [

				'U' => '5249',

			]

		],

		'pdf' => [

			'mime'		=> 'application/pdf',
			'extension' => 'pdf',
			'max-size'	=> '1500000',
			'description' => 'Portable Document Format file',

			'identity' 		=> [

				'U' => '2550',

			]

		],

		'doc' => [

			'mime'			=> 'application/msword',
			'extension' 	=> 'doc',
			'max-size'		=> '500000',
			'description' 	=> 'MS Word document',
			'identity' 		=> [

				'U' => 'd0cf',

			]

		],

		'docx' => [

			'mime'			=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'extension'		=> 'docx',
			'max-size'		=> '500000',
			'description'	=> 'MS Word document',
			'identity' 		=> [

				'U' => '504b',

			]

		],

		'rtf' => [

			'mime'			=> 'application/rtf',
			'extension'		=> 'rtf',
			'max-size'		=> '800000',
			'description'	=> 'Rich Text document',

			'identity'		=> [

				'U' => '7b5c',

			]

		],

		'xls' => [

			'mime'		=> 'application/vnd.ms-excel',
			'extension' => 'xls',
			'max-size'	=> '500000',
			'description' => 'MS Excel document',

			
			'identity'		=> [

				'U' => '7b5c',

			]

		],

		'xlsx' => [

			'mime'		=> 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'extension' => 'xlsx',
			'max-size'	=> '500000',
			'description' => 'MS Excel document'

		],

		'ppt' => [

			'mime'		=> 'application/vnd.ms-powerpoint',
			'extension' => 'ppt',
			'max-size'	=> '10000000',
			'description' => 'MS PowerPoint document'

		],

		'pptx' => [

			'mime'		=> 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'extension' => 'pptx',
			'max-size'	=> '10000000',
			'description' => 'MS PowerPoint document'

		],

		'odt' => [

			'mime'      => 'application/vnd.oasis.opendocument.text',
			'extension' => 'odt',
			'max-size'	=> '10000000',
			'description' => 'OpenDocument Text file',

			'identity'		=> [

				'U' => '504b',

			]

		],

		'zip' => [

			'mime'		=> 'application/zip',
			'extension' => 'zip',
			'max-size'	=> '150000000',
			'description' => 'Archived files',

			'identity'		=> [

				'U' => '504b',

			]

		],

		'tar' => [

			'mime'		=> 'application/tar',
			'extension' => 'tar',
			'max-size'	=> '150000000',
			'description' => 'Archived files',

			'identity'		=> [

				'U' => '7461',

			]
 
		],

		'tar.gz' => [

			'mime'		=> 'application/tar+gzip',
			'extension' => 'gz',
			'max-size'	=> '150000000',
			'description' => 'Archived files',

			'identity'		=> [

				'U' => '1f8b',

			]

		],

		'txt' => [

			'mime'		=> 'text/plain',
			'extension' => 'txt',
			'max-size'	=> '500000',
			'description' => 'Text document',

			'identity'		=> [

				'U' => 'd09a',

			]

		]

	];

	public static $notify;

	public static $ssl;

	function __construct( Notifications $notify, ?bool $ssl ) {

		self::$notify = $notify;

		self::$ssl = $ssl;

	}

	public static function get(): iterable {

		$vars = [];

		if( count( $_FILES ) > 0 ) {

			foreach( $_FILES as $F ) {

				if( !(bool)$F['error'] ) {

					$ext = explode('.', self::escape( trim($F['name']) ));

					$isFileValid = self::isValidFile( [$F['tmp_name'], $F['size'], [$F['type'], $ext[count($ext) - 1]], self::escape(trim($F['name']))] );

					if( !(bool)$isFileValid ) {

						self::$notify::set('notice', 'Invalid FILE in POST');

					}

					$vars['f'][ base64_decode( key($_FILES) ) ][] = [

						'name'  => self::escape(trim($F['name'])),
						'size'  => round((int)$F['size'] / 1024, 1), // Kb
						'type'  => explode('/', $F['type']),
						'temp'  => $F['tmp_name'],
						'valid' => $isFileValid

					];

				}

			}

		}

		// [SESSION]
		if( count($_SESSION) > 0 ) {

			foreach( $_SESSION as $sn => $sv ) {

				$vars['s'][ self::escape($sn) ] = self::escape($sv);

			}

		}

		// [COOKIES]
		if( count($_COOKIE) > 0 ) {

			foreach( filter_input_array(INPUT_COOKIE, FILTER_SANITIZE_STRING | FILTER_SANITIZE_FULL_SPECIAL_CHARS | FILTER_SANITIZE_ENCODED, FILTER_REQUIRE_ARRAY) as $cn => $cv ) {

				$vars['c'][ str_replace('__RevolveR_', '', self::escape($cn)) ] = self::escape($cv);

			}

		}

		// [POST && GET] variables
		$get_and_post_vars = iterator_to_array(

			self::perform()

		);

		$identity_index = 0;

		$field_name = '';

		foreach( $get_and_post_vars as $r ) {

			$cv = explode('~:::~', $r);

			if( $cv[3] === 'option') {

				$nm = explode( '***', $cv[ 1 ] );

				if( $cv[ 0 ] === 'p' ) {

					$validity = self::isValid( $cv[ 3 ], $cv[ 2 ], $cv[ 4 ] );

					if( !(bool)$validity ) {

						if( $field_name === 'revolver_captcha' ) {

							self::$notify::set('notice', 'Security check not pass');

						}

						if( $cv[ 3 ] !== 'hidden' && $cv[ 3 ] !== 'radio' && $cv[ 3 ] !== 'checkbox' ) {

							self::$notify::set('notice', 'Invalid POST '. $field_name .' :: [ input type '. $cv[ 3 ] .'; length: '. $cv[ 4 ] .' ]', null);

						}

					}

					$field_name = $nm[0];

					$vars[ $cv[ 0 ] ][ $field_name ]['name'] = $field_name;

					$vars[ $cv[ 0 ] ][ $field_name ]['type'] = $cv[ 3 ];

					$vars[ $cv[ 0 ] ][ $field_name ]['value'][ $nm[count($nm) - 1] ] = $cv[ 2 ];

					$vars[ $cv[ 0 ] ][ $field_name ]['valid'] = $validity;

					if( (bool)$validity ) {

						$identity_index++;

					}

					$vars[ $cv[ 0 ] ]['identity']['validity'] = $identity_index === count($get_and_post_vars) ? 1 : 0;

					$vars[ $cv[ 0 ] ]['identity']['validity_count'] = $identity_index;

					$vars[ $cv[ 0 ] ]['identity']['fields_total'] = count($get_and_post_vars);

				}
				else {

					$vars[ $cv[ 0 ] ][ $nm[ 0 ] ]['name'] = $nm[ 0 ];

					$vars[ $cv[ 0 ] ][ $nm[ 0 ] ]['value'][ $nm[ count($nm) - 1] ] = $cv[ 2 ];

				}

			}
			else {

				if( $cv[ 0 ] === 'p' ) {

					$validity = self::isValid($cv[ 3 ], $cv[ 2 ], $cv[ 4 ]);

					if( !(bool)$validity ) {

						if( $field_name === 'revolver_captcha' ) {

							self::$notify::set('notice', 'Security check not pass');

						}

						if( $cv[ 3 ] !== 'hidden' && $cv[ 3 ] !== 'radio' && $cv[ 3 ] !== 'checkbox' ) {

							self::$notify::set('notice', 'Invalid POST '. $field_name .' :: [ input type '. $cv[ 3 ] .'; length: '. $cv[ 4 ] .' ]', null);

						}

					}

					$field_name = $cv[ 1 ];

					$vars[ $cv[ 0 ] ][ $field_name ]['name'] = $field_name;

					$vars[ $cv[ 0 ] ][ $field_name ]['value'] = $cv[ 2 ];

					$vars[ $cv[ 0 ] ][ $field_name ]['type'] = $cv[ 3 ];

					$vars[ $cv[ 0 ] ][ $field_name ]['valid'] = $validity;

					if( (bool)$validity ) {

						$identity_index++;

					}

					$vars[ $cv[ 0 ] ]['identity']['validity'] = $identity_index === count($get_and_post_vars) ? 1 : 0;

					$vars[ $cv[ 0 ] ]['identity']['validity_count'] = $identity_index;

					$vars[ $cv[ 0 ] ]['identity']['fields_total'] = count($get_and_post_vars);

				}
				else {

					$vars[ $cv[ 0 ] ][ $cv[ 1 ] ]['name'] = $cv[ 1 ];

					$vars[ $cv[ 0 ] ][ $cv[ 1 ] ]['value'] = $cv[ 2 ];

				}

			}

		}

		return $vars;

	}

	public static function setCookie( string $name, string $value, string $exp, string $path ): void {

		$cst = 'Set-Cookie: __'. $name .'='. rawurlencode( $value ) .'; Expires='. date('D, d M Y H:i:s', $exp) . 'GMT' .'; Path='. $path .'; Domain='. ltrim('.', $_SERVER['HTTP_HOST']) .';';

		header( self::$ssl ? $cst . ' SameSite=Strict; Secure=true;' : $cst );

	}

	protected static function perform(): iterable {

		$methods = (string)$_SERVER['REQUEST_METHOD'];

		$vars_dl = [];

		if( in_array( $methods, ['POST', 'GET'] ) ) {

			switch( $methods ) {

				case 'POST':

					$post_vars = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING | FILTER_SANITIZE_FULL_SPECIAL_CHARS | FILTER_SANITIZE_ENCODED, FILTER_REQUIRE_ARRAY ) ?? [];

					$vars_dl = [ $post_vars, 'p' ];

					break;

				case 'GET':

					$get_vars = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING | FILTER_SANITIZE_FULL_SPECIAL_CHARS | FILTER_SANITIZE_ENCODED, FILTER_REQUIRE_ARRAY ) ?? [];

					$vars_dl = [ $get_vars, 'g' ]; 

					break;

			}

			$c = 0;

			foreach( $vars_dl[ 0 ] as $v => $f ) {

				if( !empty($f) ) {

					yield $c++ => ( $vars_dl[ 1 ] === 'p' ) ? $vars_dl[ 1 ] .'~:::~'. self::escape( base64_decode($v) ) .'~:::~'. self::escape(urldecode(base64_decode($f))) : $vars_dl[ 1 ] .'~:::~'. self::escape( $v ) .'~:::~'. self::escape( $f );

				}

			}

		}
		else {

			exit('<h1>ACCESS Exception :: method '. $methods .' blocked!</h1>');

		}

	}

	protected static function isValidFile( iterable $f ): int {

		$valid = 0;

		if( isset(self::$file_descriptors[ $f[ 2 ][ 1 ] ]) ) { // check extension is correct

			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { 

				// WINDOWS
				if( (int)self::$file_descriptors[ $f[ 2 ][ 1 ] ]['max-size'] >= (int)$f[ 1 ] ) { // check file size possible

					if( preg_match('/(^[a-zA-Z0-9]+([a-zA-Z\_0-9\.-]*))$/', $f[ 3 ]) ) { // check file name is correct

						$valid = 1;

					}

				}

			} 
			else { 

				if( (int)self::$file_descriptors[ $f[ 2 ][ 1 ] ]['max-size'] >= (int)$f[ 1 ] ) { // check file size possible

					if( preg_match('/(^[a-zA-Z0-9]+([a-zA-Z\_0-9\.-]*))$/', $f[ 3 ]) ) { // check file name is correct

						$valid = 1;

					}

				}

			}

		}

		return $valid;

	}

	protected static function isValid( string $t, string $v, string $l ): int {

		$flag = 0;

		switch( $t ) {

			case 'text':
			case 'radio':
			case 'search':
			case 'hidden':
			case 'option':
			case 'checkbox':
			case 'password':

				$flag = strlen($v) > 0 ? 1 : 0;

				break;

			case 'color':

				if( preg_match('/#?([[:xdigit:]]{3}){1,2}\b/i', $v) && strlen($v) > 0 ) {

					$flag = 1;

				}

				break;

			case 'email':

				if( filter_var($v, FILTER_VALIDATE_EMAIL) && strlen($v) > 0 ) {

					$flag = 1;

				}

				break;

			case 'url':

				if( filter_var($v, FILTER_VALIDATE_URL) && strlen($v) > 0 ) {

					$flag = 1;

				}

			break;

			case 'number':
			case 'range':

				if( ( filter_var($v, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND) || !(bool)$v ) && strlen($v) > 0 ) {

					$flag = 1;

				}

				break;

			case 'tel':

				if( preg_match('/^[\+0-9\-\(\)\s]*$/', $v) && strlen($v) > 0 ) {

					$flag = 1;

				}

				break;

			case 'date':

				$d = explode('-', $v);

				if( checkdate((int)$d[ 1 ], (int)$d[ 2 ], (int)$d[ 0 ]) && strlen($v) > 0 ) { 

					$flag = 1;

				}

				break;

			case 'time':

				if( preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $v) && strlen($v) > 0 ) {

					$flag = 1;

				}

				break;

		}

		if( $t === 'text' && (int)$l === -1 ) {

			return 1;

		}
		else {

			return ( (int)$l === -1 || strlen(trim($v)) <= (int)$l ? true : null ) && $flag ? 1 : 0;

		}

	}

	protected static function escape( string $v ): string {

		return trim(

				stripslashes(

					htmlspecialchars( $v )

				)

			);

	}

}

?>
