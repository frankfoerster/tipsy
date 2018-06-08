<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Class TipsTable
 *
 * @property GamesTable Games
 * @property UsersTable Users
 */
class TipsTable extends Table
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

        $this->belongsTo('Games');
        $this->belongsTo('Users');

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
            ->requirePresence('user_id', 'create', __('The field "user_id" must be present.'))
            ->requirePresence('game_id', 'create', __('The field "game_id" must be present.'))
            ->requirePresence('result1', true, __('The field "result1" must be present.'))
            ->requirePresence('result2', true, __('The field "result2" must be present.'))

            ->notEmpty('result1', __('Please enter your tip.'))
            ->notEmpty('result2', __('Please enter your tip.'))

            ->add('result1', [
                'number' => [
                    'rule' => ['numeric'],
                    'message' => __('Your tip must be a number.'),
                ]
            ])
            ->add('result2', [
                'number' => [
                    'rule' => ['numeric'],
                    'message' => __('Your tip must be a number.'),
                ]
            ]);

        return $validator;
    }
}
