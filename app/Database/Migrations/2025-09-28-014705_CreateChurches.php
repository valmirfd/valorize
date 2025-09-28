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
            'user_id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'address_id'     => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nome'       => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
            ],
            'telefone' => [
                'type'                  => 'VARCHAR',
                'constraint'            => '20',
                'null'                  => true,
                'default'               => null,
            ],
            'cnpj'       => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
                'default'    => null,
            ],
            'code'       => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
            ],
            'situacao'       => [
                'type'       => 'ENUM',
                'constraint' => ['sede', 'filial'],
            ],
            'superintendente_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
                'default'        => null,
            ],
            'titular_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
                'default'        => null,
            ],
            'is_sede'       => [
                'type'       => 'BOOLEAN',
                'null' => false,
                'default' => false,
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
            'ativo'       => [
                'type'       => 'BOOLEAN',
                'null' => false,
                'default' => true,
            ],
        ]);


        $this->forge->addKey('id', true); // primary key        

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('address_id', 'addresses', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('churches');
    }

    public function down()
    {
        $this->forge->dropTable('churches');
    }
}
