<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasesitemaps\services;

use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutbasesitemaps\models\Settings as SproutBaseSitemapsSettings;
use craft\base\Model;
use yii\base\Component;
use yii\db\Exception;

/**
 * @property null|Model                 $pluginSettings
 * @property Model                      $redirectsSettings
 * @property SproutBaseSitemapsSettings $sitemapsSettings
 * @property int                        $descriptionLength
 */
class Settings extends Component
{
    /**
     * @return SproutBaseSitemapsSettings
     */
    public function getSitemapsSettings(): SproutBaseSitemapsSettings
    {
        /** @var SproutBaseSitemapsSettings $settings */
        $settings = SproutBase::$app->settings->getBaseSettings(SproutBaseSitemapsSettings::class, 'sprout-sitemaps');

        return $settings;
    }

    /**
     * @param array $settingsArray
     *
     * @return int
     * @throws Exception
     */
    public function saveSitemapsSettings(array $settingsArray)
    {
        return SproutBase::$app->settings->saveBaseSettings($settingsArray, SproutBaseSitemapsSettings::class);
    }
}
