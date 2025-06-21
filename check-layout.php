<?php

// Read the file
$file = file_get_contents('resources/views/fields/show.blade.php');

// Count occurrences of tab sections
$tabSections = substr_count($file, '<!-- Field Details Tabs -->');
$tabPanes = substr_count($file, 'tab-pane fade');
$facilitiesTab = substr_count($file, 'id="facilities" role="tabpanel"');
$scheduleTab = substr_count($file, 'id="schedule" role="tabpanel"');

// Find where to insert the schedule tab
$facilitiesTabPos = strpos($file, '<div class="tab-pane fade" id="facilities" role="tabpanel">');
$facilityNotePos = strpos($file, '<div class="facility-note mt-4 p-3 bg-light rounded">');

// Output
echo "Tab sections found: $tabSections\n";
echo "Tab panes found: $tabPanes\n";
echo "Facilities tabs found: $facilitiesTab\n";
echo "Schedule tabs found: $scheduleTab\n";
echo "Position of first facilities tab: $facilitiesTabPos\n";
echo "Position of facility note: $facilityNotePos\n";

// Find string segments before and after the facility note
if ($facilityNotePos > 0) {
    echo "\nContent before facility note:\n";
    echo substr($file, $facilityNotePos - 100, 100) . "\n";
    
    echo "\nFacility note:\n";
    echo substr($file, $facilityNotePos, 200) . "\n";
    
    echo "\nContent after facility note:\n";
    echo substr($file, $facilityNotePos + 400, 100) . "\n";
}

// Check tab closing structure
$tabClosingPos = strpos($file, '</div>', $facilityNotePos + 400);
if ($tabClosingPos > 0) {
    echo "\nTab closing structure:\n";
    echo substr($file, $tabClosingPos - 50, 100) . "\n";
}
