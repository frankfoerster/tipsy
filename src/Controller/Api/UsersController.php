<?php

namespace App\Controller\Api;

use App\Model\Entity\User;
use App\Model\Table\TokensTable;
use App\Model\Table\UsersTable;
use Cake\Mailer\MailerAwareTrait;

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

        $this->Guardian->allow([
            'login',
            'signup',
            'verify',
            'lostPassword',
            'resetPassword',
            'notFound'
        ]);
    }

    /**
     * Login action
     * POST
     *
     * @return void
     * @throws \Aura\Intl\Exception
     * @throws \Exception
     */
    public function login()
    {
        if (!$this->request->is('post')) {
            $this->_respondWithMethodNotAllowed();
            return;
        }

        $login = $this->request->getData('login');
        $password = $this->request->getData('password');

        $user = $this->Users->findByCredentials($login, $password);

        if ($user) {
            $this->Guardian->authenticate($user);

            // Respond with the auth token in the Authorization header.
            $this->response = $this->response->withHeader('Authorization', 'Bearer ' . $this->Guardian->user('token.token'));

            $this->set('user', [
                'username' => $user->username,
                'email' => $user->email,
                'verified' => $user->verified,
                'tips' => $this->Users->Tips->findTipsForUser($user->id)
            ]);
        } else {
            $this->set('message', __('Wrong email/username or password.'));
            $this->_respondWithUnauthorized();
        }

        $this->set('_serialize', ['user', 'message']);
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
        if (!$this->request->is('post')) {
            $this->_respondWithMethodNotAllowed();
            return;
        }

        $this->Guardian->logout();
        $this->set('message', __('You were logged out.'));
        $this->set('_serialize', ['message']);
    }

    /**
     * Info action
     * GET
     *
     * @return void
     */
    public function info()
    {
        if (!$this->request->is('get')) {
            $this->_respondWithMethodNotAllowed();
            return;
        }

        $user = $this->Guardian->user();

        $this->set('user', [
            'username' => $user->username,
            'email' => $user->email,
            'verified' => $user->verified,
            'tips' => $this->Users->Tips->findTipsForUser($user->id)
        ]);
        $this->set('_serialize', ['user']);
    }

    /**
     * Signup action
     * POST
     *
     * @throws \Aura\Intl\Exception
     * @throws \Exception
     */
    public function signup()
    {
        if (!$this->request->is('post')) {
            $this->_respondWithMethodNotAllowed();
            return;
        }

        /** @var User $user */
        $user = $this->Users->newEntity();

        if ($this->Users->create($user, $this->request->getData())) {
            $token = $this->Users->Tokens->create($user, TokensTable::TYPE_VERIFY_EMAIL, 'P2D');
            $this->getMailer('User')->send('signup', [$user, $token->token]);
            $this->set('message', __('Your account has been created.'));
        } else {
            $this->set('message', __('Please correct the marked errors.'));
            $this->set('errors', $user->getErrors());
            if ($user->hasRuleErrors()) {
                $this->_respondWithConflict();
            } else {
                $this->_respondWithValidationErrors();
            }
        }

        $this->set('_serialize', ['message', 'errors']);
    }

    /**
     * Verify action
     * POST
     */
    public function verify()
    {
        if (!$this->request->is('post')) {
            $this->_respondWithMethodNotAllowed();
            return;
        }

        $token = $this->request->getData('token');
        if (empty($token)) {
            $this->_respondWithBadRequest();
            return;
        }

        $user = $this->Users->findByToken($token, TokensTable::TYPE_VERIFY_EMAIL);
        if (empty($user)) {
            $this->set('message', 'This verification url is no longer valid.');
            $this->set('_serialize', ['message']);
            $this->_respondWithBadRequest();
            return;
        }

        $this->Users->verify($user->id);
        $this->Users->Tokens->expireToken($token);

        $this->set('message', 'Your email address has been verified.');
        $this->set('_serialize', ['message']);
    }

    /**
     * Request a new verification email.
     * POST
     *
     * @return void
     * @throws \Exception
     */
    public function requestVerificationEmail()
    {
        if (!$this->request->is('post')) {
            $this->_respondWithMethodNotAllowed();
            return;
        }

        $user = $this->Guardian->user();
        if (!$user->verified) {
            $this->Users->Tokens->expireTokens($user->id, TokensTable::TYPE_VERIFY_EMAIL);
            $token = $this->Users->Tokens->create($user, TokensTable::TYPE_VERIFY_EMAIL, 'P2D');
            $this->getMailer('User')->send('verify', [$user, $token->token]);
            $this->set('message', __('Please check your inbox.'));
        } else {
            $this->set('message', __('Your email address is already verified.'));
        }

        $this->set('_serialize', ['message']);
    }

    /**
     * Lost password action
     * POST
     *
     * @return void
     * @throws \Aura\Intl\Exception
     * @throws \Exception
     */
    public function lostPassword()
    {
        if (!$this->request->is('post')) {
            $this->_respondWithMethodNotAllowed();
            return;
        }

        $user = $this->Users->findByEmail($this->request->getData('email'));

        if ($user) {
            $token = $this->Tokens->create($user, TokensTable::TYPE_LOST_PASSWORD);
            $this->getMailer('User')->send('lostPassword', [$user, $token]);
        }

        // We always respond positively to avoid email phishing.
        $this->set('message', __('Please check your inbox and follow the instructions in the mail to reset your password.'));
        $this->set('_serialize', 'message');
    }

    /**
     * Reset password action
     * PATCH
     *
     * @return void
     * @throws \Aura\Intl\Exception
     */
    public function resetPassword()
    {
        if (!$this->request->is('patch')) {
            $this->_respondWithMethodNotAllowed();
            return;
        }

        // @TODO

        $token->user = $this->Users->patchEntity($token->user, $this->request->getData(), ['validate' => 'resetPassword']);
        if ($this->Users->save($token->user)) {
            $this->Tokens->useToken($token);
            $this->set('message', __('Your new password has been saved.'));
        } else {
            $this->set('message', __('Please correct the marked errors.'));
            $this->set('errors', $token->user->getErrors());
        }

    /**
     * NotFound action
     * ANY
     *
     * Default api action that is called when no other api route matched.
     *
     * @return void
     */
    public function notFound()
    {
        $this->set('message', 'API endpoint "' . $this->request->getUri()->getPath() . '" does not exist.');
        $this->set('_serialize', ['message']);
        $this->_respondWithNotFound();
    }
}
