<?php

use yii\db\Migration;

/**
 * Class m200402_170618_init_project
 */
class m200402_170700_add_advertiser extends Migration
{
    // /**
    //  * {@inheritdoc}
    //  */
    // public function safeUp()
    // {

    // }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function safeDown()
    // {
    //     echo "m200402_170618_init_project cannot be reverted.\n";

    //     return false;
    // }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        // Add table for save information from Yashi_Advertiser.csv

        // Create Table zz__yashi_advertiser
        $this->execute('CREATE TABLE `zz__yashi_advertiser` (
            `yashi_advertiser_id` INT(11) UNSIGNED NOT NULL,
            `advertiser_name` VARCHAR(100) DEFAULT NULL,
            PRIMARY KEY (`yashi_advertiser_id`)
            ) ENGINE=INNODB DEFAULT CHARSET=utf8;');
    }

    public function down()
    {
        echo "m200402_170700_add_advertiser can be reverted." . PHP_EOL;

        $this->dropTable('zz__yashi_advertiser');

        return true;
    }
}
