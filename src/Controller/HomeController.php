<?php

namespace App\Controller;
use App\Model\Table\GamesTable;
use App\Model\Table\GroupsTable;
use App\Model\Table\TeamsTable;

/**
 * Class HomeController
 *
 * @property GamesTable Games
 * @property GroupsTable Groups
 * @property TeamsTable Teams
 */
class HomeController extends AppController
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Games');
        $this->loadModel('Groups');
        $this->loadModel('Teams');
    }

    /**
     * Index action
     * GET
     *
     * @return void
     */
    public function index()
    {
        $games = $this->Games->findForStore();
        $groups = $this->Groups->findForStore();
        $teams = $this->Teams->findForStore();
        $ranking = $this->Games->findRankingForStore();

        $this->set([
            'games' => $games,
            'groups' => $groups,
            'teams' => $teams,
            'ranking' => $ranking
        ]);
    }
}
