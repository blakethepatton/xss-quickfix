<?PHP
error_reporting(E_ALL);
ini_set('display_errors', '1');


include "header.html";

require_once "Parsedown.php";
require_once "ParsedownExtra.php";
require_once "ParsedownFilter.php";
require_once "oembed.php";
require_once "simple_html_dom.php";

require "SuperParser.php";



$content = file_get_contents("input.md");


$parser = new SuperParser();
$newContent = $parser->parse($content);

echo $newContent;



include "footer.html";
?>