<?php

namespace App\Model\Table;

use App\Model\Entity\Game;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;

/**
 * Class GamesTable
 *
 * @property TeamsTable Team1
 * @property TeamsTable Team2
 * @property TipsTable Tips
 * @property TipsTable UserTip
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

        $this->hasOne('UserTip', [
            'className' => 'Tips',
            'foreign_key' => 'game_id'
        ]);
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

    /**
     * Find all games for the vuex store.
     *
     * @return \Cake\Collection\CollectionInterface
     */
    public function findForStore()
    {
        $games = $this->find()
            ->select([
                'id',
                'team1_id',
                'team2_id',
                'result1',
                'result2',
                'team1_note',
                'team2_note',
                'playing_time',
                'is_preliminary',
                'is_last_sixteen',
                'is_quarter_final',
                'is_semi_final',
                'is_game_for_3rd_place',
                'is_final'
            ]);

        $winCase = $games->newExpr()
            ->addCase(
                [
                    // win
                    $games->newExpr()->add([
                        'UserTip.result1 > UserTip.result2'
                    ]),
                    $games->newExpr()->add([
                        'UserTip.result1 <= UserTip.result2',
                    ])
                ],
                [1, 0],
                ['integer', 'integer']
            );

        $drawCase = $games->newExpr()
            ->addCase(
                [
                    // draw
                    $games->newExpr()->add([
                        'UserTip.result1 = UserTip.result2'
                    ]),
                    $games->newExpr()->add([
                        'UserTip.result1 <> UserTip.result2',
                    ])
                ],
                [1, 0],
                ['integer', 'integer']
            );

        $loseCase = $games->newExpr()
            ->addCase(
                [
                    // lose
                    $games->newExpr()->add([
                        'UserTip.result1 < UserTip.result2'
                    ]),
                    $games->newExpr()->add([
                        'UserTip.result1 > UserTip.result2',
                    ])
                ],
                [1, 0],
                ['integer', 'integer']
            );

        return $games
            ->select([
                'times_win' => $games->func()->sum($winCase),
                'times_draw' => $games->func()->sum($drawCase),
                'times_lose' => $games->func()->sum($loseCase)
            ])
            ->contain(['UserTip' => function (Query $query) use ($winCase, $drawCase, $loseCase) {
                return $query->select([
                    'game_id',
                ]);
            }])
            ->group('Games.id')
            ->order(['playing_time' => 'asc'])
            ->map(function(Game $entity) {
                if (!empty($entity->playing_time)) {
                    $entity->set('playing_timestamp', $entity->playing_time->getTimestamp());
                }
                return $entity;
            })
            ->combine('id', function (Entity $entity) { return $entity; });
    }

    /**
     * Find user ranking for the vuex store.
     *
     * @return \Cake\Collection\CollectionInterface
     */
    public function findRankingForStore()
    {
        $games = $this->find()
            ->contain([
                'UserTip' => function (Query $query) {
                    return $query
                        ->select([
                            'user_id',
                            'game_id',
                            'result1',
                            'result2'
                        ])
                        ->contain([
                            'Users' => function (Query $query) {
                                return $query->select([
                                    'id',
                                    'username'
                                ]);
                            }
                        ]);
                }
            ]);

        $pointsCase = $games->newExpr()
            ->addCase(
                [
                    // win
                    $games->newExpr()->add([
                        'UserTip.result1 > UserTip.result2',
                        'Games.result1 > Games.result2',
                        'UserTip.result1 != Games.result1 OR UserTip.result2 != Games.result2'
                    ]),
                    // lose
                    $games->newExpr()->add([
                        'UserTip.result1 < UserTip.result2',
                        'Games.result1 < Games.result2',
                        'UserTip.result1 != Games.result1 OR UserTip.result2 != Games.result2'
                    ]),
                    // draw
                    $games->newExpr()->add([
                        'UserTip.result1 = UserTip.result2',
                        'Games.result1 = Games.result2',
                        'UserTip.result1 != Games.result1 OR UserTip.result2 != Games.result2'
                    ]),
                    //exact
                    $games->newExpr()->add([
                        'UserTip.result1 = Games.result1',
                        'UserTip.result2 = Games.result2'
                    ])
                ],
                [1, 1, 1, 3],
                ['integer', 'integer', 'integer', 'integer']
            );

        $games->select(['points' => $pointsCase]);

        $rankings = $games
            ->group('user_id')
            ->having([
                'UserTip.user_id IS NOT NULL'
            ])
            ->select([
                'total_points' => $games->func()->sum($pointsCase)
            ])
            ->order(['total_points' => 'desc'], true)
            ->filter(function (Game $game) {
                return $game->user_tip->user !== null;
            })
            ->combine(
                function (Game $game) {
                    return $game->user_tip->user->id;
                },
                function (Game $game) {
                    $user = $game->user_tip->user;
                    $user->set('total_points', $game->get('total_points'));
                    return $user;
                }
            );

        return $rankings;
    }
}
