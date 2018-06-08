<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;

/**
 * Class GamesTable
 *
 * @property TeamsTable Team1
 * @property TeamsTable Team2
 * @property TipsTable Tips
 */
class GamesTable extends Table
{
    /**
     * Initialization hook method.
     *
     * @param array $config
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->belongsTo('Team1', [
            'className' => 'Teams',
            'foreignKey' => 'team1_id'
        ]);

        $this->belongsTo('Team2',   [
            'className' => 'Teams',
            'foreignKey' => 'team2_id'
        ]);

        $this->hasMany('Tips');
    }

    /**
     * Find all games including team names.
     *
     * @param Query $query
     * @return array|Query
     */
    public  function findWithTeamNames(Query $query)
    {
        return $query
            ->contain([
                'Team1' => function (Query $query) {
                    return $query->select(['name']);
                },
                'Team2' => function (Query $query) {
                    return $query->select(['name']);
                }
            ]);

    }
}
