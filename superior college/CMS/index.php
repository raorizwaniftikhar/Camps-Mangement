<?php require_once('Connections/DataPool.php'); ?>
<?php
mysql_select_db($database_DataPool, $DataPool);
$query_Users = "SELECT * FROM users";
$Users = mysql_query($query_Users, $DataPool) or die(mysql_error());
$row_Users = mysql_fetch_assoc($Users);
$totalRows_Users = mysql_num_rows($Users);
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['UID'])) {
  $loginUsername=$_POST['UID'];
  $password=$_POST['PWD'];
  $MM_fldUserAuthorization = "Type";
  $MM_redirectLoginSuccess = "crossroad.php";
  $MM_redirectLoginFailed = "deadend.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_DataPool, $DataPool);
  	
  $LoginRS__query=sprintf("SELECT UID, PWD, Type FROM users WHERE UID='%s' AND PWD='%s'",
  get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $password : addslashes($password)); 
   
  $LoginRS = mysql_query($LoginRS__query, $DataPool) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'Type');
    
    
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Superior College : CMS</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {color: #000000; font-weight: bold; }
.style4 {color: #000000}
.logintexto {
	border: medium solid #339966;
}
.style5 {color: #FFFFFF}
-->
</style>
</head>

<body>
<form id="Authentication_FORM" name="Authentication_FORM" method="POST" action="<?php echo $loginFormAction; ?>">
  <table width="763"  height="493" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="763" background="images/login1.jpg"><table width="522" height="359" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="222">&nbsp;</td>
        </tr>
        <tr>
          <td><table width="513" border="0" align="center">
              <tr>
                <td width="43"><span class="style4">Login</span><span class="style3">:</span> </td>
                <td width="149"><input name="UID" type="text" class="logintexto" id="UID" tabindex="0" /></td>
                <td width="72" class="style4">Password:</td>
                <td width="153"><input name="PWD" type="password" class="logintexto" id="PWD" tabindex="0" /></td>
                <td width="74"><input name="Submit" type="submit" class="buttonS" value="Submit" /></td>
              </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <br />
  <br />
  <br />  
  <br />
  <table width="915" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td bgcolor="#6FAEB0"><div align="center" class="style5">Project by: Muhammad Akmal </div></td>
    </tr>
    <tr>
      <td bgcolor="#6FAEB0"><div align="center" class="style5"></div></td>
    </tr>
  </table>
</form>
</body>
</html>
<?php
mysql_free_result($Users);
?>
