<?php
    require_once "config.php";
    checkStudentLogin();
    $sid    = $_SESSION['sid'];
	$filter = "student";
    $student_email = $_SESSION['email'];
    //d($_GET,1);

    $filter = "class";
    if(isset($_GET['filter']) && trim($_GET['filter'])!=""){
        $filter = mysql_real_escape_string($_GET['filter']);
        if(! in_array($filter,array("class","quiz","skill"))){
            $filter = "student";
        }
    }
    
    $duration = "year";
    if(isset($_GET['duration']) && trim($_GET['duration'])!=""){
        $duration = mysql_real_escape_string($_GET['duration']);
        if(! in_array($duration,array("week","month", "quarter", "semester", "year"))){
            $duration = "year";
        }
    }

    $year = date("Y");
    

   // include "studentboard.graph.php";
    include "studentboard.graph_new.php";
	include "student_progress.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>lurnn</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <!-- bootstrap -->
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

    <!-- libraries -->
    <link href="css/lib/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />
    <link href="css/lib/font-awesome.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <link rel="stylesheet" type="text/css" href="css/elements.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">

    <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/index.css" type="text/css" media="screen" />    

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- lato font -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="./img/favicon.jpg"/>
    
	<?/* //-------Script for line graph--------------------------------
    <script type="text/javascript" src="line_graph.js"></script>
    	
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() 
		{
			//var jsonz = '<?php echo $graph_month; ?>';
			//var deco  = jQuery.parseJSON(jsonz);
			<?php 
			$get_duration =  $_GET['duration']; 
			$get_filter   =  $_GET['filter'];
			if($get_duration == 'year' && $get_filter == 'quiz')
			{
			?>
			var data = google.visualization.arrayToDataTable([
				
			  ['Year', 'Quiz'],
			  <?php for($i=2010; $i<=$years; $i++) { 
				
				if(array_key_exists($i,$graph_year))
				{
					$pointz = $graph_year[$i];
				}
				else
				{
					$pointz = 0;
				}
			  ?>
			  ['<?php echo $i;?>', <?php echo $pointz; ?>],
			  <?php } ?>
			  
			]);
		<?php
			} elseif($get_duration == 'week' && $get_filter == 'quiz' ) {
		?>
			var data = google.visualization.arrayToDataTable([
				
			  ['Year', 'Quiz'],
			  <?php for($i=$weekz-6; $i<=$weekz; $i++) { 
				
				if(array_key_exists($i,$graph_week))
				{
					$pointz = $graph_week[$i];
				}
				else
				{
					$pointz = 0;
				}
			  ?>
			  ['<?php $tempDate = date("Y-m-".$i); echo date('D', strtotime( $tempDate)); ?>', <?php echo $pointz; ?>],
			  <?php } ?>
			  
			]);
		<?php
			} elseif($get_duration == 'month' && $get_filter == 'quiz') {
		?>
			var data = google.visualization.arrayToDataTable([
				
			  ['Year', 'Quiz'],
			  <?php for($i=1; $i<=12; $i++) { 
				
				if(array_key_exists($i,$graph_month))
				{
					$pointz = $graph_month[$i];
				}
				else
				{
					$pointz = 0;
				}
			  ?>
			  ['<?php echo strftime("%b", mktime(0, 0, 0, $i));?>', <?php echo $pointz; ?>],
			  <?php } ?>
			  
			]);
		<?php
			} elseif($get_duration == 'semester' && $get_filter == 'quiz') {
		?>
			var data = google.visualization.arrayToDataTable([
				
			  ['Year', 'Quiz'],
			  <?php for($i=date('m')-7; $i<=date('m'); $i++) { 
				
				if(array_key_exists($i,$graph_sem))
				{
					$pointz = $graph_sem[$i];
				}
				else
				{
					$pointz = 0;
				}
			  ?>
			  ['<?php echo strftime("%b", mktime(0, 0, 0, $i));?>', <?php echo $pointz; ?>],
			  <?php } ?>
			]);
		<?php
			}
			else
			{ ?>
				var data = google.visualization.arrayToDataTable([
				  ['Year',  'Class'],
				  ['Jan',  400],
				  ['Feb',  500],
				  ['Mar',  550],
				  ['Apr',  600],
				  ['May',  700],
				  ['June', 800],
				  ['July', 900],
				  ['Aug',  950],
				  ['Sep',  980],
				  ['Oct',  700],
				  ['Nov',  800],
				  ['Dec',  900],
				]);
		<?	}
		?>
			var options = {
			  //title: 'Company Performance'
			};

			var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

			chart.draw(data, options);
		}
    </script>
	//-----END LINE GRAPH SCRIPT-----------------------------*/?>
