<?php 

$capabilities = array(
    'block/mail_express:myaddinstance' => array(
        'captype' => 'write', 
        'contextlevel' => CONTEXT_SYSTEM, 
        'archetypes' => array(
            'user' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle_fmr/my:manageblocks'
    ), 

    'block/mail_express:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS, 
        'captype' => 'write', 
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW, 
            'manager' => CAP_ALLOW
        ), 
        'clonepermissionsfrom' => 'moodle_fmr/site:manageblocks'
    ),

);