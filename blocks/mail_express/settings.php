<?php 

$settings->add(new admin_setting_heading(
    'headerconfig',
    get_string('headerconfig', 'block_mail_express'), 
    get_string('desconfig', 'block_mail_express')
));

$settings->add(new admin_setting_configcheckbox(
    'mail_express/Allow_HTML', 
    get_string('labelallowhtml', 'block_mail_express'), 
    get_string('descallowhtml', 'block_mail_express'), 
    '0'
));