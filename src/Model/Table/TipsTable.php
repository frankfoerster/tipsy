<?php

namespace App\Model\Table;

use App\Model\Entity\Game;
use App\Model\Entity\Tip;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
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
                    'rule' => ['naturalNumber', true],
                    'message' => __('Your tip must be a positive number.'),
                ]
            ])
            ->add('result2', [
                'number' => [
                    'rule' => ['naturalNumber', true],
                    'message' => __('Your tip must be a positive number.'),
                ]
            ]);

        return $validator;
    }

    /**
     * Rules that need to pass when saving a tip.
     *
     * @param RulesChecker $rules
     * @return RulesChecker|\Cake\ORM\RulesChecker
     * @throws \Aura\Intl\Exception
     */
    public function buildRules(RulesChecker $rules)
    {
        // Add a rule that is applied for create and update operations
        $rules->add([$this, 'isVotingAllowed'], 'is-voting-allowed', [
            'errorField' => 'game_id',
            'message' => __('You can only vote until 15 minutes prior to the start of the game.')
        ]);

        return $rules;
    }

    /**
     * Find all tips of all games for the given $userId.
     *
     * @param int $userId
     * @return \Cake\Collection\CollectionInterface
     */
    public function findTipsForUser($userId)
    {
        $gamesWithUserTip = $this->Games->find()
            ->select(['id'])
            ->contain([
                'UserTip' => function(Query $query) use ($userId) {
                    $query
                        ->select([
                            'UserTip.id',
                            'UserTip.game_id',
                            'UserTip.result1',
                            'UserTip.result2',
                        ]);

                    if ($userId !== null) {
                        $query->where(['user_id' => $userId]);
                    }

                    return $query;
                }
            ])
            ->order(['Games.id' => 'asc'])
            ->combine(
                function (Game $game) {
                    return $game->id;
                },
                function (Game $game) {
                    if ($game->user_tip === null) {
                        $game->user_tip = $this->newEntity([
                            'game_id' => $game->id,
                            'result1' => null,
                            'result2' => null,
                            'voted' => false
                        ], ['validate' => false]);
                    } else {
                        $game->user_tip->unsetProperty(['id', 'user_id']);
                        $game->user_tip->set('voted', true);
                    }
                    return $game->user_tip;
                }
            );

        return $gamesWithUserTip;
    }

    /**
     * Find an existing tip for the given $userId and $gameId.
     *
     * @param int $userId
     * @param int $gameId
     * @return array|Tip|null
     */
    public function findExisting($userId, $gameId)
    {
        return $this->find()
            ->select([
                'id'
            ])
            ->where([
                'user_id' => $userId,
                'game_id' => $gameId
            ])->first();
    }

    /**
     * Check if voting is allowed on the given game.
     *
     * @param Tip $tip
     * @return bool
     */
    public function isVotingAllowed(Tip $tip)
    {
        /** @var Game $game */
        $game = $this->Games->find()
            ->select(['playing_time'])
            ->where(['id'=> $tip->game_id])
            ->first();

        $currentDateTime = new \DateTime();
        $minutesToStartOfGame = ($game->playing_time->getTimestamp() - $currentDateTime->getTimestamp()) / 60;
        $votingAllowed = ($minutesToStartOfGame > 15);

        return $votingAllowed;
    }
}
