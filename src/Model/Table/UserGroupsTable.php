<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Class UserGroupsTable
 *
 * @property UsersTable Users
 */
class UserGroupsTable extends Table
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

        $this->hasMany('Users');
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
            ->notEmpty('name', __('Please enter the name of the group.'))
            ->requirePresence('name', true, __('The field "name" must be present.'));

        return $validator;
    }
}
