<?php
namespace App\View;

use App\View\Helper\EmailHelper;
use Cake\View\View;
use FrankFoerster\Asset\View\Helper\AssetHelper;

/**
 * Application View
 *
 * @property AssetHelper Asset
 * @property EmailHelper Email
 */
class AppView extends View
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize()
    {
        $this->loadHelper('Asset', ['className' => 'FrankFoerster/Asset.Asset']);
        $this->loadHelper('Email');
    }
}
