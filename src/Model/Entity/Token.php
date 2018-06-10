<?php

namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Class Token
 *
 * @property int id
 * @property int user_id
 * @property string token
 * @property string type
 * @property bool used
 * @property FrozenTime expired
 * @property bool force_expired
 * @property FrozenTime created
 * @property User user
 */
class Token extends Entity
{

}
