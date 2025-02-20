<?php include"header.php";?>
<?php include"sidebar.php";?>
<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
 <div class="page-content">
       <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Add Portfolio</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Portfolio</a></li>
                                        <li class="breadcrumb-item active">Add</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->


                    <div class="row">

                        <!--end col-->
                        <div class="col-xxl-9">
                            <div class="card mt-xxl-n5">
                                <div class="card-header">
                                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab" aria-selected="false">
                                                <i class="fas fa-home"></i> Add Portfolio
                                            </a>
                                        </li>


                                    </ul>
                                </div>



                                <?php
           $status = "OK"; //initial status
$msg="";
           if(ISSET($_POST['save'])){
$port_title = mysqli_real_escape_string($con,$_POST['port_title']);
$port_desc = mysqli_real_escape_string($con,$_POST['port_desc']);
$port_detail = mysqli_real_escape_string($con,$_POST['port_detail']);

//  if ( strlen($port_title) < 5 ){
// $msg=$msg."Portfolio Title Must Be More Than 5 Char Length.<BR>";
// $status= "NOTOK";}
//  if ( strlen($port_desc) > 150 ){
// $msg=$msg."Portfolio description Must Be Less Than 150 Char Length.<BR>";
// $status= "NOTOK";}

// if ( strlen($port_detail) < 15 ){
//   $msg=$msg."Portfolio Detail Must Be More Than 15 Char Length.<BR>";
//   $status= "NOTOK";}



$uploads_dir = 'uploads/portfolio';

        $tmp_name = $_FILES["ufile"]["tmp_name"];
        // basename() may prevent filesystem traversal attacks;
        // further validation/sanitation of the filename may be appropriate
        $name = basename($_FILES["ufile"]["name"]);
        $random_digit=rand(0000,9999);
        $new_file_name=$random_digit.$name;

        move_uploaded_file($tmp_name, "$uploads_dir/$new_file_name");

if($status=="OK")
{
    $photo_names = array();
    $video_names = array();

    // Handle additional photos
    if(isset($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
        foreach($_FILES['photos']['tmp_name'] as $key => $tmp_name){
            if($_FILES['photos']['error'][$key] == 0){
                $photo_name = rand(0000,9999) . basename($_FILES['photos']['name'][$key]);
                if(move_uploaded_file($tmp_name, "$uploads_dir/$photo_name")) {
                    $photo_names[] = $photo_name;
                }
            }
        }
    }

    // Handle videos
    if(isset($_FILES['videos']) && !empty($_FILES['videos']['name'][0])) {
        foreach($_FILES['videos']['tmp_name'] as $key => $tmp_name){
            if($_FILES['videos']['error'][$key] == 0){
                $video_name = rand(0000,9999) . basename($_FILES['videos']['name'][$key]);
                if(move_uploaded_file($tmp_name, "$uploads_dir/$video_name")) {
                    $video_names[] = $video_name;
                }
            }
        }
    }

    $photos_str = !empty($photo_names) ? implode(',', $photo_names) : '';
    $videos_str = !empty($video_names) ? implode(',', $video_names) : '';

    $qb=mysqli_query($con,"INSERT INTO portfolio (port_title, port_desc, port_detail, ufile, media_photos, media_videos) 
                          VALUES ('$port_title', '$port_desc', '$port_detail', '$new_file_name', '$photos_str', '$videos_str')");

    if(!$qb){
        $errormsg = "Error adding portfolio entry";
    } else {
        $errormsg= "
<div class='alert alert-success alert-dismissible alert-outline fade show'>
                  Portfolio has been added successfully.
                  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                  </div>
 ";
    }
}
	}

        elseif ($status!=="OK") {
            $errormsg= "
<div class='alert alert-danger alert-dismissible alert-outline fade show'>
                     ".$msg." <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button> </div>"; //printing error if found in validation


    }
    else{
			$errormsg= "
      <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                 Some Technical Glitch Is There. Please Try Again Later Or Ask Admin For Help.
                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                 </div>"; //printing error if found in validation


		}
          
           ?>



                                <div class="card-body p-4">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                        <?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
						{
						print $errormsg;
						}
   ?>
              <form action="" method="post" enctype="multipart/form-data">
                                                <div class="row">



   <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label"> Portfolio Title</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="port_title" placeholder="Enter Portfolio Title">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label"> Short Description</label>
                                                            <textarea class="form-control" id="exampleFormControlTextarea5" name="port_desc" rows="2"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label"> Portfolio Detail</label>
                                                            <textarea class="form-control" id="exampleFormControlTextarea5" name="port_detail" rows="3"></textarea>
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Main Photo</label>
                                                            <input type="file" class="form-control" id="firstnameInput" name="ufile" >
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="additionalPhotos" class="form-label">Additional Photos</label>
                                                            <input type="file" class="form-control" id="additionalPhotos" name="photos[]" multiple accept="image/*">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="videos" class="form-label">Videos</label>
                                                            <input type="file" class="form-control" id="videos" name="videos[]" multiple accept="video/*">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-12">
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <button type="submit" name="save" class="btn btn-primary">Add Portfolio</button>

                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                                <!--end row-->
                                            </form>
                                        </div>
                                        <!--end tab-pane-->

                                        <!--end tab-pane-->

                                        <!--end tab-pane-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>


                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include"footer.php";?>