</head>
<body>

   

   
    <?php include "includes/student_menu.php"; ?>
    <?php include "includes/student_left_side_bar.php"; ?>



  <!-- main container -->
    <div class="content">

        
        <div class="container-fluid">

            <!-- upper main stats -->
            <?php include "stats.php";?>
            <!-- end upper main stats -->

            <div id="pad-wrapper" style="margin-top:1px;">
				
                <!-- statistics chart built with jQuery Flot -->
                <div class="row-fluid chart">
				<div class="span4">
					<?php 
						$sql    = sprintf("SELECT *FROM students WHERE sid='%s'",$_SESSION['sid']);
						$result = mysql_query($sql);
						$user   = mysql_fetch_object($result);
					?>
					<h2 style="font-size: 12px; font-weight: bold; margin: 21px 0px 0px;">USER PROFILE</h2>
					<span>
						<img src="uploads/<?php echo $user->profile_picture;?>" class="avatar">
					</span>
					<span style="float:right; width:77%">
						<a href="profile-info.php" style="border: 1px solid #444; padding: 1px; border-radius: 5px; float: left; text-align: center; width: 38px; margin: 5px 0px 0px; text-decoration: none;">
							EDIT
						</a>
					</span>
				</div>
				
				<div class="span8">
					<div class="span3" style="margin:15px 0 0 0; float:right;">   
                        <div class="ui-select" style="width:190px;">
							<select id="progress" onchange="window.location='studentboard.php?progress='+$('#progress').val();">
								<option value="class"  <?php if($progress=='class') echo "selected='selected'";?>>Progress By Class</option>
								<option value="assignment"  <?php if($progress=='assignment') echo "selected='selected'";?>>Progress By Assignment</option>
								<?/*<option value="skill"  <?php if($progress=='skill') echo "selected='selected'";?>>Progress By Skill</option>*/?>
								<option value="skill"  <?php if($progress=='skill') echo "selected='selected'";?>>Progress By Skill</option>
							</select>
                        </div>
                    </div>
				</div>
				
				<?/* //----SHOW LINE GRAPH HERE BY AJAX AND JQUERY--------------
				<div class="span8">
                    <div class="span3" style="margin:15px 0 0 0">                        
                        <div class="ui-select">
                          <select id="duration" onchange="window.location='studentboard.php?filter='+$('#filter').val()+'&duration='+$('#duration').val();">
                            <option value="week"  <?php if($duration=='week') echo "selected='selected'";?>>Week</option>
                            <option value="month" <?php if($duration=='month') echo "selected='selected'";?>>Month</option>
                            <option value="quarter"  <?php if($duration=='quarter') echo "selected='selected'";?>>Quarter</option>
                            <option value="semester"  <?php if($duration=='semester') echo "selected='selected'";?>>Semester</option>
                            <option value="year"  <?php if($duration=='year') echo "selected='selected'";?>>Year</option>
                          </select>
                        </div>
                    </div>
                    <div class="span6" style="margin:15px 0 0 0">
                        <div class="ui-select">
                          <select id="filter" onchange="window.location='studentboard.php?filter='+$('#filter').val()+'&duration='+$('#duration').val();">
                            <option value="class"  <?php if($filter=='class') echo "selected='selected'";?>>By Class</option>
                            <option value="quiz"  <?php if($filter=='quiz') echo "selected='selected'";?>>By Quiz</option>
                            <option value="skill"  <?php if($filter=='skill') echo "selected='selected'";?>>By Skill</option>
                          </select>
                        </div>
                    </div>
                   
                    <!----<div id="statsChart" class="span12" style="height:700px;text-align:center;margin-top:0;">
                    </div>-->
					<div class="span12" id="chart_div" style="width: 900px; height: 500px;"></div>
					
                    <div class="span12 table-products section" style="margin-top:0;padding-top:0;" id="tablechart">
					</div>
				</div>
				*/?>
				<div class="span12" style="margin:10px 0 0 0">
					<div class="span6 left_one">
						<a href="studentupcoming_assignments.php">
							<span style="float:left; width:33%">
								<p class="in_head">UPCOMING ASSIGNMENTS</p>
							</span>
								<?php
									//--- Get QUIZES-----------------------	
										//$sql = "SELECT quizes.*, class_students.* FROM quizes INNER JOIN class_students ON quizes.class_id = class_students.class_id Where class_students.email = '$student_email' ORDER BY quizes.quiz_id DESC";
								$stu_grade = $_SESSION['grade_level'];
										/*$sql = "SELECT quizes.*, classes.* FROM quizes INNER JOIN classes ON quizes.class_id = classes.class_id Where classes.grade_level = '$stu_grade' and quizes.is_aptitude = '0' ORDER BY quizes.quiz_id DESC";
										
										$result = mysql_query($sql);
										//d($sql,1);

										while($feedquiz  = mysql_fetch_object($result))
										{
											$feedquizs[] = $feedquiz;
										} */
										
										$sql = "SELECT quizes.*, class_students.* FROM quizes INNER JOIN class_students ON quizes.class_id = class_students.class_id Where class_students.email = '$student_email' AND grade_level = '$stu_grade' AND quizes.is_aptitude = '0' ORDER BY quizes.quiz_id DESC";
										$result = mysql_query($sql);
										//d($sql,1);
										while($feedquiz  = mysql_fetch_object($result))
										{
											$feedquizsz[] = $feedquiz;
										} 
										
									//--- Get ESSAY----------------------------------
										$query  = sprintf("SELECT essays.*, class_students.* FROM essays INNER JOIN class_students ON essays.class_id = class_students.class_id Where class_students.grade_level = '$stu_grade' ORDER BY essays.essay_id DESC");
										$result = mysql_query($query);
										while($assigned_class  = mysql_fetch_object($result)){
											$feedessays[] = $assigned_class;
										}
								?>
					<span style="float: left; overflow-y: scroll; height: 200px; width: 100%;">
					<table id="student_classes" class="table table-hover table-responsive">
					<tbody>
 					<!----SHOW QUIZ------------>
						<?php
						   if(is_array($feedquizsz) && !empty($feedquizsz))
							{
								//echo "<pre>"; print_r($feedquizs);
							  ?>
								<?php $i=1;
								   foreach ($feedquizsz as $assigned_class) {
								   //echo "<pre>"; print_r($assigned_class);
								// check quiz is taken or not------------
									$quzzID  =  $assigned_class->quiz_id;
									
									$sql = "SELECT * FROM quiz_answers Where student_id = '$sid' && quiz_id = '$quzzID'";
									$result = mysql_query($sql);
									$quzGet = mysql_fetch_object($result);
									$countQZ = mysql_num_rows($result);
									if($countQZ =='0')
									{
								?>
									<tr>
									  <td>
										<img src="img/quiz_icon.png" height="16" width="16"/>
										<a target="_blank" href="start-quiz-answer.php?id=<?php echo $assigned_class->quiz_id;?>" style="text-decoration:underline">
										<?/*<a href="#" style="text-decoration:underline">*/?>
											<?php echo "Quiz". $i++." (".$assigned_class->quiz_subject.")";?>
										</a>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->quiz_created_date);
										echo "<b>Available until </b>".date_format($date, 'F j'); ?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->quiz_holding_date_time);
										echo "<b>Due </b>".date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									</tr>
									<?php } /*}*/ } ?>
							  <?php
							}
						?>
						
					<!----SHOW ESSAY------------>
						<?php
						   if(is_array($feedessays) && !empty($feedessays))
							{
								//echo "<pre>"; print_r($feedessays); die();
							  ?>
								<?php $i=1;
								   foreach ($feedessays as $assigned_class) {
								   //echo "<pre>"; print_r($assigned_class);
								   $classID =  $assigned_class->class_id;
								//--- Below code will show the record of only tose classes in which current login student is assigned-------------- 
										$esIID = $assigned_class->essay_id;
										$sqlh = "SELECT * FROM essay_comments Where for_student_id = '$sid' && essay_id = '$esIID'";
										$resulth = mysql_query($sqlh);
										$class_grd = mysql_fetch_object($resulth);
										$Is = mysql_num_rows($resulth);
										if($Is == '0')
										{
								   ?>
									<tr>
									  <td>
										<img src="img/essay_icon.png" height="16" width="16"/>
											<a target="_blank" href="view_essay_student.php?id=<?php echo $assigned_class->essay_id;?>" style="text-decoration:underline">
											<?php echo "Essay". $i++." (".$assigned_class->essay_title.")";?>
										</a>
										</td>
									  <td>
										<?php
										$date = date_create($assigned_class->avlaible_until);
										echo "<b>Available until </b>".date_format($date, 'F j'); ?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->due_date);
										echo "<b>Due </b>".date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									</tr>
									<?php } } ?>
							  <?php
							}
						?>
						
					</tbody>
					</table>
					</span>
							</span>
						
					</div>
					
					<div class="span6 left_one">
						<a href="studentGrades.php">
							<span style="float:left; width:33%">
								<p class="in_head">RECENTLY GRADED
								</p>
							</span>
						</a>
							<span style="clear:both; float:left; width:100%;">
								<?/*<img src="img/demo-image.png" class="avatar">*/?>
								<?php 
									//--- Get QUIZES-----------------------	
										$sql = "SELECT quizes.*, class_students.* FROM quizes INNER JOIN class_students ON quizes.class_id = class_students.class_id Where class_students.email = '$student_email' ORDER BY quizes.quiz_id DESC";
										$result = mysql_query($sql);
										//d($sql,1);
										while($feedquiz  = mysql_fetch_object($result))
										{
											$feedquizs[] = $feedquiz;
										} 
								?>
					<span style="float:left; height:200px; overflow-y:scroll; width:100%;">
					<table id="student_classes" class="table table-hover table-responsive">
					<tbody>
					<tr class="listingz" style="background:#a3a3a3">
						<td style="color:#000; font-weight:bold;">Name</td>
						<td style="color:#000; font-weight:bold;">Due</td>
						<td style="color:#000; font-weight:bold;">Score</td>
						<td style="color:#000; font-weight:bold;">Out Of</td>
					</tr>
					<!----SHOW QUIZ------------>
						<?php
						   if(is_array($feedquizs) && !empty($feedquizs))
							{
								//echo "<pre>"; print_r($feedquizs);
							  ?>
								<?php $i=1;
								   foreach ($feedquizs as $assigned_class) {
								   //echo "<pre>"; print_r($assigned_class);
								//--get achieved points for this quiz----------------
									$quzID = $assigned_class->quiz_id;
									$sql = "SELECT * FROM quiz_answers Where student_id = '$sid' && quiz_id = '$quzID'";
									$result = mysql_query($sql);
									$quzGet = mysql_fetch_object($result);
									$countQZ = mysql_num_rows($result);
									if($countQZ !='0')
									{
								?>
									<tr>
									  <td>
										<img src="img/quiz_icon.png" height="16" width="16"/>
										<?/*<a target="_blank" href="start-answer.php?id=<?php echo $assigned_class->quiz_id;?>" style="text-decoration:underline">*/?>
										
											<?php echo "Quiz". $i++?>
											<?php
												$qz_sub = $assigned_class->quiz_subject;
												if($qz_sub) { echo " (".$qz_sub.")"; }
											?>
										<?/*</a>*/?>
									  </td>
									  <td>
										<?php
										$date = date_create($assigned_class->quiz_holding_date_time);
										echo date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									  <td>
										<?php 
											$sqltot = "SELECT SUM(points) FROM quiz_answers Where student_id = '$sid' && quiz_id = '$quzID'";
											$resulttot = mysql_query($sqltot);
											$quzGettot = mysql_fetch_array($resulttot);
											//echo "<pre>"; print_r($quzGettot);
											echo round($quzGettot['SUM(points)']);
										?>
									  </td>
									  <td>
										<?php echo $assigned_class->points; ?>
									  </td>
									</tr>
									<?php /*} }*/ } } ?>
							  <?php
							}
						?>
						
					<!----SHOW ESSAY------------>
						<?php
						   if(is_array($feedessays) && !empty($feedessays))
							{
								
							  ?>
								<?php $i=1;
								   foreach ($feedessays as $assigned_class) {
								   //echo "<pre>"; print_r($assigned_class);
								   $classID =  $assigned_class->class_id;
								//--- Below code will show the record of only tose classes in which current login student is assigned-------------- 
										$esIID = $assigned_class->essay_id;
										$sqlh = "SELECT * FROM essay_comments Where for_student_id = '$sid' && essay_id = '$esIID'";
										$resulth = mysql_query($sqlh);
										$class_grd = mysql_fetch_object($resulth);
										$Is = mysql_num_rows($resulth);
										if($Is != '0')
										{
								   ?>
									<tr>
									  <td>
										<img src="img/essay_icon.png" height="16" width="16"/>
											<?/*<a target="_blank" href="view_essay_student.php?id=<?php echo $assigned_class->essay_id;?>" style="text-decoration:underline">*/?>
											<?php echo "Essay". $i++." (".$assigned_class->essay_title.")";?>
										<?/*</a>*/?>
										</td>
									  <td>
										<?php
										$date = date_create($assigned_class->due_date);
										echo date_format($date, 'F j \a\t g:ia'); ?>
									  </td>
									  <td>
										<?php 
											$esyId = $assigned_class->essay_id; 
											$sqlh = "SELECT * FROM essay_comments Where for_student_id = '$sid' && essay_id = '$esyId'";
											$resulth = mysql_query($sqlh);
											$class_grd = mysql_fetch_object($resulth);
											if($class_grd->grade) { echo $class_grd->grade; } else { echo '0.00'; }
										?>
									  </td>
									  <td>10</td>
									</tr>
									<?php } } ?>
							  <?php
							}
						?>
						
					</tbody>
					</table>
					</span>
							</span>
						
					</div>
					
					<div class="span6 left_one" style="margin-left:0px;">
						<a href="student-offer-classes.php" alt="Enroll" title="Enroll">
							<span style="float:left; width:33%">
								<p class="in_head">CLASS LIST</p>
							</span>
						</a>
							<span style="clear:both; float:left; width:100%;">
								<?/*<img src="img/demo-image.png" class="avatar">*/?>
								<?php
									//$query  = sprintf("SELECT t1.*,t2.user_name,t2.fname,t2.lname,t2.email FROM classes as t1 LEFT JOIN teachers as t2 ON t1.creator_tid=t2.tid WHERE t1.grade_level='%s' AND t1.class_id NOT IN (SELECT t3.class_id FROM class_students as t3 WHERE t3.email='%s')",$_SESSION['grade_level'],$student_email );
									$grade_level = $_SESSION['grade_level'];
									$query  = sprintf("SELECT t1.*,t2.user_name,t2.fname,t2.lname,t2.email FROM classes as t1 LEFT JOIN teachers as t2 ON t1.creator_tid=t2.tid WHERE t1.grade_level='%s'",$_SESSION['grade_level'],$student_email );
									$result = mysql_query($query);
									while($offer_class  = mysql_fetch_object($result)){
									   //$offer_classes[$offer_class->class_id] = $offer_class;
									   $offer_classes[] = $offer_class;
									}
								?>
						<span style="float:left; height:200px; overflow-y:scroll; width:100%;">
						<?php
                       if(is_array($offer_classes) && !empty($offer_classes)){
                          ?>

                            <table class="table table-hover table-responsive">
                              <thead>
                                <tr style="float: left; width: 477px;">
                                  <th style="float:left; width:39px">Class Name</th>
									<th style="float:left; width:39px">Class Details</th>
									<th style="float:left; width:39px">Grade Level</th>
									<th style="float:left; width:53px">Meeting Hours</th>
									<th style="float:left; width:59px">No of Students</th>
									<th style="float:left; width:39px">Start date</th>
									<th style="float:left; width:39px">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                               <?php $i=1;
                               foreach ($offer_classes as $offer_class) {
                               ?>
                                <tr class="<?php echo $offer_class->status;?>" style="float: left; width: 477px;">
                                  <td style="float:left; width:39px; text-align:center;">
									<a href="class_dashboard.php?Qid=<?php echo $offer_class->class_id; ?>">
										<?php echo $offer_class->class_name;?>
									</a>
								  </td>
                                  <td style="float:left; width:39px; text-align:center;"><?php echo substr($offer_class->class_details,0,25)."...";?></td>
                                  <td style="float:left; width:39px; text-align:center;"><?php echo $offer_class->grade_level;?></td>
                                  
                                  <td style="float:left; width:39px; text-align:center;"><?php echo $offer_class->meeting_hours;?></td>
                                  <td style="float:left; width:58px; text-align:center;"><?php echo $offer_class->fname;?> <?php echo $offer_class->lname;?></td>
                                  <?/*<td style="float:left; width:39px; text-align:center;"><?php echo $offer_class->email;?></td>*/?>
                                  <td style="float:left; width:56px; text-align:center;">
									<?php
										$date = date_create($class->created_date);
										echo date_format($date, 'j F'); 
									?>
								  </td>
								  
                                  <td style="float:left; width:39px; text-align:center;">
								  <?/*<a class="btn btn-primary" href="view-class-students.php?id=<?php echo $offer_class->class_id?>" >View Students</a>*/?>
								  </br>
								  <a class="btn btn-primary" href="student-offer-classes.php?id=<?php echo $offer_class->class_id?>" style="width: 29px; padding: 0px 2px 0px 3px;">Join</a></td>
                                </tr>
                                <?php } ?>
                              </tbody>
                            </table>
                          <?php
                       }else{
                          ?>
                          <div class="alert alert-warning">
                            <strong>Warning!</strong> No courses available for you to enroll.
                          </div>
                          <?php
                       }
                    ?>
								</span>
							</span>
						
					</div>
					
					<div class="span6 left_one">
						<a href="student_annoucements.php">
							<span style="float:left; width:33%">
								<p class="in_head">ANNOUNCEMENTS</p>
							</span>	
						</a>
							<?/*<span style="clear:both; float:left">
								<img src="img/demo-image.png" class="avatar">
							</span>*/?>
							<span style="float:left; height:200px; overflow-y:scroll; width:100%;">
							<?php
							//--GET ALL ANNOUCEMENTS-------------	
								$end_sql    = "SELECT annoucements.*, classes.* FROM annoucements LEFT JOIN classes ON annoucements.class_id = classes.class_id WHERE classes.grade_level='$grade_level' ORDER BY ann_id DESC";
								$end_result = mysql_query($end_sql);
								$contxx = mysql_num_rows($end_result);
								if($contxx != '0') 
								{
							?>
							<table class="table table-hover table-responsive">
							<thead>
								<tr>
									<th>#</th>
									<th>Announcement Title</th>
									<th>Announcement Content</th>
									<th>Class Name</th>
									<th>Announcement Timestamp</th>
								</tr>
							</thead>
							<?php 
								$i="0";
								while($ann = mysql_fetch_object($end_result))
								{ $i++;?>
								
									<tr>
										<td><?php echo $i; ?></td>
										<td>
											<a href="#" onclick="show_pops(<?php echo $ann->ann_id;?>)">
												<?php echo $ann->ann_title;?>
											</a>
										</td>
										<td><?php echo substr($ann->ann_text,0,30)."...";?></td>
										<td>
											<?php 
												$classId = $ann->class_id;
												$end_s1 = "SELECT * FROM classes WHERE class_id='$classId'";
												$end_res1 = mysql_query($end_s1);
												$ann1 = mysql_fetch_object($end_res1);
												echo $ann1->class_name;
											?>
										</td>
										<td><?php echo $ann->timestamp;?></td>
									</tr>
								<?
								}
								?>
							
							</table>
							<?php
							}
							else
							{
								echo "No Announcement Found.";
							}
							?>
							</span>
							
							</a>
					</div>
				</div>
            </div>
        </div>
    </div>


  <!-- scripts -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.10.2.custom.min.js"></script>
    <!-- knob -->
    <script src="js/jquery.knob.js"></script>
    <!-- flot charts -->

    <script src="js/theme.js"></script>

    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();
			//$("#myModal_pOp").show();
			//$('.modal-backdrop').show();
        });
		//--Function to show information pop-up 
		function show_pops(str)
		{
			$.ajax({
				type: "POST",
				url: "getAnnouncementInfo.php",
				data: 'str='+str,
				context: document.body
				}).done(function(result){
					//alert(result);
					//return false;
					$('#InfoByAjax').html(result);
					$('.modal-backdrop').show();
					$('.annoucementINF').show();
				});
			
		}
		
		function HideMe()
		{
			$('.modal-backdrop').hide();
			$('.annoucementINF').hide();
		}
    </script>
	<?php
	$sql = "SELECT * from quiz_answers where student_id='$sid'";
	$result = mysql_query($sql);
	$counTT = mysql_num_rows($result);
	if($counTT == "0")
	{
	?>
	<div id="myModal_pOp">
		<form enctype="multipart/form-data" method="post" id="fileupload">
			<div class="modal-header">
				<?/*<span id="CrossME" onclick="return HideMe();" >×</span>*/?>
				<h3 id="myModalLabel">Welcome to Lurnn.com</h3>
				<p id="para">TIME TO GET YOU ENROLLED!</p>
				<div class="row-fluid">
                    <p class="span2 offset9">
					<a class="btn btn-primary" href="student-offer-classes.php" style="float: right; width: 195px;">
					CONTINUE TO ENROLLMENT
					</a>
					</p>
                </div>
			</div>
		</form>
	</div>
	<div class="modal-backdrop fade in"></div>
	<?
	}
	?>
	
	<!----POP-UP WITH ANNOUNCEMENT INFORMATION---------->
	<div id="myModal_pOp" class="annoucementINF" style="display:none; height:auto;">
		<form enctype="multipart/form-data" method="post" id="fileupload">
			<div class="modal-header">
				<span id="CrossME" onclick="return HideMe();" >×</span>

				<span id="InfoByAjax"></span>
			</div>
		</form>
	</div>
	<div class="modal-backdrop fade in" style="display:none;"></div>
	
</body>
</html>