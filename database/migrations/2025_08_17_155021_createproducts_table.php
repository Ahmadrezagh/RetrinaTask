<?php

require_once __DIR__ . '/../../core/Migration.php';

use Core\Migration;

class CreateproductsTable extends Migration
{
    /**
     * Run the migration
     */
    public function up()
    {
        $columns = [
            '`id` INT AUTO_INCREMENT PRIMARY KEY',
            // Add your columns here
            ...$this->timestamps(),
            '',
            '-- Indexes',
            // Add your indexes here
        ];
        
        $this->createTable('products', $columns);
    }
    
    /**
     * Reverse the migration
     */
    public function down()
    {
        $this->dropTable('products');
    }
}
