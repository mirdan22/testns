<?php
include "header.php";
if(!isset($_GET["id"])) {
    die("Portfolio ID not provided");
}
$todo = mysqli_real_escape_string($con,$_GET["id"]);

// Verify database connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Test query
$rt = mysqli_query($con,"SELECT * FROM portfolio WHERE id='$todo'");
if(!$rt) {
    die("Query failed: " . mysqli_error($con));
}
if(mysqli_num_rows($rt) == 0) {
    die("No portfolio found with ID: $todo");
}
?>
        <!-- ***** Breadcrumb Area Start ***** -->
        <section class="section breadcrumb-area overlay-dark d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- Breamcrumb Content -->
                        <div class="breadcrumb-content d-flex flex-column align-items-center text-center">
                            <h2 class="text-white text-uppercase mb-3">Portfolio Details</h2>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a class="text-uppercase text-white" href="index.php">Home</a></li>

                                <li class="breadcrumb-item text-white active">Portfolio</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ***** Breadcrumb Area End ***** -->


        <?php
    $rt=mysqli_query($con,"SELECT * FROM portfolio where id='$todo'");
    $tr = mysqli_fetch_array($rt);
    $port_title = "$tr[port_title]";
    $port_detail = "$tr[port_detail]";
    $ufile = "$tr[ufile]";
    $media_photos = !empty($tr['media_photos']) ? explode(',', $tr['media_photos']) : [];
    $media_videos = !empty($tr['media_videos']) ? explode(',', $tr['media_videos']) : [];
?>


        <!-- ***** About Area Start ***** -->
        <section class="section about-area ptb_100">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-lg-6">
                        <!-- About Thumb -->
                        <div class="about-thumb text-center">
                            <img src="dashboard/uploads/portfolio/<?php print $ufile;?>" alt="img" class="mb-4">

                            <!-- Additional Media Gallery -->
                          
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <!-- About Content -->
                        <div class="about-content section-heading text-center text-lg-left pl-md-4 mt-5 mt-lg-0 mb-0">
                            <h2 class="mb-3"><?php print $port_title?></h2>
                            <p><?php print $port_detail;?></p>
                            <!-- Counter Area -->

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ***** About Area End ***** -->

        <!-- ***** Gallery Section Start ***** -->
      <!-- ***** Gallery Section Start ***** -->
<section class="section gallery-area bg-gray ptb_50">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">Project Gallery</h3>
                <p class="section-heading text-center">Click on Images to View</p>
                <div class="row g-4">
                    <?php
                    if (!empty($media_photos)) {
                        foreach ($media_photos as $photo) {
                            echo "<div class='col-md-4 mb-4'>
                                    <div class='gallery-item' onclick='openPopup(\"dashboard/uploads/portfolio/$photo\", \"image\")'>
                                        <img src='dashboard/uploads/portfolio/$photo' alt='Project Photo' class='img-fluid rounded shadow'>
                                    </div>
                                  </div>";
                        }
                    }

                    if (!empty($media_videos)) {
                        foreach ($media_videos as $video) {
                            echo "<div class='col-md-4 mb-4'>
                                    <div class='gallery-item' onclick='openPopup(\"dashboard/uploads/portfolio/$video\", \"video\")'>
                                        <video class='img-fluid rounded shadow'>
                                            <source src='dashboard/uploads/portfolio/$video' type='video/mp4'>
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                  </div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Gallery Section End ***** -->

<div id="galleryPopup" class="popup-overlay">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <div class="popup-inner">
            <span class="prev-btn" onclick="changeSlide(-1)">&#10094;</span>
            <div id="popupMedia"></div>
            <span class="next-btn" onclick="changeSlide(1)">&#10095;</span>
        </div>
        <!-- Scroll Indicator -->
        <div class="scroll-indicator">
            Scroll Down
            <span class="arrow-down">↓</span>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function openPopup(src, type) {
    mediaItems = document.querySelectorAll('.gallery-item'); 
    currentIndex = Array.from(mediaItems).findIndex(item => item.onclick.toString().includes(src));

    const popupMedia = document.getElementById("popupMedia");
    popupMedia.innerHTML = type === "image"
        ? `<img src="${src}" class="popup-image">`
        : `<video controls autoplay class="popup-video"><source src="${src}" type="video/mp4"></video>`;

    document.getElementById("galleryPopup").style.display = "flex";

    // Check if scroll indicator is needed
    setTimeout(() => {
        const popupContent = document.querySelector('.popup-content');
        const scrollIndicator = document.querySelector('.scroll-indicator');

        if (popupContent.scrollHeight > popupContent.clientHeight) {
            scrollIndicator.style.display = "block";
        } else {
            scrollIndicator.style.display = "none";
        }
    }, 300);
}

    let currentIndex = 0;
    let mediaItems = [];

    function openPopup(src, type) {
        mediaItems = document.querySelectorAll('.gallery-item'); 
        currentIndex = Array.from(mediaItems).findIndex(item => item.onclick.toString().includes(src));

        const popupMedia = document.getElementById("popupMedia");
        popupMedia.innerHTML = type === "image"
            ? `<img src="${src}" class="popup-image">`
            : `<video controls autoplay class="popup-video"><source src="${src}" type="video/mp4"></video>`;

        document.getElementById("galleryPopup").style.display = "flex";
    }

    function closePopup() {
        document.getElementById("galleryPopup").style.display = "none";
    }

    function changeSlide(direction) {
        currentIndex += direction;
        if (currentIndex < 0) currentIndex = mediaItems.length - 1;
        if (currentIndex >= mediaItems.length) currentIndex = 0;

        let newSrc = mediaItems[currentIndex].querySelector("img, video source").getAttribute("src");
        let newType = mediaItems[currentIndex].querySelector("img") ? "image" : "video";
        openPopup(newSrc, newType);
    }
</script>

<!-- CSS -->
<style>
    /* Custom Scrollbar */
.popup-content::-webkit-scrollbar {
    width: 8px;
}

.popup-content::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}

.popup-content::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.5);
    border-radius: 10px;
}

