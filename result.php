<?php
     require_once('db.php');
	 // Connect to the server
     require_once('connect.php');
	 function showerror() {
	 die("Error " . mysql_errno() . " : " . mysql_error());
    }
	
	//get the parameters from search page
	$condition = $_GET["condition"];
    $winename = $_GET["winename"];
    $wineryname = $_GET["wineryname"];
    $region = $_GET["region"];
    $grapeVariety = $_GET["grapeVariety"];
    $yearFrom = $_GET["yearFrom"];
    $yearTo = $_GET["yearTo"];
    $min_instock = $_GET['min_instock'];
    $min_ordered = $_GET['min_ordered'];
    $min_cost = $_GET["min_cost"];
    $max_cost = $_GET["max_cost"];
	
	//simple validation on sever side
		
		if ($yearFrom > $yearTo){
			echo "<p><strong>Invalid year range! yearFrom should larger than yearTo</strong></p>";
		}
			
		if(isset($min_cost) && $min_cost != NULL | isset($max_cost) && $max_cost != NULL){
		    if($min_cost>$max_cost){
				echo "<p><strong>Maximum cost should larger than minimum cost!</strong></p>";
		    }
		}
    
    $query = 'SELECT DISTINCT wine.wine_id, wine_name, year, winery_name, region_name, cost, on_hand, SUM(qty) qty, SUM(price)
              FROM wine, winery, region, inventory, items, wine_variety
              WHERE wine.winery_id = winery.winery_id AND
                    winery.region_id = region.region_id AND
                    wine.wine_id = inventory.wine_id AND
                    wine.wine_id = items.wine_id AND
                    wine.wine_id = wine_variety.wine_id';
    
    if($condition == 'all') {// Query all data
        $query .= ' GROUP BY items.wine_id
                    ORDER BY wine_name, year
                    LIMIT 100';
    }
    else {// Query part of data
        
        if($winename != '') {
            $winename = str_replace("'", "''", $winename);
            $query .= " AND wine.wine_name LIKE '%$winename%'";
        }
        if($wineryname != '') {
            $wineryname = str_replace("'", "''", $wineryname);
            $query .= " AND winery_name LIKE '%$wineryname%'";
        }
        if($region != 1) {
            $query .= " AND region.region_id = $region";
        }
        if($grapeVariety != 0) {
            $query .= " AND variety_id = $grapeVariety";
        }
        if(($yearFrom != 0) && ($yearTo != 0)) {
            $query .= " AND year >= $yearFrom AND year <= $yearTo";
        } else if($yearFrom != 0) {
            $query .= " AND year >= $yearFrom";
        } else if($yearTo != 0) {
            $query .= " AND year <= $yearTo";
        }
        if($min_instock != 0) {
            $query .= " AND on_hand >= $min_instock";
        }
        if($min_cost != 0) {
            $query .= " AND cost >= $min_cost";
        }
        if($max_cost != 0) {
            $query .= " AND cost <= $max_cost";
        }
        if($min_ordered != 0) {
            $query .= " GROUP BY items.wine_id
                        HAVING qty >= $min_ordered
                        ORDER BY wine_name, year LIMIT 100";
        }
        else $query .= ' GROUP BY items.wine_id
                         ORDER BY wine_name, year LIMIT 100';
        
        //echo $query;
    }
   
    
    $result = mysql_query($query, $dbconn);
    if(!$result) {
        echo "Invalid query [$query]";
        exit;
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Search Result</title>
</head>
<body bgcolor="#E0FFFF">
<div id="container">
        <div id="header">
            <div id="caption"><h1 align="center">Welcome to WineStore</h1></div>
        </div>
        <div id="body">
        	<div id="sr"><h2>Search Result</h2></div>
            <div id="navi"><h2><a href="search.php" title="Back">Back to Search Page</a></h2></div>
            <div id="result">
                <?php
                    if(!$result) echo "<div class='noResult'>No fetch data.</div>";
                    else {
                ?>
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <th bgcolor="#00FFFF">Grape Name</th>
                        <th bgcolor="#FFFFFF">Grape Variety</th>
                        <th bgcolor="#00FFFF">Year</th>
                        <th bgcolor="#FFFFFF">Grape Winery</th>
                        <th bgcolor="#00FFFF">Region</th>
                        <th bgcolor="#FFFFFF">Cost in <br/>Inventory</th>
                        <th bgcolor="#00FFFF">Available<br/>Number</th>
                        <th bgcolor="#FFFFFF">Stock<br/>Sold</th>
                        <th bgcolor="#00FFFF">Revenue</tr>
                    <?php
                        while($row = mysql_fetch_row($result)) {
                    ?>
                    <tr>
                        <td bgcolor="#00FFFF"><?php echo $row[1]; ?></td>
                        <td bgcolor="#FFFFFF" style="line-height: 15px">
                        <?php
                            $query = "SELECT variety FROM wine_variety, grape_variety
                                      WHERE wine_variety.wine_id = $row[0] AND
                                      wine_variety.variety_id = grape_variety.variety_id
                                      ORDER BY variety";
                            $varieties = mysql_query($query, $dbconn);
                            $str = "";
                            while($variety = mysql_fetch_row($varieties)) {
                                $str .= "$variety[0], ";
                            }
                            //$str = substr($str, 0, strlen($str));
                            echo substr($str, 0, strlen($str)-2);
                        ?>
                        </td>
                        <td bgcolor="#00FFFF"><?php echo $row[2]; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row[3]; ?></td>
                        <td bgcolor="#00FFFF"><?php echo $row[4]; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo '$'. $row[5]; ?></td>
                        <td bgcolor="#00FFFF"> <?php echo $row[6]; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row[7]; ?></td>
                        <td bgcolor="#00FFFF"><?php echo '$'. $row[8]; ?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
                <?php
                    }
                ?>
            </div>
        </div>
        
    </div>
</body>
</html>
