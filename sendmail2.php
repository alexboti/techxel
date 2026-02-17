<?php

// Calculate the maximum size that can be uploaded to php // 
   $maxsize = ini_get('upload_max_filesize'); 
   if (!is_numeric($maxsize)) { 
       if (strpos($maxsize, 'G') !== false) 
           $maxsize = intval($maxsize)*1024*1024*1024; 
       elseif (strpos($maxsize, 'M') !== false) 
           $maxsize = intval($maxsize)*1024*1024; 
       elseif (strpos($maxsize, 'K') !== false) 
           $maxsize = intval($maxsize)*1024; 
   } 
   // When we email the attachemnts, we'll have to base64 encode and chunk it. // 
   // Converting to base 64 increases the overhead by a factor 1.33 therefore  // 
   // we don't want to accept more than maxsize/1.33 into the raw files.       // 
   $maxsize = intval($maxsize/1.33); 
   //print "<form id=\"chirurgeondata\" enctype=\"multipart/form-data\" name=\"send\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";  

// Define mime boundary // 
   $mime_boundary = "==Multipart_Boundary_wjk".md5(time())."wjk==";  
    
   /* Prepare the headers */ 
$subject = "Bookings";
$body = "Full Name : $FullName\n";
$body .= "Title : $Title\n";
$body .= "Position : $Position\n";
$body .= "Company : $Company\n";
$body .= "Business : $Business\n";
$body .= "Position : $Position\n";
$body .= "City : $City\n";
$body .= "Town : $Town\n";
$body .= "State : $select\n";
$body .= "Zip code : $Zip\n";
$body .= "Borough : $Borough\n";
$body .= "Email: $Email\n";
$body .= "Web site : $Website\n";
$body .= "Type of model to hire : $Hire\n";
$body .= "Phone number: $PhoneHire\n";
$body .= "Talent Name : $TalentName\n";
$body .= "Purpose of assignement : $Purpose\n";
$body .= "Assignement location : $Location\n";
$body .= "Assignment detail : $Detail\n";
$body .= "Name and address of Studio : $Studio\n";
$body .= "Studio phone: $StudioPhone\n";
$body .= "Start time : $StartTime\n";
$body .= "End Time : $FinishTime\n";
$body .= "Preferred time : $Preferred\n";
$body .= "Date assignment : $DateAssignment\n";
$body .= "Wardrobe/special requirements: $Wardrobe\n";

$body .= "Will this project be published?:" . ($yesno==1) ? " yes\n" : " no\n";
$body .= "Will travel and hotel accommodation?:" . ($secondyesno==1) ? " yes\n" : " no\n";
$body .= "Full Detail: $FullDetail\n";
$body .= "Will travel be paid?:" . ($thirdyesno==1) ? " yes\n\n" : " no\n\n";
$body .= "FINANCE:\n";
$body .= "Model fees agreed: $Fees\n";
$body .= "Agency fees: $AgencyFees\n";
$body .= "Chaperone fees (if applicable): $Chaperone\n";
$body .= "Payment method: $s_Payment\n";
$body .= "Total: $Total\n\n\n";
$body .= "Print name for signature: $PrintName\n";
$to= "mikestyles@styleelitetalent.com,michelle@styleelitetalent.com";

   $headers  = "From: " . $FullName . "<" . $to . ">\n"; 
   $headers .= "X-Mailer: PHP v" . phpversion() . "\n"; 
   $headers .= "X-Priority: 3\n"; //1 = Urgent, 3 = Normal 
   $headers .= "Return-Path: <". $Email ."\n";  
   $headers .= "MIME-Version: 1.0\n";                                                     // Define MIME  
   $headers .= "Content-Type: multipart/mixed; \n\tboundary=\"{$mime_boundary}\"\n\n";    // Define content type and set Boundary 
   $headers .= "This is a multi-part message in MIME format.\n\n";                        // Explain it to earlier MTAs 

   /* Define the message proper */ 
   $message .= "--{$mime_boundary}\n";                               // Start the message with a mime boundary 
   $message .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n"; // Define the content type & charset 
   $message .= "Content-Transfer-Encoding: quoted-printable\n\n";    // Use Content-Type: text/html to send HTML 
   $message .= $body ."\n";                                           // Add our message, in this case it's plain text. 
    
   $message .= "--{$mime_boundary}--\n";                             // Insert the Termination Boundary 
  
   // send the message 
   //$success = 
   mail($to, $subject, $message, $headers); 
    
   header('Location: http://www.styleelitetalent.com/thanks1.htm');

?>
