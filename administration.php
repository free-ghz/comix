<?php
	require_once("c_sql.php");
	require_once("c_page.php");

?><?php
	$database = new CsDatabase();
?><?php
	/*
		PROCESSING STUFF COMING FROM $_POST
	*/
		var_dump($_FILES);
	if (isset($_FILES["fileselect"]) && $_FILES["fileselect"]["tmp_name"] !== "") {
		$image_md5 = md5_file($_FILES["fileselect"]["tmp_name"]);
		$target_filename = "comix/" . $image_md5 . "." . pathinfo(basename($_FILES["fileselect"]["name"]), PATHINFO_EXTENSION);
		if (move_uploaded_file($_FILES["fileselect"]["tmp_name"], $target_filename)) {
			echo("> md5 is $image_md5");
			echo("> file uploaded!");

			$frontier = $database->getFrontier();
			echo("> got frontier!");

			if ($frontier !== false) {
				echo("> fontier is true.");

				$database->addPage($image_md5, $target_filename, $target_filename, $_POST["title"], $_POST["desc"], "", $frontier->getHash());
				$frontier->setNextMD5($image_md5);
				$database->updatePage($frontier);
			} else {
				echo("> frontier is false.");
				$database->addPage($image_md5, $target_filename, $target_filename, $_POST["title"], $_POST["desc"], "", "");
			}
		}
	}
?><?php
	$all_pages = $database->getAllPages2();
	var_dump($all_pages);
?>
<form id="upload" action="administration.php" method="POST" enctype="multipart/form-data">
	<h3>upload a new page</h3>
	<input type="file" id="fileselect" name="fileselect" /><br />
	<input type="text" name="title" /> title<br />
	<input type="text" name="desc" /> description<br />
	(chapter)<br />
	<button id="submitbutton" type="submit">upload</button>
</form>