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
require "ParsedownFilter.php";

$content = file_get_contents("input.md");
$autoEmbed = new App\Libraries\AutoEmbed();
//$parsedown = new Parsedown();
$html = new simple_html_dom();
$parsedown = new ParsedownFilter( 'myFilter' );

function myFilter( &$el ){

    switch( $el[ 'name' ] ){
        case 'a':

            $url = $el[ 'attributes' ][ 'href' ];

            /***
                If there is no protocol handler, and the link is not an open protocol address, 
                the links must be relative so we can return as there is nothing to do.
            ***/

            if( strpos( $url, '://' ) === false )
                if( ( ( $url[ 0 ] == '/' ) && ( $url[ 1 ] != '/' ) ) || ( $url[ 0 ] != '/' ) ){ return; }


            if( strpos( $url, $_SERVER["SERVER_NAME"] ) === false ){
                $el[ 'attributes' ][ 'rel' ] = 'nofollow';
                $el[ 'attributes' ][ 'target' ] = '_blank';
            }
            break;

    }
}



$html->load($content, true, false);

// convert html links to md flavored links
$a = array(); //save original element info
if(count($html->find('a'))>0){
    $i = 0;
    foreach($html->find('a') as $e)
    {
        $url = $old = $e->href;
        $query = parse_url($url, PHP_URL_QUERY);
        // Returns a string if the URL has parameters or NULL if not
        if ($query) {
            // $url .= '&__php_a_id='.$i;
        } else {
            // $url .= '?__php_a_id='.$i;
        }
        $new = "[".$e->innertext."](".$url.")";
        $a[$i] = array('new'=>$new, 'url'=>$url, 'original'=>$e->outertext, 'oldurl'=>$old);
        $e->outertext = $new;
        $i++;
    }
    unset($e);
}
// convert html img tags to md flavored img tags
$img = array(); //save original element info
if(count($html->find('img'))>0){
    $i = 0;
    foreach($html->find('img') as $e){
        (isset($e->attr['src'])) ? $src = $e->attr['src'] : $src='';
        (isset($e->attr['alt'])) ? $alt = $e->attr['alt'] : $alt='';

        $url = $src;
        $query = parse_url($url, PHP_URL_QUERY);
        // Returns a string if the URL has parameters or NULL if not
        if ($query) {
            // $url .= '&__php_img_id='.$i;
        } else {
            // $url .= '?__php_img_id='.$i;
        }
        $new = "![".$alt."](".$url.")";
        $img[$i] = array('new'=>$new, 'url'=>$url, 'original'=>$e->outertext, 'oldurl'=>$src);
        $e->outertext = $new;
        $i++;
    }
    // save on resources.
    unset($e);
}

$response = $parsedown->setMarkupEscaped(true)->parse($autoEmbed->parse($html));

// now that all the images and links are generated... restore the url's and or code
if(count($a)>0){
    foreach($a as $link){
        // inside of code tag
        $response = str_replace($link['new'], htmlspecialchars($link['original'], ENT_HTML5|ENT_QUOTES), $response);
        // it's been formatted, fix the url back to the original
        // $response = str_replace($link['url'], $link['oldurl'], $response);
    }
}
if(count($img)>0){
    foreach($img as $image){
        // inside of code tag
        $response = str_replace($image['new'], htmlspecialchars($image['original'], ENT_HTML5|ENT_QUOTES), $response);
        // it's been formatted, fix the url back to the original
        // $response = str_replace($image['url'], $image['oldurl'], $response);
    }
}

$html->load($response, true, false);
if(count($html->find('a'))>0){
    foreach($html->find('a') as $e){
        if(stripos($e->href, 'javascript:')!==false){
            // gets any links that aren't in code blocks and have javascript in them
            $e->outertext = htmlspecialchars($e->outertext, ENT_HTML5|ENT_QUOTES);
        } 
    }
}
if(count($html->find('img'))>0){
    foreach($html->find('img') as $e){
        if(stripos($e->attr['src'], 'javascript:')!==false){
            // gets any links that aren't in code blocks and have javascript in them
            $e->outertext = htmlspecialchars($e->outertext, ENT_HTML5|ENT_QUOTES);
        } 
    }
}


echo $html;


?>
</body>

</html>
