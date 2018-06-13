<?php

namespace App\Controller\Api;

/**
 * Class ContentController
 */
class ContentController extends ApiAppController
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

        $this->Guardian->allow(['imprint']);
    }

    /**
     * Imprint action
     *
     * @throws \Aura\Intl\Exception
     * @return void
     */
    public function imprint()
    {
        $view = $this->createView();
        $imprint = $view->element('imprint');

        $this->set('imprint', $imprint);
        $this->set('_serialize', ['imprint']);
    }
}
