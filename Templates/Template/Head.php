
<head>

	<meta charset="utf-8" />

	<meta name="viewport" content="initial-scale=1, maximum-scale=1" />

	<meta name="description" content="<?php print $RKV->descr .' | '. $brand; ?>" />
	<meta name="host" content="<?php print $host; ?>" />

	<meta property="og:site_name" content="<?php print $RKV->brand; ?>" />
	<meta property="og:type" content="website" />

	<meta property="og:locale" content="<?php print main_language; ?>" />
	<meta property="og:title" content="<?php print $RKV->title; ?>" />
	<meta property="og:description" content="<?php print $RKV->descr; ?>" />

	<meta property="og:url" content="<?php print $host; ?>" />

	<title><?php print $title ?></title>

	<?php if( $RKV->installed ): ?>

	<link rel="alternate" type="application/atom+xml" href="/aggregator/" />

	<?php endif; ?>

	<?php $f = 0; foreach( $istyles as $css ) {

		if( $f++ > 0 ) {

			$RKI->Template::$b[] = '	'. $css;			

		}

		$RKI->Template::$b[] = $css ."\n";

	}

	$RKI->Template::print();

	?>

	<link rel="icon mask-icon shortcut apple-touch-icon" type="image/png" sizes="any" href="/favicon.ico" />
	<link rel="manifest" href="/manifest/?revolver_cmf">

	<meta name="generator" content="RevolveR CMF v.<?php print rr_version; ?>" />

</head>
