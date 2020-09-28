<?php

// A very simple PHP ShareX Custom uploader that anyone can use!

/*************************
*  Configuration Values  *
*************************/

/*
*   List of tokens.
*   Never share tokens to random users, only share it
*   with users that you allow
*   Using a random password generator for a random token is preferred
*/
define("TOKENS", array(
    "FIRST_TOKEN",
    "SECOND_TOKEN",
));

/*
*   Determines if the filename should be random (boolean value)
*   If set to true, files that are uploaded would be renamed to a 
*   set of random characters
*   If false, use original filename
*/
define("RANDOM_FILENAME", true);

/*
*   Length of the random filename
*   Requires "RANDOM_FILENAME" to be true
*/
define("RANDOM_LENGTH", 7);

/*
*   Character set to use for random filename
*   Requires "RANDOM_FILENAME" to be true
*/
define("CHARACTERS_AVAILABLE", "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");

/*
*   Base url of your website
*/
define("BASE_URL", "https://yourdomain.com");

/*
*   The output url for your uploaded content
*   Make sure to include a "/" at the end
*/
define("OUTPUT_URL", "https://yoursubdomain.yourdomain.com/");

/*
*   The output folder for your uploaded content
*   Leave a "/" for current folder
*/
define("OUTPUT_FOLDER", "/");


/*
*   Core
*/
if (isset($_POST["token"])) {
    if (in_array($_POST["token"], TOKENS)) {
        $targetFile = $_FILES["sharexcustomuploader"]["name"];
        $fileExtension = pathinfo($targetFile, PATHINFO_EXTENSION);
        if (RANDOM_FILENAME) {
            $randomString = generateRandomString(RANDOM_LENGTH);
            $targetFolder = getcwd() . OUTPUT_FOLDER . $randomString . "." . $fileExtension;
        } else {
            $targetFolder = getcwd() . OUTPUT_FOLDER . $_FILES["sharexcustomuploader"]["name"] . "." . $fileExtension;
        }

        if (move_uploaded_file($_FILES["sharexcustomuploader"]['tmp_name'], $targetFolder)) {
            $output = explode(OUTPUT_FOLDER, $targetFolder);
            printf("%s", OUTPUT_URL . end($output));
        } else {
            printf("File upload failed... (Ensure your directory has 777 permissions!");
        }
    } else {
        header("Location: ".BASE_URL);
    }
} else {
    header("Location: ".BASE_URL);
}

function generateRandomString($length = 7) {
    $characterSet = CHARACTERS_AVAILABLE;
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characterSet[random_int(0, strlen(CHARACTERS_AVAILABLE) - 1)];
    }
    return $randomString;
}

?>
