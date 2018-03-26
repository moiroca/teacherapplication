<?php 

return [
	'host'	 	=> env('ACLC_DB_HOST', 'localhost'),
    'port' 		=> env('ACLC_DB_PORT', '3306'),
    'database' 	=> env('ACLC_DB_DATABASE', ''),
    'username' 	=> env('ACLC_DB_USERNAME', ''),
    'password' 	=> env('ACLC_DB_PASSWORD', ''),
    'table' 	=> env('ACLC_TABLE', ''),
    'column' 	=> env('ACLC_TABLE_FIELD', ''),
];