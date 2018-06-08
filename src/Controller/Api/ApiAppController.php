<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Controller\Component\AuthComponent;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;

/**
 * Class ApiAppController
 *
 * @property AuthComponent Auth
 */
class ApiAppController extends AppController
{
    /**
     * Initialization hook method.
     *
     * @return void
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');

        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'userModel' => 'Users',
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginAction' => [
                'controller' => 'Home',
                'action' => 'unauthorized',
                '_ext' => 'json'
            ],
            'authorize' => ['Controller'],
            'authError' => __('Please login to access this website.')
        ]);
    }

    /**
     * beforeFilter callback
     *
     * - ensure the incoming request is an ajax request
     * - check if a valid user token is provided
     * - if not log out the user
     *
     * @param Event $event
     * @return \Cake\Http\Response|null|void
     * @throws \Aura\Intl\Exception
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        if (!$this->request->is('ajax')) {
            throw new BadRequestException();
        }

        $userId = $this->Auth->user('id');

        if ($userId && !$this->_checkUserToken()) {
            $this->Auth->logout();
            throw new ForbiddenException(__('Invalid authentication token.'));
        }
    }

    /**
     * Check if the user token is valid.
     *
     * @return bool
     */
    protected function _checkUserToken()
    {
        $requestToken = $this->_getRequestToken();

        if (!$requestToken) {
            return false;
        }

        return $requestToken === $this->_getUserToken();
    }

    /**
     * Get the authorization token from the current request.
     *
     * @return array
     */
    protected function _getRequestToken()
    {
        return $this->request->getHeader('Authorization');
    }

    /**
     * Get the stored user token from the session.
     *
     * @return string
     */
    protected function _getUserToken()
    {
        return $this->Auth->user('token');
    }
}
