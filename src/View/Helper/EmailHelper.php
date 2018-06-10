<?php
namespace App\View\Helper;

use Cake\View\Helper;

/**
 * Class EmailHelper
 *
 * @property Helper\UrlHelper $Url
 * @property Helper\HtmlHelper $Html
 */
class EmailHelper extends Helper
{
    /**
     * Helpers used by this helper.
     *
     * @var array
     */
    public $helpers = [
        'Url',
        'Html'
    ];

    /**
     * Render a big action button.
     *
     * @param string $linkText The button text to display.
     * @param array|string $url The url the button should link to.
     * @param string $bgColor The background color.
     * @param string $textColor The text color.
     * @return string
     */
    public function bigActionButton($linkText, $url, $bgColor = '#c21429', $textColor = "#ffffff")
    {
        return $this->_View->element('Email/big-action-button', [
            'linkText' => $linkText,
            'url' => $this->Url->build($url, true),
            'bgColor' => $bgColor,
            'textColor' => $textColor
        ]);
    }

    /**
     * Link to the home page.
     *
     * @return string
     */
    public function linkToHomepage()
    {
        return $this->Html->link(
            rtrim(preg_replace('/https{0,1}:\/{2}/', '', $this->Url->build('/', true)), '/'),
            $this->Url->build('/', true)
        );
    }

    /**
     * Link to imprint.
     *
     * @return string
     * @throws \Aura\Intl\Exception
     */
    public function linkToImprint()
    {
        return $this->Html->link(
            __('Imprint'),
            $this->Url->build('/imprint', true)
        );
    }
}
