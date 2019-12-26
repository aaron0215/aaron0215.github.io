<?php
include 'top.php';

// Open a CSV file
$debug = false;
if(isset($_GET["debug"])){
     $debug = true; 
}

$myFolder = 'data/';

$myFileName = 'menu';

$fileExt = '.csv';

$filename = $myFolder . $myFileName . $fileExt;

if ($debug) print '<p>filename is ' . $filename;

    $file=fopen($filename, "r");

if($debug){
    if($file){
       print '<p>File Opened Succesful.</p>';
    }else{
       print '<p>File Open Failed.</p>';
     }
}

if($file){
    if($debug) print '<p>Begin reading data into an array.</p>';

    // read the Name row, copy the line for each Name row
    // you have.
    $Names[] = fgetcsv($file);

    if($debug) {
         print '<p>Finished reading Names.</p>';
         print '<p>My Name array</p><pre>';
         print_r($Names);
         print '</pre>';
     }

     // read all the data
     while(!feof($file)){
         $Prices[] = fgetcsv($file);
     }

     if($debug) {
         print '<p>Finished reading data. File closed.</p>';
         print '<p>My data array<p><pre> ';
         print_r($Prices);
         print '</pre></p>';
     }
} // ends if file was opened 

    fclose($file);
?>

<article id="menuHP">
    <h2 class='menu'>Here is the menu of Hotpot stuff</h2>
    <table class="outer">
        <tr>
            <th><img src="photo/animatedhotpot.gif" alt="hotpot" width="400" height="300" class="menuside">
                <img src="photo/hotpotleft.png" alt="hotpot" width="300" height="400" class="menuside">
            </th>
            <th>
                <table class="menu">
<?php
foreach($Names as $Name){
    print'<tr>';
    print'<th class="menu">' . $Name[0] . '</th>';
    print'<th class="menu">' . $Name[1] . '</th>';
    print'<th class="menu">' . $Name[2] . '</th>';
    print'<th class="menu">' . $Name[3] . '</th>';
    print'</tr>' . PHP_EOL;
}
foreach($Prices as $Price){
    print'<tr>';
    print'<td class="menu">' . $Price[0] . '</td>';
    print'<td class="menu">' . $Price[1] . '</td>';
    print'<td class="menu">' . $Price[2] . '</td>';
    print'<td class="menu">' . $Price[3] . '</td>';
    print'</tr>' . PHP_EOL;
}

?>
                </table>
            </th>
        <th><img src="photo/hotpotright.png" alt="hotpot" width="300" height="400" class="menuside">
        <img src="photo/animatedCC.gif" alt="hotpot" width="400" height="300" class="menuside"></th>
           </tr>
    </table>
            </article>
<?php
include ('footer.php');
?>

    </body>
</html>