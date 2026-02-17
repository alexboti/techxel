<?php
 
 $fp="fcontact.txt";
/*
 Calculate the maximum size that can be uploaded to php // 
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
   

   foreach($_FILES as $fp){                                    // For each item item in the $_FILES array 
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
   
//* Prepare the headers 
$subject = $contact;
$body = "Full Name : $your_name\n";
$body .= "Email : $your_email\n";
//$body=$body . "Parent Name : $parent_name\n";
$body .= "Email: $your_comments\n";


$to="support@techxel.com,info@techxel.com";
   $headers  = "From: " . $first_name . "<" . $to . ">\n"; 
   $headers .= "X-Mailer: PHP v" . phpversion() . "\n"; 
   $headers .= "X-Priority: 3\n"; //1 = Urgent, 3 = Normal 
   $headers .= "Return-Path: <". $email ."\n";  
   $headers .= "MIME-Version: 1.0\n";                                                     // Define MIME  
   $headers .= "Content-Type: multipart/mixed; \n\tboundary=\"{$mime_boundary}\"\n\n";    // Define content type and set Boundary 
   $headers .= "This is a multi-part message in MIME format.\n\n";                        // Explain it to earlier MTAs 

   //* Define the message proper 
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
   
   $message .= "--{$mime_boundary}--\n";                             // Insert the Termination Boundary 
  
   // send the message 
   //$success = 
   //mail($to, $subject, $message, $headers); 
    
   //header('Location: http://www.techxel.com/thanks.htm');
   
   
   */
   
   //email address verification
if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {

}

else {

  echo "Invalid email address.";
  exit();
}


//echo "Creation of the file fcontact.txt.\n";

//fct_create_file();

//function create or open the contact file
   $filename = "fcontact.txt";
   if  (file_exists($filename)) 
   {
	$fp = fopen($filename, 'a') or die("can't open file");
	
	//echo "\nFile already exists !";
	$myarray=array();
	$myarray[0]="$fname";
	$myarray[1]="$lname";
	$myarray[2]="$address";
	$myarray[3]="$city";
	$myarray[4]="$zip";
	$myarray[5]="$state";
	$myarray[6]="$email\n";
	$myContent="$myarray[0],$myarray[1],$myarray[2],$myarray[3],$myarray[4],$myarray[5],$myarray[6]\n;";
	fputs ($fp,$myContent,8192);
	//fwrite ($fp,$Myarray,8192);
	//echo "File was updated\n";
	//echo "New contact list\n";
	fclose($fp);
	$fp = fopen($filename, 'r');
	while (!feof($fp))
	{ 
	$row= fgets($fp,4096);
	}
	
	fclose($fp);
	
	} 
	else 
	{
		$fp = fopen($filename, 'w') or die("can't open file");
		//initialisation du tableau
		$myarray[0]="First Name";
		$myarray[1]="Last Name";
		$myarray[2]="Address";
		$myarray[3]="City";
		$myarray[4]="Zipcode";
		$myarray[5]="State";
		$myarray[6]="Email";
		$myContent="$myarray[0],$myarray[1],$myarray[2],$myarray[3],$myarray[4],$myarray[5],$myarray[6]\n";
		fwrite($fp,$myContent);
		
		//echo "File was created";
		fclose($fp);
	}


?>
