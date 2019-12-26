<?php
include 'top.php';
$thisURL = $domain . $phpSelf;
$firstName = "";
$firstNameERROR = false;
$email = "";
$emailERROR = false;
$describe = "";
$describeERROR = false;
$contactRecord = array();
$contactERROR = array();

if (isset($_POST["btnSubmit"])) {
   if (!securityCheck($thisURL)) {       
        $msg = '<p>Sorry you cannot access this page. ';     
        $msg.= 'Security breach detected and reported.</p>';       
        die($msg);   
    }
    
    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $contactRecord[] = $firstName;
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);  
    $contactRecord[] = $email;
    $describe = htmlentities($_POST["txtDes"], ENT_QUOTES, "UTF-8");
    $contactRecord[] = $describe;
    
    if($firstName == ""){
        $contactERROR[] = "Please enter your first name";
        $firstNameERROR = true;
    }
    elseif(!verifyCharacter($firstName)){
       $contactERROR[] = "Your first name appears to have invalid characters."; 
       $firstNameERROR = true;
    }
    
    if ($email == "") {    
        $contactERROR[] = 'Please enter your email address';
        $emailERROR = true;} 
    elseif (!verifyEmail($email)) {
        $contactERROR[] = 'Your email address appears to be incorrect.';
        $emailERROR = true;}

    if ($describe == "") { 
        $contactERROR[] = "Please enter your problem";
        $describeERROR= true;
        }
        
    if (!$contactERROR) {
        if ($debug){
            print PHP_EOL . '<p>Form is valid</p>';
            }
            $myFolder = 'data/';
            $myFileName = 'contact';
            $fileExt = '.csv';
            $filename = $myFolder.$myFileName.$fileExt;
            if ($debug) print PHP_EOL . '<p>filename is ' . $filename;
            $file = fopen($filename, 'a');
            fputcsv($file, $contactRecord);
            fclose($file);
    }
}   
?>

<article id="contactSec">
    <?php
    if (isset($_POST["btnSubmit"]) AND empty($contactERROR)) { // closing of if marked with: end body submit
        print '<h1>Thanks for your contact.</h1>';
        print '<h2 class="contact">We will reply your message within 24 hours.</h2>';
        } 
    else{
        print '<h2 class="contact">Phone: 123-456-7890</h2>';
        print '<h2 class="contact">Email: Fastchuan@gmail.com</h2>';
        print '<h2 class="contact">You can type in your question in the form below</h2>';
        print '<p class="form-heading">We will reply your message within 24 hours</p>';
    if ($contactERROR) {
            print '<div id="errors">' . PHP_EOL; 
            print '<h2>Your form has the following mistakes that need to be fixed.</h2>' . PHP_EOL;
            print '<ol>' . PHP_EOL;
            foreach ($contactERROR as $err) {
                print '<li>' . $err . '</li>' . PHP_EOL;
            }
            
            print '</ol>' . PHP_EOL;
            print '</div>' . PHP_EOL;
        }
    ?>
        <form action="<?php print $phpSelf; ?>"
          id="contactForm"
          method="post">
            
            <fieldset class="contact">
                    <legend>Contact Information</legend>
                    <p>
                        <input autofocus
                                <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                                id="txtFirstName"
                                maxlength="45"
                                name="txtFirstName"
                                onfocus="this.select()"
                                placeholder="Enter your first name"
                                tabindex="100"
                                type="text"
                                value="<?php print $firstName; ?>" >                    
                    </p>
                    
                    <p>   
                            <input 
                                    <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   id="txtEmail"
                                   maxlength="45"
                                   name="txtEmail"
                                   onfocus="this.select()"
                                   placeholder="Enter a valid email address"
                                   tabindex="120"
                                   type="text"
                                   value="<?php print $email; ?>" >
                    </p>
                   

                    <textarea <?php if ($describeERROR) print 'class="mistake"'; ?>
                                  id="txtDes" 
                                  name="txtDes" 
                                  onfocus="this.select()" 
                                  placeholder="Please describe your question briefly"
                                  tabindex="200"><?php print $describe; ?></textarea>
            </fieldset>
                    <fieldset class="buttons">
                    <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Submit" >
                    </fieldset>
        </form>
    
    <?php     
    } //end body submit
?>
</article>

<?php
include ('footer.php');
?>

   </body>
</html>