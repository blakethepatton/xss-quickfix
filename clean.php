<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blake Patton - Freelance Developer</title>

    <!-- Bootstrap Core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>
<?PHP
require "oembed.php";
require "Parsedown.php";
require "simple_html_dom.php";


function getBetweenStr($string, $start, $end)
{
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);    
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

$content = file_get_contents("input.md");

$content = str_replace('<', '&lt;', $content);

$autoEmbed = new App\Libraries\AutoEmbed();
$parsedown = new Parsedown();
$response = $parsedown->parse($autoEmbed->parse($content));

$html = new simple_html_dom();
$html->load($response, true, false);

foreach($html->find('code') as $e){
    $e->innertext = str_replace('&amp;lt;', '&lt;', $e->innertext);
}

echo $html;

?>
</body>

</html>
