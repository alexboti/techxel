<?php

/**********************************************************************
 **********************************************************************
 ***Don't change or move this file. It's for AfricanbizDirectory.com***
 **********************************************************************
 **********************************************************************/


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
$subject = "Get me listed on ABD";
$body = "First Name : $First_Name\n" ;
$body=$body . "Last Name : $Last_Name\n";
$body=$body .  "Company: $Company\n";
$body=$body . "Adress 1 : $Address1\n";
$body=$body . "Adress 2 : $Address2\n";
$body=$body . "City : $City\n";
$body=$body . "State : $State\n";
$body=$body . "Zip code : $Zip\n";
$body=$body . "Phone: $Phone\n";
$body=$body .  "Email: $Email\n";
$body=$body . "Type of business : $Type_Business\n";
$body=$body . "Business Website : $Business_Website\n";
$body=$body . "About Us: $About_Us\n";
$body=$body . "I am interested in advertising:\n";
$body=$body . "Print : $Print\n";
$body=$body . "ABD : $ABD\n";
$body=$body . "All : $All\n";
$body=$body . "Comments : $Comments\n";

$to="info@africanyellowpages.us";
//$email1="subscription@styleelitetalent.com";
   $headers  = "From: " . $First_name . "<" . $to . ">\n"; 
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
    
//echo $body ;
/*reset($_FILES);  

   
   // DEBUG // 

   foreach($_FILES as $userfile){                                    // For each item item in the $_FILES array 
      $attachment = $userfile['tmp_name'];                           // Assign them to easier names 
      $attachment_name = $userfile['name'];  
      $attachment_type = $userfile['type']; 
      $attachment_size = $userfile['size']; 
      // DEBUG // 
      //print "processing ".$attachment." - ".$attachment_name." a ".$attachment_type." file of ".$attachment_size." bytes <BR>\n"; 
      // DEBUG // 

      if (is_uploaded_file($attachment)) {                           //Check for a file uploaded 
        // DEBUG // 
        //print $attachment." verified as uploaded.  Encoding and attaching.<BR>\n"; 
        // DEBUG //         
        $fp = fopen($attachment, "rb");                              //Open it for read in binary mode 
           $data = fread($fp, filesize($attachment));                //Read it 
           $data = chunk_split(base64_encode($data));                //Base 65 encode it and Chunk it   
        fclose($fp);                                                 // close the file 
        $message .= "--{$mime_boundary}\n";                          // Insert a Boundary, Encoding and Disposition instructions 
        $message .= "Content-Type: {$attachment_type};\n\tname=\"" . $attachment_name . "\"\n"; 
        $message .= "Content-Transfer-Encoding: base64\n"; 
        $message .= "Content-Disposition: attachment;\n\tfilename=\"" . $attachment_name . "\"\n\n"; 
        $message .= $data."\n\n";                                    //Insert the base64 encoded message 
        $message .= "\n";   
      } 
   } 
   */
   
   $message .= "--{$mime_boundary}--\n";                             
   // Insert the Termination Boundary
    
   // send the message 
   //$success = ,
   $to="info@africanyellowpages.us";
   mail($to, $subject, $message, $headers); 
    
   header('Location: http://www.africanbizdirectory.com/thanks.html');

?>
