<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Class TeamsTable
 *
 * @property TeamGroupsTable TeamGroups
 */
class TeamsTable extends Table
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

        $this->belongsTo('TeamGroups');
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
            ->notEmpty('name', __('Please enter the team name.'))
            ->add('name', [
                'name-minLength' => [
                    'rule' => ['minLength', 3],
                    'message' => __('The team name must contain at least 3 characters.')
                ]
            ])
            ->requirePresence('name', true, __('The team name must be present.'));

        return $validator;
    }
}
