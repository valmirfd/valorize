<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIgrejas extends Migration
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
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
                'null'           => true,
                'default'        => null,
            ],
            'titular_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'null'           => true,
                'default'        => null,
            ],
            'is_sede'       => [
                'type'       => 'BOOLEAN',
                'null' => false,
                'default' => false,
            ],
            'ativo'       => [
                'type'       => 'BOOLEAN',
                'null' => false,
                'default' => true,
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
        $this->forge->addKey('nome');
        $this->forge->addKey('code');


        $this->forge->addForeignKey(
            fieldName: 'user_id',
            tableName: 'users',
            tableField: 'id',
            onUpdate: 'CASCADE',
            onDelete: 'CASCADE'
        );

        $this->forge->addForeignKey(
            fieldName: 'address_id',
            tableName: 'addresses',
            tableField: 'id',
            onUpdate: 'CASCADE',
            onDelete: 'CASCADE'
        );

        $this->forge->createTable('igrejas');
    }

    public function down()
    {
        $this->forge->dropTable('igrejas');
    }
}
