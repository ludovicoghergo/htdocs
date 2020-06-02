<?php include('server.php') ?>
<?php
  $user = $_SESSION['username'];
  $row = getUserInfo($user);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>

  <!-- SITE TITTLE -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Classimax</title>

  <!-- FAVICON -->
  <link href="img/favicon.png" rel="shortcut icon">
  <!-- PLUGINS CSS STYLE -->
  <!-- <link href="plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet"> -->
  <!-- Bootstrap -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap-slider.css">
  <!-- Font Awesome -->
  <link href="plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- Owl Carousel -->
  <link href="plugins/slick-carousel/slick/slick.css" rel="stylesheet">
  <link href="plugins/slick-carousel/slick/slick-theme.css" rel="stylesheet">
  <!-- Fancy Box -->
  <link href="plugins/fancybox/jquery.fancybox.pack.css" rel="stylesheet">
  <link href="plugins/jquery-nice-select/css/nice-select.css" rel="stylesheet">
  <!-- CUSTOM CSS -->
  <link href="css/style.css" rel="stylesheet">


  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>

<body class="body-wrapper">


  <section>
  	<div class="container">
  		<div class="row">
  			<div class="col-md-12">
  				<nav class="navbar navbar-expand-lg navbar-light navigation">
  					<a class="navbar-brand" href="index.php">
  						<img src="images/logo_home.png" alt="">
  					</a>
  					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
  					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
  						<span class="navbar-toggler-icon"></span>
  					</button>
  					<div class="collapse navbar-collapse" id="navbarSupportedContent">
  						<ul class="navbar-nav ml-auto main-nav ">
  							<li class="nav-item active">
  								<a class="nav-link" href="index.php">Home</a>
  							</li>
  							<li class="nav-item dropdown dropdown-slide">
                    <a class="nav-link dropdown-toggle"  href="category.php">Catalog<span></span>
  								  </a>
  							</li>
  						</ul>
  						<ul class="navbar-nav ml-auto mt-10">
                <?php  if (isset($_SESSION['username'])) : ?>
                      <li class="nav-item">
      								          <a class="nav-link login-button" href="personalpage.php"><?php echo ucfirst($_SESSION['username']); ?></a>
      							  </li>
                      <li class="nav-item">
      								          <a class="nav-link login-button" href="logout.php">Logout</a>
      							  </li>
                <?php else : ?>
                  <li class="nav-item">
  								          <a class="nav-link login-button" href="login.php">Login</a>
  							  </li>
                <?php endif ?>
  						</ul>
  					</div>
  				</nav>
  			</div>
  		</div>
  	</div>
  </section>
<!--==================================
=            User Profile            =
===================================-->

