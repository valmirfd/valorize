<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIgrejasImages extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'igreja_id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'image'       => [
                'type'       => 'VARCHAR',
                'constraint' => '240',
            ],
        ]);


        $this->forge->addKey('id', true); // primary key        

        $this->forge->addForeignKey('igreja_id', 'igrejas', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('igrejas_images');
    }

    public function down()
    {
        $this->forge->dropTable('igrejas_images');
    }
}
