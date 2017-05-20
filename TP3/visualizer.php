<?php
    require_once './phpChart/conf.php';
    require_once 'db_request.php';
    require_once 'tp3_connection.php';
    
    function setChartSettings($pc, $chartTitle, $xName, $yName)
    {
        $pc->set_title(array('text'=>"Chart for $chartTitle"));
        $pc->set_xaxes(array(
            'xaxis'  => array(
			'labelOptions'=>array('fontSize'=>'13pt'), 
			'label'=>$xName,)
        ));

        $pc->set_yaxes(array(
            'yaxis'  => array('labelOptions'=>array('fontSize'=>'13pt'),
				'label'=>$yName,)
        ));
        $pc->set_legend(array('show'=>true));
        $pc->set_animate(true);
    }
    
    function handleCalls($callVal)
    {   
        
        if(isset($_POST["calls"]))
        {
            if(in_array($callVal, $_POST["calls"]))
            {
                    echo "checked=checked";
            }
        }
    }
    
    function handleFilters($filterVal)
    {
        if (isset($_POST["filters"]) && strcmp($_POST["filters"], $filterVal) == 0)
        {
            echo "checked=checked";
        }
    }  
?>


<!DOCTYPE HTML>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    
      <?php
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $javascript = "google.charts.load(\"current\", {packages:[\"corechart\", \"bar\"]});\n";
            $javascript .= "google.charts.setOnLoadCallback(drawChart);\n";
            $javascript .= "function drawChart() {\n";
            $javascript .= "var data = google.visualization.arrayToDataTable([\n";
            if(isset($_POST["filters"]))
            {
                $chartType = $_POST["filters"];
                if (strcmp($chartType, "NbCalls") == 0)
                {
                    if(isset($_POST["calls"]) && isset($_POST["user"]))
                    {
                        $user = $_POST["user"];
                        $colNames = "['Start Date',";
                        foreach ($_POST["calls"] as $key=>$value)
                        {
                            $colNames .= "'$value', ";

                        }
                        $colnames = substr($colNames, 0, strrpos($colNames, ","));
                        $javascript .= "$colnames],\n";
                        $firstDate = getEarliestDate($conn);
                        $startDate = strtotime($firstDate);
                        $endDate  = strtotime($firstDate.'+5minute');
                        $counter = 0;
                        while ( $counter < 20)
                        {

                            $formattedStartDate =  date("Y-m-d H:i:s.u" ,$startDate);
                            $rowValues = "['$formattedStartDate', ";
                            foreach ($_POST["calls"] as $key=>$value)
                            {       
                                $noCalls = getNoCallsFromUserAndFunction($conn, $value, $user, $formattedStartDate , date("Y-m-d H:i:s.u" ,$endDate));
                                $rowValues .= "$noCalls, ";

                            }
                            $rowValues = substr($rowValues, 0, strrpos($rowValues, ","));
                            $javascript .= $rowValues."],\n";

                            $tmpDate = $startDate;
                            $startDate = $endDate;
                            $endDate = $tmpDate + 60 * 5; // 5 minutes
                            $counter++;

                        }
                       $javascript = substr($javascript, 0, strrpos($javascript, ","));
                       $javascript .="]);\n";


                       $javascript .= "var options = {".
                                    "title: 'Number of calls by Functions for user: $user',".
                                    "width: 900,".
                                    "height: 500,".
                                    "legend: { position: 'top', maxLines: 3 },".
                                    "bar: { groupWidth: '75%' },".
                                    "isStacked: true,".
                                    "hAxis: { title: 'Time'},".
                                    "vAxis: {title: 'Number of calls made'}".
                                    "};";
                       $javascript .= "var chart = new google.visualization.ColumnChart(document.getElementById('chart'));";
                       $javascript .="chart.draw(data, options);}";
                       echo $javascript;

                    }   
                }  
            }
        }                  
      ?>



  </script>
  </head>
    <body>
        
        <form id="visualizerForm" name="visualizerForm" action="" method="POST">
            <legend>Calls: </legend>
            <input type="checkbox" <?php handleCalls("SearchDocuments") ?> name="calls[]" value="SearchDocuments">SearchDocuments
            <input type="checkbox" <?php handleCalls("GetDocument") ?> name="calls[]" value="GetDocument">GetDocument
            <input type="checkbox" <?php handleCalls("SetDocuments") ?> name="calls[]" value="SetDocuments">SetDocuments
            <input type="checkbox" <?php handleCalls("UpdateDocuments") ?> name="calls[]" value="UpdateDocuments">UpdateDocuments
            <input type="checkbox" <?php handleCalls("GetDocumentResspace") ?> name="calls[]" value="GetDocumentResspace">GetDocumentRespace
            
            <legend>Users: </legend>
            <select name="user">
            <?php
               foreach(GetUsers($conn) as $key => $user)
               {
                    $option ="<option value=\"$user\" ";
                    if(isset($_POST["user"]) && strcmp($user, $_POST["user"]) == 0)
                    {   
                        $option = $option . "selected='selected'";
                    }      
                    $option = $option. ">$user</option>";
                    echo $option;
                  
               } 
            ?>
           </select>
            <legend>Filters: </legend>
            <input  type="radio" <?php handleFilters("TimeElapsed")?> name="filters" value="TimeElapsed" onclick="document.getElementById('visualizerForm').submit();"> TimeElapsed
            <input type="radio" <?php handleFilters("TimeSeekRead")?> name="filters" value="TimeSeekRead" onclick="document.getElementById('visualizerForm').submit();"> TimeSeekRead
            <input type="radio" <?php handleFilters("NbCalls")?> name="filters" value="NbCalls" onclick="document.getElementById('visualizerForm').submit();"> Number of calls
        </form>
        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $chartType = "";
            if(isset($_POST["filters"]))
            {
                $chartType = $_POST["filters"];
            }
            if (strcmp($chartType, "TimeElapsed") == 0)
            {        
                if(isset($_POST["calls"]))
                {
                    $series;   
                    foreach($_POST["calls"] as $key=>$value)
                    {
                        $series[$key] = getTimeElapsedFromFunction($conn, $value);                    
                    }

                    $pc = new C_PhpChartX($series,'basic_chart');
                    setChartSettings($pc, "Chart for Time Elapsed", "Starting Time in seconds", "Time elapsed in milliseconds");

                    foreach($series as $key => $value)
                    {
                        $pc->add_series(array('showLabel'=>true,'showMarker'=>true,'shadow'=>false, 'label' => $_POST["calls"][$key]));
                    }

                    $pc->draw();
                }
            }
            else if (strcmp($chartType, "TimeSeekRead") == 0)
            {
                $serie = getTimeSeekReadFromSearchDocuments($conn);
                $pc = new C_PhpChartX(array($serie) ,'basic_chart');
                setChartSettings($pc, "Chart for Time Seek Read for SearchDocuments", "Starting Time in seconds", "Time Seek Read in milliseconds");
                $pc->draw();
            }      
        } 
        ?>
     
        <div id="chart" style="width: 1200px; height: 650px"></div>
        
    </body>
    
</html>
