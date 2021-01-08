
<!-- RevolveR :: Ads breadcrumb chunks -->
<?php if( isset(PASS[ 3 ]) ): ?>

<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

	<a itemtype="https://schema.org/WebPage" itemprop="item" itemid="<?php print $RKV->host .'/'. PASS[ 1 ] .'/'; ?>" href="<?php print '/'. PASS[ 1 ] .'/'; ?>" title="<?php print etranslations[ $ipl ][ PASS[ 1 ] ]; ?>" itemscope>
		<b itemprop="name"><?php print etranslations[ $ipl ][ PASS[ 1 ] ]; ?></b>
	</a>

	<meta itemprop="position" content="2" />

</li>

<li>
	<span>›</span>
</li>

<?php endif; ?>
