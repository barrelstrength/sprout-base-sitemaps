<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasesitemaps\models;


use barrelstrength\sproutbase\base\SproutSettingsInterface;
use craft\base\Model;
use Craft;

/**
 *
 * @property array $settingsNavItems
 */
class Settings extends Model implements SproutSettingsInterface
{
    /**
     * @var string
     */
    public $pluginNameOverride = '';

    /**
     * @var bool
     */
    public $enableCustomSections = false;

    /**
     * @var bool
     */
    public $enableDynamicSitemaps = true;

    /**
     * @var bool
     */
    public $enableMultilingualSitemaps = false;

    /**
     * @var int
     */
    public $totalElementsPerSitemap = 500;

    /**
     * @var array
     */
    public $siteSettings = [];

    /**
     * @var array
     */
    public $groupSettings = [];

    /**
     * @inheritdoc
     */
    public function getSettingsNavItems(): array
    {
        return [
            'general' => [
                'label' => Craft::t('sprout-base-sitemaps', 'General'),
                'url' => 'sprout-base-sitemaps/settings/general',
                'selected' => 'general',
                'template' => 'sprout-base-sitemaps/settings/general'
            ],
            'sitemaps' => [
                'label' => Craft::t('sprout-base-sitemaps', 'Sitemaps'),
                'url' => 'sprout-base-sitemaps/settings/sitemaps',
                'selected' => 'sitemaps',
                'template' => 'sprout-base-sitemaps/settings/sitemaps'
            ]
        ];
    }
}