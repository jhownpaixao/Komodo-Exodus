<?php

$string /* html */= "
<!--xhp
import Router;
import Router->Component as C;


xhp-->
";
$content = file_get_contents(__DIR__ . '/../example/Views/Templates/Default.xhp');
preg_match_all("/(?<=<!--xhp)(.|\n)*?(?=xhp-->)/", $content, $match, PREG_UNMATCHED_AS_NULL);

//render code
$code = $match[0][0];
$code = str_replace(PHP_EOL, '', $code);
$lines = array_filter(explode(';', $code));

$process = [];
$globals = [];
$defines = [];
//process code
foreach ($lines as $line) {
    $line = preg_replace('/\s+/', ' ', $line);
    $d = array_filter(explode(' ', $line));
    if (!$d) {
        continue;
    }
    // var_dump($d );
    switch ($d[1]) {
        case 'import':
            import($globals, $d);
            break;

        case 'define':
            import($globals, $d);
            break;

        default:
            # code...
            break;
    }

}

function import(&$globals, $data)
{
    $value = $data[2];
    $name = $data[2];
    if (count($data) > 2) {
        $name = $data[4];
    }
    $globals[$name] = $value;
}

function define(&$defines, $data)
{
    $value = $data[2];
    $name = $data[2];
    if (count($data) > 2) {
        $name = $data[4];
    }
    $defines[$name] = $value;
}