<section class="user-profile section">
	<div class="container">
		<div class="row">
			<div class="col-md-10 offset-md-1 col-lg-3 offset-lg-0">
				<div class="sidebar">
					<!-- User Widget -->
					<div class="widget user">
						<!-- User Image -->
						<div class="image d-flex justify-content-center">
							<img src="images/user/user-thumb.jpg" alt="" class="">
						</div>
						<!-- User Name -->
						<h5 class="text-center">
              <?php
              echo ucfirst($row['fname']);
              echo ' ';
              echo ucfirst($row['lname'])
              ?>
            </h5>
					</div>
					<!-- Dashboard Links -->
					<div class="widget dashboard-links">
            <ul>
							<li><a class="my-1 d-inline-block disabled" href="new_sell.php"><i class="fa fa-gavel" aria-hidden="true"></i> New Sell</a></li>
							<li><a class="my-1 d-inline-block disabled" href="user_activities.php"><i class="fa fa-exchange" aria-hidden="true"></i> My activities</a></li>
							<li><a class="my-1 d-inline-block disabled" href="user_messages.php"><i class="fa fa-envelope" aria-hidden="true"></i> Messages</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-10 offset-md-1 col-lg-9 offset-lg-0">
				<!-- Edit Profile Welcome Text -->
				<div class="widget welcome-message">
					<h2>New Sell</h2>
					<p>Insert here the product's data</p>
				</div>
				<!-- Edit Personal Info -->
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="widget personal-info">
							<h3 class="widget-header user">Product's Information</h3>
							<form method="post" action="new_sell.php" enctype="multipart/form-data">
								<!-- First Name -->
								<div class="form-group">
									<label for="first-name">Title</label>
                  <input type="text" class="form-control" name="title"  id="first-name">
								</div>
								<!-- Last Name -->
								<div class="form-group">
									<label for="last-name">Category</label>
                  <select name="category" id="" class="form-control w-100">
                      <option value="">Select Category</option>
                      <option value="Car">Car</option>
                      <option value="Home">House</option>
                      <option value="Sport">Sport</option>
                      <option value="Outside">Outside</option>
                      <option value="Health">Health</option>
                      <option value="Dress">Dress</option>
                      <option value="IT">IT</option>
                      <option value="Other">Outside</option>
                  </select>
								</div>
                <div class="form-group">
									<label for="last-name">Description</label>
									<input type="text" class="form-control" name="desc" id="last-name">
								</div>
                <div class="form-group">
									<label for="last-name">Precio/H</label>
									<input type="text" class="form-control" name="precio" id="last-name">
								</div>
                <div class="form-group">
									<label for="last-name">Location</label>
									<input type="text" class="form-control" name="location" id="last-name">
								</div>
                <div class="form-group">
                <label for="new-email">Cover Image</label>
								<input type="file" name="picture" class="form-control" id="picture">
                </div>
								<!--<div class="form-group">
									<label for="last-name">Produc't photo</label>
									<input type="file" class="form-control-file mt-2 pt-1" name="photo"   id="input-file">
								 </div>
               -->
								<!-- Submit button -->
								<button class="btn btn-transparent"  type="submit" name="new_sell">OK</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<footer class="footer section section-sm">
  <!-- Container Start -->
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-7 offset-md-1 offset-lg-0">
        <!-- About -->
        <div class="block about">
          <!-- footer logo -->
          <img src="images/logo-footer.png" alt="">
          <!-- description -->
          <p class="alt-color">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
            laboris nisi ut aliquip ex ea commodo consequat.</p>
        </div>
      </div>
      <!-- Link list -->
      <div class="col-lg-2 offset-lg-1 col-md-3">
        <div class="block">
          <h4>Site Pages</h4>
          <ul>
            <li><a href="#">Boston</a></li>
            <li><a href="#">How It works</a></li>
            <li><a href="#">Deals & Coupons</a></li>
            <li><a href="#">Articls & Tips</a></li>
            <li><a href="terms-condition.html">Terms & Conditions</a></li>
          </ul>
        </div>
      </div>
      <!-- Link list -->
      <div class="col-lg-2 col-md-3 offset-md-1 offset-lg-0">
        <div class="block">
          <h4>Admin Pages</h4>
          <ul>
            <li><a href="category.html">Category</a></li>
            <li><a href="single.html">Single Page</a></li>
            <li><a href="store.html">Store Single</a></li>
            <li><a href="single-blog.html">Single Post</a>
            </li>
            <li><a href="blog.html">Blog</a></li>



          </ul>
        </div>
      </div>
      <!-- Promotion -->
      <div class="col-lg-4 col-md-7">
        <!-- App promotion -->
        <div class="block-2 app-promotion">
          <div class="mobile d-flex">
            <a href="">
              <!-- Icon -->
              <img src="images/footer/phone-icon.png" alt="mobile-icon">
            </a>
            <p>Get the Dealsy Mobile App and Save more</p>
          </div>
          <div class="download-btn d-flex my-3">
            <a href="#"><img src="images/apps/google-play-store.png" class="img-fluid" alt=""></a>
            <a href="#" class=" ml-3"><img src="images/apps/apple-app-store.png" class="img-fluid" alt=""></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container End -->
</footer>
<!-- Footer Bottom -->
<footer class="footer-bottom">
  <!-- Container Start -->
  <div class="container">
    <div class="row">
      <div class="col-sm-6 col-12">
        <!-- Copyright -->
        <div class="copyright">
          <p>Copyright © <script>
              var CurrentYear = new Date().getFullYear()
              document.write(CurrentYear)
            </script>. All Rights Reserved, theme by <a class="text-primary" href="https://themefisher.com" target="_blank">themefisher.com</a></p>
        </div>
      </div>
      <div class="col-sm-6 col-12">
        <!-- Social Icons -->
        <ul class="social-media-icons text-right">
          <li><a class="fa fa-facebook" href="https://www.facebook.com/themefisher" target="_blank"></a></li>
          <li><a class="fa fa-twitter" href="https://www.twitter.com/themefisher" target="_blank"></a></li>
          <li><a class="fa fa-pinterest-p" href="https://www.pinterest.com/themefisher" target="_blank"></a></li>
          <li><a class="fa fa-vimeo" href=""></a></li>
        </ul>
      </div>
    </div>
  </div>
  <!-- Container End -->
  <!-- To Top -->
  <div class="top-to">
    <a id="top" class="" href="#"><i class="fa fa-angle-up"></i></a>
  </div>
</footer>

<!-- JAVASCRIPTS -->
<script src="plugins/jQuery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/popper.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap-slider.js"></script>
  <!-- tether js -->
<script src="plugins/tether/js/tether.min.js"></script>
<script src="plugins/raty/jquery.raty-fa.js"></script>
<script src="plugins/slick-carousel/slick/slick.min.js"></script>
<script src="plugins/jquery-nice-select/js/jquery.nice-select.min.js"></script>
<script src="plugins/fancybox/jquery.fancybox.pack.js"></script>
<script src="plugins/smoothscroll/SmoothScroll.min.js"></script>
<!-- google map -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcABaamniA6OL5YvYSpB3pFMNrXwXnLwU&libraries=places"></script>
<script src="plugins/google-map/gmap.js"></script>
<script src="js/script.js"></script>

</body>

</html>
