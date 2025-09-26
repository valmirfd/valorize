<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChurches extends Migration
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
            'name'       => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
            ],
            'phone'       => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'email'       => [
                'type'       => 'VARCHAR',
                'constraint' => '170',
            ],
            'address'       => [
                'type'       => 'VARCHAR',
                'constraint' => '170',
            ],
            'created_at'       => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
            'updated_at'       => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
            'deleted_at'       => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
        ]);


        $this->forge->addKey('id', true); // primary key
        $this->forge->addKey('name');
        $this->forge->addKey('phone');
        $this->forge->addKey('email');


        $this->forge->createTable('churches');
    }

    public function down()
    {
        $this->forge->dropTable('churches');
    }
}
