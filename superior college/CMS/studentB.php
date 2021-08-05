<?php require_once('Connections/DataPool.php'); ?>
<?php
session_start();
$colname_Student_Info = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Student_Info = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Student_Info = sprintf("SELECT * FROM students WHERE UID = '%s'", $colname_Student_Info);
$Student_Info = mysql_query($query_Student_Info, $DataPool) or die(mysql_error());
$row_Student_Info = mysql_fetch_assoc($Student_Info);
$totalRows_Student_Info = mysql_num_rows($Student_Info);

$_SESSION['L1'] = $row_Student_Info['L1'];
$_SESSION['L2'] = $row_Student_Info['L2'];
$_SESSION['L3'] = $row_Student_Info['L3'];
$_SESSION['L4'] = $row_Student_Info['L4'];
$_SESSION['L5'] = $row_Student_Info['L5'];
$_SESSION['L6'] = $row_Student_Info['L6'];
$_SESSION['L7'] = $row_Student_Info['L7'];


$colname_Lecture1 = "-1";
if (isset($_SESSION['L1'])) {
  $colname_Lecture1 = (get_magic_quotes_gpc()) ? $_SESSION['L1'] : addslashes($_SESSION['L1']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Lecture1 = sprintf("SELECT * FROM lectures WHERE LID = %s", $colname_Lecture1);
$Lecture1 = mysql_query($query_Lecture1, $DataPool) or die(mysql_error());
$row_Lecture1 = mysql_fetch_assoc($Lecture1);
$totalRows_Lecture1 = mysql_num_rows($Lecture1);

$colname_Lecture2 = "-1";
if (isset($_SESSION['L2'])) {
  $colname_Lecture2 = (get_magic_quotes_gpc()) ? $_SESSION['L2'] : addslashes($_SESSION['L2']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Lecture2 = sprintf("SELECT * FROM lectures WHERE LID = %s", $colname_Lecture2);
$Lecture2 = mysql_query($query_Lecture2, $DataPool) or die(mysql_error());
$row_Lecture2 = mysql_fetch_assoc($Lecture2);
$totalRows_Lecture2 = mysql_num_rows($Lecture2);

$colname_Lecture3 = "-1";
if (isset($_SESSION['L3'])) {
  $colname_Lecture3 = (get_magic_quotes_gpc()) ? $_SESSION['L3'] : addslashes($_SESSION['L3']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Lecture3 = sprintf("SELECT * FROM lectures WHERE LID = %s", $colname_Lecture3);
$Lecture3 = mysql_query($query_Lecture3, $DataPool) or die(mysql_error());
$row_Lecture3 = mysql_fetch_assoc($Lecture3);
$totalRows_Lecture3 = mysql_num_rows($Lecture3);

$colname_Lecture4 = "-1";
if (isset($_SESSION['L4'])) {
  $colname_Lecture4 = (get_magic_quotes_gpc()) ? $_SESSION['L4'] : addslashes($_SESSION['L4']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Lecture4 = sprintf("SELECT * FROM lectures WHERE LID = %s", $colname_Lecture4);
$Lecture4 = mysql_query($query_Lecture4, $DataPool) or die(mysql_error());
$row_Lecture4 = mysql_fetch_assoc($Lecture4);
$totalRows_Lecture4 = mysql_num_rows($Lecture4);

$colname_Lecture5 = "-1";
if (isset($_SESSION['L5'])) {
  $colname_Lecture5 = (get_magic_quotes_gpc()) ? $_SESSION['L5'] : addslashes($_SESSION['L5']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Lecture5 = sprintf("SELECT * FROM lectures WHERE LID = %s", $colname_Lecture5);
$Lecture5 = mysql_query($query_Lecture5, $DataPool) or die(mysql_error());
$row_Lecture5 = mysql_fetch_assoc($Lecture5);
$totalRows_Lecture5 = mysql_num_rows($Lecture5);

$colname_Lecture6 = "-1";
if (isset($_SESSION['L6'])) {
  $colname_Lecture6 = (get_magic_quotes_gpc()) ? $_SESSION['L6'] : addslashes($_SESSION['L6']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Lecture6 = sprintf("SELECT * FROM lectures WHERE LID = %s", $colname_Lecture6);
$Lecture6 = mysql_query($query_Lecture6, $DataPool) or die(mysql_error());
$row_Lecture6 = mysql_fetch_assoc($Lecture6);
$totalRows_Lecture6 = mysql_num_rows($Lecture6);

$colname_Lecture7 = "-1";
if (isset($_SESSION['L7'])) {
  $colname_Lecture7 = (get_magic_quotes_gpc()) ? $_SESSION['L7'] : addslashes($_SESSION['L7']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Lecture7 = sprintf("SELECT * FROM lectures WHERE LID = %s", $colname_Lecture7);
$Lecture7 = mysql_query($query_Lecture7, $DataPool) or die(mysql_error());
$row_Lecture7 = mysql_fetch_assoc($Lecture7);
$totalRows_Lecture7 = mysql_num_rows($Lecture7);

$_SESSION['T1'] = $row_Lecture1['TID'];
$_SESSION['T2'] = $row_Lecture2['TID'];
$_SESSION['T3'] = $row_Lecture3['TID'];
$_SESSION['T4'] = $row_Lecture4['TID'];
$_SESSION['T5'] = $row_Lecture5['TID'];
$_SESSION['T6'] = $row_Lecture6['TID'];
$_SESSION['T7'] = $row_Lecture7['TID'];

$colname_Teacher1 = "-1";
if (isset($_SESSION['T1'])) {
  $colname_Teacher1 = (get_magic_quotes_gpc()) ? $_SESSION['T1'] : addslashes($_SESSION['T1']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Teacher1 = sprintf("SELECT * FROM teachers WHERE TID = %s", $colname_Teacher1);
$Teacher1 = mysql_query($query_Teacher1, $DataPool) or die(mysql_error());
$row_Teacher1 = mysql_fetch_assoc($Teacher1);
$totalRows_Teacher1 = mysql_num_rows($Teacher1);

$colname_Teacher2 = "-1";
if (isset($_SESSION['T2'])) {
  $colname_Teacher2 = (get_magic_quotes_gpc()) ? $_SESSION['T2'] : addslashes($_SESSION['T2']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Teacher2 = sprintf("SELECT * FROM teachers WHERE TID = %s", $colname_Teacher2);
$Teacher2 = mysql_query($query_Teacher2, $DataPool) or die(mysql_error());
$row_Teacher2 = mysql_fetch_assoc($Teacher2);
$totalRows_Teacher2 = mysql_num_rows($Teacher2);

$colname_Teacher3 = "-1";
if (isset($_SESSION['T3'])) {
  $colname_Teacher3 = (get_magic_quotes_gpc()) ? $_SESSION['T3'] : addslashes($_SESSION['T3']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Teacher3 = sprintf("SELECT * FROM teachers WHERE TID = %s", $colname_Teacher3);
$Teacher3 = mysql_query($query_Teacher3, $DataPool) or die(mysql_error());
$row_Teacher3 = mysql_fetch_assoc($Teacher3);
$totalRows_Teacher3 = mysql_num_rows($Teacher3);

$colname_Teacher4 = "-1";
if (isset($_SESSION['T4'])) {
  $colname_Teacher4 = (get_magic_quotes_gpc()) ? $_SESSION['T4'] : addslashes($_SESSION['T4']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Teacher4 = sprintf("SELECT * FROM teachers WHERE TID = %s", $colname_Teacher4);
$Teacher4 = mysql_query($query_Teacher4, $DataPool) or die(mysql_error());
$row_Teacher4 = mysql_fetch_assoc($Teacher4);
$totalRows_Teacher4 = mysql_num_rows($Teacher4);

$colname_Teacher5 = "-1";
if (isset($_SESSION['T5'])) {
  $colname_Teacher5 = (get_magic_quotes_gpc()) ? $_SESSION['T5'] : addslashes($_SESSION['T5']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Teacher5 = sprintf("SELECT * FROM teachers WHERE TID = %s", $colname_Teacher5);
$Teacher5 = mysql_query($query_Teacher5, $DataPool) or die(mysql_error());
$row_Teacher5 = mysql_fetch_assoc($Teacher5);
$totalRows_Teacher5 = mysql_num_rows($Teacher5);

$colname_Teacher6 = "-1";
if (isset($_SESSION['T6'])) {
  $colname_Teacher6 = (get_magic_quotes_gpc()) ? $_SESSION['T6'] : addslashes($_SESSION['T6']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Teacher6 = sprintf("SELECT * FROM teachers WHERE TID = %s", $colname_Teacher6);
$Teacher6 = mysql_query($query_Teacher6, $DataPool) or die(mysql_error());
$row_Teacher6 = mysql_fetch_assoc($Teacher6);
$totalRows_Teacher6 = mysql_num_rows($Teacher6);

$colname_Teacher7 = "-1";
if (isset($_SESSION['T7'])) {
  $colname_Teacher7 = (get_magic_quotes_gpc()) ? $_SESSION['T7'] : addslashes($_SESSION['T7']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Teacher7 = sprintf("SELECT * FROM teachers WHERE TID = %s", $colname_Teacher7);
$Teacher7 = mysql_query($query_Teacher7, $DataPool) or die(mysql_error());
$row_Teacher7 = mysql_fetch_assoc($Teacher7);
$totalRows_Teacher7 = mysql_num_rows($Teacher7);



echo $_SESSION['MM_Username'];



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Welcome <?php echo $row_Student_Info['Full_Name']; ?></title>
<style type="text/css">
<!--
.style1 {font-size: 36px}
-->
</style>
</head>

<body>
Welcome <?php echo $row_Student_Info['Full_Name']; ?> Roll Number : <?php echo $row_Student_Info['RNUM']; ?> Class : <?php echo $row_Student_Info['CID']; ?> <br />
<?php if ($totalRows_Lecture1 > 0) { // Show if recordset not empty ?>
  <table width="767" height="183" border="0" align="center">
    <tr>
      <td width="757" height="179" background="imgs/cou.jpg"><table width="757" height="149" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="160" height="35">&nbsp;</td>
          <td width="107"><div align="right"><strong>AT</strong></div></td>
          <td width="124"><div align="center"><?php echo $row_Lecture1['Time']; ?></div></td>
          <td width="125"><div align="right"><strong>IN</strong></div></td>
          <td width="123"><div align="center"><?php echo $row_Lecture1['Room']; ?></div></td>
          <td width="118">&nbsp;</td>
        </tr>
        <tr>
          <td height="63"><table width="134" height="48" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="67">&nbsp;</td>
              <td width="67" valign="bottom"><span class="style1">1</span></td>
            </tr>
          </table></td>
          <td colspan="4"><a href="subject.php?L=<?php echo $row_Lecture1['LID']; ?>" class="style1"><?php echo $row_Lecture1['Name']; ?></a></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td colspan="2" valign="middle"><a href="<?php echo $row_Teacher1['TID']; ?>"><?php echo $row_Teacher1['Full_Name']; ?></a></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_Lecture2 > 0) { // Show if recordset not empty ?>
  <table width="767" height="183" border="0" align="center">
    <tr>
      <td width="757" height="179" background="imgs/cou.jpg"><table width="757" height="149" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="160" height="35">&nbsp;</td>
          <td width="107"><div align="right"><strong>AT</strong></div></td>
          <td width="124"><div align="center"><?php echo $row_Lecture2['Time']; ?></div></td>
          <td width="125"><div align="right"><strong>IN</strong></div></td>
          <td width="123"><div align="center"><?php echo $row_Lecture2['Room']; ?></div></td>
          <td width="118">&nbsp;</td>
        </tr>
        <tr>
          <td height="63"><table width="134" height="48" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="67">&nbsp;</td>
              <td width="67" valign="bottom"><span class="style1">2</span></td>
            </tr>
            </table></td>
          <td colspan="4"><a href="subject.php?L=<?php echo $row_Lecture2['LID']; ?>" class="style1"><?php echo $row_Lecture2['Name']; ?></a></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td colspan="2" valign="middle"><a href="<?php echo $row_Teacher1['TID']; ?>"><?php echo $row_Teacher2['Full_Name']; ?></a></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
      </table>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_Lecture3 > 0) { // Show if recordset not empty ?>
    <table width="767" height="183" border="0" align="center">
      <tr>
        <td width="757" height="179" background="imgs/cou.jpg"><table width="757" height="149" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="160" height="35">&nbsp;</td>
              <td width="107"><div align="right"><strong>AT</strong></div></td>
              <td width="124"><div align="center"><?php echo $row_Lecture3['Time']; ?></div></td>
              <td width="125"><div align="right"><strong>IN</strong></div></td>
              <td width="123"><div align="center"><?php echo $row_Lecture3['Room']; ?></div></td>
              <td width="118">&nbsp;</td>
            </tr>
            <tr>
              <td height="63"><table width="134" height="48" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="67">&nbsp;</td>
                    <td width="67" valign="bottom"><span class="style1">3</span></td>
                  </tr>
              </table></td>
              <td colspan="4"><a href="subject.php?L=<?php echo $row_Lecture1['LID']; ?>" class="style1"><?php echo $row_Lecture3['Name']; ?></a></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td colspan="2" valign="middle"><a href="<?php echo $row_Teacher1['TID']; ?>"><?php echo $row_Teacher3['Full_Name']; ?></a></td>
              <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
  </table>
    <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_Lecture4 > 0) { // Show if recordset not empty ?>
    <table width="767" height="183" border="0" align="center">
      <tr>
        <td width="757" height="179" background="imgs/cou.jpg"><table width="757" height="149" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="160" height="35">&nbsp;</td>
              <td width="107"><div align="right"><strong>AT</strong></div></td>
              <td width="124"><div align="center"><?php echo $row_Lecture4['Time']; ?></div></td>
              <td width="125"><div align="right"><strong>IN</strong></div></td>
              <td width="123"><div align="center"><?php echo $row_Lecture4['Room']; ?></div></td>
              <td width="118">&nbsp;</td>
            </tr>
            <tr>
              <td height="63"><table width="134" height="48" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="67">&nbsp;</td>
                    <td width="67" valign="bottom"><span class="style1">4</span></td>
                  </tr>
              </table></td>
              <td colspan="4"><a href="subject.php?L=<?php echo $row_Lecture1['LID']; ?>" class="style1"><?php echo $row_Lecture4['Name']; ?></a></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td colspan="2" valign="middle"><a href="<?php echo $row_Teacher1['TID']; ?>"><?php echo $row_Teacher4['Full_Name']; ?></a><a href="<?php echo $row_Teacher1['TID']; ?>"></a></td>
              <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
  </table>
    <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_Lecture5 > 0) { // Show if recordset not empty ?>
    <table width="767" height="183" border="0" align="center">
      <tr>
        <td width="757" height="179" background="imgs/cou.jpg"><table width="757" height="149" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="160" height="35">&nbsp;</td>
              <td width="107"><div align="right"><strong>AT</strong></div></td>
              <td width="124"><div align="center"><?php echo $row_Lecture5['Time']; ?></div></td>
              <td width="125"><div align="right"><strong>IN</strong></div></td>
              <td width="123"><div align="center"><?php echo $row_Lecture5['Room']; ?></div></td>
              <td width="118">&nbsp;</td>
            </tr>
            <tr>
              <td height="63"><table width="134" height="48" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="67">&nbsp;</td>
                    <td width="67" valign="bottom"><span class="style1">5</span></td>
                  </tr>
              </table></td>
              <td colspan="4"><a href="subject.php?L=<?php echo $row_Lecture1['LID']; ?>" class="style1"><?php echo $row_Lecture5['Name']; ?></a></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td colspan="2" valign="middle"><?php echo $row_Teacher5['Full_Name']; ?></td>
              <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
  </table>
    <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_Lecture6 > 0) { // Show if recordset not empty ?>
    <table width="767" height="183" border="0" align="center">
      <tr>
        <td width="757" height="179" background="imgs/cou.jpg"><table width="757" height="149" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="160" height="35">&nbsp;</td>
              <td width="107"><div align="right"><strong>AT</strong></div></td>
              <td width="124"><div align="center"><?php echo $row_Lecture6['Time']; ?></div></td>
              <td width="125"><div align="right"><strong>IN</strong></div></td>
              <td width="123"><div align="center"><?php echo $row_Lecture6['Room']; ?></div></td>
              <td width="118">&nbsp;</td>
            </tr>
            <tr>
              <td height="63"><table width="134" height="48" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="67">&nbsp;</td>
                    <td width="67" valign="bottom"><span class="style1">6</span></td>
                  </tr>
              </table></td>
              <td colspan="4"><a href="subject.php?L=<?php echo $row_Lecture1['LID']; ?>" class="style1"><?php echo $row_Lecture6['Name']; ?></a></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td colspan="2" valign="middle"><a href="<?php echo $row_Teacher1['TID']; ?>"><?php echo $row_Teacher6['Full_Name']; ?></a></td>
              <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
  </table>
    <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_Lecture7 > 0) { // Show if recordset not empty ?>
    <table width="767" height="183" border="0" align="center">
      <tr>
        <td width="757" height="179" background="imgs/cou.jpg"><table width="757" height="149" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="160" height="35">&nbsp;</td>
          <td width="107"><div align="right"><strong>AT</strong></div></td>
          <td width="124"><div align="center"><?php echo $row_Lecture7['Time']; ?></div></td>
          <td width="125"><div align="right"><strong>IN</strong></div></td>
          <td width="123"><div align="center"><?php echo $row_Lecture7['Room']; ?></div></td>
          <td width="118">&nbsp;</td>
        </tr>
          <tr>
            <td height="63"><table width="134" height="48" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="67">&nbsp;</td>
              <td width="67" valign="bottom"><span class="style1">7</span></td>
            </tr>
            </table></td>
          <td colspan="4"><a href="subject.php?L=<?php echo $row_Lecture1['LID']; ?>" class="style1"><?php echo $row_Lecture7['Name']; ?></a></td>
          <td>&nbsp;</td>
        </tr>
          <tr>
            <td>&nbsp;</td>
          <td colspan="2">&nbsp;</td>
          <td colspan="2" valign="middle"><a href="<?php echo $row_Teacher1['TID']; ?>"><?php echo $row_Teacher7['Full_Name']; ?></a></td>
          <td>&nbsp;</td>
        </tr>
        </table></td>
    </tr>
  </table>
    <?php } // Show if recordset not empty ?></body>
</html>
<?php
mysql_free_result($Student_Info);

mysql_free_result($Lecture1);

mysql_free_result($Lecture2);

mysql_free_result($Lecture3);

mysql_free_result($Lecture4);

mysql_free_result($Lecture5);

mysql_free_result($Lecture6);

mysql_free_result($Lecture7);

mysql_free_result($Teacher1);

mysql_free_result($Teacher2);

mysql_free_result($Teacher3);

mysql_free_result($Teacher4);

mysql_free_result($Teacher5);

mysql_free_result($Teacher6);

mysql_free_result($Teacher7);
?>
