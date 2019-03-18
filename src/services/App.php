<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasesitemaps\services;

use craft\base\Component;

class App extends Component
{
    /**
     * @var Sitemaps
     */
    public $sitemaps;

    /**
     * @var XmlSitemap
     */
    public $xmlSitemap;

    public function init()
    {
        ;
        $this->sitemaps = new Sitemaps();
        $this->xmlSitemap = new XmlSitemap();
    }
}