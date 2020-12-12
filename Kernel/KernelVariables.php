<?php

 /*
  * RevolveR CMF Kernel Variables
  *
  * Compare Kernel Variables to Model
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

$RKV = (object)[

	'request'	=> $RNV->request,
	'path'		=> $RNV->path,
	'host'		=> $RNV->host,
	'lang'		=> $RNV->lang,
	'installed'	=> $RNV->installed,
	'auth'		=> $RNV->auth,
	'xfound'	=> N,
	'brand'		=> $brand,
	'title'		=> $title,
	'descr'		=> $descr

];

