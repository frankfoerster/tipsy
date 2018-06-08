<?php

namespace App\Mailer;

use App\Model\Entity\User;
use Cake\Mailer\Mailer;

/**
 * Class UserMailer
 */
class UserMailer extends Mailer
{
    /**
     * signup email
     *
     * @param User $user
     */
    public function signup(User $user)
    {
        $this
            ->to($user->email)
            ->setSubject(sprintf('Welcome %s', $user->username))
            ->set('user', $user);
    }

    /**
     * lost password email
     *
     * @param User $user
     * @param $hash
     */
    public function lostPassword(User $user, $hash)
    {
        $this
            ->to($user->email)
            ->setSubject(sprintf('Welcome %s', $user->username))
            ->set('hash', $hash);
    }
}
