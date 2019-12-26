<?php
// parse the url into htmlentities to remove any suspicious vales that someone
// may try to pass in. htmlentities helps avoid security issues.

$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");

// break the url up into an array, then pull out just the filename
$path_parts = pathinfo($phpSelf);

 ?>	
<!DOCTYPE html>
<html lang="en">
    <head>
<!-- you can add php code here (similar to nav.php) to print a different title on each page -->
        <title>FastChuan</title>

        <meta charset="utf-8">
        <meta name="author" content="Aaron Zhang & Anyi Huang">
        <meta name="description" content="website of resterant">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/custom3.css" type="text/css" media="screen">



    <?php
    $debug = false;

        // This if statement allows us in the classroom to see what our variables are
        // This is NEVER done on a live site 
        if (isset($_GET["debug"])) {
            $debug = true;
        }
    
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// PATH SETUP
//

$domain = '//';

$server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8');

$domain .= $server;


        if ($debug) {

            print '<p>php Self: ' . $phpSelf;
            print '<p>Path Parts<pre>';
            print_r($path_parts);
            print '</pre></p>';
        }

// %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// inlcude all libraries. 
// 
// Common mistake: not have the lib folder with these files.
// Google the difference between require and include
//
        print  PHP_EOL . '<!-- include libraries -->' . PHP_EOL;

        require_once('lib/security.php');
        if ($path_parts['filename'] == "reserve" OR $path_parts['filename'] == "contact" ) {
            print PHP_EOL . '<!-- include form libraries -->' . PHP_EOL;
                    include "lib/validation-functions.php";
                    include "lib/mail-message.php";}

        print  PHP_EOL . '<!-- finished including libraries -->' . PHP_EOL;
        ?>	

    </head>
    <?php
// giving each body tag an id really helps with css later on
            print '<body id="' . $path_parts['filename'] . '">';
    print'<!-- ######################     Start of Body   ############################ -->';
    include ('header.php');
    include ('nav.php');
    ?>