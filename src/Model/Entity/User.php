<?php

namespace App\Model\Entity;

use Cake\Auth\PasswordHasherFactory;
use Cake\ORM\Entity;
use Cake\Utility\Hash;

/**
 * Class User
 *
 * @property int id
 * @property int user_group_id
 * @property string username
 * @property string email
 * @property string password
 * @property bool verified
 * @property UserGroup user_group
 */
class User extends Entity
{
    /**
     * Always hash the users password on save calls.
     *
     * @param string $password The password to hash.
     * @return string
     */
    protected function _setPassword($password)
    {
        return PasswordHasherFactory::build('Default')->hash($password);
    }

    /**
     * Check if the user has any rule errors.
     *
     * @return bool
     */
    public function hasRuleErrors()
    {
        $ruleErrorPaths = [
            'email.unique',
            'username.unique'
        ];

        $errors = $this->getErrors();

        foreach ($ruleErrorPaths as $errorPath) {
            if (Hash::get($errors, $errorPath) !== null) {
                return true;
            }
        }

        return false;
    }
}
