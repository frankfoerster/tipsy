<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $verifyEmailLink
 */

use Cake\Core\Configure;

$this->set('title', __('Verify Email'));

echo __(
    'Hello {username},{nl}you requested a new verification email.{nl}{nl}To verify your email address click the following link.{nl}{nl}{verifyEmailLink}',
    [
        'username' => $user->username,
        'appName' => Configure::read('appName'),
        'nl' => "\n",
        'verifyEmailLink' => $this->Url->build('/verify/' . $token, true) . "\n"
    ]
);
