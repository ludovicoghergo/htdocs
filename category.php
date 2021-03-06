<?php
    include('server.php');
    if(isset($_GET['page'])){
      $page = $_GET['page'];
    }else $page = 0;
    $allsells = getAllSells($page*9);
    $categories = getAllCategories();
    $locations = getAllLocation();
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
<section class="page-search">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <!-- Advance Search -->
        <div class="advance-search">
          <form>
            <div class="form-row">
              <div class="form-group col-md-4">
                <input type="text" class="form-control my-2 my-lg-0" id="inputtext4" placeholder="What are you looking for">
              </div>
              <div class="form-group col-md-3">
                <input type="text" class="form-control my-2 my-lg-0" name="inputCategory4" placeholder="Category" onchange="this.form.submit()">
                <?php
                   if(isset($_GET["inputCategory4"])){
                    $categ = $_GET["inputCategory4"];
                    $allsells = getAllSellsCategory($page*9, $categ);
                  }
                  ?>
              </div>
              <div class="form-group col-md-3">
                <input type="text" class="form-control my-2 my-lg-0" name="inputLocation4" placeholder="Location" onchange="this.form.submit()">
                  <?php
                   if(isset($_GET["inputLocation4"])){
                    $loca = $_GET["inputLocation4"];
                    $allsells = getAllSellsInArea($page*9, $loca);
                  }
                ?>
              </div>
              <div class="form-group col-md-2">

                <button type="submit" class="btn btn-primary">Search Now</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="section-sm">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="search-result bg-gray">
          <h2>Catalog</h2>
          <?php
            $number_results = mysqli_num_rows($allsells);
            echo "$number_results articles on sale";
          ?>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <div class="category-sidebar">
          <div class="widget category-list">
  <h4 class="widget-header">Top Categories</h4>
  <ul class="category-list">
    <?php if (mysqli_num_rows($categories) > 0):?>
      <?php while($cat = mysqli_fetch_assoc($categories)): ?>
        <li><a href='category.php?<?php echo $cat['category'] ?>=true'><?php echo $cat['category'] ?></a></li>
        <?php
        $actual = $cat['category'];
        if(isset($_GET[$actual]))
          $allsells = getAllSellsCategory($page*9, $actual);
        ?>
      <?php endwhile ?>
    <?php endif ?>
  </ul>
</div>

<div class="widget category-list">
  <h4 class="widget-header">Areas</h4>
  <ul class="category-list">
    <?php if (mysqli_num_rows($locations) > 0):?>
      <?php while ($loc = mysqli_fetch_assoc($locations)): ?>
        <li><a href='category.php?<?php echo $loc['location'] ?>=true'><?php echo $loc['location'] ?></a></li>
        <?php
        $yoo = $loc['location'];
        if(isset($_GET[$yoo]))
          $allsells = getAllSellsInArea($page*9, $yoo);
      ?>
      <?php endwhile ?>
    <?php endif ?>
  </ul>
</div>



<div class="widget filter">
  <h4 class="widget-header">Show Produts</h4>
  <select>
    <option>Popularity</option>
    <option value="1">Top rated</option>
    <option value="2">Lowest Price</option>
    <option value="4">Highest Price</option>
  </select>
</div>

<div class="widget price-range w-100">
  <h4 class="widget-header">Price Range</h4>
  <div class="block">
            <input class="range-track w-100" type="text" data-slider-min="0" data-slider-max="5000" data-slider-step="5"
            data-slider-value="[0,5000]">
        <div class="d-flex justify-content-between mt-2">
            <span class="value">$10 - $5000</span>
        </div>
  </div>
</div>

<div class="widget product-shorting">
  <h4 class="widget-header">By Condition</h4>
  <div class="form-check">
    <label class="form-check-label">
      <input class="form-check-input" type="checkbox" value="">
      Brand New
    </label>
  </div>
  <div class="form-check">
    <label class="form-check-label">
      <input class="form-check-input" type="checkbox" value="">
      Almost New
    </label>
  </div>
  <div class="form-check">
    <label class="form-check-label">
      <input class="form-check-input" type="checkbox" value="">
      Gently New
    </label>
  </div>
  <div class="form-check">
    <label class="form-check-label">
      <input class="form-check-input" type="checkbox" value="">
      Havely New
    </label>
  </div>
