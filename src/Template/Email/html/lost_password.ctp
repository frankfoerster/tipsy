<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var string $token
 */

$this->set('title', __('Reset Password'));

echo __(
    'Hello {username},{nl}you recently requested a password reset.{nl}{nl}To reset your password please click the following link.{nl}{nl}{resetPasswordLink}',
    [
        'username' => '<b>' . $user->username . '</b>',
        'nl' => '<br>',
        'resetPasswordLink' => $this->Email->bigActionButton(__( 'Reset password'), '/reset-password/' . $token)
    ]
);
