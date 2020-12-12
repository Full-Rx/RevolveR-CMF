<?php

 /* 
  * 
  * RevolveR Source Minifier compress source
  *
  * supported programming languages sources: 
  *
  * 1]. ECMA Script with RevolveR code style.
  *
  * v.2.0.0.0
  *
  *
  *
  *
  *
  *                   ^
  *                  | |
  *                @#####@
  *              (###   ###)-.
  *            .(###     ###) \
  *           /  (###   ###)   )
  *          (=-  .@#####@|_--"
  *          /\    \_|l|_/ (\
  *         (=-\     |l|    /
  *          \  \.___|l|___/
  *          /\      |_|   /
  *         (=-\._________/\
  *          \             /
  *            \._________/
  *              #  ----  #
  *              #   __   #
  *              \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

final class Minifier {

  public static function minify( string $source ): string {

    return preg_replace([

      '#/\*(?:[^*]*(?:\*(?!/))*)*\*/#', // 1 :: trim multiple lines comments
      '/[^:\'"\\\]\/\/.*/m',            // 2 :: trim single lines comments excepts quoted strings
      '!\s+!',                          // 3 :: trim multiple spaces
      '/\t/',                           // 4 :: trim tabulations
      '/\R/',                           // 5 :: trim line breaks

    ], ['', '', ' ', '', ''], 

    $source);

  }

}

?>
