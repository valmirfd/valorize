<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCompanies extends Migration
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
            'user_id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
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
        ]);


        $this->forge->addKey('id', true); // primary key
        $this->forge->addKey('name');
        $this->forge->addKey('phone');
        $this->forge->addKey('email');

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');


        $this->forge->createTable('companies');
    }

    public function down()
    {
        $this->forge->dropTable('companies');
    }
}
