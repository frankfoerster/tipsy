<?php

namespace App\Model\Table;

use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Hash;

/**
 * Class GroupsTable
 *
 * @property TeamsTable Teams
 */
class GroupsTable extends Table
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
     * Find all groups for the vuex store.
     *
     * @return \Cake\Collection\CollectionInterface
     */
    public function findForStore()
    {
        return $this->find()
            ->select([
                'id',
                'name'
            ])
            ->contain([
                'Teams' => function (Query $query) {
                    return $query->select([
                        'id',
                        'group_id'
                    ]);
                }
            ])
            ->order([
                'name' => 'asc'
            ])
            ->map(function(Entity $entity) {
                if (!empty($entity->get('teams'))) {
                    $entity->set('teams', Hash::extract($entity->get('teams'), '{n}.id'));
                }
                return $entity;
            })
            ->combine('id', function (Entity $entity) { return $entity; });
    }
}
