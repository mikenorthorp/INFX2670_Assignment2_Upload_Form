<?php

/* -------------------------- */
/* START VARIABLE DECLERATION */
/* -------------------------- */

$time_stamp = "";



/* -------------------------- */
/*   END VARIABLE DECLERATION */
/* -------------------------- */

// If a post request is submitted, santize user input fields for the form
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	/* ------------------ */
	/*  START VALIDATION  */
	/* ------------------ */

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

		// Upload the file
		// Check if file was uploaded
		if (isset($_POST['submit_files'])) {

			// Set upload dir
			$tmpName = $_FILES['upload_file']['tmp_name'];
			$uploadDir = './uploads/' . $_FILES['upload_file']['name'];

			// Make sure uploads directory is created
		    mkdir('./uploads');

		    // Upload the file
		    move_uploaded_file($tmpName, $uploadDir);

		    print_r($_FILES);
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
				<p><label for="upload_field">Upload a File</label></p>
				<input type="file" id="upload_file" name="upload_file"><br>
				<span class="error"><?php echo $uploadError ?></span><br>
			</div>

			<input type="submit" name="submit_files" value="Submit Files" id="btn">
			<br><span class="error"><?php echo $someEmptyError ?></span><br>
			<span class="error"><?php echo $errorsOnPage ?></span>
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