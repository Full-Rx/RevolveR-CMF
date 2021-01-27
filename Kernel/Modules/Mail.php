<?php
 
 /*
  * 
  * RevolveR eMail class
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

final class eMail {

	protected static $escape;
	protected static $from;

	public static $status = null;

	function __construct() {

		self::$escape = new Markup();

		self::$from = default_email;

	}

	public static function send( string $to, string $subject, string $message, ?iterable $attachments = null ): void {

		// Unique parts boundaries
		$main_m = 'RevolveR_message_parts_'.  md5(str_replace(' ', '', date('l jS \of F Y h i s A')));

		if( self::isEmail(self::$from) && self::isEmail($to) ) {

			$headers  = 'MIME-Version: 1.0' ."\n";

			$headers .= 'Date: '. date('d.m.Y h:i:s') ."\n";
			$headers .= 'Importance: High' ."\n";

			$headers .= 'Return-Path: =?utf-8?B?'. self::encode(self::$from) .'?='. "\n";

			$headers .= 'From: =?utf-8?B?'. self::encode(BRAND) .'?= <'. self::$from .">\n";
			$headers .= 'To: =?utf-8?B?'. self::encode('Subscriber') .'?= <'. $to .">\n";

			$headers .= 'Content-Type: multipart/related; boundary="'. $main_m .'"' ."\n\n"; 

			// Start parts
			$html_template .= '--'. $main_m ."\n";
			$html_template .= 'Content-type: text/html; charset=utf-8' ."\n";
			$html_template .= 'Content-Transfer-Encoding: 8bit' ."\n";
			$html_template .= 'Content-Disposition: inline'. "\n\n";

			$html_template .= '<!DOCTYPE html>' ."\n";
			$html_template .= '<html>' ."\n"; 
			$html_template .= '<head>' ."\n";

			$html_template .= '<meta charset="utf-8" />' ."\n";
			$html_template .= '<title>'. BRAND .'</title>' ."\n";

			$html_template .= '</head>' ."\n";
			$html_template .= '<body style="margin: 0; padding: 0">' ."\n";

			$html_template .= '<div style="width: 90%; margin: 1vw auto; border: .1vw dashed #de4bc7f5; background: transparent linear-gradient(to right, #cedce7 0%, #8faebdd9 100%); border-radius: .2vw; box-shadow: 0 0 .4vw #555">';
			$html_template .= '<div style="display: table; width: 100%; height: 6vw; background: #c3417d30; border-bottom: .1vw dashed #de4bc7f5">';
			$html_template .= '<div style="display: table-cell; vertical-align: middle; padding: 0 2.4vw">';

			$html_template .= '<h1 style="display: inline-block; text-shadow: 0 0 0.2vw #fff; letter-spacing: .3vw; font: bold 2.4vw \'Verdana\'; color: #a080dee6;">'. BRAND .'</h1>';
			$html_template .= '<h2 style="display: inline-block; float: right; letter-spacing: .3vw; font: bold 2.4vw \'Tahoma\'; color: #868686e6;">R</h2>';

			$html_template .= '</div>';
			$html_template .= '</div>';
			$html_template .= '<div style="display: table; width: 100%; height: 6vw; background: transparent linear-gradient(to left, #cedce7 0%, #e4c2d8d9 100%); box-shadow: inset 0 0 .2vw #555; margin: 0 -.16vw 0 -.1vw">';
			$html_template .= '<div style="display: table-cell; vertical-align: middle;">';    

			$html_template .= '<p style="padding: 1.1vw 1vw 0; margin: 0 0 1.4vw; font: normal 1.6vw \'Arial\'; line-height: 1.8vw; text-shadow: 0 0 0.1vw #fff; color: #8a4640;">';

			$html_template .= self::stringifyLimiter($message) ."\n";

			$html_template .= '</p>';

			$html_template .= '</div>';
			$html_template .= '</div>';
			$html_template .= '<div style="display: table; width: 100%; height: 6vw; background: #c3417d30; border-top: .1vw dashed #de4bc7f5">';
			$html_template .= '<div style="display: table-cell; vertical-align: middle; text-align: center">';

			$html_template .= '[ <a style="border-bottom: .1vw dotted #b00000eb; text-decoration: none; text-transform: capitalize; font: normal 1.6vw sans-serif; color: #9a5858eb;" href="'. site_host .'" target="_blank">'. BRAND .'</a> ]';

			$html_template .= '</div>';
			$html_template .= '</div>';
			$html_template .= '</div>';

			$html_template .= '</body>' ."\n";
			$html_template .= '</html>' ."\n";

			// Attachements
			if( is_array($attachments) ) {

				foreach( $attachments as $a ) {

					$file = file_get_contents($a, true);

					if( $file ) {

						foreach( allowed_uploads as $attachement ) {

							if( pathinfo(basename($a), PATHINFO_EXTENSION) === $attachement['extension'] ) {

								// Start attachements parts
								$html_template .= '--'. $main_m ."\n";
								$html_template .= 'Content-Type: application/octet-stream; name='. basename($a) ."\n"; 
								$html_template .= 'Content-Description: '. basename($a) ."\n";
								$html_template .= 'Content-Transfer-Encoding: base64' . "\n";
								$html_template .= 'Content-Disposition: attachment; size='. strlen($file) .'; filename='. basename($a) ."\n\n";
								$html_template .= chunk_split(base64_encode($file), 68) ."\n";

							}

						}

					}

				}

			}

			// End of parts
			$html_template .= '--'. $main_m .'--';

			self::$status = mail($to, '=?utf-8?B?'. self::encode($subject) .'?=', $html_template, $headers, '-f '. self::$from);

		}

	}

	protected static function stringifyLimiter( string $message ): string {

		return 

			str_replace(

				[

					'<p>',
					'target="_blank"'

				],

				[

					'<p style="padding: 1.1vw 1vw 0; margin: 0 0 1.4vw; font: normal 1.6vw \'Arial\'; line-height: 1.8vw; text-shadow: 0 0 0.1vw #fff; color: #8a4640;">',
					'target="_blank" style="border-bottom: .1vw dotted #b00000eb; text-decoration: none; text-transform: capitalize; font: normal 1.6vw \'sans-serif\'; color: #9a5858eb;"'

				],

				self::$escape::Markup( 

					htmlspecialchars_decode(

						html_entity_decode(

							$message

						)

					)

				)

			);

	}

	protected static function encode( string $s ): string {

		$result = self::$escape::Markup( 

			htmlspecialchars_decode( 

				html_entity_decode( 

					$s

				)

			)

		);

		return base64_encode( $result );

	}

	protected static function isEmail( string $email ): ?string {

		return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;

	}

}

?>