.popup-content::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.8);
}

/* Scroll Down Animation */
.scroll-indicator {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    font-size: 16px;
    font-weight: bold;
    opacity: 0.8;
    animation: fadeInOut 2s infinite;
    text-align: center;
    display: none; /* Initially hidden */
}

/* Down Arrow Animation */
.arrow-down {
    display: block;
    font-size: 24px;
    animation: bounce 1.5s infinite;
}

/* Fade In/Out Animation */
@keyframes fadeInOut {
    0%, 100% { opacity: 0.8; }
    50% { opacity: 0.3; }
}

/* Bouncing Down Arrow Animation */
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(10px); }
}

.popup-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.popup-content {
    max-height: 90vh; /* Prevents the popup from exceeding the viewport */
    overflow-y: auto; /* Enables vertical scrolling */
    display: flex;
    align-items: flex-start; /* Ensures images open from the top */
    justify-content: center;
    padding: 10px; /* Adds a little spacing */
}

.popup-content img {
    max-width: 90%;
    max-height: none; /* Ensures scrolling for long images */
    display: block;
}

.popup-image{
    max-width: 90%;
    max-height: 90%;
    display: block;
    margin: auto;
}
.popup-video {
    max-width: 80vw;  /* Limits width to 80% of the viewport */
    max-height: 70vh; /* Limits height to 70% of the viewport */
    display: block;
    margin: auto;
    object-fit: contain; /* Ensures it scales properly */
}
@media (max-width: 768px) {
    .popup-arrow {
        position: absolute;
        bottom: 10px; /* Moves arrows to the bottom */
        left: 50%;
        transform: translateX(-50%);
        width: auto;
    }

    .popup-arrow.left {
        left: 30%;
    }

    .popup-arrow.right {
        left: 70%;
    }
}
.close-btn {
    position: fixed;
    top: 20px;  /* Fixed at top */
    right: 20px; /* Fixed at right */
    font-size: 40px;
    color: white;
    cursor: pointer;
    /* background: rgba(255, 255, 255, 0.2); */
    padding: 10px;
    border-radius: 50%;
    transition: 0.3s ease;
    z-index: 10000; /* Ensure it's above everything */
}

.close-btn:hover {
    color: #4e4e8f;
}

.prev-btn, .next-btn {
    position: fixed;
    top: 50%;
    font-size: 50px;
    color: white;
    cursor: pointer;
    padding: 15px;
    /* background: rgba(255, 255, 255, 0.2); */
    border-radius: 50%;
    transition: 0.3s ease;
    transform: translateY(-50%);
    z-index: 10000; /* Ensure above everything */
}

.prev-btn:hover, .next-btn:hover {
    color: #4e4e8f;
}

.prev-btn {
    left: 20px; /* Fixed on the left */
}

.next-btn {
    right: 20px; /* Fixed on the right */
}


