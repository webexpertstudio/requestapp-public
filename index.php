<?php
ob_start();
session_start(); 
require ("includes/functions.php");  
require ("includes/connection.php");  // Poziva kod za konekciju na bazu podataka.
include_once ("includes/header.php");

$_SESSION['reportfrom'] = date("Y-m-d");
$_SESSION['reportto'] = date("Y-m-d");

if($_SESSION['odobreno'] == "da")
{
	switch ($_SESSION['sess_agent_status'])
	{
		case "admin":
		case "manager":
			if(isset($_GET['page']))   // Proverava da li u linku postoji definisana promenljiva page.
			{
				require ($_GET['page'].".php");  // Ukoliko postoji učitava se php fajl sa nazivom promenljive.
			}
			else  
			{
				require ("managerview.php"); // Pri uspešnoj prijavi korisnika defaultna stranica je stranica proizvoda.
			}	
		break;
		case "agent":
			if(isset($_GET['page']))   // Proverava da li u linku postoji definisana promenljiva page.
			{
				require ($_GET['page'].".php");  // Ukoliko postoji učitava se php fajl sa nazivom promenljive.
			}
			else  
			{
				require ("agentview.php"); // Pri uspešnoj prijavi korisnika defaultna stranica je stranica proizvoda.
			}	
		break;
	}
}
else
{
	require ("login.php");
}
include ("includes/footer.php");
?>