<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect to the login page
  header("Location: /Major-project/login/login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BuildWise</title>



  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Favicons
    ================================================== -->
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
  <link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">

  <!-- Bootstrap -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">

  <!-- Stylesheet
    ================================================== -->
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/nivo-lightbox/nivo-lightbox.css">
  <link rel="stylesheet" type="text/css" href="css/nivo-lightbox/default.css">
  <link rel="stylesheet" type="text/css" href="css/my.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">
  <!-- Navigation
    ==========================================-->
  <nav id="menu" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand page-scroll" href="#page-top">BuildWise</a>

        <div class="phone"><span> Build your</span>dream house </div>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#about" class="page-scroll">Introduction</a></li>
          <li><a href="#services" class="page-scroll">Estimator</a></li>
          <li><a href="#portfolio" class="page-scroll">EMI Calculator</a></li>
          <li><a href="#testimonials" class="page-scroll">Models</a></li>
          <li><a href="#contact" class="page-scroll">Contact us</a></li>
          <!--<li><a href="../login/logout.php" class="page-scroll">Logout</a></li>-->
        </ul>
      </div>
      <!-- /.navbar-collapse -->
    </div>
  </nav>
  <!-- Header -->
  <header id="header">
    <div class="intro">
      <div class="overlay">
        <div class="container">
          <div class="row">
            <div class="col-md-8 col-md-offset-2 intro-text">
              <h1>Low Cost Housing<br></h1>
              <p>Low Cost Housing is a new concept which deals with effective budgeting and following of techniques which help in reducing the cost construction through the use of locally available materials along with improved skills and technology without sacrificing the strength, performance and life of the structure.</p>
              <a href="#about" class="btn btn-custom btn-lg page-scroll">Learn More</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- Get Touch Section -->
  <div id="get-touch">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-1">
          <h3>Discover how affordable your home-building project can be</h3>
          <p>Take the first step toward your budget-friendly dream home today!
          </p>
        </div>
        <div class="col-xs-12 col-md-4 text-center"><a href="../calculations/test.php" class="btn btn-custom btn-lg page-scroll">Free Estimate</a></div>
      </div>
    </div>
  </div>
  <!-- About Section -->
  <div id="about">
    <div class="container">
      <div class="row">
        <!--<div class="col-xs-12 col-md-6"> <img src="img/about.jpg" class="img-responsive" alt=""> </div>!-->
        <!--<div class="col-xs-12 col-md-6">!-->
        <div class="about-text">
          <h2>About Lowcost Housing</h2>
          <p>Low Cost Housing is a new concept which deals with effective budgeting and following of techniques which help in reducing the cost construction through the use of locally available materials along with improved skills and technology without sacrificing the strength, performance and life of the structure.There is huge misconception that low cost housing is suitable for only sub standard works and they are constructed by utilizing cheap building materials of low quality.The fact is that Low cost housing is done by proper management of resources.Economy is also achieved by postponing finishing works or implementing them in phases.Building Cost
            The building construction cost can be divided into two parts namely:
            Building material cost : 65 to 70 %
            Labour cost : 65 to 70 %
            Now in low cost housing, building material cost is less because we make use of the locally available materials and also the labour cost can be reduced by properly making the time schedule of our work. Cost of reduction is achieved by selection of more efficient material or by an improved design.


            Areas from where cost can be reduced are:-<br>
            <br>1) Reduce plinth area by using thinner wall concept.Ex.15 cms thick solid concrete block wall.

            <br>2) Use locally available material in an innovative form like soil cement blocks in place of burnt brick.

            <br>3) Use energy efficiency materials which consumes less energy like concrete block in place of burnt brick.

            <br>4) Use environmentally friendly materials which are substitute for conventional building components like use R.C.C. Door and window frames in place of wooden frames.
            <br>5) Preplan every component of a house and rationalize the design procedure for reducing the size of the component in the building.
            <br>6) By planning each and every component of a house the wastage of materials due to demolition of the unplanned component of the house can be avoided.

            <br>7) Each component of the house shall be checked whether if it’s necessary, if it is not necessary, then that component should not be used.
          </p>

          <!--<h3>Why Choose Us?</h3>
          <div class="list-style">
            <div class="col-lg-6 col-sm-6 col-xs-12">
              <p></p>
            </div>
            <div class="col-lg-6 col-sm-6 col-xs-12">
              <ul>
                <li>Free Consultation</li>
                <li>Satisfied Customers</li>
                <li>Project Management</li>
                <li>Affordable Pricing</li>
              </ul>
            </div>
          </div>!-->
        </div>
      </div>
    </div>
  </div>
  </div>









  <div id="about">
    <div class="container">
      <div class="row">
        <!--<div class="col-xs-12 col-md-6"> <img src="img/about.jpg" class="img-responsive" alt=""> </div>!-->
        <!--<div class="col-xs-12 col-md-6">!-->
        <div class="about-text">
          <h2>Abouut Estimator</h2>
          <p>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Explicabo libero maxime temporibus corrupti! Minus maxime exercitationem omnis inventore sit, repudiandae qui architecto. Vero nulla magnam mollitia consectetur molestiae expedita voluptate?
        </p>
        <div class="col-xs-12 col-md-4 text-center"><a href="../calculations/test.php" class="btn btn-custom btn-lg page-scroll">Free Estimate</a></div>

          <!--<h3>Why Choose Us?</h3>
          <div class="list-style">
            <div class="col-lg-6 col-sm-6 col-xs-12">
              <p></p>
            </div>
            <div class="col-lg-6 col-sm-6 col-xs-12">
              <ul>
                <li>Free Consultation</li>
                <li>Satisfied Customers</li>
                <li>Project Management</li>
                <li>Affordable Pricing</li>
              </ul>
            </div>
          </div>!-->
        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- Services Section -->
  <!--<div class="nigga" id="services" >
    <h1>Find Out How Much Your Home-building Project Will Cost</h1>
    <form class="calculator-form" action="../calc/server.php" method="post">

      <div class="form-group">
        <label for="district">Select District</label>
        <select id="district" name="district">
          <option value="">Select District</option>
            <option value="Thiruvananthapuram">Thiruvananthapuram</option>
            <option value="Kollam">Kollam</option>
            <option value="Pathanamthitta">Pathanamthitta</option>
            <option value="Alappuzha">Alappuzha</option>
            <option value="Kottayam">Kottayam</option>
            <option value="Idukki">Idukki</option>
            <option value="Ernakulam">Ernakulam</option>
            <option value="Thrissur">Thrissur</option>
            <option value="Palakkad">Palakkad</option>
            <option value="Malappuram">Malappuram</option>
            <option value="Kozhikode">Kozhikode</option>
            <option value="Wayanad">Wayanad</option>
            <option value="Kannur">Kannur</option>
            <option value="Kasaragod">Kasaragod</option>
        </select>
      </div>
      <!--<div class="form-group">
        <label for="location">Select Location</label>
        <select id="location" name="location">
          <option value="">Select Location</option>
          <!-- Locations will populate dynamically based on district ID 
        </select>
      </div>


      <div class="form-group">
        <label for="area">Area</label>
        <div class="area-input">
          <input type="number" id="area" name="area" placeholder="5000">
          <div class="unit-options">
            <label>
              <input type="radio" name="unit" value="sqft" checked> Sq. Feet
            </label>
            <label>
              <input type="radio" name="unit" value="sqm"> Sq. Meter
            </label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="residential">Residential Building</label>
        <select id="residential" name="residential">
          <option value="1bhk">1 BHK</option>
          <option value="2bhk">2 BHK</option>
          <option value="3bhk">3 BHK</option>
          <option value="4bhk">4 BHK</option>
        </select>
      </div>
      <button type="submit" class="next-button" id="est" ><a href="../calc/server.php">Estimate Cost ➝</a></button>
    </form>
  </div>!-->

  <!--</div>
    <div class="row">
      <div class="col-md-4">
        <div class="service-media"> <img src="img/services/service-1.jpg" alt=" "> </div>
        <div class="service-desc">
          <h3>New Home Construction</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam sedasd commodo nibh ante facilisis bibendum dolor feugiat at.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="service-media"> <img src="img/services/service-2.jpg" alt=" "> </div>
        <div class="service-desc">
          <h3>Home Additions</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam sedasd commodo nibh ante facilisis bibendum dolor feugiat at. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="service-media"> <img src="img/services/service-3.jpg" alt=" "> </div>
        <div class="service-desc">
          <h3>Bathroom Remodels</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam sedasd commodo nibh ante facilisis bibendum dolor feugiat at.</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="service-media"> <img src="img/services/service-4.jpg" alt=" "> </div>
        <div class="service-desc">
          <h3>Kitchen Remodels</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam sedasd commodo nibh ante facilisis bibendum dolor feugiat at.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="service-media"> <img src="img/services/service-5.jpg" alt=" "> </div>
        <div class="service-desc">
          <h3>Windows & Doors</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam sedasd commodo nibh ante facilisis bibendum dolor feugiat at.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="service-media"> <img src="img/services/service-6.jpg" alt=" "> </div>
        <div class="service-desc">
          <h3>Decks & Porches</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam sedasd commodo nibh ante facilisis bibendum dolor feugiat at.</p>
        </div>!-->
  </div>
  </div>
  </div>
  </div>
  <!-- Gallery Section -->
  <div id="portfolio">
    <div class="container">
      <div class="section-title">
        <h2>EMI Calculator</h2>
      </div>
      <div class="row">
        <div class="portfolio-items">
          <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="portfolio-item">
              <div class="hover-bg"> <a href="img/portfolio/01-large.jpg" title="Project Title" data-lightbox-gallery="gallery1">
                  <div class="hover-text">
                    <h4>Lorem Ipsum</h4>
                  </div>
                  <img src="img/portfolio/01-small.jpg" class="img-responsive" alt="Project Title">
                </a> </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="portfolio-item">
              <div class="hover-bg"> <a href="img/portfolio/02-large.jpg" title="Project Title" data-lightbox-gallery="gallery1">
                  <div class="hover-text">
                    <h4>Adipiscing Elit</h4>
                  </div>
                  <img src="img/portfolio/02-small.jpg" class="img-responsive" alt="Project Title">
                </a> </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="portfolio-item">
              <div class="hover-bg"> <a href="img/portfolio/03-large.jpg" title="Project Title" data-lightbox-gallery="gallery1">
                  <div class="hover-text">
                    <h4>Lorem Ipsum</h4>
                  </div>
                  <img src="img/portfolio/03-small.jpg" class="img-responsive" alt="Project Title">
                </a> </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="portfolio-item">
              <div class="hover-bg"> <a href="img/portfolio/04-large.jpg" title="Project Title" data-lightbox-gallery="gallery1">
                  <div class="hover-text">
                    <h4>Lorem Ipsum</h4>
                  </div>
                  <img src="img/portfolio/04-small.jpg" class="img-responsive" alt="Project Title">
                </a> </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="portfolio-item">
              <div class="hover-bg"> <a href="img/portfolio/05-large.jpg" title="Project Title" data-lightbox-gallery="gallery1">
                  <div class="hover-text">
                    <h4>Adipiscing Elit</h4>
                  </div>
                  <img src="img/portfolio/05-small.jpg" class="img-responsive" alt="Project Title">
                </a> </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="portfolio-item">
              <div class="hover-bg"> <a href="img/portfolio/06-large.jpg" title="Project Title" data-lightbox-gallery="gallery1">
                  <div class="hover-text">
                    <h4>Dolor Sit</h4>
                  </div>
                  <img src="img/portfolio/06-small.jpg" class="img-responsive" alt="Project Title">
                </a> </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="portfolio-item">
              <div class="hover-bg"> <a href="img/portfolio/07-large.jpg" title="Project Title" data-lightbox-gallery="gallery1">
                  <div class="hover-text">
                    <h4>Dolor Sit</h4>
                  </div>
                  <img src="img/portfolio/07-small.jpg" class="img-responsive" alt="Project Title">
                </a> </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="portfolio-item">
              <div class="hover-bg"> <a href="img/portfolio/08-large.jpg" title="Project Title" data-lightbox-gallery="gallery1">
                  <div class="hover-text">
                    <h4>Lorem Ipsum</h4>
                  </div>
                  <img src="img/portfolio/08-small.jpg" class="img-responsive" alt="Project Title">
                </a> </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="portfolio-item">
              <div class="hover-bg"> <a href="img/portfolio/09-large.jpg" title="Project Title" data-lightbox-gallery="gallery1">
                  <div class="hover-text">
                    <h4>Adipiscing Elit</h4>
                  </div>
                  <img src="img/portfolio/09-small.jpg" class="img-responsive" alt="Project Title">
                </a> </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Testimonials Section -->
  <div id="testimonials">
    <div class="container">
      <div class="section-title">
        <h2>Testimonials</h2>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="testimonial">
            <div class="testimonial-image"> <img src="img/testimonials/01.jpg" alt=""> </div>
            <div class="testimonial-content">
              <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam sedasd commodo nibh ante facilisis bibendum dolor feugiat at."</p>
              <div class="testimonial-meta"> - John Doe </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial">
            <div class="testimonial-image"> <img src="img/testimonials/02.jpg" alt=""> </div>
            <div class="testimonial-content">
              <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam sedasd commodo nibh ante facilisis."</p>
              <div class="testimonial-meta"> - Johnathan Doe </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial">
            <div class="testimonial-image"> <img src="img/testimonials/03.jpg" alt=""> </div>
            <div class="testimonial-content">
              <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam sedasd commodo nibh ante facilisis bibendum dolor feugiat at."</p>
              <div class="testimonial-meta"> - John Doe </div>
            </div>
          </div>
        </div>
        <div class="row"> </div>
        <div class="col-md-4">
          <div class="testimonial">
            <div class="testimonial-image"> <img src="img/testimonials/04.jpg" alt=""> </div>
            <div class="testimonial-content">
              <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam sedasd commodo nibh ante facilisis bibendum dolor feugiat at."</p>
              <div class="testimonial-meta"> - Johnathan Doe </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial">
            <div class="testimonial-image"> <img src="img/testimonials/05.jpg" alt=""> </div>
            <div class="testimonial-content">
              <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam sedasd commodo nibh ante facilisis bibendum dolor feugiat at."</p>
              <div class="testimonial-meta"> - John Doe </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="testimonial">
            <div class="testimonial-image"> <img src="img/testimonials/06.jpg" alt=""> </div>
            <div class="testimonial-content">
              <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed dapibus leo nec ornare diam sedasd commodo nibh ante facilisis."</p>
              <div class="testimonial-meta"> - Johnathan Doe </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Contact Section -->
  <div id="contact">
    <div class="container">
      <div class="col-md-8">
        <div class="row">
          <div class="section-title">
            <h2>Get In Touch</h2>
            <p>Please fill out the form below to send us an email and we will get back to you as soon as possible.</p>
          </div>
          <form name="sentMessage" id="contactForm" novalidate>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <input type="text" id="name" class="form-control" placeholder="Name" required="required">
                  <p class="help-block text-danger"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input type="email" id="email" class="form-control" placeholder="Email" required="required">
                  <p class="help-block text-danger"></p>
                </div>
              </div>
            </div>
            <div class="form-group">
              <textarea name="message" id="message" class="form-control" rows="4" placeholder="Message" required></textarea>
              <p class="help-block text-danger"></p>
            </div>
            <div id="success"></div>
            <button type="submit" class="btn btn-custom btn-lg">Send Message</button>
          </form>
        </div>
      </div>
      <div class="col-md-3 col-md-offset-1 contact-info">
        <div class="contact-item">
          <h4>Contact Info</h4>
          <p><span>Address</span>4321 California St,<br>
            San Francisco, CA 12345</p>
        </div>
        <div class="contact-item">
          <p><span>Phone</span> +1 123 456 1234</p>
        </div>
        <div class="contact-item">
          <p><span>Email</span> info@company.com</p>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="social">
            <ul>
              <li><a href="#"><i class="fa fa-facebook"></i></a></li>
              <li><a href="#"><i class="fa fa-twitter"></i></a></li>
              <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
              <li><a href="#"><i class="fa fa-youtube"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer Section -->
  <div id="footer">
    <div class="container text-center">
      <p>&copy; 2025 BUILDWISE Design by Computer Engineering Department MTI Thrissur </p>
    </div>
  </div>
  <script type="text/javascript" src="js/jquery.1.11.1.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="js/SmoothScroll.js"></script>
  <script type="text/javascript" src="js/nivo-lightbox.js"></script>
  <script type="text/javascript" src="js/jqBootstrapValidation.js"></script>
  <script type="text/javascript" src="js/contact_me.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
  <script type="text/javascript" src="js/my.js"></script>
</body>

</html>