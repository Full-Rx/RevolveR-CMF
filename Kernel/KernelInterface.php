<?php

 /*
  * RevolveR CMF Kernel Interface
  *
  * Reconstruct Kernel Parts to Kernel Interface
  *
  * v.2.0.0.0
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

function KI(

		Language $l,
		Calendar $c,
		Notifications $n,
		Captcha $cp,
		Cipher $cr,
		SecureVariablesDispatcher $d,
		Markup $m,
		Model $xm,
		Auth $a,
		Route $r,
		Menu $mx,
		Node $xn,
		eMail $ma,
		DetectUserAgent $du,
		HTMLFormBuilder $f,
		Conclude $cl

	): object {

	return (object)[

		'Language'  => $l,
		'Calendar'  => $c,
		'Notify'    => $n,
		'Captcha'   => $cp,
		'Cipher'    => $cr,
		'Vars'	    => $d,
		'HTML'    	=> $m,
		'Model'	    => $xm,
		'Auth'	    => $a,
		'Route'     => $r,
		'Menu'	    => $mx,
		'Node'	    => $xn,
		'Email'	    => $ma,
		'UserAgent' => $du,
		'HTMLForm'	=> $f,
		'Template'	=> $cl

	];

}

function installerRKI(

		Markup $m,
		Menu $mx,
		Captcha $cp,
		Conclude $cl

	): object {

	return (object)[

		'Menu'	    => $mx,
		'HTML'    	=> $m,
		'Captcha'   => $cp,
		'Template'	=> $cl

	];

}

if( INSTALLED ) {

	$RKI = KI($lang, $calendar, $notify, $captcha, $cipher, $D, $markup, $model, $auth, $route, $menu, $node, $mail, $uaInfo, $form, $resolve);

} 
else {

	$RKI = installerRKI($markup, $menu, $captcha, $resolve);

}
