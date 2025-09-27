<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsUsers extends Migration
{
    public function up()
    {
        $fields = [
            'name' => [
                'type'                  => 'VARCHAR',
                'constraint'            => '30',
                'null'                  => true,
            ],
            'last_name' => [
                'type'                  => 'VARCHAR',
                'constraint'            => '50',
                'null'                  => true,
            ],
            'cpf' => [
                'type'                  => 'VARCHAR',
                'constraint'            => '20',
                'null'                  => true,
            ],
            'birth' => [
                'type'                  => 'DATE',
                'null'                  => true,
            ],
            'phone' => [
                'type'                  => 'VARCHAR',
                'constraint'            => '20',
                'null'                  => true,
            ],
            'display_phone' => [
                'type'                  => 'BOOLEAN',
                'default'               => true,
            ],
            'is_superintendente'       => [
                'type'       => 'BOOLEAN',
                'null' => false,
                'default' => true,
            ],
            'superintendente_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
                'default'        => null,
            ],
        ];


        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'name');
        $this->forge->dropColumn('users', 'last_name');
        $this->forge->dropColumn('users', 'cpf');
        $this->forge->dropColumn('users', 'birth');
        $this->forge->dropColumn('users', 'phone');
        $this->forge->dropColumn('users', 'display_phone');
        $this->forge->dropColumn('users', 'is_superintendente');
        $this->forge->dropColumn('users', 'superintendente_id');
    }
}
