<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Outbound_Messages extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'ref_message_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'sender_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'message' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'request_url' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            '`sent_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('outbound_messages');
    }

    public function down()
    {
        $this->dbforge->drop_table('outbound_messages');
    }
}