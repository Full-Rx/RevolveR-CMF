

<?php if( $RKV->request !== '/' ): ?>

<div class="revolver__breadcrumb">

	<h6 class="revolver__meta-header">Breadcrumb</h6>

	<?php require('Breadcrumb.php') ?>

</div>

<?php endif;?>


<!-- RevolveR :: footer -->
<footer class="revolver__footer <?php print $auth_class; ?>">

	<p><a href="https://revolvercmf.ru" title="RevolveR CMF by RevolveR Labs powered" target="_blank">RevolveR CMF</a> | <?php print $title; ?> | <span><?php print date('Y'); ?></span></p>

</footer>
