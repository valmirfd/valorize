<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChurchesImages extends Migration
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
            'church_id'          => [
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

        $this->forge->addForeignKey('church_id', 'churches', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('churches_images');
    }

    public function down()
    {
        $this->forge->dropTable('churches_images');
    }
}
