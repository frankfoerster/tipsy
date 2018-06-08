<?php

namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Class Game
 *
 * @property int id
 * @property int team1_id
 * @property int team2_id
 * @property int result1
 * @property int result2
 * @property FrozenTime playing_time
 * @property bool is_preliminary
 * @property bool is_last_sixteen
 * @property bool is_quarter_final
 * @property bool is_semi_final
 * @property bool is_final
 * @property Team team1
 * @property Team team2
 */
class Game extends Entity
{

}
