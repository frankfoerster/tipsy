<?php

namespace App\Controller\Api;

use App\Model\Entity\User;
use App\Model\Table\TokensTable;
use App\Model\Table\UsersTable;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Mailer\MailerAwareTrait;
use Cake\Utility\Security;

/**
 * Class UserController
 *
 * @property UsersTable Users
 * @property TokensTable Tokens
 */
class UsersController extends ApiAppController
{
    use MailerAwareTrait;

    /**
     * Initialization hook method.
     *
     * @return void
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Users');
        $this->loadModel('Tokens');

        $this->Auth->allow([
            'login',
            'register',
            'lostPassword',
            'resetPassword'
        ]);
    }

    /**
     * Login action
     * POST
     *
     * @return void
     * @throws \Aura\Intl\Exception
     */
    public function login()
    {
        if (!$this->request->is(['post'])) {
            throw new MethodNotAllowedException('Invalid request');
        }

        $user = $this->Users->findByCredentials($this->request);

        if (!$user) {
            throw new UnauthorizedException(__('Wrong email or password.'));
        }

        // Generate auth token.
        $token = Security::hash($user->id . $user->email, 'sha1', true);

        // Add auth token to user and update the session.
        $user->set('token', $token);
        $this->Auth->setUser($user);

        // Respond with the auth token in the Authorization header.
        $this->response = $this->response->withHeader('Authorization', 'Bearer ' . $token);

        $this->set('user', $this->Auth->user());
        $this->set('_serialize', ['user']);
    }

    /**
     * Logout action
     * POST
     *
     * @return void
     * @throws \Aura\Intl\Exception
     */
    public function logout()
    {
        $this->Auth->logout();
        $this->set('message', __('You were logged out.'));
        $this->set('_serialize', ['message']);
    }

    /**
     * Register action
     * POST
     *
     * @throws \Aura\Intl\Exception
     */
    public function register()
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var User $user */
        $user = $this->Users->newEntity();

        if ($this->Users->create($user, $this->request->getData())) {
            $this->getMailer('User')->send('signup', [$user]);
            $this->set('message', __('Your account has been created.'));
        } else {
            $this->set('message', __('Please correct the marked errors.'));
            $this->set('errors', $user->getErrors());
        }

        $this->set('_serialize', ['message', 'errors']);
    }

    /**
     * Lost password action
     * POST
     *
     * @return void
     * @throws \Aura\Intl\Exception
     */
    public function lostPassword()
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $user = $this->Users->findByEmail($this->request->getData('email'));

        if ($user) {
            $token = $this->Tokens->create($user, 'lost-password');
            $this->getMailer('User')->send('lostPassword', [$user, $token]);
        }

        // We always respond positively to avoid email phishing.
        $this->set('message', __('Please check your inbox and follow the instructions in the mail to reset your password.'));
        $this->set('_serialize', 'message');
    }

    /**
     * Reset password action
     * POST
     *
     * @return void
     * @throws \Aura\Intl\Exception
     */
    public function resetPassword()
    {
        if (!$this->request->is('patch')) {
            throw new MethodNotAllowedException();
        }

        $token = $this->request->getData('token');
        if (!$token || !$this->Tokens->exists(['token' => $token, 'used' => false])) {
            throw new BadRequestException(__('Invalid token'));
        }

        $token = $this->Tokens->findUnusedTokenWithUser($token);

        if ($token->hasExpired()) {
            $this->Tokens->useToken($token);
            throw new UnauthorizedException(__('Your token has expired.'));
        }

        $token->user = $this->Users->patchEntity($token->user, $this->request->getData(), ['validate' => 'resetPassword']);
        if ($this->Users->save($token->user)) {
            $this->Tokens->useToken($token);
            $this->set('message', __('Your new password has been saved.'));
        } else {
            $this->set('message', __('Please correct the marked errors.'));
            $this->set('errors', $token->user->getErrors());
        }

        $this->set('_serialize', ['message', 'errors']);
    }
}
