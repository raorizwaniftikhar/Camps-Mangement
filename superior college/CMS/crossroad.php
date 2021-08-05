<?php

session_start();

		switch ($_SESSION['MM_UserGroup']) {
		case 0:
			header("Location: adminT.php"); 
			break;
		case 1:
			header("Location: student.php"); 
			break;
		case 2:
			header("Location: iteacher.php");
			break;
		}

?>