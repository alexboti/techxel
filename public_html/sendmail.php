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
$subject = "Surveys";
$body = "First Name : $first_name\n";
$body .= "Last Name : $last_name\n";
//$body=$body . "Parent Name : $parent_name\n";
$body .= "Email: $email\n";
$body .= "\n How did you hear about us: ";

switch  ($s_how_hear)
{
case 1 :
$body .= "Website";
break;
case 2 :
//
$body .= "Magazine";
break;

case 3 :
//
$body .= "commercial";
break;

case 4 :
//
$body .= "Friends";
break;

case 5 :
//
$body .= "Internet";
break;

case 6 :
//
$body .= "News paper";
break;

case 7 :
//
$body .= "Business";
break;

case 8 :
//
$body .= "Company";
break;

case 9 :
//
$body .= "Flyer";
break;

case 10 :
//
$body .= "Radio";
break;

case 11 :
//
$body .= "Television";
break;

default:
//
$body .= "Other";
break;
}

$body .= "\n Are you a current customer of Techxel? ";
$body .= ($yesno==1) ? " yes" : " no";
$body .= "\n Did we provide you with some sort of services before ? ";
$body .= ($secondyesno==1) ? "yes" : " no";
$body .= ($secondyesno==1) ? "\n Types of services we offered you : \n $textarea2" : "\n";
$body .= "\n Were you satisfied with the services provided? ";
$body .= ($thirdyesno==1) ? " yes " : " no";
$body .= ($thirdyesno!=1) ? "\n Why no : $textarea3\n" : "\n";
$body .= "\n If you are still one of our clients, are you satisfied \n with the way we offer services to you or your company? ";
$body .= ($fourthyesno==1) ? " yes.  " : " no";
$body .= ($fourthyesno!=1) ? " \n Reasons : $textarea4\n" : " Thanks\n";
$body .= "\n According to you, how would you rate our service management? $select2\n";
$body .= "\n When you call and leave a message, is our staff quick in returning your call? $select3";
$body .= "\n Would you recommend us to other customers or companies? $select4";
$body .= ($select4==0 || $select4==-1) ? " \n$textfield\n" : "\n";
$body .= "\n What would you suggest we do in order to improve our services and better serve our customers in the future? $textarea5"; 
$body .= "\n If you have additional comments regarding our company, please share with : $textarea";

$to="support@techxel.com,info@techxel.com";
   $headers  = "From: " . $first_name . "<" . $to . ">\n"; 
   $headers .= "X-Mailer: PHP v" . phpversion() . "\n"; 
   $headers .= "X-Priority: 3\n"; //1 = Urgent, 3 = Normal 
   $headers .= "Return-Path: <". $email ."\n";  
   $headers .= "MIME-Version: 1.0\n";                                                     // Define MIME  
   $headers .= "Content-Type: multipart/mixed; \n\tboundary=\"{$mime_boundary}\"\n\n";    // Define content type and set Boundary 
   $headers .= "This is a multi-part message in MIME format.\n\n";                        // Explain it to earlier MTAs 

   /* Define the message proper */ 
   $message .= "--{$mime_boundary}\n";                               // Start the message with a mime boundary 
   $message .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n"; // Define the content type & charset 
   $message .= "Content-Transfer-Encoding: quoted-printable\n\n";    // Use Content-Type: text/html to send HTML 
   $message .= $body ."\n";                                           // Add our message, in this case it's plain text. 
    

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
   $message .= "--{$mime_boundary}--\n";                             // Insert the Termination Boundary 
  
   // send the message 
   //$success = 
   mail($to, $subject, $message, $headers); 
    
   header('Location: http://www.techxel.com/thanks.htm');

?>
