<?php

//fetch_poll_data.php

include('connect.php');

$options = array("Yes", "No");

$total_poll_row = get_total_rows($con);

$output = '';
if($total_poll_row > 0) {
 foreach($options as $row){
    $query = "
        SELECT * FROM tbl_poll WHERE options = '".$row."'
    ";
    $statement = $con->prepare($query);
    $statement->execute();
    $total_row = $statement->rowCount();

    $percentage_vote = round(($total_row/$total_poll_row)*100, 2);
    $progress_bar_class = '';
    if($percentage_vote >= 40){
        $progress_bar_class = 'progress-bar-success';
    }
    else if($percentage_vote >= 25 && $percentage_vote < 40){
        $progress_bar_class = 'progress-bar-info';
    }
    else if($percentage_vote >= 10 && $percentage_vote < 25){
        $progress_bar_class = 'progress-bar-warning';
    }
    else{
        $progress_bar_class = 'progress-bar-danger';
    }
  $output .= '
   <div class="row col-md-6 col-md-offset-3">
        <div class="col-sm-2 text-center" align="left">
            <label>'.$row.'</label>
        </div>
        <div class="col-sm-10">
            <div class="progress ">
                <div class="progress-bar '.$progress_bar_class.'" 
                    role="progressbar" aria-valuenow="'.$percentage_vote.'" aria-valuemin="0" aria-valuemax="100" 
                    style="width:'.$percentage_vote.'%">
                        '.$percentage_vote.' % 
                </div>
            </div>
        </div>
   </div>
  ';
 }
}

echo $output;


function get_total_rows($con)
{
    $query = "SELECT * FROM tbl_poll WHERE options != 'uncounted'";
    $statement = $con->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}