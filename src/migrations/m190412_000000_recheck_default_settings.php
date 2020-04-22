<?php

namespace barrelstrength\sproutbasesitemaps\migrations;

use barrelstrength\sproutbasesitemaps\migrations\Install as SproutBaseSitemapsInstallMigration;
use craft\db\Migration;
use craft\errors\SiteNotFoundException;
use yii\base\Exception;

class m190412_000000_recheck_default_settings extends Migration
{
    /**
     * @return bool
     * @throws SiteNotFoundException
     * @throws Exception
     */
    public function safeUp(): bool
    {
        $installMigration = new SproutBaseSitemapsInstallMigration();
        $installMigration->insertDefaultSettings();

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