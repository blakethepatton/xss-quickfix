<?PHP
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "header.html";
require "libs/oembed.php";
require "libs/Parsedown.php";
require "libs/ParsedownExtra.php";
require "libs/ParsedownFilter.php";
require_once 'libs/HTMLPurifier.standalone.php';

$content = file_get_contents("input.md");
$autoEmbed = new App\Libraries\AutoEmbed();
//$parsedown = new Parsedown();
$parsedown = new ParsedownFilterExtra( 'myFilter' );

$cfg = HTMLPurifier_Config::createDefault();

$cfg->set('HTML.SafeIframe', true);
$cfg->set('URI.SafeIframeRegexp', 
    '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/|codepen\.io|embed\.plnkr\.co|jsfiddle\.net|widget-prime\.rafflecopter\.com)%'
    ); //allow YouTube and Vimeo

    
$purifier = new HTMLPurifier($cfg);



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

$response = $parsedown->parse($autoEmbed->parse($content));

$clean_html = $purifier->purify($response);

echo $clean_html;

include "footer.html";
?>