</div>

        </div>
      </div>
      <div class="col-md-9">
        <div class="category-search-filter">
          <div class="row">
            <div class="col-md-6">
              <form method=post>
                   Filters
                  <select name="order" onchange="this.form.submit()">
                  <option value="" disabled selected>Choose a filter</option>
                  <option value="1">Most Recent</option>
                  <option value="2">Order by price</option>
                  <option value="3">Highest Price</option>
                  <option value="4">Order By Area</option>
                </select>

                <?php
                   if(isset($_POST["order"])){
                    $var = $_POST["order"];
                    if($var == "1"){
                      $allsells = getAllSells($page*9);
                    }
                    else if($var == "2"){
                      $allsells = getAllSellsSorted($page*9);
                    }
                    else if($var == "3"){
                     $allsells = getAllSellsSortedDesc($page*9);
                    }
                    else if($var == "4"){
                     $allsells = getAllSellsByArea($page*9);
                    }
                   }
                ?>

                <form method=post>
                   Select area
                  <select name="area" onchange="this.form.submit()">
                  <option value="0">Areas</option>
                  <option value="1">Napuli</option>
                  <option value="2">Torino</option>
                  <option value="3">Palermo</option>
                  <option value="4">Milano</option>
                </select>

                <?php
                   if(isset($_POST["area"])){
                    $var = $_POST["area"];
                    if($var == "1"){
                      $allsells = getAllSellsInArea($page*9, 'Napuli');
                    }
                    else if($var == "2"){
                      $allsells = getAllSellsInArea($page*9, 'Torino');
                    }
                    else if($var == "3"){
                      $allsells = getAllSellsInArea($page*9, 'Palermo');
                    }
                    else if($var == "4"){
                      $allsells = getAllSellsInArea($page*9, 'Milano');
                    }
                   }
                ?>
            </div>
            <div class="col-md-6">
              <div class="view">
              </div>
            </div>
          </div>
        </div>
        <div class="product-grid-list">
          <div class="row mt-30">

            <?php if (mysqli_num_rows($allsells) > 0):?>
              <?php while($sell = mysqli_fetch_assoc($allsells)): ?>
                <div class="col-sm-12 col-lg-4 col-md-6">
                  <div class="product-item bg-light">
                    <div class="card">
                      <div class="thumb-content">
                        <a href="product.php?sells_id=<?php echo $sell['ID']  ?>">
                          <img class="card-img-top img-fluid" src="images/sell/<?php echo $sell['picture']  ?>" alt="Card image cap" style="width:240px;height:180px;">
                        </a>
                      </div>
                      <div class="card-body">
                        <h4 class="card-title"><a href="product.php?sells_id=<?php echo $sell['ID']  ?>"><?php echo $sell['title'] ?></a></h4>
                        <ul class="list-inline product-meta">
                          <li class="list-inline-item">
                            <a href="single.html"><?php echo $sell['precio']."$" ?></a>
                          </li>
                          <li class="list-inline-item">
                            <a href="#"><i class="fa fa-calendar"></i><?php echo $sell['creation_time'] ?></a>
                          </li>
                          <li class="list-inline-item">
                            <a href="single.html"><i class="fa fa-folder-open-o"></i><?php echo $sell['category'] ?></a>
                          </li>
                          <li class="list-inline-item">
                            <a href="single.html"><?php echo "Area of ".$sell['location']; ?></a>
                          </li>
                        </ul>
                        <p class="card-text"><?php echo $sell['description'] ?></p>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endwhile ?>
            <?php endif ?>
          </div>
        </div>
        <div class="pagination justify-content-center">
          <nav aria-label="Page navigation example">
            <ul class="pagination">

              <li class="page-item">
                <a class="page-link" href= 'category.php?page=<?php echo ($page - 1) ?>'aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                  <span class="sr-only">Previous</span>
                </a>
              </li>

              <li class="page-item"><a class="page-link" href="#"><?php echo ($page+1) ?></a></li>
              <li class="page-item "><a class="page-link" href="#"><?php echo ($page+2) ?></a></li>
              <li class="page-item"><a class="page-link" href="#"><?php echo ($page+3) ?></a></li>
              <li class="page-item">
                <a class="page-link" href='category.php?page=<?php echo ($page + 1) ?>' aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                  <span class="sr-only">Next</span>
                </a>
              </li>

            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
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
