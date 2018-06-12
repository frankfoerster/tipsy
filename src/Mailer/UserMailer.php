<?php

namespace App\Mailer;

use App\Model\Entity\User;
use Cake\Core\Configure;
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
     * @param string $token
     * @return \Cake\Mailer\Email
     * @throws \Aura\Intl\Exception
     */
    public function signup(User $user, $token)
    {
        return $this
            ->_prepareEmail($user, __('Welcome to {appName}', ['appName' => Configure::read('appName')]))
            ->set([
                'user' => $user,
                'token' => $token,
                'instanceName' => 'Tipsy - ' . Configure::read('appTitle')
            ]);
    }

    /**
     * verify email
     *
     * @param User $user
     * @param string $token
     * @return \Cake\Mailer\Email
     * @throws \Aura\Intl\Exception
     */
    public function verify(User $user, $token)
    {
        return $this
            ->_prepareEmail($user, __('{appName}: Verify your email address', ['appName' => Configure::read('appName')]))
            ->set([
                'user' => $user,
                'token' => $token,
                'instanceName' => 'Tipsy - ' . Configure::read('appTitle')
            ]);
    }

    /**
     * lost password email
     *
     * @param User $user
     * @param string $token
     * @return \Cake\Mailer\Email
     * @throws \Aura\Intl\Exception
     */
    public function lostPassword(User $user, $token)
    {
        return $this
            ->_prepareEmail($user, __('{appName}: Reset Password', ['appName' => Configure::read('appName')]))
            ->set([
                'user' => $user,
                'token' => $token,
                'instanceName' => 'Tipsy - ' . Configure::read('appTitle')
            ]);
    }

    /**
     * Prepare the UserMailer Email instance.
     *
     * @param User $user The user entity.
     * @param string $subject The subject for email.
     * @return \Cake\Mailer\Email
     */
    protected function _prepareEmail(User $user, $subject)
    {
        return $this
            ->setTransport('default')
            ->setEmailFormat('both')
            ->setFrom(Configure::read('appMail'), Configure::read('appName'))
            ->setTo($user->email)
            ->setSubject($subject);
    }
}
