<?php
Class MyQueryReport
{
	var $mycon; 	// mySQL Connection
	var $query_result;	// Query Result
	var $nrows;	// Number of Rows in Result
	var $ncols;	// Number of Columns in Result
	var $mainhead;	// Main Heading 
	var $colhead = array();	// Column Headings
	var $column_align = array();	//Alignment of Column data (left,right or centre)
	
	var $color_theme; 	// Color flavor of Report
	var $color_report_frame;// Color of Report Frame
	var $color_main_head;	// Color of Main Heading
	var $color_column_head;	// Color of Column Heading
	var $color_rows_odd;	// Color of Report - Odd Rows
	var $color_rows_even;	// Color of Report - Even Rows

	function MyQueryReport()
	{
	}
	
	function connect_db($hostname, $username, $pwd, $db)// Make mysql db connection
	{
		$this->mycon = mysql_connect($hostname,$username,$pwd) or
			die("hostname=$hostname, User Name=$username, Password=$pwd 
				Could not connect to mySQL");
		mysql_select_db($db) or
			die("Could not select database $db");
	}
	
	function execute_query($strqry)		//Execute query using the Query String
	{
		$this->query_result = mysql_query($strqry) or	// Result set of query
				die("Could not Execute Query");
		$this->set_nrows($this->query_result);
		$this->set_ncols($this->query_result);
		$this->set_colhead($this->query_result);
		$this->set_column_align($this->query_result);
	}
	
	function set_nrows($result)
	{
		$this->nrows=mysql_num_rows($result);	// Number of rows in Result
	}
	
	function set_ncols($result)
	{
		$this->ncols=mysql_num_fields($result);	// Number of Cols in Result	
	}	
	
	function set_colhead($result)
	{
		for ($i=0; $i < $this->ncols; $i++)	// Generate Column Headings
		{
			$this->colhead[$i] = mysql_field_name($result,$i);
		}
	}
	
	function set_column_align($result) // Set alignment of column data
	{
		for ($i=0; $i < $this->ncols; $i++)	
		{
			$field_type = mysql_field_type($result, $i); // check the field type
			switch($field_type)
			{
				case "string":	// If string align left
					$this->column_align[$i] = "left";
					break;
				case "int":	// If Integer or Real align right
				case "real":
					$this->column_align[$i] = "right";
					break;
				case "date":	// If date align centre
					$this->column_align[$i] = "center";
					break;
			}
		}
	}
		
	function set_color_theme()	// Set the VIBGYOR & white  background color themes
	{
	// colors of report frame, mainheading, column heading, odd row & even row
		$this->color_theme["violet"]=array("ff44ee","ff77ee","ffaaee","ffddee","ffeeee");
		$this->color_theme["indigo"]=array("9900dd","9955dd","99aadd","99dddd","99ffdd");
		$this->color_theme["blue"]=array("6611ff","6666ff","6688ff","66bbff","66ccff");
		$this->color_theme["green"]=array ("FFFFFF","66CC00", "#66FF00","cccc99","ffffcc");
		$this->color_theme["yellow"]=array("ffff00","ffff66","ffee88","ffeecc","ffffcc");
		$this->color_theme["orange"]=array("ff6622","ff6622","ff8866","ffbb66","ffcc66");
		$this->color_theme["red"]=array("ff0000","ff1100","ff6633","ff6688","ff9988");
		$this->color_theme["white"]=array("ffffff","ffffff","ffffff","ffffff","ffffff");
	}
	function set_color($color)	// set the color of Report frame, Main Head, Col Head,
					// odd row and even rows
	{
		$this->set_color_theme();
		if (!isset($this->color_theme[$color])) // set white as default color
		{
			$color="white";
		}
		$this->color_report_frame 	= $this->color_theme[$color][0];
		$this->color_main_head 		= $this->color_theme[$color][1];
		$this->color_column_head 	= $this->color_theme[$color][2];
		$this->color_rows_odd 		= $this->color_theme[$color][3];
		$this->color_rows_even 		= $this->color_theme[$color][4];
	}
	
	function display_error($err)// Display error Message
	{		
		echo "<tr bgcolor=". $this->color_main_head.  " height=100>";
		echo "<td align=center><H1> $err </H1></td>
			  </tr>";	
		$this->end_table();	
		$this->end_page();				
	}		
		
	function start_page()	// Starting html tags for web page 
	{
		echo 	"<html>
				 <head></head>
				 <body bgcolor=ffffff>";// background color white
	}
	
	function end_page()	// Ending html tags for  web page
	{
		echo	"</body>
				 </html> ";
	}			 
	function start_table()	// Starting table html tags 
	{
		//
		echo "<table border=0 cellspacing=2 cellpadding=0 bgcolor=". 
				$this->color_report_frame.  " width=750 align=center>"; 
	}

	function end_table()	// Ending table html tags
	{
		echo "</table>";
	}
	
	function draw_mainhead($mainhead) // Draw Main heading of Table
	{
	
		echo "<tr bgcolor=". $this->color_main_head . " height=50>
			  <td align=center  colspan=". $this->ncols . "><H1> $mainhead </H1></td>
			  </tr>";

	}
	
	function draw_colhead()	// Draw Column Headings for each column
	{
		echo "<tr bgcolor=". $this->color_column_head . ">";
		for ($i=0; $i < $this->ncols; $i++)
		{
			echo "<td align=". $this->column_align[$i]. "><b>";
			echo trim($this->colhead[$i]);
			echo "</b>
				  </td>";
		}
		echo "</tr>\n";	
	}
	
	function draw_report_body()//Draw the body of the report table with data from query result
	{
		for ($row=0; $row < $this->nrows; $row++)
		
		{
			if ($row % 2 == 1)	//Odd row or Even row
			{
				$color_rows = $this->color_rows_odd;
			}
			else
			{
				$color_rows = $this->color_rows_even;
			}
			
			echo "<tr bgcolor=$color_rows >\n";			
			for ($i=0; $i < $this->ncols; $i++)
			{
				echo "<td align=". $this->column_align[$i] . " >";
				echo mysql_result($this->query_result,$row, $i);
				echo "</td>\n";
			}
			echo "</tr>\n";
		}					
	}	
	
	function display_report($strqry="", $heading="", $color="white")// Display the Report
	{
		$this->set_color($color);
		$this->start_page();
		$this->start_table();
		if (trim($strqry) == "")// If Query String is blank
		{
			$this->display_error("Blank Query String");
			exit;
		}				
		$this->execute_query($strqry);
		if ($this->nrows == 0) 	// If no records in the result set
		{
			$this->display_error("Data Not Available");
			exit;
		}
		else
		{
			$this->draw_mainhead($heading);
			$this->draw_colhead();
			$this->draw_report_body();
		}
		$this->end_table();	
		$this->end_page();	
	}
}	
?>
	
