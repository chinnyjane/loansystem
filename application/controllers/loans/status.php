<?php
if ($query->row()->STATUS=='01') {
	$status = 'Current';
} else if ($query->row()->STATUS=='30') {
	$status = 'PAST DUE';
} else if ($query->row()->STATUS=='60') {
	$status = 'LITIGATED';
} else if ($query->row()->STATUS=='80') {
	$status = 'ACQUIRED ASSET';
} else if ($query->row()->STATUS=='85') {
	$status = 'BAD DEBT';
} else if ($query->row()->STATUS=='90') {
	$status = 'Closed Account';
} else if (intval($query->row()->STATUS)>85) {
	$status = 'REF TO ATTY';
}
?>