<?php
	session_start();

	if(isset($_SESSION))
	{
		unset ($_SESSION['user']);
		unset ($_SESSION['nivel']);
		unset ($_SESSION['gerente']);
		unset ($_SESSION['email']);
		unset ($_SESSION['udn']);
		unset ($_SESSION['area']);
		unset ($_SESSION['permiso']);

		session_destroy();

		echo "<script> window.location = '../ERP'</script>";
	}
	else
	{
		//header("location:index.html");
		echo "<script> window.location = '../ERP'</script>";
	}
?>
