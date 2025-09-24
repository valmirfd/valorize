<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAddresses extends Migration
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
            'street'       => [
                'type'       => 'VARCHAR',
                'constraint' => '70',
            ],
            'number'       => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'default'    => null,
            ],
            'city'       => [
                'type'       => 'VARCHAR',
                'constraint' => '70',
            ],
            'district'       => [
                'type'       => 'VARCHAR',
                'constraint' => '70',
            ],
            'postalcode'       => [
                'type'       => 'VARCHAR',
                'constraint' => '9', // 32113-110
            ],
            'state'       => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
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


        $this->forge->addKey('id', true);
        $this->forge->addKey('street');
        $this->forge->addKey('city');
        $this->forge->addKey('district');
        $this->forge->addKey('state');
        $this->forge->addKey('postalcode');

        $this->forge->createTable('addresses');
    }

    public function down()
    {
        $this->forge->dropTable('addresses');
    }
}
