<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var string $token
 */

use Cake\Core\Configure;

$this->set('title', __('Verify Email'));

echo __(
    'Hello {username},{nl}before you can start using {appName}, you have to verify your email address. To verify your email address click the following link.{nl}{nl}{verifyEmailLink}',
    [
        'username' => '<b>' . $user->username . '</b>',
        'appName' => Configure::read('appName'),
        'nl' => '<br>',
        'verifyEmailLink' => $this->Email->bigActionButton(__( 'Verify Email'), '/verify/' . $token)
    ]
);
