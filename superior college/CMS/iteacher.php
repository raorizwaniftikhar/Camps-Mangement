<?php require_once('Connections/DataPool.php'); ?>
<?php
session_start();

$colname_Teacher_Info = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Teacher_Info = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Teacher_Info = sprintf("SELECT * FROM teachers WHERE UID = '%s'", $colname_Teacher_Info);
$Teacher_Info = mysql_query($query_Teacher_Info, $DataPool) or die(mysql_error());
$row_Teacher_Info = mysql_fetch_assoc($Teacher_Info);
$totalRows_Teacher_Info = mysql_num_rows($Teacher_Info);

$_SESSION['TID'] = $row_Teacher_Info['TID'];

$colname_Lectures = "-1";
if (isset($_SESSION['TID'])) {
  $colname_Lectures = (get_magic_quotes_gpc()) ? $_SESSION['TID'] : addslashes($_SESSION['TID']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Lectures = sprintf("SELECT * FROM lectures WHERE TID = %s", $colname_Lectures);
$Lectures = mysql_query($query_Lectures, $DataPool) or die(mysql_error());
$row_Lectures = mysql_fetch_assoc($Lectures);
$totalRows_Lectures = mysql_num_rows($Lectures);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Welcome</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
</head>
<body id="page5">
<div class="site_center">
	<!-- header  -->
	<div class="bg_logo">
		<img src="images/00_home_06.jpg" style="width: 440px; height: 94px" /></div>
	<div class="menu">
	  <div class="bg_menu"><img src="images/1page_bg_menu.jpg" alt="" width="469" height="106" usemap="#Map" />
<map name="Map" id="Map"><area shape="rect" coords="27,11,128,36" href="iteacher.php" alt="" />
<area shape="rect" coords="147,9,218,38" href="aboutt.php" alt="" />
<area shape="rect" coords="256,8,321,38" href="helpt.php" alt="" />
<area shape="rect" coords="348,9,444,40" href="logout.php" alt="" />
</map></div>
	</div>
	<!-- content -->
<div class="content">
		<div class="content_border_left">
			<div class="content_bot">
				<div class="content_corner_left">
					<div class="content_corner_right">
						<div class="bg_right_content">
							<div class="indent_content">
								<!--left_col-->
								<div class="col_1">
									<div class="extra_left"> 
									  <p>Welcome  <span class="text_1"><?php echo $row_Teacher_Info['Full_Name']; ?></span><br />
                                      </p>
									  <table width="767" height="183" border="0" align="center">
                                        <tr background="imgs/tcou.jpg">
                                        <td width="757" height="179"><?php do { ?>
                                            <table width="757" height="149" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                  <td width="160" height="35">&nbsp;</td>
                                                  <td width="107"><div align="right"><strong>AT</strong></div></td>
                                                  <td width="124"><div align="center"><?php echo $row_Lectures['Time']; ?></div></td>
                                                  <td width="125"><div align="right"><strong>IN</strong></div></td>
                                                  <td width="123"><div align="center"><?php echo $row_Lectures['Room']; ?></div></td>
                                                  <td width="118">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td height="63"><table width="134" height="48" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                      <td width="67">&nbsp;</td>
                                                      <td width="67" valign="bottom">&nbsp;</td>
                                                      </tr>
                                                  </table></td>
                                                  <td colspan="4"><a href="tdetails.php?L=<?php echo $row_Lectures['LID']; ?>" class="link"><?php echo $row_Lectures['Name']; ?></a></td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td>&nbsp;</td>
                                                  <td colspan="2">&nbsp;</td>
                                                  <td colspan="2" valign="middle"><a></a></td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                  </table>
                                              <?php } while ($row_Lectures = mysql_fetch_assoc($Lectures)); ?></td>
                                        </tr>
                                      </table>
                                      
    
                                      
                                     
</div>
								</div>
								<!--right_col-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($Teacher_Info);

mysql_free_result($Lectures);
?>