<?php
	/*$myVar=false;
	if ($FirstName=="")
	   {
		$myVar=true;
	   echo "The first name is required";
	   }
	elseif ($LastName == "")
	   {
	   $myVar=true;
	   echo "The last name is required";
	   }
	elseif ($email == "")
	   {
	   $myVar=true;
	   echo"Your email address is required";
	   }
	endif;
	
	if ($myVar==true)
		 header('Location: http://www.formeetingsolutions/meetingspec.html');
	endif;*/

	   /*$strmessage = "Visitor " ;
       $strmessage .= "$firstname";
	   $strmessage .= "$lastname";
	   $strmessage .= " $email";
	   $strmessage .= " sent a message from web page MS-Meetingspec.asp on the formeetingsolutions.com web site\n";*/
	   $strmessage = "Market            : $market\n";
	   $strmessage .= "What city         : $whatcity\n";
	   $strmessage .= "What state        : $whatstate\n";
	   $strmessage .= "What country      : $whatcountry\n";
	   $strmessage .= "From date         : $fromdate\n";
	   $strmessage .= "To date           : $todate\n";
	   $strmessage .= "Rate Range        : $raterange\n";
	   $strmessage .= "No of rooms       : $noofrooms\n";
	   $strmessage .= "No of attendees   : $noofattendees\n";
	   $strmessage .= "Need space?       : $function\n";
	   $strmessage .= "Any other info    : $anyotherinfo\n";
	   $strmessage .= "Title             : $title\n";
	   $strmessage .= "Title other       : $titleother\n";
	   $strmessage .= "First name        : $firstname\n";
	   $strmessage .= "Last name         : $fastname\n";
	   $strmessage .= "Job title         : $jobtitle\n";
	   $strmessage .= "Organization      : $orgname\n";
	   $strmessage .= "Address           : $address\n";
	   $strmessage .= "Suite             : $suite\n";
	   $strmessage .= "City              : $city\n";
	   $strmessage .= "Zip Code          : $zip\n";
	   $strmessage .= "Country           : $country\n";
	   $strmessage .= "Phone             : $phone\n";
	   $strmessage .= "Fax               : $fax\n";
	   $strmessage .= "Email             : $email\n";
	   $strmessage .= "Hear from         : $hearfrom\n";
	   $strmessage .= "Hear from other   : $hearfromother\n";
	   
	   $to="info@formeetingsolutions.com,nriker@formeetingsolutions.com";
	   //$to="mmatthews@formeetingsolutions.com";
	   //$to="samuel@techxel.com";
	   $subject="Visitor $firstname $lastname sent an email form from web page MS-Meetingspec.asp on the formeetingsolutions.com web site";
	   //, "Sales", "info@formeetingsolutions.com", strMessage,  "", "vandooren@rmr.com"
   $headers  = "From: " . $firstname . "<" . $to . ">\n"; 
   $headers .= "X-Mailer: PHP v" . phpversion() . "\n"; 
   $headers .= "X-Priority: 3\n"; //1 = Urgent, 3 = Normal 
   $headers .= "Return-Path: <". $email ."\n";  
   $headers .= "MIME-Version: 1.0\n";                                                     // Define MIME  
   $headers .= "Content-Type: multipart/mixed; \n\tboundary=\"{$mime_boundary}\"\n\n";    // Define content type and set Boundary 
   $headers .= "This is a multi-part message in MIME format.\n\n";                        // Explain it to earlier MTAs 
   mail($to, $subject, $strmessage, $headers); 
   header('Location: http://www.formeetingsolutions.com/thanks1.htm');
?>
