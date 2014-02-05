<?php

/* -------------------------- */
/* START VARIABLE DECLERATION */
/* -------------------------- */



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
		// Unhide results area
		$resultsView = "";
		$fullyValidated = 1;

		// Write contents to file, append if already data inside, and lock file from being changed while writing
		// Make sure unicode characters dont get printed
		file_put_contents(RESULTS_FILE, htmlspecialchars_decode($content), FILE_APPEND | LOCK_EX);
		// Make sure it is readable by all users
		chmod(RESULTS_FILE, 0644);
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
		<form method="post">
			<h1> File Upload and Manipulation </h1>

			<div id="upload" <?php if(!empty($uploadError)) { echo 'class="errorOutline"'; } ?>>
				<p><label for="upload_field">Name</label></p>
				<input type="text" id="upload_field" name="upload_field"><br>
				<span class="error"><?php echo $uploadError ?></span><br>
			</div>

			<input type="submit" value="Submit" id="btn">
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