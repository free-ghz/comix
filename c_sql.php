<?php
	require_once("c_page.php");

	class CsDatabase {
		private $db;

		function __construct() {
			$this->dbInit();
		}

		private function dbInit() {
			$db = null;
			try {
				$db = new PDO("sqlite:comix.sql"); // Skapar databasen
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				// Tabellen inte finns så skapas den
				$db->exec(
					"CREATE TABLE IF NOT EXISTS pages (serial INTEGER PRIMARY KEY, url TEXT, thumb TEXT, hash TEXT, next TEXT, prev TEXT, title TEXT, description TEXT, chapter INTEGER)"
				);
				$db->exec(
					"CREATE TABLE IF NOT EXISTS chapters (serial INTEGER PRIMARY KEY, title TEXT, description TEXT)"
				);
				$db->exec(
					"CREATE TABLE IF NOT EXISTS setting (serial INTEGER PRIMARY KEY, key TEXT, value TEXT)"
				);
			}
			catch(PDOEception $e) {
				die("Something went wrong -> " .$e->getMessage());
			}
			$this->db = $db;
		}

		private function makeCsPage($data) {
			$page = new CsPage();
			$page->setImageURL($data["url"]);
			$page->setThumbURL($data["thumb"]);
			$page->setPageName($data["title"]);
			$page->setPageDescription($data["description"]);
			$page->setHash($data["hash"]);
			$page->setChapter($data["chapter"]);

			if ($data["next"] == null) {
				$page->setNextMD5("");
			} else {
				$page->setNextMD5($data["next"]);
			}
			if ($data["prev"] == null) {
				$page->setPreviousMD5("");
			} else {
				$page->setPreviousMD5($data["prev"]);
			}

			return $page;
		}

		function getPage($hash) {
			$getPage = "SELECT * FROM pages WHERE hash LIKE '$hash'";
			$getPage = $this->db->prepare($getPage);
			$getPage->execute();
			$data = $getPage->fetch();

			return $this->makeCsPage($data);
		}

		function getFrontier() {
			$getPage = "SELECT * FROM pages WHERE next LIKE ''";
			$getPage = $this->db->prepare($getPage);
			$getPage->execute();
			$data = $getPage->fetch();
			if ($data == false) return false;
			return $this->makeCsPage($data);
		}

		function getFirst() {
			$getPage = "SELECT * FROM pages WHERE prev LIKE ''";
			$getPage = $this->db->prepare($getPage);
			$getPage->execute();
			$data = $getPage->fetch();
			if ($data == false) return false;
			return $this->makeCsPage($data);
		}


		function getAllPages() {
			// turns out this is where things get messy
			$allPages = array();
			$nextHash = "";
			$i = 0;
			while (true) {
				$getPage = "SELECT * FROM pages WHERE prev LIKE '".$nextHash."'";
				$getPage = $this->db->prepare($getPage);
				$getPage->execute();
				$data = $getPage->fetch();
				if ($data == false) break;
				if ($i == 20) break;

				$page = $this->makeCsPage($data);
				$allPages[] = $page;
				

				$nextHash = $page->getHash();
				if ($nextHash == "") break;
				$i++;
			}
			return $allPages;
		}
		function getAllPages2() {
			// turns out this is where things get messy
			$allPages = array();
			$nextHash = "";
			$i = 0;
			$getPage = "SELECT * FROM pages";
			$getPage = $this->db->prepare($getPage);
			$getPage->execute();
			$data = $getPage->fetchAll();
			foreach($data as $d) {
				$page = $this->makeCsPage($d);
				$allPages[] = $page;	
			}
			
			return $allPages;
		}

		function addPage($hash, $url, $thumb, $title, $description, $next, $prev, $chapter = 0) {
			$statement = $this->db->prepare("INSERT INTO pages(url, thumb, hash, next, prev, title, description, chapter) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$statement->bindValue(1, $url, SQLITE3_TEXT);
			$statement->bindValue(2, $thumb, SQLITE3_TEXT);
			$statement->bindValue(3, $hash, SQLITE3_TEXT);
			$statement->bindValue(4, $next, SQLITE3_TEXT);
			$statement->bindValue(5, $prev, SQLITE3_TEXT);
			$statement->bindValue(6, $title, SQLITE3_TEXT);
			$statement->bindValue(7, $description, SQLITE3_TEXT);
			$statement->bindValue(8, $chapter, SQLITE3_INTEGER);
			$statement->execute();
		}

		function updatePage($csPage) {
			$statement = $this->db->prepare("UPDATE pages SET url = ?, thumb = ?, next = ?, prev = ?, title = ?, description = ?, chapter = ? WHERE hash = ?");
			$statement->bindValue(1, $csPage->getImageURL(), SQLITE3_TEXT);
			$statement->bindValue(2, $csPage->getThumbURL(), SQLITE3_TEXT);
			$statement->bindValue(3, $csPage->getNextMD5(), SQLITE3_TEXT);
			$statement->bindValue(4, $csPage->getPreviousMD5(), SQLITE3_TEXT);
			$statement->bindValue(5, $csPage->getPageName(), SQLITE3_TEXT);
			$statement->bindValue(6, $csPage->getPageDescription(), SQLITE3_TEXT);
			$statement->bindValue(7, $csPage->getChapter(), SQLITE3_INTEGER);
			$statement->bindValue(8, $csPage->getHash(), SQLITE3_TEXT);
			$statement->execute();
		}
	}
?>