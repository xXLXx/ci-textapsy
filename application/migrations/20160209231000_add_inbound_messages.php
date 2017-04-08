<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Inbound_Messages extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'txtnation_msg_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'number' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'message' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'request_url' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'network' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'shortcode' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'country' => array(
                'type' => 'VARCHAR',
                'constraint' => 2
            ),
            'responded_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'status' => array(
                'type' => 'INT',
                'constraint' => 1,
                'unsigned' => TRUE
            ),
            '`sent_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('inbound_messages');
    }

    public function down()
    {
        $this->dbforge->drop_table('inbound_messages');
    }
}