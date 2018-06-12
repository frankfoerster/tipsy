<?php

namespace App\Mailer\Preview;

use App\Mailer\UserMailer;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use DebugKit\Mailer\MailPreview;

/**
 * Class UserMailerPreview
 *
 * @property UsersTable Users
 */
class UserMailerPreview extends MailPreview
{
    /**
     * Signup email preview
     *
     * @return \Cake\Mailer\Email
     * @throws \Aura\Intl\Exception
     */
    public function signup()
    {
        /** @var UserMailer $mailer */
        $mailer = $this->getMailer('User');

        $this->loadModel('Users');

        /** @var User $user */
        $user = $this->Users->newEntity([
            'username' => 'test',
            'email' => 'example@example.com'
        ]);

        $token = $this->Users->Tokens->generateToken();

        return $mailer->signup($user, $token);
    }

    /**
     * Verify email preview
     *
     * @return \Cake\Mailer\Email
     * @throws \Aura\Intl\Exception
     */
    public function verify()
    {
        /** @var UserMailer $mailer */
        $mailer = $this->getMailer('User');

        $this->loadModel('Users');

        /** @var User $user */
        $user = $this->Users->newEntity([
            'username' => 'test',
            'email' => 'example@example.com'
        ]);

        $token = $this->Users->Tokens->generateToken();

        return $mailer->verify($user, $token);
    }

    /**
     * lost password email preview
     *
     * @return \Cake\Mailer\Email
     * @throws \Aura\Intl\Exception
     */
    public function lostPassword()
    {
        /** @var UserMailer $mailer */
        $mailer = $this->getMailer('User');

        $this->loadModel('Users');

        /** @var User $user */
        $user = $this->Users->newEntity([
            'username' => 'test',
            'email' => 'example@example.com'
        ]);

        $token = $this->Users->Tokens->generateToken();

        return $mailer->lostPassword($user, $token);
    }
}
