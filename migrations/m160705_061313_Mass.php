<?php

use yii\db\Schema;
use yii\db\Migration;

class m160705_061313_Mass extends Migration {

    public function safeUp() {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        else {
            $tableOptions = null;
        }
        
        $connection = Yii::$app->db;

        try {
            $this->createTable('{{%work_session}}', [
                'id' => Schema::TYPE_PK . "",
                'start' => Schema::TYPE_DATETIME . " NOT NULL",
                'stop' => Schema::TYPE_DATETIME . "",
                'report' => Schema::TYPE_TEXT . "",
                'user_id' => Schema::TYPE_INTEGER . "(11)",
                'shift' => Schema::TYPE_STRING . "(55)",
                'start_timestamp' => Schema::TYPE_INTEGER . "(11)",
                'stop_timestamp' => Schema::TYPE_INTEGER . "(11)",
                ], $tableOptions);

            $this->createTable('{{%work_session_user}}', [
                'id' => Schema::TYPE_PK . "",
                'start' => Schema::TYPE_DATETIME . " NOT NULL",
                'stop' => Schema::TYPE_DATETIME . " NOT NULL",
                'user_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
                'session_id' => Schema::TYPE_INTEGER . "(11) NOT NULL",
                'report' => Schema::TYPE_TEXT . "",
                'shift' => Schema::TYPE_STRING . "(55)",
                'start_timestamp' => Schema::TYPE_INTEGER . "(11)",
                'stop_timestamp' => Schema::TYPE_INTEGER . "(11)",
                ], $tableOptions);

            $this->createTable('{{%work_session_schedule}}', [
                'id' => Schema::TYPE_PK . "",
                'date' => Schema::TYPE_DATE . " NOT NULL",
                'user_id' => Schema::TYPE_INTEGER . "(11)",
                'shift' => Schema::TYPE_STRING . "(55)",
                ], $tableOptions);
            
            $this->addForeignKey(
                'fk_session_id', '{{%work_session_user}}', 'session_id', '{{%work_session}}', 'id', 'CASCADE', 'CASCADE'
            );
            
        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' ';
        }
    }

    public function safeDown() {
        $connection = Yii::$app->db;
        try {
            $this->dropTable('{{%work_session}}');
            $this->dropTable('{{%work_session_user}}');
            $this->dropTable('{{%work_session_schedule}}');
        } catch (Exception $e) {
            echo 'Catch Exception ' . $e->getMessage() . ' ';
        }
    }

}
