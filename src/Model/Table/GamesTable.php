<?php

namespace App\Model\Table;

use App\Model\Entity\Game;
use App\Model\UserTipTrait;
use Cake\I18n\FrozenTime;
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
    use UserTipTrait;

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

        return $games
            ->select([
                'times_win' => $games->func()->sum($this->getTipWinCase($games)),
                'times_draw' => $games->func()->sum($this->getTipDrawCase($games)),
                'times_lose' => $games->func()->sum($this->getTipLoseCase($games))
            ])
            ->contain(['UserTip' => function (Query $query) {
                return $query->select([
                    'game_id'
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
     * Find the playing time of the first game.
     *
     * @return bool|FrozenTime
     */
    public function findFirstPlayingTime()
    {
        /** @var Game $game */
        $game = $this->find()
            ->select('playing_time')
            ->order(['playing_time' => 'asc'])
            ->first();

        if (empty($game)) {
            return false;
        }

        return $game->playing_time;
    }
}
