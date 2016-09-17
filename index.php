<?PHP
error_reporting(E_ALL);
ini_set('display_errors', '1');


include "header.html";
require "SuperParser.php";



$content = file_get_contents("input.md");


$parser = new SuperParser();
$newContent = $parser->parse($content);

echo $newContent;



include "footer.html";
?>