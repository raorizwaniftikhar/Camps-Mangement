<?php require_once('Connections/DataPool.php'); ?>
<?php
$colname_Recordset1 = "-1";
if (isset($_GET['id'])) {
  $colname_Recordset1 = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_DataPool, $DataPool);
$query_Recordset1 = sprintf("SELECT * FROM assignments WHERE Against = %s", $colname_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $DataPool) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Assignment View</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {font-size: 12}
.style2 {font-size: 12px}
-->
</style>
</head>
<body id="page5">
<div class="site_center">
	<!-- header  -->
	<div class="bg_logo">
		<img src="images/00_home_06.jpg" style="width: 440px; height: 94px" /></div>
	<div class="menu">
		<div class="bg_menu">


			<ul class="navigation">
				<li><a href="#"  class="bg_none"> </a></li>
				<li><a href="#"> </a></li>
			</ul>
		    <img src="images/1page_bg_menu.jpg" alt=" " width="469" height="106" usemap="#Menu_Map" />
		    <map name="Menu_Map" id="Menu_Map">
              <area shape="rect" coords="26,7,125,38" href="iteacher.php" />
              <area shape="rect" coords="142,8,223,38" href="aboutt.php" alt="" />
              <area shape="rect" coords="254,5,329,39" href="helpt.php" alt="" />
              <area shape="rect" coords="346,10,442,38" href="logout.php" alt="" />
          </map>
		</div>
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
								    <p class="link_2 style2"><a href="iteacher.php" class="link">&lt;&lt; Back </a></p>
								    <p class="text">Showing Files Submitted Against the Asignment </p>
								    <?php do { ?>
							        <table width="574" border="0">
							          <tr>
							            <td width="81">&nbsp;</td>
                                        <td width="483"><span class="style1"><a href="<?php echo $row_Recordset1['File']; ?>"><?php echo trim($row_Recordset1['File']); ?></a></span></td>
                                      </tr>
						                </table>
								      <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?><p>&nbsp;</p>
								  </div>
								</div>
								<!--right_col-->
								<div class="col_2 ind_top_col">
                                	<div class="extra_left_1">
                               	  <img src="images/5page_title_2.gif" class="title_2" alt="" width="148" height="25" /></div>
                                  <div class="footer">OCMS&copy;2009  </div>
                              </div>
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
mysql_free_result($Recordset1);
?>