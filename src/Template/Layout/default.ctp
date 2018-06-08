<?php
/**
 * @var \App\View\AppView $this
 */

use Cake\Core\Configure;

$debug = Configure::read('debug');

$options = [
    'appBaseUrl' => $this->Url->build(['controller' => 'Home', 'action' => 'index']),
    'apiBaseUrl' => $this->Url->build('/api'),
    'appTitle' => Configure::read('appTitle')
]

?>
<!DOCTYPE html lang="en">
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('Betz: ') . $this->fetch('title') ?></title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Asset->css('css/app.css') ?>
</head>
<body>
    <div id="app"></div>
    <?= $this->fetch('content') ?>
    <script>
        var AppConfig = <?= json_encode($options) ?>;
    </script>
    <?= $this->Asset->js('js/app' . (!$debug ? '.min' : '') . '.js') ?>
</body>
</html>