</style>
        <!-- ***** Gallery Section End ***** -->


        <!-- ***** Our Goal Area End ***** -->

        <!-- ***** Team Area Start ***** -->

        <!-- ***** Team Area End ***** -->

       <!--====== Contact Area Start ======-->
       <section id="contact" class="contact-area ptb_100">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-lg-5">




                        <!-- Contact Us -->
                        <div class="contact-us">
                            <ul>
                                <!-- Contact Info -->
                                <li class="contact-info color-1 bg-hover active hover-bottom text-center p-5 m-3">
                                    <span><i class="fas fa-mobile-alt fa-3x"></i></span>
                                    <a class="d-block my-2" href="tel:<?php print $phone1 ?>">
                                        <h3><?php print $phone1 ?></h3>
                                    </a>

                                </li>
                                <!-- Contact Info -->
                                <li class="contact-info color-1 bg-hover active hover-bottom text-center p-5 m-3">
                                    <span><i class="fas fa-envelope-open-text fa-3x"></i></span>
                                    <a class="d-none d-sm-block my-2" href="mailto:<?php print $email1 ?>">
                                        <h3><?php print $email1 ?></h3>
                                    </a>
                                    <a class="d-block d-sm-none my-2" href="mailto:<?php print $email1 ?>">
                                        <h3><?php print $email1 ?></h3>
                                    </a>

                                </li>
                            </ul>
                        </div>
                           <!-- Contact us end -->  






                    </div>

                    <div class="col-12 col-lg-6 pt-4 pt-lg-0">
                        <!-- Section Heading -->
                        <div class="section-heading text-center mb-3">
                            <h2><?php print $contact_title ?></h2>
                            <p class="d-none d-sm-block mt-4"><?php print $contact_text ?></p>
                        </div>

                        <!-- Contact Box -->
                        <div class="contact-box text-center">
                            <!-- Contact Form -->
                            <?php
           $status = "OK"; //initial status
$msg="";
           if(ISSET($_POST['save'])){
$name = mysqli_real_escape_string($con,$_POST['name']);
$email = mysqli_real_escape_string($con,$_POST['email']);
$phone = mysqli_real_escape_string($con,$_POST['phone']);
$message = mysqli_real_escape_string($con,$_POST['message']);

 if ( strlen($name) < 5 ){
$msg=$msg."Name Must Be More Than 5 Char Length.<BR>";
$status= "NOTOK";}
 if ( strlen($email) < 9 ){
$msg=$msg."Email Must Be More Than 10 Char Length.<BR>";
$status= "NOTOK";}
if ( strlen($message) < 10 ){
    $msg=$msg."Message Must Be More Than 10 Char Length.<BR>";
    $status= "NOTOK";}

if ( strlen($phone) < 8 ){
  $msg=$msg."Phone Must Be More Than 8 Char Length.<BR>";
  $status= "NOTOK";}

  if($status=="OK")
  {

$recipient="nightstonestudios0@gmail.com";

$formcontent="NAME:$name \n EMAIL: $email  \n PHONE: $phone  \n MESSAGE: $message";

$subject = "New Enquiry from NS Studios Website";
$mailheader = "From: nightstonestudios0@gmail.com \r\n";
$result= mail($recipient, $subject, $formcontent);

          if($result){
                  $errormsg= "
  <div class='alert alert-success alert-dismissible alert-outline fade show'>
                   Enquiry Sent Successfully. We shall get back to you ASAP.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
   "; //printing error if found in validation

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
             }
             ?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
						{
						print $errormsg;
						}
   ?>

<form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="name" placeholder="Name" required="required">
                                        </div>

                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email" placeholder="Email" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="phone" placeholder="Phone" required="required">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <textarea class="form-control" name="message" placeholder="Message" required="required"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-bordered active btn-block mt-3" name="save"><span class="text-white pr-3"><i class="fas fa-paper-plane"></i></span>Send Message</button>
                                    </div>
                                </div>
                            </form>
                            <p class="form-message"></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====== Contact Area End ======-->
        <!--====== Call To Action Area Start ======-->
        <section class="section cta-area bg-overlay ptb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10">
                        <!-- Section Heading -->
                        <div class="section-heading text-center m-0">
                            <h2 class="text-white"><?php print $enquiry_title; ?></h2>
                            <p class="text-white d-none d-sm-block mt-4"><?php print $enquiry_text; ?></p>
                            <a href="contact" class="btn btn-bordered-white mt-4">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====== Call To Action Area End ======-->
<?php include "footer.php"; ?>