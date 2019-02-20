<?php

use Illuminate\Database\Migrations\Migration;

class AlterChannelsDropAutoincrement extends Migration
{
//    private $sql = "ALTER TABLE `channels` MODIFY `id` INTEGER UNSIGNED NOT NULL";
    private $sqlPatterns = [
        "LOCK TABLES channels WRITE, broadcasts WRITE, migrations WRITE;",
        "ALTER TABLE broadcasts DROP FOREIGN KEY fk_broadcasts_channel_id;",
        "ALTER TABLE channels MODIFY id INTEGER UNSIGNED NOT NULL %s;",
        "ALTER TABLE broadcasts ADD CONSTRAINT fk_broadcasts_channel_id FOREIGN KEY (channel_id) REFERENCES channels (id);",
        "UNLOCK TABLES;",
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = sprintf(join($this->sqlPatterns), "");
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = sprintf(join($this->sqlPatterns), "AUTO_INCREMENT");
        DB::connection()->getPdo()->exec($sql);
    }
}
