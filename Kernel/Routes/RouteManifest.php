<?php

 /* 
  * 
  * RevolveR Route User
  *
  * v.2.0.0.0
  *
  *
  *
  *
  *
  *               ^
  *              | |
  *            @#####@
  *          (###   ###)-.
  *        .(###     ###) \
  *       /  (###   ###)   )
  *      (=-  .@#####@|_--"
  *      /\    \_|l|_/ (\
  *     (=-\     |l|    /
  *      \  \.___|l|___/
  *      /\      |_|   /
  *     (=-\._________/\
  *      \             /
  *        \._________/
  *          #  ----  #
  *          #   __   #
  *          \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */


$manifest = '{
 "short_name": "'. BRAND .'",
 "name": "'. TITLE .'",
 "icons": [
   {
     "src": "/Interface/logo.png",
     "sizes": "16x16",
     "type": "image/png"
   },
   {
     "src": "/Interface/logo.png",
     "sizes": "32x32",
     "type": "image/png"
   },
   {
     "src": "/Interface/logo.png",
     "sizes": "48x48",
     "type": "image/png"
   },
   {
     "src": "/Interface/logo.png",
     "sizes": "72x72",
     "type": "image/png"
   },
   {
     "src": "/Interface/logo.png",
     "sizes": "96x96",
     "type": "image/png"
   },
   {
     "src": "/Interface/logo.png",
     "sizes": "144x144",
     "type": "image/png"
   },
   {
     "src": "/Interface/logo.png",
     "sizes": "192x192",
     "type": "image/png"
   },
   {

     "src": "/Interface/logo.png",
     "sizes": "512x512",
     "type": "image/png"

   }
 ],
 "start_url": "'. $RNV->host .'",
 "background_color": "#7ACCE5",
 "theme_color": "#7ACCE5",
 "orientation": "any",
 "display": "standalone"
}';

print $manifest;

define('serviceOutput', [

  'ctype'     => 'application/json',
  'route'     => '/manifest/'

]);

?>
