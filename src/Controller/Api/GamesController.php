<?php

namespace App\Controller\Api;
use App\Model\Table\GamesTable;

/**
 * Class GamesController
 *
 * @property GamesTable Games
 */
class GamesController extends ApiAppController
{
    /**
     * Initialization hook method.
     *
     * @return void
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Games');
    }

    public function index()
    {
        $games = $this->Games->find()->order(['playing_time' => 'asc']);

        $this->set('games', $games);
        $this->set('_serialize', ['games']);
    }
}
