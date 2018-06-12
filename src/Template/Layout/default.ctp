<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Collection\CollectionInterface $games
 * @var \Cake\Collection\CollectionInterface $groups
 * @var \Cake\Collection\CollectionInterface $teams
 * @var \Cake\Collection\CollectionInterface $ranking
 */

use Cake\Core\Configure;

$debug = Configure::read('debug');

$options = [
    'appBaseUrl' => $this->Url->build(['controller' => 'Home', 'action' => 'index']),
    'apiBaseUrl' => $this->Url->build('/api'),
    'appTitle' => Configure::read('appTitle'),
    'authorizationHeader' => Configure::read('authorizationHeader'),
    'backgroundImage' => $this->Url->build('/img/bg/3.jpg'),
    'medalIcon3rd' => $this->Url->build('/img/icons/3rd_place_medal.svg'),
    'trophyIcon' => $this->Url->build('/img/icons/trophy.svg'),
    'games' => $games,
    'groups' => $groups,
    'teams' => $teams,
    'ranking' => $ranking
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Configure::read('appName') . ': ' . Configure::read('appTitle') ?></title>
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
