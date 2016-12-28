<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Psychics_Fields extends CI_Migration {

    public function up()
    {   
        $this->dbforge->drop_column('psychics', 'name');
        $fields = [
            'username' => [
                'type'          => 'VARCHAR',
                'constraint'    => 50
            ],
            'fname' => [
                'type'          => 'VARCHAR',
                'constraint'    => 50
            ],
            'lname' => [
                'type'          => 'VARCHAR',
                'constraint'    => 50
            ],
            'password' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255
            ],
            'home_address' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255
            ],
            'email_address' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255
            ],
            'paypal_address' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255
            ],
            'profile_img' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255
            ],
            'mobile_num' => [
                'type'          => 'VARCHAR',
                'constraint'    => 50
            ],
            'home_phone' => [
                'type'          => 'VARCHAR',
                'constraint'    => 50
            ],
        ];
        $this->dbforge->add_column('psychics', $fields);
    }

    public function down()
    {
        $this->dbforge->add_column('psychics', [
            'name' => [
                'type'          => 'VARCHAR',
                'constraint'    => 255
            ]
        ]);
        $this->dbforge->drop_column('inbound_messages', 'username');
        $this->dbforge->drop_column('inbound_messages', 'fname');
        $this->dbforge->drop_column('inbound_messages', 'lname');
        $this->dbforge->drop_column('inbound_messages', 'password');
        $this->dbforge->drop_column('inbound_messages', 'home_address');
        $this->dbforge->drop_column('inbound_messages', 'email_address');
        $this->dbforge->drop_column('inbound_messages', 'paypal_address');
        $this->dbforge->drop_column('inbound_messages', 'profile_img');
        $this->dbforge->drop_column('inbound_messages', 'mobile_num');
        $this->dbforge->drop_column('inbound_messages', 'home_phone');
    }
}