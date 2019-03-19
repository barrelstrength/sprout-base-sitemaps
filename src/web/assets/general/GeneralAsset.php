<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasesitemaps\web\assets\general;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class GeneralAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = '@sproutbasesitemaps/web/assets/general/dist';

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/general.js'
        ];

        parent::init();
    }
}