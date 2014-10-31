<?php
    // You have to write all php code here
   require_once "config.php";
   if(isset($_SESSION['tid'])){
      $user_id = $_SESSION['tid'];
      $type    = "teacher";
      $email   = $_SESSION['email']; 
   }else if(isset($_SESSION['sid'])){
      $user_id = $_SESSION['sid'];
      $type    = "student";
      $email   = $_SESSION['email'];
   }else{
        echo "<script>window.location='/';</script>";
        exit;
   }
   if(!isset($_GET['id']) || is_int($_GET['id'])){
        exit("Page Not Found");
   }

   $post_id = $_GET['id'];
   
   if((isset($_SESSION['tid']) || isset($_SESSION['sid'])) && isset($_POST['submit'])){
      
      if(isset($_POST['comments']) && $_POST['comments']!=""){
        $comments = mysql_real_escape_string(trim($_POST['comments']));
      }else{
        $errors['comments'] = "Comment is required";
      }
     
      if(!isset($errors) || empty($errors)){
         $sql = sprintf("INSERT INTO discussions_comments(post_id,commented_by,user_id,type,comments,created_date) VALUES('%d','%s','%d','%s','%s','%s')",
                $post_id,
                $_SESSION['email'],
                $user_id,
                $type,
                $comments,
                date("Y-m-d H:i:s")
            );
         if(mysql_query($sql)){
           
         }else{
            
            $errors['other'] = "Sorry!!! Internal Problem! Please try again later";
         }
      }

      if(isset($errors) && !empty($errors)){
         $_SESSION['flash_errors'] = $errors;
      }else{
         //$_SESSION['flash_success'] = "You have successfully created this post";
      }
      header("Location:".curPageURL());exit;
   }
   
   if(isset($_GET['id'])){
      $post_id = mysql_real_escape_string($_GET['id']);
      $sql     = 'SELECT *FROM discussions_details_with_comments_attachments WHERE post_id ="'.$post_id.'"';
      //d($sql,1);
      $result  = mysql_query($sql);
      $feed    = mysql_fetch_object($result);
      if(mysql_num_rows($result)!=1){
         $error = "Error 404! Page not found";
      }
   }else{
      $error = "You are not allowed to access this page";
   }
   $sql      = "SELECT * FROM `teacher_student_profile` WHERE user_id='$user_id' AND type='$type' AND email='".$_SESSION['email']."'";
   $result   = mysql_query($sql);
   $user     = mysql_fetch_object($result);
   //d($user,1);
   $sql      = "SELECT * FROM `discussions_comments_details` WHERE post_id='$post_id' ORDER BY comment_id";
   $result   = mysql_query($sql);
   while($comment     = mysql_fetch_object($result)){
      $comments[$comment->comment_id]       = $comment;
   }
  

  if($type=="student"){

        $sql    = "SELECT * FROM `students_assigned_classes` WHERE sid='$user_id'";
       
        $result = mysql_query($sql);
        while($class = mysql_fetch_object($result)){
            $classes[$class->class_id] = $class;
        }

        $sql    = "SELECT *FROM `discussions_attachment` 
                           WHERE post_id='$post_id'";
        $result = mysql_query($sql);

        while($attachment = mysql_fetch_object($result)){
            $attachments[] = $attachment;
        }

   }else{

        $sql    = "SELECT * FROM `classes` WHERE creator_tid ='".$_SESSION['tid']."'";
        //d($user,1);
        $result = mysql_query($sql);

        while($class = mysql_fetch_object($result)){
            $classes[$class->class_id] = $class;
        }
        
        $sql    = "SELECT *FROM discussions_attachment
                           WHERE post_id ='$post_id'";
        $result = mysql_query($sql);
        //d($sql,1);
        while($attachment = mysql_fetch_object($result)){
            $attachments[] = $attachment;
        }
        
   }
   
