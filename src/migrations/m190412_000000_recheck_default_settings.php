<?php

namespace barrelstrength\sproutbasesitemaps\migrations;

use craft\db\Migration;
use barrelstrength\sproutsitemaps\migrations\Install as SproutBaseSitemapsInstallMigration;
/**
 * m190412_000000_recheck_default_settings migration.
 */
class m190412_000000_recheck_default_settings extends Migration
{
    /**
     * @return bool
     * @throws \craft\errors\SiteNotFoundException
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\base\NotSupportedException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function safeUp()
    {
        $installMigration = new SproutBaseSitemapsInstallMigration();

//        if ($this->db->tableExists($oldTable)){
            $installMigration->insertDefaultSettings();
//        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190412_000000_recheck_default_settings cannot be reverted.\n";
        return false;
    }
}