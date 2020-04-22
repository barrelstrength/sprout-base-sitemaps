<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasesitemaps\web\assets\sitemaps;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class SitemapsAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = '@sproutbasesitemaps/web/assets/sitemaps/dist';

        $this->depends = [
            CpAsset::class,
        ];

        $this->css = [
            'css/sitemaps.css'
        ];

        $this->js = [
            'js/sitemaps.js'
        ];

        parent::init();
    }
}