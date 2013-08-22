<?php
echo $this->Form->create(array('type' => 'file'));
echo $this->Form->file('zipFile');
echo $this->Form->end('submit');

$output = '';
$output .= $this->Html->tableHeaders(array('Filename', 'Level Name', 'Errors'));

foreach($uploads as $upload) {
	$class = '';
	$class .= sizeof($upload['errors']) ? ' error' : '';

	$output .= "<tr class=\"$class\">";
	$output .= '<td>' . $upload['filename'] . '</td>';
	$output .= '<td>' . $upload['name'] . '</td>';
	$output .= '<td>' . $this->Html->nestedList($upload['errors']) . '</td>';
	$output .= '</tr>';
}

echo $this->Html->tag('table', $output, array('class' => 'mass-upload-info'));

?>