<?php

require_once __DIR__ . '/../../core/Migration.php';

use Core\Migration;
use Core\Database\Schema\Schema;

class AddProfileImageToUsersTable extends Migration
{
    /**
     * Run the migration
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->string('profile_image')->nullable()->after('email_verified_at');
        });
    }
    
    /**
     * Reverse the migration
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('profile_image');
        });
    }
}
