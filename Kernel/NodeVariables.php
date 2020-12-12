<?php

 /*
  * RevolveR CMF Node Variables
  *
  * Compare Node Variables to Model
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

$RNV = (object)[

	'request'	  => RQST,
	'path'		  => PASS,
	'host'	  	=> site_host,
	'lang'	  	=> TRANSLATIONS[ $ipl ],
	'installed'	=> INSTALLED,
	'auth'   		=> Auth

];
