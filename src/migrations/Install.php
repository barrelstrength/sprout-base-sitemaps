<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasesitemaps\migrations;

use Craft;

use craft\db\Migration;
use barrelstrength\sproutbasesitemaps\models\Settings as SproutSitemapSettings;
use craft\services\Plugins;

class Install extends Migration
{
    /**
     * @var string The database driver to use
     */
    public $driver;

    /**
     * @return bool
     * @throws \Throwable
     * @throws \craft\errors\SiteNotFoundException
     */
    public function safeUp(): bool
    {
        $this->createTables();
        $this->insertDefaultSettings();

        return true;
    }

    /**
     * @return bool|void
     * @throws \Throwable
     */
    public function safeDown()
    {
        $this->dropTable('{{%sproutseo_sitemaps}}');
    }

    // Protected Methods
    // =========================================================================

    protected function createTables()
    {
        $table = '{{%sproutseo_sitemaps}}';

        if (!$this->db->tableExists($table)) {
            $this->createTable($table, [
                'id' => $this->primaryKey(),
                'siteId' => $this->integer()->notNull(),
                'uniqueKey' => $this->string(),
                'urlEnabledSectionId' => $this->integer(),
                'enabled' => $this->boolean()->defaultValue(false),
                'type' => $this->string(),
                'uri' => $this->string(),
                'priority' => $this->decimal(11, 1),
                'changeFrequency' => $this->string(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
            ]);

            $this->createIndexes();
            $this->addForeignKeys();
        }
    }

    protected function createIndexes()
    {
        $this->createIndex(null, '{{%sproutseo_sitemaps}}', ['siteId']);
    }

    protected function addForeignKeys()
    {
        $this->addForeignKey(null, '{{%sproutseo_sitemaps}}', ['siteId'], '{{%sites}}', ['id'], 'CASCADE', 'CASCADE');
    }

    /**
     * @throws \craft\errors\SiteNotFoundException
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\base\NotSupportedException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function insertDefaultSettings()
    {
        $settings = $this->getSproutSitemapSettingsModel();

        // Add our default plugin settings
        $pluginHandle = 'sprout-sitemaps';
        Craft::$app->getProjectConfig()->set(Plugins::CONFIG_PLUGINS_KEY.'.'.$pluginHandle.'.settings', $settings->toArray());

        // Remove unused settings
        Craft::$app->getProjectConfig()->remove(Plugins::CONFIG_PLUGINS_KEY.'.sprout-base-sitemaps');
    }

    /**
     * @return SproutSitemapSettings
     * @throws \craft\errors\SiteNotFoundException
     */
    private function getSproutSitemapSettingsModel(): SproutSitemapSettings
    {
        $projectConfig = Craft::$app->getProjectConfig();
        $settings = new SproutSitemapSettings();

        $sproutSitemapsSettings = $projectConfig->get('plugins.sprout-sitemaps.settings');

        // If we already have settings and a structureId defined for Sprout Redirects
        if ($sproutSitemapsSettings &&
            isset($sproutSitemapsSettings['siteSettings']) &&
            !empty($sproutSitemapsSettings['siteSettings'])) {

            $settings->siteSettings = $sproutSitemapsSettings['siteSettings'];
            return $settings;
        }

        // Need to fix how settings were stored in an earlier install
        // @deprecate in future version
        $sproutBaseSitemapSettings = $projectConfig->get('plugins.sprout-base-sitemaps.settings');

        if ($sproutBaseSitemapSettings &&
            isset($sproutBaseSitemapSettings['siteSettings']) &&
            !empty($sproutBaseSitemapSettings['siteSettings'])) {

            $settings->siteSettings = $sproutBaseSitemapSettings['siteSettings'];
            return $settings;
        }

        // Need to check for how we stored data in Sprout SEO schema and migrate things if we find them
        // @deprecate in future version
        $sproutSeoSettings = $projectConfig->get('plugins.sprout-seo.settings');

        if ($sproutSeoSettings &&
            isset($sproutSeoSettings['siteSettings']) &&
            !empty($sproutSeoSettings['siteSettings'])) {

            $settings->siteSettings = $sproutSeoSettings['siteSettings'];
            return $settings;
        }

        // If none of the above have an existing siteSettings, create a new one
        $site = Craft::$app->getSites()->getPrimarySite();
        $settings->siteSettings[$site->id] = $site->id;
        return $settings;
    }
}
