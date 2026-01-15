<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE email_logs DROP FOREIGN KEY email_logs_user_id_foreign');
        DB::statement('ALTER TABLE email_logs MODIFY user_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE email_logs ADD CONSTRAINT email_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE email_logs DROP FOREIGN KEY email_logs_user_id_foreign');
        DB::statement('ALTER TABLE email_logs MODIFY user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE email_logs ADD CONSTRAINT email_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
    }
};
