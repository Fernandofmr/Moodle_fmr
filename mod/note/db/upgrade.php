<?php 

defined('MOODLE_INTERNAL') || die;

function xmldb_note_upgrade($oldversion=0) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2021122800) {
        $table = new xmldb_table('note_user_notes');
        $field = new xmldb_field('notetitle');
        $field->set_attributes(XMLDB_TYPE_CHAR, '255', null, null, null, null, 'noteid');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        
        upgrade_mod_savepoint(true, 2021122800, 'note');
    }

    if ($oldversion < 2021122300) {

        $table = new xmldb_table('note');
        $field = new xmldb_field('name');
        $field->set_attributes(XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('description');
        $field->set_attributes(XMLDB_TYPE_TEXT, null, null, null, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('timemodified');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


        $table = new xmldb_table('note_user_notes');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('noteid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('content', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('userid', XMLDB_KEY_FOREIGN, array('userid'), 'user', array('id'));
        $table->add_key('noteid', XMLDB_KEY_FOREIGN, array('noteid'), 'note', array('id'));
        
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

    }

    upgrade_mod_savepoint(true, 2021122300, 'note');
    //return true;
}