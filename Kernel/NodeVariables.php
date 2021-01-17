<?php

 /*
  * RevolveR CMF Node Variables
  *
  * Compare Node Variables to Model
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

$RNV = (object)[

	'request'	  => RQST,
	'path'		  => PASS,
	'host'	  	=> site_host,
	'lang'	  	=> TRANSLATIONS[ $ipl ],
	'installed'	=> INSTALLED,
	'auth'   		=> Auth

];
