<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>UrbanCodez HMS</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="The River template project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="styles/bootstrap-4.1.2/bootstrap.min.css">
<link href="plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.3.4/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.3.4/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="plugins/OwlCarousel2-2.3.4/animate.css">
<link href="plugins/jquery-datepicker/jquery-ui.css" rel="stylesheet" type="text/css">
<link href="plugins/colorbox/colorbox.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="styles/responsive.css">
<link rel="stylesheet" type="text/css" href="styles/booking.css">
<link rel="stylesheet" type="text/css" href="styles/booking_responsive.css">
<link rel="stylesheet" type="text/css" href="styles/contact.css">
<link rel="stylesheet" type="text/css" href="styles/contact_responsive.css">
</head>
<body>

<div class="super_container">
	
	<!-- Header -->

	<header class="header">
		<div class="header_content d-flex flex-row align-items-center justify-content-start">
			<div class="logo"><a href="#">UC HMS</a></div>
			<div class="ml-auto d-flex flex-row align-items-center justify-content-start">
				<nav class="main_nav">
					<ul class="d-flex flex-row align-items-start justify-content-start">
						<li class="active"><a href="index.php">Home</a></li>
						<li><a href="about.php">About us</a></li>
						<li><a href="gallery.php">Gallery</a></li>
						<li><a href="contact.php">Contact</a></li>

						<?php if (isset($_SESSION['customer_id'])): ?>
        				    <!-- If user is logged in, show Profile option -->
        				    <li>
        				        <a href="user_profile.php" class="badge text-dark" style="background-color:#FFA37B; font-weight: bolder;">
									Profile</a>
        				    </li>
        				<?php else: ?>
        				    <!-- If user is not logged in, show Login/Register -->
        				    <li><a href="login.php">Login/Register</a></li>
        				<?php endif; ?>
					</ul>
				</nav>
				<div class="book_button"><a href="booking.php">Book Online</a></div>
				<div class="header_phone d-flex flex-row align-items-center justify-content-center">
					<img src="images/phone.png" alt="">
					<span>0123-4567890</span>
				</div>

				<!-- Hamburger Menu -->
				<div class="hamburger"><i class="fa fa-bars" aria-hidden="true"></i></div>
			</div>
		</div>
	</header>


    	<!-- Menu -->

	<div class="menu trans_400 d-flex flex-column align-items-end justify-content-start">
		<div class="menu_close"><i class="fa fa-times" aria-hidden="true"></i></div>
		<div class="menu_content">
			<nav class="menu_nav text-right">
				<ul>
						<li class="active"><a href="index.php">Home</a></li>
						<li><a href="about.php">About us</a></li>
						<li><a href="gallery.php">Gallery</a></li>
						<li><a href="contact.php">Contact</a></li>
						
						<?php if (isset($_SESSION['customer'])): ?>
        				    <!-- If user is logged in, show Profile option -->
        				    <li>
        				        <a href="user_profile.php" class="badge text-dark" style="background-color:#FFA37B; font-weight: bolder;">Profile</a>
        				    </li>
        				<?php else: ?>
        				    <!-- If user is not logged in, show Login/Register -->
        				    <li><a href="login.php">Login/Register</a></li>
        				<?php endif; ?>
				</ul>
			</nav>
		</div>
		<div class="menu_extra">
			<div class="menu_book text-right"><a href="booking.php">Book online</a></div>
			<div class="menu_phone d-flex flex-row align-items-center justify-content-center">
				<img src="images/phone-2.png" alt="">
				<span>0183-12345678</span>
			</div>
		</div>
	</div>