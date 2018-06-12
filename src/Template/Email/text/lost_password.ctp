<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var string $token
 */

use Cake\Core\Configure;

$this->set('title', __('Reset Password'));

echo __(
    'Hello {username},{nl}you recently requested a password reset.{nl}{nl}To reset your password please click the following link.{nl}{nl}{resetPasswordLink}',
    [
        'username' => $user->username,
        'nl' => "\n",
        'resetPasswordLink' => $this->Url->build('/reset-password' . $token, true) . "\n"
    ]
);
