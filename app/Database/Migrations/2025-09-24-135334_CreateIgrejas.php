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
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
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
            'codigo'       => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
            ],
            'situacao'       => [
                'type'       => 'ENUM',
                'constraint' => ['sede', 'filial'],
            ],
            'cep'       => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'rua'       => [
                'type'       => 'VARCHAR',
                'constraint' => '140',
            ],
            'numero'       => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'bairro'       => [
                'type'       => 'VARCHAR',
                'constraint' => '140',
            ],
            'cidade'       => [
                'type'       => 'VARCHAR',
                'constraint' => '140',
            ],
            'estado'       => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
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
            'descricao'       => [
                'type'       => 'LONGTEXT',
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
        $this->forge->addKey('nome'); 
        $this->forge->addKey('bairro'); 
        $this->forge->addKey('cidade'); 
        $this->forge->addKey('estado'); 

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('igrejas');
    }

    public function down()
    {
        $this->forge->dropTable('igrejas');
    }
}
