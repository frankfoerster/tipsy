<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use App\Controller\Component\GuardianComponent;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;

/**
 * Class ApiAppController
 *
 * @property GuardianComponent Guardian
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
        $this->RequestHandler->renderAs($this, 'json');
        $this->response = $this->response->withType('application/json');

        $this->loadComponent('Guardian');
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
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        if (!$this->request->is('ajax')) {
            throw new BadRequestException();
        }
    }

    /**
     * Respond with the given status $code and the specified $reason.
     *
     * @param int $code
     * @param string $reason
     * @return void
     */
    protected function _respondWith($code = 200, $reason = '')
    {
        $this->response = $this->response->withStatus($code, $reason);
    }

    protected function _respondWithBadRequest()
    {
        $this->_respondWith(400, 'BAD REQUEST');
    }

    protected function _respondWithUnauthorized()
    {
        $this->_respondWith(401, 'UNAUTHORIZED');
    }

    protected function _respondWithNotFound()
    {
        $this->_respondWith(404, 'NOT FOUND');
    }

    protected function _respondWithMethodNotAllowed()
    {
        $this->_respondWith(405, 'METHOD NOT ALLOWED');
    }

    protected function _respondWithValidationErrors()
    {
        $this->_respondWith(422, 'UNPROCESSABLE ENTITY');
    }

    protected function _respondWithConflict()
    {
        $this->_respondWith(409, 'CONFLICT');
    }
}
