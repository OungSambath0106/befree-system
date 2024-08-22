<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => storage_path('temp/'),
	'font_path' => base_path('public/fonts/'),
	'font_data' => [
		'KantumruyPro' => [
			'R'  => 'KantumruyPro-Regular.ttf',   
			'B'  => 'KantumruyPro-Bold.ttf',       
			'I'  => 'KantumruyPro-Italic.ttf',
			'useOTL' => 0xFF,   
			'useKashida' => 75,      
		],
		'hanuman' => [
			'R'  => 'Hanuman-Regular.ttf',
			'useOTL' => 0xFF,   
			'useKashida' => 75,
		]
	]
];
