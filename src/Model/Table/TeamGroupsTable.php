<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Class TeamGroupsTable
 *
 * @property TeamsTable Teams
 */
class TeamGroupsTable extends Table
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

        $this->hasMany('Teams');
    }

    /**
     * Default validation configuration.
     *
     * @param Validator $validator
     * @return Validator
     * @throws \Aura\Intl\Exception
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->notEmpty('name', __('Please enter a name for the game group.'))
            ->requirePresence('name', true, __('The field "name" must be present.'));

        return $validator;
    }

    /**
     * Find team groups with their corresponding team.
     *
     * @param Query $query
     * @return array|Query
     */
    public  function findWithTeam(Query $query)
    {
        return $query
            ->contain([
                'Teams' => function (Query $query) {
                    return $query->select(['name']);
                },
                'Team2' => function (Query $query) {
                    return $query->select(['name']);
                }
            ]);
    }
}
