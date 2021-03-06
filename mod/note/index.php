<?php 

require_once('../../config.php');

$id = required_param('id', PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $id))) {
    print_error('Course ID is incorrect');
}

require_course_login($course, true);
$PAGE->set_pagelayout('incourse');

$event = \mod_note\event\course_module_instance_list_viewed::created(array('context' => context_course::instance($course->id)));
$event->add_record_snapshot('course', $course);
$event->trigger();

$strnote          = get_string('modulename', 'note');
$strnotes         = get_string('modulenameplural', 'notes');
$strname          = get_string('name');
$strintro         = get_string('moduleintro');
$strlastmodified  = get_string('lastmodified');

$PAGE->set_url('/mod/page/index.php', array('id' => $course->id));
$PAGE->set_title($course->shortname.': '.$strnotes);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($strnotes);

echo $OUTPUT->header();
echo $OUTPUT->heading($strnotes);

if (!$notes = get_all_instances_in_course('note', $course)) {
    notice(get_string('thereareno', 'moodle', $strnotes), "$CFG->wwwroot/course/view.php?id=$course->id");
    exit;
}

$usesections = course_format_uses_sections($course->format);

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($usesections) {
    $strsectionname = get_string('sectionname', 'format_'.$course->format);
    $table->head = array ($strsectionname, $strname, $strintro);
    $table->align = array ('center', 'left', 'left');

}else {
    $table->head = array ($strlastmodified, $strname, $strintro);
    $table->align = array ('left', 'left', 'left');
}

$modinfo = get_fast_modinfo($course);
$currentsection = '';
foreach ($pages as $page) {
    $cm = $modinfo->cms[$page->coursemodule];
    if($usesections) {
        $printsection = '';
        if ($page->section !== $currentsection) {
            if ($page->section) {
                $printsection = get_section_name($course, $page->section);
            }
            if ($currentsection !== '') {
                $table->data[] = 'hr';
            }
            $currentsection = $page->section;
        }

    }else {
        $printsection = '<span class="smallinfo">' . userdata($page->timemodified) . "</span>";
    }

    $class = $page->visible ? '' : 'class="dimed"';

    $table->data[] = array(
        $printsection, 
        "<a $class href=\"view.php?id=$cm->id\">" . format_string($page->name) . "</a>", 
        format_module_intro('note', $page, $cm->id));

}

echo html_writer::table($table);

echo $OUTPUT->footer();
