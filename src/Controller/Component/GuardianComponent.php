<?php

namespace App\Controller\Component;

use App\Model\Entity\User;
use App\Model\Table\TokensTable;
use App\Model\Table\UsersTable;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Response;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\Table;
use Cake\Utility\Hash;

class GuardianComponent extends Component
{

    /**
     * Holds an array of all allowed actions for the current controller.
     *
     * @var array
     */
    protected $_allowedActions = [];

    /**
     * Holds a reference to the authenticated user.
     *
     * @var User|null
     */
    protected $_user;

    /**
     * Holds a reference to the UsersTable instance.
     *
     * @var UsersTable|null
     */
    protected $_usersTable;

    /**
     * Initialization hook method.
     *
     * @param array $config
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
    }

    /**
     * Callback for Controller.startup event.
     *
     * @param Event $event Event instance.
     * @return Response|null
     * @throws \ReflectionException
     */
    public function startup(Event $event)
    {
        return $this->checkAuth($event);
    }

    /**
     * Check initial authentication and authorization.
     *
     * @param Event $event
     * @return Response|null
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function checkAuth(Event $event)
    {
        $controller = $this->getController();

        $action = strtolower($controller->getRequest()->getParam('action'));
        if (!$controller->isAction($action)) {
            return null;
        }

        if ($this->_isAllowed($action)) {
            return null;
        }

        if ($this->_authenticate() && $this->_isAuthorized()) {
            return null;
        }

        $event->stopPropagation();

        return $controller->getResponse()->withStatus(403);
    }

    /**
     * Allow the given $actions on the current controller.
     *
     * @param array $actions
     * @return void
     */
    public function allow(array $actions = [])
    {
        $this->_allowedActions = array_unique(array_merge($this->_allowedActions, $actions));
    }

    /**
     * Get the current user from storage.
     *
     * @param string|null $key Field to retrieve. Leave null to get entire User record.
     * @return User|mixed|null Either User record or null if no user is logged in, or retrieved field if key is specified.
     */
    public function user($key = null)
    {
        if (!$this->_user) {
            return null;
        }

        if ($key === null) {
            return $this->_user;
        }

        return Hash::get($this->_user, $key);
    }

    /**
     * Log a user out.
     *
     * @return void
     */
    public function logout()
    {
        $this->getModel()->Tokens->expireToken($this->user('token.token'));
    }

    /**
     * Get the table instance.
     *
     * @return UsersTable|Table
     */
    public function getModel()
    {
        if ($this->_usersTable === null) {
            $this->_usersTable = $this->_loadTable();
        }

        return $this->_usersTable;
    }

    /**
     * Authenticate the given $user.
     *
     * @param User $user
     * @throws \Exception
     */
    public function authenticate(User $user)
    {
        // Generate auth token.
        $token = $this->getModel()->Tokens->create($user, TokensTable::TYPE_ACCESS);
        $user->set('token', $token);

        $this->_user = $user;
    }

    /**
     * Load an instance of the UsersTable.
     *
     * @return UsersTable|Table
     */
    protected function _loadTable()
    {
        return (new TableLocator())->get('Users');
    }

    /**
     * Checks whether the given action is accessible without authentication.
     *
     * @param string $action The action to check against.
     * @return bool True if action is accessible without authentication else false.
     */
    protected function _isAllowed($action)
    {
        $action = strtolower($action);

        return in_array($action, array_map('strtolower', $this->_allowedActions));
    }

    /**
     * Authenticate the current request and setup the user.
     *
     * @return bool
     * @throws \Exception
     */
    protected function _authenticate()
    {
        $token = $this->_getToken();
        $user = $this->getModel()->findByToken($token, TokensTable::TYPE_ACCESS);

        if (!$user) {
            return false;
        }

        $user->set('token', Hash::get($user->_matchingData, 'Tokens'));

        $this->getModel()->Tokens->renewTokenExpiration($token);

        $this->_user = $user;

        return true;
    }

    /**
     * Check if the current user is authorized to access the controller action.
     *
     * @return bool
     */
    protected function _isAuthorized()
    {
        return true;
    }

    /**
     * Get the token from the Authorization header of the current request.
     *
     * @return string|null
     */
    protected function _getToken()
    {
        $authorization = $this->getController()->getRequest()->getHeader(Configure::read('authorizationHeader'));
        if (empty($authorization)) {
            return null;
        }

        $token = trim(preg_replace('/^Bearer\s+/i', '', (string)$authorization[0]));
        if (empty($token)) {
            return null;
        }

        return $token;
    }
}
