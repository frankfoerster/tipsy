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
 * @property FrozenTime created
 * @property User user
 */
class Token extends Entity
{
    /**
     * Check if the token has expired.
     *
     * @param int $validForHours The number of hours the token is valid after token creation.
     * @return bool
     */
    public function hasExpired($validForHours = 24)
    {
        $tokenTimestamp = $this->created->getTimestamp();
        $currentTimestamp = (new \DateTime())->getTimestamp();

        $differenceInHours = ($currentTimestamp - $tokenTimestamp) / 60 / 60;

        return $differenceInHours > (int)$validForHours;
    }
}
