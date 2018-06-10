<?php
/**
 * @var \App\View\AppView $this
 * @var string $instanceName
 */

$nl = "\n";

?>
--------------------------------------------<?= $nl ?>
<?= $this->get('instanceName') ?><?= $nl ?>
<?= $this->get('title') ?><?= $nl ?>
--------------------------------------------<?= $nl ?>
<?= $nl ?>
<?php echo $this->fetch('content') ?><?= $nl ?>
<?= $nl ?>
<?= __('Kind regards') ?>,<?= $nl ?>
<?= $instanceName ?><?= $nl ?>
<?= $this->Email->Url->build('/', true) ?>