?>
<!DOCTYPE html>
<html>
<head>
	<title>lurnn</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
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
    <style>

        textarea {
            border:2px solid #ccc;
            padding: 10px;
            vertical-align: top;
        }

        .animated {
            -webkit-transition: height 0.2s;
            -moz-transition: height 0.2s;
            transition: height 0.2s;
        }

    </style>
    <!-- blueimp Gallery styles -->
    <link rel="stylesheet" href="http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="fileupload/css/jquery.fileupload.css">
    <link rel="stylesheet" href="fileupload/css/jquery.fileupload-ui.css">
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" href="fileupload/css/jquery.fileupload-noscript.css"></noscript>
    <noscript><link rel="stylesheet" href="fileupload/css/jquery.fileupload-ui-noscript.css"></noscript>
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
</head>
<body>

   

   
     <?php
     if($type=="teacher"){
       include "includes/menu.php";
       include "includes/left_side_bar.php";
     }else{
       include "includes/student_menu.php";
       include "includes/student_left_side_bar.php";
     }
     ?>




	<!-- main container -->
    <div class="content">
        
        <div class="container-fluid">
            <?php
                if(isset($error)){
                    ?>
                    <div class="alert alert-danger">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <?php
                        echo $error;
                    ?>
                    </div>
                    <?php
                }   
            ?>

            <div id="pad-wrapper">
                <!-- statistics chart built with jQuery Flot -->
                <div class="row-fluid">
                    <div id="discussion_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <form  id="fileupload" action="discussion_edit.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="post_id" value="<?php echo $feed->post_id?>"/>
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel">Edit Post</h3>
                            </div>
                            <div class="modal-body">
                                 
                                <div class="control-group">
                                    <!--<label class="control-label" for="inputEmail">Subject</label>-->
                                    <div class="controls">
                                        <input type="text" name="post_title" value="<?php echo trim($feed->post_title) ;?>" required="required" class="span12" placeholder="Type Your Post Title">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <!--<label class="control-label" for="input">Description</label>-->
                                    <div class="controls">
                                        <textarea name="post_content" required="required" class="animated span12" placeholder="Write Your Post Details"><?php echo trim($feed->post_content) ;?></textarea>
                                    </div>
                                </div>
                                <div class="control-group" style="height:70px;">
                                          
                                    <div class="controls">
                                    <?php if($type=="teacher"){ ?>
                                        <div class="span6">
                                            <label>Skills (Comma Separated):</label>
                                            <input type="text" name="skills" required="required" class="span12" value="<?php echo $feed->skills ;?>" />

                                        </div>
                                    <?php } ?>
                                        <div class="span6">
                                            <label>Choose Class:</label>
                                            <div class="ui-select span12">
                                                <select name="class_id" required="required">
                                                <?php if(is_array($classes) && !empty($classes) ){ 

                                                      foreach($classes as $class){
                                                ?>

                                                    <option value="<?php echo $class->class_id;?>" <?php if($class->class_id == $feed->class_id) {echo 'selected="selected"';}?>><?php echo $class->class_name;?></option>
                                                <?php }} ?>
                                                </select>
                                            </div>
                                        </div>
                                                
                                    </div>
                                </div>
                                <div class="control-group fileupload-buttonbar">
                                    <div class="span7">
                                        <!-- The fileinput-button span is used to style the file input field as button -->
                                        <span class="btn btn-success fileinput-button">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span>Add Attachment</span>
                                            <input type="file" name="files[]" multiple>
                                        </span>
                                        <button type="submit" class="btn btn-primary start">
                                            <i class="glyphicon glyphicon-upload"></i>
                                            <span>Start Upload</span>
                                        </button>
                                        
                                        <!-- The global file processing state -->
                                        <span class="fileupload-process"></span>
                                    </div>
                                    <!-- The global progress state -->
                                    <div class="span5 fileupload-progress fade">
                                        <!-- The global progress bar -->
                                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                        </div>
                                        <!-- The extended global progress state -->
                                        <div class="progress-extended">&nbsp;</div>
                                    </div>
                                </div>
                                        <!-- The table listing the files available for upload/download -->
                                <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                                
                                       <?php if(is_array($attachments) && !empty($attachments)){ ?>
                                       <table class="table table-striped" role="presentation">
                                    <tbody class="previous_files">
                                       <?php
                                            foreach ($attachments as $attachment) {
                                                $file_name = urldecode(@end(@explode("/",$attachment->file_path)));

                                                //continue;
                                             ?>

                                        
                                        <tr class="template-download fade in" id="attachment_<?php echo $attachment->attachment_id;?>">
                                            <td>
                                                <span class="preview"></span>
                                            </td>
                                            <td>
                                                <p class="name">
                                                    
                                                        <a download="<?php echo $file_name?>" title="<?php echo $file_name?>" href="<?php echo $attachment->file_path;?>"><?php echo $file_name?></a>                                            
                                                </p>
                                                
                                            </td>
                                            <td>
                                                <p class="size"><?php echo formatSizeUnits(filesize(".".parse_url($attachment->file_path,PARSE_URL_PATH)));?></p>
                                                
                                            </td>
                                            <td>
                                                
                                                    <button onclick="deleteAttachment('<?php echo $attachment->attachment_id?>');return false;" class="btn btn-danger delete">
                                                        <i class="glyphicon glyphicon-trash"></i>
                                                        <span>Delete</span>
                                                    </button>
                                                    
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        </tbody>
                                </table>       
                                       
                                        <?php } ?>
                                    
                                    
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                                <button type="submit" name="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>        
                    <div class="media" id="post_<?php echo $feed->post_id?>">
                      <a class="pull-left" href="#">
                        <img src="uploads/<?php echo $feed->profile_picture?>" style="width: 64px; height: 64px;" alt="64x64" class="media-object" data-src="holder.js/64x64">
                      </a>
                      <div class="media-body">
                        <p class="media-heading"><strong><?php echo $feed->fname?> <?php echo $feed->lname?></strong> created a post in <strong><?php echo $feed->class_name?> ( <?php echo $feed->class_code?> )</strong>
                          <?php
                          if($type=="teacher" || ($user_id==$feed->user_id && $email==$feed->email && $type=="student")){
                            ?>
                            &nbsp; &nbsp; <a class="btn btn-info" data-backdrop="static" data-toggle="modal" data-target="#discussion_edit">Edit</a>
                            <a class="btn btn-danger" href="#" onclick="deletePost('<?php echo $feed->post_id;?>');return false;">Delete</a>
                            <?php
                          }
                          ?>
                        </p>
                        
                        <div class="media">
                          <div class="media-body span11" style="padding-left: 30px;">
                            <h3 class="media-heading"><?php echo $feed->post_title;?></h3>
                            <p style="text-align:justify;"><?php echo nl2br($feed->post_content);?></p>
                           
                            <p>
                               <strong class="pull-left"><?php echo time_elapsed_string(strtotime($feed->created_date)); ?></strong>
                              
                               <?php if($feed->total_attachments ==1) { ?>
                               <a class="btn btn-small btn-primary pull-right" href="download.php?id=<?php echo $feed->post_id;?>" title="Click here to download"><?php echo $feed->total_attachments;?> Attachment</a>
                               <?php }else if($feed->total_attachments >1){
                                ?>
                                 <a class="btn btn-small btn-primary pull-right" href="download.php?id=<?php echo $feed->post_id;?>" title="Click here to download"><?php echo $feed->total_attachments;?> Attachments</a>
                                <?php
                                } ?>
                               <p class="pull-right"> &nbsp; </p>
                               <?php if($feed->total_comments ==1) { ?>
                               <a class="btn btn-small btn-primary pull-right" href="discussiondetails.php?id=<?php echo $feed->post_id;?>#comments"><?php echo $feed->total_comments;?> Comment</a>
                               <?php }else if($feed->total_comments >1){
                                ?>
                                 <a class="btn btn-small btn-primary pull-right" href="discussiondetails.php?id=<?php echo $feed->post_id;?>#comments" ><?php echo $feed->total_comments;?> Comments</a>
                                <?php
                                } ?>
                               
                            </p>
                          </div>
                        </div>
                        <?php 
                            if(is_array($comments) && !empty($comments)){
                               foreach($comments as $comment){
                                ?>
                                <div class="media" style="margin-left:30px;margin-top:30px;" id="commentbox_<?php echo $comment->comment_id;?>">
                                  <a href="#" class="pull-left">
                                    <img  class="media-object" src="uploads/<?php echo $comment->profile_picture?>" style="width: 64px; height: 64px;">
                                  </a>
                                  <div class="media-body">
                                    <p class="media-heading"><strong><?php echo $comment->fname?> <?php echo $comment->lname?></strong> commented on this post <strong><?php echo time_elapsed_string(strtotime( $comment->created_date));?></strong></p>
                                     <p class="span10" style="text-align:justify;margin-left:0;">
                                        <a href="#" data-name="comments" class="editable editable-click editable-disabled" id="comment_<?php echo $comment->comment_id;?>" data-type="textarea" data-pk="<?php echo $comment->comment_id;?>" data-url="/discussion.ajax.php?action=edit_comment" data-title="Edit your comments">
                                            <?php echo nl2br($comment->comments)?>
                                        </a><br/>

                                     </p>
                                     <p class="span10 text-right">
                                     <button class="btn btn-info" onclick="editComment('<?php echo $comment->comment_id;?>');return false;">Edit</button> &nbsp; 
                                        <button class="btn btn-danger" onclick="deleteComment('<?php echo $comment->comment_id;?>');return false;">Delete</button>
                                     </p>
                                    
                                  </div>
                                </div>
                                <?
                               }
                            }    
                        ?>
                        

                        <form class="row-fluid" method="post" enctype="multipart/form-data">
                        
                            <div class="control-group">
                                <!--<label class="control-label" for="input">Description</label>-->
                                <div class="controls" style="margin-left:30px;margin-top:30px;">
                                    <div class="media">
                                      <a href="#" class="pull-left">
                                        <img  class="media-object" src="uploads/<?php echo $user->profile_picture?>" style="width: 64px; height: 64px;" src="">
                                      </a>
                                      <div class="media-body">
                                        <textarea name="comments" rows="1" required="required" class="animated span10" placeholder="Write Your Comment"></textarea>
                                        <br/>
                                        <button type="submit" name="submit" class="btn btn-primary">Post Comment</button>
                                      </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </form>
                      </div>
                    </div>
          
                   
                </div>
            </div>
        </div>
    </div>

 
    
	
    <!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>

<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                    <input type="hidden" name="attachments[]" value="{%=file.url%}">
                {% } else { %}
                    <span>{%=file.name%}</span>
                    <input type="hidden" name="attachments[]" value="{%=file.name%}">
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="fileupload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="fileupload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="fileupload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="fileupload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="fileupload/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="fileupload/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="fileupload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="fileupload/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="fileupload/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="fileupload/js/cors/jquery.xdr-transport.js"></script>
<![endif]-->
<!-- scripts -->
    <!-- knob -->
    <script src="js/jquery.knob.js"></script>
    <!-- flot charts -->
    <script src="js/theme.js"></script>
    <script src='js/jquery.autosize.min.js'></script>
    <script src="js/bootbox.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap-editable/js/bootstrap-editable.min.js"></script>
   
    <script type="text/javascript">
        $(function () {

            // jQuery Knobs
            $(".knob").knob();
            $('.animated').autosize({append: "\n"});
        });
        function deleteAttachment(attachment_id){
            bootbox.confirm("Are you sure to delete this Attachment?", function(result) {
                if(result==false) return;
                $.ajax({
                    type: "POST",
                    url: "discussion.ajax.php",
                    data: { action: "delete_attachment", attachment_id: attachment_id }
                })
                .done(function( msg ) {
                  if(msg=="yes"){
                      bootbox.alert("<h3 class='text-success'>You have successfully deleted this Attachment.</h3>");
                      $("#attachment_"+attachment_id).hide();
                   }else{
                      bootbox.alert("<h3 class='text-danger'>Sorry!! we could not delete this attachment.Please try again later.</h3>");
                   }
                });
               
          }); 
            
            
        }
        function deletePost(post_id){
             bootbox.confirm("Are you sure to delete this post?", function(result) {
                if(result==false) return;

                $.ajax({
                    type: "POST",
                    url: "discussion.ajax.php",
                    data: { action: "delete_post", post_id: post_id }
                })
                .done(function( msg ) {
                   
                   if(msg=="yes"){
                      bootbox.alert("<h3 class='text-success'>You have successfully deleted this post.</h3>");
                      window.location="discussionboard.php";
                   }else{
                      bootbox.alert("<h3 class='text-danger'>Sorry!! we could not delete this post.Please try again later.</h3>");
                   }
                });
                
          }); 
            
        }

        
        function editComment(id){
            $('#comment_'+id).editable({
              mode:"popup",
              validate: function(value) {
                if($.trim(value) == '') {
                  return 'This field is required';
                }
              },
              onblur: "submit",
              success: function(response, newValue) {
                //console.log(response);
                //console.log(response.status);
                //console.log(response.msg);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
                $('#comment_'+id).editable("disable");
              },
              error: function(response, newValue) {
                $('#comment_'+id).editable("disable");
              }
            });

            $('#comment_'+id).editable("enable");
        }
        function deleteComment(comment_id){
             bootbox.confirm("Are you sure to delete this comment?", function(result) {
                if(result==false) return;

                $.ajax({
                    type: "POST",
                    url: "discussion.ajax.php",
                    data: { action: "delete_comment", comment_id: comment_id }
                })
                .done(function( msg ) {
                   
                   if(msg=="yes"){
                      //bootbox.alert("<h3 class='text-success'>You have successfully deleted this comment.</h3>");
                      $("#commentbox_"+comment_id).hide();
                      console.log(comment_id);
                   }else{
                      bootbox.alert("<h3 class='text-danger'>Sorry!! we could not delete this comment.Please try again later.</h3>");
                   }
                });
                
          }); 
            
        }
    </script>
</body>
</html>