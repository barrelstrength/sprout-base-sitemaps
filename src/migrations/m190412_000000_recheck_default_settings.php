<?php

namespace barrelstrength\sproutbasesitemaps\migrations;

use barrelstrength\sproutsitemaps\migrations\Install as SproutBaseSitemapsInstallMigration;
use craft\db\Migration;
use craft\errors\SiteNotFoundException;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\web\ServerErrorHttpException;

/**
 * m190412_000000_recheck_default_settings migration.
 */
class m190412_000000_recheck_default_settings extends Migration
{
    /**
     * @return bool
     * @throws SiteNotFoundException
     * @throws ErrorException
     * @throws Exception
     * @throws NotSupportedException
     * @throws ServerErrorHttpException
     */
    public function safeUp(): bool
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
    public function safeDown(): bool
    {
        echo "m190412_000000_recheck_default_settings cannot be reverted.\n";

        return false;
    }
}