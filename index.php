<?php
	require_once("c_page.php");
	require_once("c_sql.php");

	if (!isset($_GET['theme'])) {
		echo("u didnt have ?theme=a theme<br />i think one of these should do:<br /><br />");
		$a = glob("theme/*", GLOB_ONLYDIR);
		foreach ($a as $c) {
			$b = explode("/", $c);
			$d = $b[count($b)-1];
			echo('<a href="?theme='.$d.'">'.$d.'</a>');
		}
		echo("<br />-------------------------------------");
		var_dump($a);
		exit(0);
	} 

	$database = new CsDatabase();


	// get number
	if (!isset($_GET['page'])) {
		$page = $database->getFirst();
	} else {
		$page = $database->getPage($_GET['page']);
	}

	if ($page == false) exit("there is no comic :|");
	$page->setBaseURL("?theme=" . $_GET['theme'] . "&page=");


	$theme_name = $_GET['theme'];
	$themePage = file_get_contents("theme/" . $theme_name . "/page.html");
	$themePage = str_replace("{%pr%}", "theme/" . $theme_name . "/", $themePage);
	$themePage = str_replace("{%description%}", $page->getPageDescription(), $themePage);
	$themePage = str_replace("{%image%}", $page->getImageURL(), $themePage);
	if ($page->hasNext()) {
		$themePage = str_replace("{%next%}", $page->getNextURL(), $themePage);
		$themePage = str_replace("{%%next", "", $themePage);
		$themePage = str_replace("next%%}", "", $themePage);
	} else {
		$themePage = preg_replace("/\{%%next(.*?)next%%\}/ims", "", $themePage);
	}

	if ($page->hasPrevious()) {
		$themePage = str_replace("{%previous%}", $page->getPreviousURL(), $themePage);
		$themePage = str_replace("{%%previous", "", $themePage);
		$themePage = str_replace("previous%%}", "", $themePage);
	} else {
		$themePage = preg_replace("/\{%%previous(.*?)previous%%\}/ims", "", $themePage);
	}

	echo($themePage);

?>
