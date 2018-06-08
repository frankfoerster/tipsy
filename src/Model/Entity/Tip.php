<?php

namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Class Tip
 *
 * @property int id
 * @property int game_id
 * @property int user_id
 * @property int result1
 * @property int result2
 * @property FrozenTime created
 * @property Game game
 * @property User user
 */
class Tip extends Entity
{

}
