<?php
class config{
	public function siteurl(){
		return "http://localhost/AnoWS/sia";
	}
	
	public function koneksi(){
		return mysqli_connect("localhost","root","","sia");
	}
}
