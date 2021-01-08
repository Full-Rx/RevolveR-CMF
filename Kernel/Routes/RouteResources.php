<?php

 /* 
  * 
  * RevolveR Cache Server Worker
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

$cache = [site_host .'/', site_host .'/manifest/'];

print json_encode(

  array_merge( 

    $cache,

    array_merge(

      $RKI->Template::publicResourcesCacheServerResources('style', styles), 
      $RKI->Template::publicResourcesCacheServerResources('script', scripts)

    )

  )

);

define('serviceOutput', [

  'ctype'     => 'application/json',
  'route'     => '/resources/'

]);

?>
