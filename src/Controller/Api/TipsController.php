<?php

namespace App\Controller\Api;

use App\Model\Entity\Tip;
use App\Model\Table\TipsTable;

/**
 * Class TipsController
 *
 * @property TipsTable Tips
 */
class TipsController extends ApiAppController
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

        $this->loadModel('Tips');
    }

    /**
     * @throws \Aura\Intl\Exception
     */
    public function createOrUpdate()
    {
        if (!$this->request->is('post')) {
            $this->_respondWithMethodNotAllowed();
            return;
        }

        $userId = $this->Guardian->user('id');
        $gameId = $this->request->getData('gameId');
        $result1 = $this->request->getData('result1');
        $result2 = $this->request->getData('result2');

        if ($gameId !== null) {
            $gameId = (int)$gameId;
        }

        if ($result1 !== null) {
            $result1 = (int)$result1;
        }

        if ($result2 !== null) {
            $result2 = (int)$result2;
        }

        $tip = $this->Tips->newEntity([
            'user_id' => $userId,
            'game_id' => $gameId,
            'result1' => $result1,
            'result2' => $result2,
        ]);

        if (!empty($tip->getErrors())) {
            $this->set('errors', $tip->getErrors());
            $this->set('message', __('Your vote is incorrect.'));
            $this->_respondWithValidationErrors();
        } else {
            $existingTip = $this->Tips->findExisting($userId, $gameId);
            if ($existingTip) {
                $tip = $this->Tips->patchEntity($existingTip, $tip->toArray());
            }
        }

        if ($this->Tips->save($tip)) {
            $this->set('message', 'Your vote has been submitted.');
        } else {
            $this->set('errors', $tip->getErrors());
            $this->set('message', __('Your vote is incorrect.'));
            $this->_respondWithValidationErrors();
        }

        $this->set('_serialize', ['message', 'errors']);
    }
}
