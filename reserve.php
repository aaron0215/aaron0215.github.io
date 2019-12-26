<?php
include ('top.php');

$thisURL = $domain . $phpSelf;
$firstName = "";
$email = "xxxx@xxx.xxx";
$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;
$errorMsg = array();
$dataRecord = array();
$mailed = false;
$type = "";
$typeERROR = false;
$Wedding = false;
$Birthday = false;
$Graduation = false;
$people = "1 - 2";
$peopleERROR = false;
$others = "";
$date = "";
$dateERROR = false;

if (isset($_POST["btnSubmit"])) {
   if (!securityCheck($thisURL)) {       
        $msg = '<p>Sorry you cannot access this page. ';     
        $msg.= 'Security breach detected and reported.</p>';       
        die($msg);   
    }
    
    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $firstName;
    
    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $lastName;
    
    $type = htmlentities($_POST["type"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $type;
    
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);  
    $dataRecord[] = $email;
    
    $people = htmlentities($_POST["people"],ENT_QUOTES,"UTF-8");
    $dataRecord[] = $people;
    
    $date = htmlentities($_POST["date"],ENT_QUOTES,"UTF-8");
    $dataRecord[] = $date;

    $birthday = htmlentities($_POST["txtOthers"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $others;
    
    if($firstName == ""){
        $errorMsg[] = "Please enter your first name";
        $firstNameERROR = true;
    }
    elseif(!verifyCharacter($firstName)){
       $errorMsg[] = "Your first name appears to have invalid characters."; 
       $firstNameERROR = true;
    }
    
    if($lastName == ""){
        $errorMsg[] = "Please enter your last name";
        $lastNameERROR = true;
    }
    elseif(!verifyCharacter($lastName)){
       $errorMsg[] = "Your last name appears to have invalid characters."; 
       $lastNameERROR = true;
    }
    
    if($type == ""){
    $errorMsg[] = "Please choose a reservation type";
    $typeERROR = true;
    }
    
    if($people == ""){
    $errorMsg[] = "Please choose a number of people";
    $peopleERROR = true;
    }
    
    if($date == ""){
    $errorMsg[] = "Please choose a number of people";
    $dateERROR = true;
    }
    
    if ($email == "") {    
        $errorMsg[] = 'Please enter your email address';
        $emailERROR = true;} 
    elseif (!verifyEmail($email)) {
        $errorMsg[] = 'Your email address appears to be incorrect.';
        $emailERROR = true;}
    
    if (isset($_POST["Wedding"])) {
    $Wedding = true;

    } else {
        $Wedding = false;
    }
    $dataRecord[] = "Wedding"; 
    
    if (isset($_POST["Birthday"])) {
    $Birthday = true;
    } else {
        $Birthday = false;
    }
    $dataRecord[] = "Birthday"; 
    
    if (isset($_POST["Graduation"])) {
    $Graduation = true;
    } else {
        $Graduation = false;
    }
    $dataRecord[] = "Graduation"; 
        
    if (!$errorMsg) {
        if ($debug){
            print PHP_EOL . '<p>Form is valid</p>';
            }
            $myFolder = 'data/';
            $myFileName = 'reservation';
            $fileExt = '.csv';
            $filename = $myFolder.$myFileName.$fileExt;
            if ($debug) print PHP_EOL . '<p>filename is ' . $filename;
            $file = fopen($filename, 'a');
            fputcsv($file, $dataRecord);
            fclose($file);
            
            $message = '<h2>Your information</h2>';
                $message .= '<p>';
                $message .= 'Here is your reservation: ';
                $message .= $people.' people; '.$date."; ".$type;
                $message .= '</p>';
            $to = $email;
            $cc = '';
            $bcc = '';

            $from = 'FastChuan<zzhang24@uvm.edu>';
            $subject = 'Your reservation confirmation';
            $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);

        }
}
    
?>

<article id="reserveSec">
    <?php
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
        print '<h2 class="contact">Thanks for your reservation.</h2>';
        print '<h2 class="contact">We will contact you to confirm more details.</h2>';
        print '<p>For your records a copy of this reservation has ';
        if (!$mailed) {
            print "not ";
        }
        print 'been sent to:</p>';
        print '<p>' . $email . '</p>';    
        } 
    else {
            print '<h1>Please fill the following blocks to finish reservation</h1>';
    if ($errorMsg) {
            print '<div id="errors">' . PHP_EOL; 
            print '<h2>Your form has the following mistakes that need to be fixed.</h2>' . PHP_EOL;
            print '<ol>' . PHP_EOL;
            foreach ($errorMsg as $err) {
                print '<li>' . $err . '</li>' . PHP_EOL;
            }
            
            print '</ol>' . PHP_EOL;
            print '</div>' . PHP_EOL;
        }
    ?>

    <form action="<?php print $phpSelf; ?>"
          id="formRegister"
          method="post">

                <fieldset class="contact">
                    <legend>Contact Information</legend>
                    <p>
                        <label class="required text-field" for="txtFirstName">First Name</label>  
                        <input autofocus
                                <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                                id="txtFirstName"
                                maxlength="45"
                                name="txtFirstName"
                                onfocus="this.select()"
                                placeholder="Enter your first name"
                                tabindex="100"
                                type="text"
                                value="<?php print $firstName; ?>"                    
                        >                    
                    </p>
                    
                    <p>
                        <label class="required text-field" for="txtLastName">Last Name</label>  
                        <input 
                                <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                                id="txtLastName"
                                maxlength="45"
                                name="txtLastName"
                                onfocus="this.select()"
                                placeholder="Enter your last name"
                                tabindex="100"
                                type="text"
                                value="<?php print $lastName; ?>"                    
                        >                    
                    </p>            
                      
                        <p>   
                        <label class="required text-field" for="txtEmail">Email</label>
                            <input 
                                    <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   id="txtEmail"
                                   maxlength="45"
                                   name="txtEmail"
                                   onfocus="this.select()"
                                   placeholder="Enter a valid email address"
                                   tabindex="120"
                                   type="text"
                                   value="<?php print $email; ?>"
                            >
                    </p>
                </fieldset> 

        <fieldset class="radio <?php if ($typeERROR) print ' mistake'; ?>">
                        <legend>Type of reservation</legend>
                        <p>
                            <label class="radio-field">
                                <input type="radio" 
                                       id="typeFamily" 
                                       name="type" 
                                       value="Family party" 
                                       tabindex="572"
                                       <?php if ($type == "Family party") echo ' checked="checked" '; ?>>
                            Family party</label>
                        </p>

                        <p>    
                            <label class="radio-field">
                                <input type="radio" 
                                       id="typeBusiness" 
                                       name="type" 
                                       value="Business dinner" 
                                       tabindex="572"
                                       <?php if ($type == "Business dinner") echo ' checked="checked" '; ?>>
                            Business dinner</label>
                        </p>
                        
                        <p>
                            <label class="radio-field">
                                <input type="radio" 
                                       id="typeFriends" 
                                       name="type" 
                                       value="Friends party" 
                                       tabindex="572"
                                       <?php if ($type == "Friends party") echo ' checked="checked" '; ?>>
                            Friends party</label>
                        </p>
                        
                        <p>
                            <label class="radio-field">
                                <input type="radio" 
                                       id="typeOthers" 
                                       name="type" 
                                       value="Others" 
                                       tabindex="572"
                                       <?php if ($type == "Others") echo ' checked="checked" '; ?>>
                            Other</label>
                        </p>
                      </fieldset>
                
        <fieldset  class="listbox <?php if ($peopleERROR) print ' mistake'; ?>">
                <legend>How many people?</legend>
                <select id="people" 
                        name="people" 
                        tabindex="520" >
                    <option <?php if($people=="1-2") print " selected "; ?>
                        value="1 - 2">1 - 2</option>
                    <option <?php if($people=="3-6") print " selected "; ?>
                        value="3 - 6">3 - 6</option>
                    <option <?php if($people=="7-10") print " selected "; ?>
                        value="7 - 10">7 - 10</option>
                    <option <?php if($people=="More than 10") print " selected "; ?>
                        value="More than 10">More than 10</option>
                </select>
        </fieldset>
        
        <fieldset  class="listbox <?php if ($dateERROR) print ' mistake'; ?>">
                <legend>The day you want</legend>
                <select id="date" 
                        name="date" 
                        tabindex="520" >
                    <option <?php if($date=="Monday") print " selected "; ?>
                        value="Monday">Monday</option>
                    <option <?php if($date=="Tuesday") print " selected "; ?>
                        value="Tuesday">Tuesday</option>
                    <option <?php if($date=="Wednesday") print " selected "; ?>
                        value="Wednesday">Wednesday</option>
                    <option <?php if($date=="Thursday") print " selected "; ?>
                        value="Thursday">Thursday</option>
                    <option <?php if($date=="Friday") print " selected "; ?>
                        value="Friday">Friday</option>
                    <option <?php if($date=="Saturday") print " selected "; ?>
                        value="Saturday">Saturday</option>
                    <option <?php if($date=="Sunday") print " selected "; ?>
                        value="Sunday">Sunday</option>
                </select>
        </fieldset>
        
        <fieldset class="checkbox">
            <legend>Special day we need to know:</legend>

                <p>
                    <label class="check-field">
                        <input <?php if ($Wedding) print " checked "; ?>
                            id="Wedding"
                            name="special"
                            tabindex="320"
                            type="checkbox"
                            value="Wedding"> Wedding ceremony</label>
                </p>
                
                <p>
                    <label class="check-field">
                        <input <?php if ($Birthday)  print " checked "; ?>
                            id="Birthday" 
                            name="special" 
                            tabindex="330"
                            type="checkbox"
                            value="Birthday"> Birthday</label>
                </p>
                
                <p>
                    <label class="check-field">
                        <input <?php if ($Graduation)  print " checked "; ?>
                            id="Graduation" 
                            name="special" 
                            tabindex="330"
                            type="checkbox"
                            value="Graduation"> Graduation</label>
                </p>
        </fieldset>
        
        <fieldset class="textarea">
                    <legend>Other things you hope us to know</legend>
                        <textarea id="txtOthers" 
                                  name="txtOthers" 
                                  onfocus="this.select()" 
                                  tabindex="200"><?php print $others; ?></textarea>  
                </fieldset>
        
            <fieldset class="buttons">
                <legend></legend>
                <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Register" >
            </fieldset> <!-- ends buttons -->
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