<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasesitemaps\migrations;

use craft\db\Migration;
use barrelstrength\sproutbasesitemaps\models\Settings as SproutSitemapSettings;
use barrelstrength\sproutbase\migrations\Install as SproutBaseInstall;
use craft\db\Query;
use Craft;

/**
 *
 * @property \barrelstrength\sproutbasesitemaps\models\Settings $sproutSitemapSettingsModel
 */
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
        $migration = new SproutBaseInstall();
        ob_start();
        $migration->safeUp();
        ob_end_clean();

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
        $settingsRow = (new Query())
            ->select(['*'])
            ->from(['{{%sprout_settings}}'])
            ->where(['model' => SproutSitemapSettings::class])
            ->one();

        if (is_null($settingsRow)){

            $settings = new SproutSitemapSettings();

            $site = Craft::$app->getSites()->getPrimarySite();
            $settings->siteSettings[$site->id] = $site->id;

            $settingsArray = [
                'model' => SproutSitemapSettings::class,
                'settings' => json_encode($settings->toArray())
            ];

            $this->insert('{{%sprout_settings}}', $settingsArray);
        }
    }
}
