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


$content = file_get_contents("input.md");

$html = new simple_html_dom();
$html->load($content, true, false);
foreach($html->find('a') as $e)
{
    $e->outertext = "[".$e->innertext."](".$e->href.")";
}
unset($e);
foreach($html->find('img') as $e){
    (isset($e->attr['src'])) ? $src = $e->attr['src'] : $src='';
    (isset($e->attr['alt'])) ? $alt = $e->attr['alt'] : $alt='';
    $e->outertext = "![".$alt."](".$src.")";
}
unset($e);



$autoEmbed = new App\Libraries\AutoEmbed();
$parsedown = new Parsedown();
$response = $parsedown->setMarkupEscaped(true)->setUrlsLinked(false)->parse($autoEmbed->parse($html));

echo $response;


?>
</body>

</html>
