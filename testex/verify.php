<?php

$html_code = file_get_contents('C:/Users/ppedr/Documents/test.txt');
$comment_pattern = '#\<\!\-\-([\s\S]+)\-\-\>#';
$verify = preg_match($comment_pattern, $html_code, $matches);

// print($verify);
// print_r($matches);
$replace = '';
$matches = implode (' ', $matches );

$html_code = str_replace($replace, $matches, $html_code);
print($html_code);

