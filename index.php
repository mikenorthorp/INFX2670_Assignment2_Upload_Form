<?php

/* -------------------------- */
/* START VARIABLE DECLERATION */
/* -------------------------- */

$time_stamp = "";
$tmpName = "";
$uploadDir = "";
$fileType = "";

// Error stuff
$errorsOccur = 0;
$uploadError = "";



/* -------------------------- */
/*   END VARIABLE DECLERATION */
/* -------------------------- */

// If a post request is submitted, santize user input fields for the form
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	/* ------------------ */
	/*  START VALIDATION  */
	/* ------------------ */

	// Setup the upload for the file
	// Check if file was uploaded
	if (isset($_POST['submit_files'])) {

		// Set upload dir
		$tmpName = $_FILES['upload_file']['tmp_name'];
		$uploadDir = './uploads/' . $_FILES['upload_file']['name'];
		$fileType = $_FILES['upload_file']['type'];

		// Make sure uploads directory is created
	    mkdir('./uploads');

	    // Check if file is empty
	    if(filesize($tmpName) == 0) {
	    	$uploadError = "Could not upload, File empty";
	    	$errorsOccur++;
	    }

	    // Check if file is not a text file
	    if ($fileType != 'text/plain') {
	    	if(!empty($uploadError)) {
	    		$uploadError = $uploadError . " and File is not a .txt file";
	    	} else {
	    		$uploadError = "Could not upload, File is not a .txt file";
	    	}
	    	$errorsOccur++;
	    }
    }


	/* ---------------- */
	/*  END VALIDATION  */
	/* ---------------- */


	/* -------------------------- */
	/* START POST VALIDATION CODE */
	/* -------------------------- */

	// If validation does not pass
	if ($errorsOccur > 0) {
		$errorsOnPage = "There are {$errorsOccur} error(s) on the page";
	} else { // Validation passes

		// Setup the upload for the file
		// Check if file was uploaded
		if (isset($_POST['submit_files'])) {
		    // Upload the file by moving it from /tmp/ to /uploads/
		    move_uploaded_file($tmpName, $uploadDir);
	    }

		// Unhide results area
		$resultsView = "";
		$fullyValidated = 1;
	}

	/* -------------------------- */
	/*   END POST VALIDATION CODE */
	/* -------------------------- */
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>File Upload</title>
	<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<div id="content">
	<h1> INFX 2670 Assignment 2 - Michael Northorp </h1>
	<div id="form">
		<form method="post" enctype="multipart/form-data">
			<h1> File Upload and Manipulation </h1>

			<div id="upload" <?php if(!empty($uploadError)) { echo 'class="errorOutline"'; } ?>>
				<p><label for="upload_file">Upload a File</label></p>
				<input type="file" id="upload_file" name="upload_file"><br>
				<span class="error"><?php echo $uploadError ?></span><br>
			</div>

			<input type="submit" name="submit_files" value="Submit Files" id="btn">
			<br><span class="error"><?php echo $errorsOnPage ?></span>
		</form>
	</div>

	<div id="results" style="<?php echo $resultsView ?>">
		<h1>File Upload</h1>
	</div>

	<div id="archive">
		<h1>Archive of Files</h1>
	</div>
</div>

</body>
</html>