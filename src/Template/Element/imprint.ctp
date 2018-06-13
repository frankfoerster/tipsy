<?php
/**
 * @var \App\View\AppView $this
 */

use Cake\Core\Configure;
?>

<p>
    <strong><?= Configure::read('Imprint.name') ?></strong><br>
    <?= Configure::read('Imprint.street') ?><br>
    <?= Configure::read('Imprint.zip') ?> <?= Configure::read('Imprint.location') ?><br>
    <?= Configure::read('Imprint.country') ?><br><br>
    <?= Configure::read('Imprint.tel') ?>
</p>

<p>
    <?= Configure::read('Imprint.operatorInfo') ?>
</p>

<?= Configure::read('Imprint.content') ?>
