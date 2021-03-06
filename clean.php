<?PHP
include "header.html";
require "oembed.php";
require "simple_html_dom.php";

require "Parsedown.php";
require "ParsedownExtra.php";
require "ParsedownFilter.php";

$content = file_get_contents("input.md");
$autoEmbed = new App\Libraries\AutoEmbed();
//$parsedown = new Parsedown();
$html = new simple_html_dom();
$parsedown = new ParsedownFilterExtra( 'myFilter' );

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
    foreach($html->find('a') as $e)
    {
        $url   = (isset($e->href)) ? $e->href : '';
        $text  = (isset($e->innertext)) ? $e->innertext : $url;
        $class = (isset($e->attr['class'])) ? trim($e->attr['class']) : '';
        $id    = (isset($e->attr['id'])) ? trim($e->attr['id']) : '';

        //setup the class
        $class = str_replace(' ', ' .', $class);
        $class = (strlen($class)>0) ? '.'.$class : null;
        //setup the id
        $id    = (strlen($id)>0) ? '#'.$id : null;

        $new = "[".$text."](".$url.")";

        if($class!=null || $id!=null){
            $new .= ' {'.trim($class.' '.$id).'}';
        }        

        $a[] = array('new'=>$new, 'original'=>$e->outertext);
        $e->outertext = $new;
    }
    unset($e);
}

// convert html img tags to md flavored img tags

$img = array(); //save original element info
if(count($html->find('img'))>0){
    foreach($html->find('img') as $e){
        $url   = (isset($e->attr['src'])) ? $e->attr['src'] : '';
        $text  = (isset($e->attr['alt'])) ? $e->attr['alt'] : '';
        $class = (isset($e->attr['class'])) ? trim($e->attr['class']) : '';
        $id    = (isset($e->attr['id'])) ? trim($e->attr['id']) : '';

        //setup the class
        $class = str_replace(' ', ' .', $class);
        $class = (strlen($class)>0) ? '.'.$class : null;
        //setup the id
        $id    = (strlen($id)>0) ? '#'.$id : null;

        $new = "![".$text."](".$url.")";

        if($class!=null || $id!=null){
            $new .= ' {'.trim($class.' '.$id).'}';
        } 
        
        $img[] = array('new'=>$new, 'original'=>$e->outertext);
        $e->outertext = $new;
    }
    // save on resources.
    unset($e);
}

// parse the page
$response = $parsedown->setMarkupEscaped(true)->parse($autoEmbed->parse($html));

// now that all the images and links are generated... restore non-markdownified with their old content.
if(count($a)>0){
    foreach($a as $link){
        // inside of code tag
        $response = str_replace($link['new'], htmlspecialchars($link['original'], ENT_HTML5|ENT_QUOTES), $response);
        
    }
}
if(count($img)>0){
    foreach($img as $image){
        // inside of code tag
        $response = str_replace($image['new'], htmlspecialchars($image['original'], ENT_HTML5|ENT_QUOTES), $response);
    }
}

// reload the document with MD run.
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
            // gets any image sources that aren't in code blocks and have javascript in them
            $e->outertext = htmlspecialchars($e->outertext, ENT_HTML5|ENT_QUOTES);
        } 
    }
}


echo $html;

include "footer.html";
?>