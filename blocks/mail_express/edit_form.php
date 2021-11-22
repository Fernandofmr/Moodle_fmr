<?php 

class block_mail_express_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        // Set the title of the section
        $mform->addElement( 'header', 'config_header', get_string('blocksettings', 'block') );


        // Setting block to allow user changing the block title
        $mform->addElement( 'text', 'config_title', get_string('blocktitle', 'block_mail_express') );
        $mform->setDefault( 'config_title', 'default value' );
        $mform->setType( 'config_title', PARAM_TEXT );
    }

}