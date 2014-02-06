<?php

/* -------------------------- */
/* START VARIABLE DECLERATION */
/* -------------------------- */

$time_stamp = "";
$tmpName = "";
$uploadDir = "";
$fileType = "";
$splitFileName = "";
$resultsView = "display:none";
$fullyValidated = 0;
$uploadDirModified = "";

// Error stuff
$errorsOccur = 0;
$uploadError = "";
$uploadTime = "";



/* -------------------------- */
/*   END VARIABLE DECLERATION */
/* -------------------------- */

// If a post request is submitted, santize user input fields for the form
if ($_SERVER["REQUEST_METHOD"] == "POST")
{

	// Check if a file needs to be deleted, if so unlink it
	if(isset($_POST['deleteFile']))
	{
	  	$file = './uploads/' . $_POST['deleteFile'];

		// Delete the file if it exists
		if (file_exists($file)) {
		    unlink($file);
		}
	}

	/* ------------------ */
	/*  START VALIDATION  */
	/* ------------------ */

	// Setup the upload for the file
	// Check if file was uploaded
	if (isset($_POST['submit_files'])) {

		// Grab the location of the tmp uploaded file
		$tmpName = $_FILES['upload_file']['tmp_name'];

		// Divides up the path name for the file to assign it a unique name based on time
		$splitFileName = pathinfo($_FILES['upload_file']['name']);

		// Set upload time and format it nicely in filename
		$uploadTime = date("d_F_Y_H:i:s", microtime(true));

		// Set upload dir, and add in the upload time to make it unique, as well as -original
		$uploadDir = './uploads/' . $splitFileName['filename'] . '_' . $uploadTime . '-original.' . $splitFileName['extension'];

		// Gets the file type of the file
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
		// Check if file was uploaded and perform manipulation on it
		if (isset($_POST['submit_files'])) {
		    // Upload the file by moving it from /tmp/ to /uploads/
		    move_uploaded_file($tmpName, $uploadDir);
		    chmod($uploadDir, 0644);


		    // Start string manipulation on file
		    $fileContent = file_get_contents($uploadDir);

		    // Check for phone number formatting

		    // Check for <script> tags
		    $fileContent = str_replace('<script>', '', $fileContent);
		    $fileContent = str_replace('</script>', '', $fileContent);

		    // Check for urls and properly format them into hyper links
			// ftp://ftp.is.co.za/rfc/rfc1808.txt

			// http://www.ietf.org/rfc/rfc2396.txt
			// ldap://[2001:db8::7]/c=GB?objectClass?one
			// mailto:John.Doe@example.com
			// news:comp.infosystems.www.servers.unix
			// tel:+1-816-555-1212
			// telnet://192.0.2.16:80/
			// urn:oasis:names:specification:docbook:dtd:xml:4.1.2

		    // Count number of words start with t and and with e in the file and add to end of file
		    $pattern = '/\bt\w+?e\b/';
		    preg_match_all($pattern, $fileContent, $matches);
		    print_r(count($matches[0]));

		    // Save the file as a modified version of file to uploads directory
		    $uploadDirModified = './uploads/' . $splitFileName['filename'] . '_' . $uploadTime . '-modified.' . $splitFileName['extension'];
		    file_put_contents($uploadDirModified, $fileContent, FILE_APPEND | LOCK_EX);
			chmod($uploadDirModified, 0644);
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

			<input type="submit" name="submit_files" value="Upload File" id="btn">
			<br><span class="error"><?php echo $errorsOnPage ?></span>
		</form>
	</div>

	<div id="results" style="<?php echo $resultsView ?>">
		<h1>File Upload Information</h1>
		<?php if($fullyValidated == 1) : ?>
			<div id="file_output">
				<?php echo nl2br(file_get_contents($uploadDirModified)); ?>
			</div>
		<?php endif; ?>
	</div>

	<div id="archive">
		<h1>Archive of Files</h1>

		<form method="post" enctype="multipart/form-data">
			<table>
				<td><strong>Filename</strong></td>
				<td><strong>Date Modified</strong></td>
				<td><strong>Delete File</strong></td>
				<?php 

				// Example modified from PHP.net docs for read
				if ($handle = opendir('./uploads')) {
				    // Loop over directory while entries are found
				    while (false !== ($fileName = readdir($handle))) {
				    	// Don't show the . or ..
				    	if($fileName != "." && $fileName != "..") {
				    		// Display the row for each file in the directory
				    		echo '<tr>';
				    		echo '<td><a href="download.php?file='. $fileName . '">' . $fileName . '</td>';
					        echo '<td>' . date("d F Y H:i:s", filemtime('./uploads/' . $fileName)) . '</td>';
					        echo '<input type="hidden" name="deleteFile" value="' . $fileName . '">';
					        echo '<td><input type="submit" value="Delete"></td>';
					        echo '</tr>';
				    	}  
				    }
			    }

			    // Close the readdir handle
			    closedir($handle);

		    	?>
	    	</table>
    	</form>
	</div>
</div>

</body>
</html>