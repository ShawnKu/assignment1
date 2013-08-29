<?php
/**
* MySQL connection check
*
* Checks the connection to the local install of MySQL
*
*/

     require_once('db.php');
	 
	 // Connect to the server
     require_once('connect.php');
	 function showerror() {
	 die("Error " . mysql_errno() . " : " . mysql_error());
    }
	
	//get all regions
    $query ='SELECT * FROM region ORDER BY region_name';
	$regions = mysql_query($query, $dbconn);
	
    //get all grape varieties
    $query ='SELECT * FROM grape_variety ORDER BY variety';
	$varieties = mysql_query($query, $dbconn);
	
	 //get all range of years
    $yearArray = array();
    $query = 'SELECT DISTINCT year FROM wine ORDER BY year';
    $years = mysql_query($query, $dbconn);
    $i = 0;
    while($row = mysql_fetch_row($years)) 
	{
        $yearArray[$i] = $row[0];
        $i++;
    }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SearchPage</title>
</head>
<body>
<div id="container">
     <div id="hearder">
	    <div id="caption"><h1 align="center">WineStore</h1>
	    </div>
	 </div>
	 <div id="body">
	  <form action="result.php" method="get" id="searchForm" name="searchForm">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr>
                    <td width="172" height="40"><strong>&nbsp;Wine Name</strong></td>
                    <td width="920" height="40">
                  <input type="text" name="winename" id="winename" class="txt" /></td>
                </tr>
                <tr>
                    <td width="172" height="40"><strong>&nbsp;Winery Name</strong></td>
                    <td height="40">
                  <input type="text" name="wineryname" id="wineryname" class="txt" /></td>
                </tr>
                <tr>
                    <td width="172" height="40"><strong>&nbsp;Region</strong></td>
                    <td height="40" >
                    <select name="region" id="region">
                        <?php
                            while($row = mysql_fetch_row($regions)) {
                                echo "<option value=\"$row[0]\">$row[1]</option>\n";
                            }
                        ?>
                    </select>
					</td>
                </tr>
                <tr>
                    <td width="172" height="40" ><strong>&nbsp;Grape Variety</strong></td>
                    <td height="40" >
                    <select name="grapeVariety" id="grapeVariety">
                        <option value="0" selected="selected">Select Variety</option>
                        <?php
                            while($row = mysql_fetch_row($varieties)) {
                                echo "<option value=\"$row[0]\">$row[1]</option>\n";
                            }
                        ?>
                  </select></td>
                </tr>
                <tr>
                    <td width="172" height="40" ><strong>&nbsp;Range of Years</strong></td>
                    <td height="40" >
                    <select name="yearFrom" id="yearFrom">
                        <option value="0" selected="selected">From year</option>
                        <?php
                            for($i=0; $i<count($yearArray); $i++) {
                                echo "<option value=\"$yearArray[$i]\">$yearArray[$i]</option>\n";
                            }
                        ?>
                    </select>
                    <select name="yearTo" id="yearTo">
                        <option value="0" selected="selected">To year</option>
                        <?php
                            for($i=0; $i<count($yearArray); $i++) {
                                echo "<option value=\"$yearArray[$i]\">$yearArray[$i]</option>\n";
                            }
                        ?>
                    </select></td>
                </tr>
                <tr>
                    <td width="172" height="40" ><strong>&nbsp;Min Number in Stock</strong></td>
                    <td height="40" ><input type="text" name="min_instock" id="min_instock" class="number" /></td>
                </tr>
                <tr>
                    <td width="172" height="40" ><strong>&nbsp;Min Number Ordered</strong></td>
                    <td height="40" ><input type="text" name="min_ordered" id="min_ordered" class="number" /></td>
                </tr>
                <tr>
                    <td height="40" ><strong>&nbsp;Cost Range</strong></td>
                    <td height="40" >
					Min Cost $<input type="text" name="min_cost" id="min_cost" class="number" />
					Max Cost $<input type="text" name="max_cost" id="max_cost" class="number" /></td>
                </tr>
                <tr>
                    <td height="40" colspan="2" align="left" ><input type="submit" name="Submitbtn" id="Submitbtn" value="Search" />
                    &nbsp;
                  <input type="reset" name="resetbtn" id="resetbtn" value="Reset" /></td>
                </tr>
            </table>
            </form>
        </div>
	 
</div>
</body>
</html>

