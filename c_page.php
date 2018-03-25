<?php
	class CsPage {
		/* 
			simple data 
		*/
		private $pageName = "";
		private $imageURL = "";
		private $thumbURL = "";
		private $pageDescription = "";
		private $chapter = "";
		function getPageName() {
			return $this->pageName;
		}
		function getPageDescription() {
			return $this->pageDescription;
		}
		function getImageURL() {
			return $this->imageURL;
		}
		function getThumbURL() {
			return $this->thumbURL;
		}
		function getNextURL() {
			return $this->baseURL . $this->nextMD5;
		}
		function getPreviousURL() {
			return $this->baseURL . $this->previousMD5;
		}
		function getChapter() {
			return $this->chapter;
		}
		function hasNext() {
			return $this->nextMD5 !== "";
		}
		function hasPrevious() {
			return $this->previousMD5 !== "";
		}
		function setPageName($input) {
			$this->pageName = $input;
		}
		function setImageURL($input) {
			$this->imageURL = $input;
		}
		function setThumbURL($input) {
			$this->thumbURL = $input;
		}
		function setPageDescription($input) {
			$this->pageDescription = $input;
		}
		function setChapter($input) {
			$this->chapter = $input;
		}
		/*
			technical data
		*/
		private $baseURL = "";
		private $hash = "";
		private $previousMD5 = "";
		private $nextMD5 = "";
		function getBaseUrl() {
			return $this->baseURL;
		}
		function setBaseURL($input) {
			$this->baseURL = $input;
		}
		function getHash() {
			return $this->hash;
		}
		function setHash($input) {
			$this->hash = $input;
		}
		function getNextMD5() {
			return $this->nextMD5;
		}
		function getPreviousMD5() {
			return $this->previousMD5;
		}
		function setNextMD5($input) {
			$this->nextMD5 = $input;
		}
		function setPreviousMD5($input) {
			$this->previousMD5 = $input;
		}
	}
?>