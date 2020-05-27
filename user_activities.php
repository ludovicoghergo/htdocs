<?php include('server.php') ?>
<?php
    checkSession();
    $sql = "SELECT * FROM users";
    $result = mysqli_query($db, $sql);
    $user = $_SESSION['username'];
    $type = getTypeActivities();
    $personal = getUserInfo($user);
    if(isset($_GET['page']) && $_GET['page'] >= 0){
      $page = $_GET['page'];
    }else $page = 0;
    if($type == 'sells')
      $result = getMySells($page*2);
    else{
      $type = 'orders';
      $result = getMyBuys($page*2);
    }
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
  								<del>
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="">Dashboard<span><i class="fa fa-angle-down"></i></span>
  								  </a>
                  </del>

  								<!-- Dropdown list -->
  								<div class="dropdown-menu">
  									<a class="dropdown-item" href="dashboard.html">Dashboard</a>
  									<a class="dropdown-item" href="dashboard-my-ads.html">Dashboard My Ads</a>
  									<a class="dropdown-item" href="dashboard-favourite-ads.html">Dashboard Favourite Ads</a>
  									<a class="dropdown-item" href="dashboard-archived-ads.html">Dashboard Archived Ads</a>
  									<a class="dropdown-item" href="dashboard-pending-ads.html">Dashboard Pending Ads</a>
  								</div>
  							</li>
  							<li class="nav-item dropdown dropdown-slide">
                  <del>
                  <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  									Pages <span><i class="fa fa-angle-down"></i></span>
  								</a>
                  </del>
  								<!-- Dropdown list -->
  								<div class="dropdown-menu">
  									<a class="dropdown-item" href="about-us.html">About Us</a>
  									<a class="dropdown-item" href="contact-us.html">Contact Us</a>
  									<a class="dropdown-item" href="user-profile.html">User Profile</a>
  									<a class="dropdown-item" href="404.html">404 Page</a>
  									<a class="dropdown-item" href="package.html">Package</a>
  									<a class="dropdown-item" href="single.html">Single Page</a>
  									<a class="dropdown-item" href="store.html">Store Single</a>
  									<a class="dropdown-item" href="single-blog.html">Single Post</a>
  									<a class="dropdown-item" href="blog.html">Blog</a>

  								</div>
  							</li>
  							<li class="nav-item dropdown dropdown-slide">
                  <del>
                  <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  									Listing <span><i class="fa fa-angle-down"></i></span>
  								</a>
                  </del>
  								<!-- Dropdown list -->
  								<div class="dropdown-menu">
  									<a class="dropdown-item" href="category.html">Ad-Gird View</a>
  									<a class="dropdown-item" href="ad-listing-list.html">Ad-List View</a>
  								</div>
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
<section class="dashboard section">
	<!-- Container Start -->
	<div class="container">
		<!-- Row Start -->
		<div class="row">
			<div class="col-md-10 offset-md-1 col-lg-4 offset-lg-0">
				<div class="sidebar">
					<!-- User Widget -->
					<div class="widget user-dashboard-profile">
						<!-- User Image -->
						<div class="profile-thumb">
							<img src="images/user/<?php echo $personal['avatar']?>" alt="" class="rounded-circle">
						</div>
						<!-- User Name -->
            <h5 class="text-center">
              <?php
              echo ucfirst($personal['fname']);
              echo ' ';
              echo ucfirst($personal['lname'])
              ?>
            </h5>
					</div>
					<!-- Dashboard Links -->
					<div class="widget user-dashboard-menu">
						<ul>
							<li>
                <a href="user_activities.php?type=orders"><i class="fa fa-user"></i> Orders</a></li>
							<li>
								<a href="user_activities.php?type=sells"><i class="fa fa-bookmark-o"></i> Sells</a>
							</li>
						</ul>
					</div>

					<!-- delete-account modal -->
											  <!-- delete account popup modal start-->
                <!-- Modal -->
                <div class="modal fade" id="deleteaccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                  aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header border-bottom-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body text-center">
                        <img src="images/account/Account1.png" class="img-fluid mb-2" alt="">
                        <h6 class="py-2">Are you sure you want to delete your account?</h6>
                        <p>Do you really want to delete these records? This process cannot be undone.</p>
                        <textarea name="message" id="" cols="40" rows="4" class="w-100 rounded"></textarea>
                      </div>
                      <div class="modal-footer border-top-0 mb-3 mx-5 justify-content-lg-between justify-content-center">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger">Delete</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- delete account popup modal end-->
					<!-- delete-account modal -->

				</div>
			</div>
			<div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
				<!-- Recently Favorited -->
				<div class="widget dashboard-container my-adslist">
          <div class="text-center table-title">
          <h2>
              My <?php echo ucfirst($type)  ?>
          </h2>
          </div>
          <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

					<table class="table table-responsive product-dashboard-table">
						<thead>
							<tr>
								<th>Title</th>
								<th>Category</th>
								<th class="text-center">Cost</th>
								<th class="text-center">Activated at</th>
                <th class="text-center">State</th>
                <?php if($type == 'sells'):?>
                <th class="text-center">Action</th>
              <?php elseif($type == 'orders'):?>
                <th class="text-center">End Time</th>
                <?php endif ?>
							</tr>
						</thead>
						<tbody>

                <?php if (mysqli_num_rows($result) > 0):?>
                  <?php while($row = mysqli_fetch_assoc($result)):?>
                    <tr>
      								<td class="action" data-title="Action">
      									<?php echo ucfirst($row['title'])?>
      								</td>
      								<td class="action" data-title="Action">
                        <span class="categories">
                        <?php echo ucfirst($row['category'])?>
                      </span>
                      </td>
                      <td class="action" data-title="Action">
                        <span>
      									<?php echo ucfirst($row['precio'])?>
                      </span>
      								</td>
      								<td class="action" data-title="Action">
                        <span>
      									<?php echo ucfirst($row['creation_time'])?>
                      </span>
      								</td>
                      <td class="action" data-title="Action">
                        <span>
      									<?php echo ucfirst($row['state'])?>
                      </span>
      								</td>
                      <?php if($type == 'sells'):?>
                      <td class="action" data-title="Action">
                        <span>
                          <ul class="list-inline justify-content-center">
      											<li class="list-inline-item">
                              <?php
                                echo '<a href="server.php?end_sell='.$row['title'].'" data-toggle="tooltip" data-placement="top" title="done" class="edit">';
                              ?>
      													<i class="fa fa-check"></i>
      												</a>
      											</li>
      										</ul>
                      </span>
      								</td>
                    <?php elseif($type == 'orders'):?>
                        <td class="action" data-title="Action">
                          <span>
                            <?php echo ucfirst($row['end_time'])?>
                          </span>
                        </td>
                    <?php endif?>
      							</tr>
                  <?php endwhile ?>
                <?php endif ?>

						</tbody>
					</table>

				</div>

				<!-- pagination -->
				<div class="pagination justify-content-center">
					<nav aria-label="Page navigation example">
						<ul class="pagination">
              <?php if($type == 'sell'): ?>
							<li class="page-item">
								<a class="page-link" href="user_activities.php?page=<?php echo($page - 1) ?>&type=sells" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only">Previous</span>
								</a>
							</li>
							<li class="page-item">
								<a class="page-link" href="user_activities.php?page=<?php echo($page + 1) ?>&type=sells" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
            <?php else: ?>
              <li class="page-item">
								<a class="page-link" href="user_activities.php?page=<?php echo($page - 1) ?>&type=orders" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span class="sr-only">Previous</span>
								</a>
							</li>
							<li class="page-item">
								<a class="page-link" href="user_activities.php?page=<?php echo($page + 1) ?>&type=orders" aria-label="Next">
									<span aria-hidden="true">&raquo;</span>
									<span class="sr-only">Next</span>
								</a>
							</li>
            <?php endif ?>
						</ul>
					</nav>
				</div>
				<!-- pagination -->

			</div>
		</div>
		<!-- Row End -->
	</div>
	<!-- Container End -->
</section>
<!--============================
=            Footer            =
=============================-->

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
