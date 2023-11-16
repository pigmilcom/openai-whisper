<?php
// ------------------------------------------------------------------------------------------------------------------------
//
//  Description:    OpenAI Whisper API
//  Author:         PIGMIL
//  Website:        https://pigmil.com
//
// ------------------------------------------------------------------------------------------------------------------------

// Start Server Session
session_start();
 
define('OPENAI_API_KEY', 'sk-YOUR_OPENAI_API_KEY');

// Path to the directory you want to save the files
$target_dir = "./tmp/";
// List of file extention to be accepted
$valid_formats1 = array("mp3", "ogg", "flac", "wav"); 

// Check if file is uploaded
if(isset($_POST['submit']) || isset($_FILES["audio"]["name"])) {
    if(isset($_POST['submit']))
    {
        $file1 = $_FILES['audio']['name']; //input file name in this code is file1
        $size = $_FILES['audio']['size'];

        if(strlen($file1))
            {
                list($txt, $ext) = explode(".", $file1);
                if(in_array($ext,$valid_formats1))
                {
                        $actual_file_name = time().'-'.rand(1000,9999).".".$ext;
                        $tmp = $_FILES['audio']['tmp_name'];
                        if(move_uploaded_file($tmp, $target_dir.$actual_file_name))
                            {
                            //success upload  
                            $target_file = $target_dir.$actual_file_name;
                            }
                        else {
                            echo "Failed to upload file. Try again later.";   
                            return false;
                            exit();
                        }
                }
        }
    }  else { 
    // Prepare the file path
    $target_file = $target_dir . time().'-'. basename($_FILES["audio"]["name"]); 

    // Move the uploaded file to your target directory
    if (move_uploaded_file($_FILES["audio"]["tmp_name"], $target_file)) {
        //echo "The file ". basename( $_FILES["audio"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
        return false;
        exit();
    }

    }

    // Prepare the headers
    $headers = [
        'Authorization: Bearer ' . OPENAI_API_KEY,
    ];

    // Create a CURLFile object / preparing the image for upload
    $cfile = new CURLFile($target_file);

    // Initialize the cURL session
    $ch = curl_init();

    // Set the URL
    curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/audio/transcriptions');

    // Set the request method to POST
    curl_setopt($ch, CURLOPT_POST, 1);

    // Set the headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Prepare the request body with the file and model
    $data = [
        'file' => $cfile,
        'model' => 'whisper-1',
    ];

    // Set the request body
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // Set option to return the result instead of outputting it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request and get the response
    $response = curl_exec($ch);

    // Check if any error occurred during the request
    if(curl_errno($ch)){
        echo 'Request Error:' . curl_error($ch);
    }

    // Close the cURL session
    curl_close($ch); 

    // Output the response
    $_SESSION['whisper_response'] = $response;
    echo $response;

} else {
    echo "No file was uploaded.";
}
