	<meta name="author" content="Lamtha2">
	
	<!-- CSS -->
	<link rel="stylesheet" href="css/grids.css">
	<link rel="stylesheet" href="css/base.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="menu/styles.css">
	<meta name="format-detection" content="telephone=no"><!-- remove default styling of telno links applied by iphone!!! -->
	<?php include 'includes/commontext.php'?>
	
</head>
<body>

<!-- internet explorer countdown -->
<!--[if lt IE 9]><div style='clear: both; height: 112px; padding:0; position: relative;'><a href="http://www.theie8countdown.com/ie-users-info"><img src="http://www.theie8countdown.com/assets/badge_iecountdown.png" border="0" height="112" width="348" alt="" /></a></div><![endif]-->
	
<header>
	<div class="row" >	
		<div class="grid_8 logo" >
		  <a href="index.php"><img src="images/logo.png" alt="Buckinghamshire Windows Logo" /></a>
		</div>
		<div class="grid_4">
			<p class="topBnrContact">Call us<br/><span style="font-size:18px">for a no obligation consultation</span><br/>on <?php echo $telno; ?></p>
		</div>
	</div>	

<div class="row" style="margin-top:30px;">	
	<div class="grid_12 container">
		<div class="center">	
			<div id='cssmenu' >
				<ul>
				   <li><a href='index.php'><span>Home</span></a></li>
				   <li><a href='about-us.php'><span>About Us</span></a></li>
				   <li class='has-sub'><a href=''><span>Products</span></a>
					  <ul>
						 <li><a href='windows.php'><span>Windows</span></a>
						 <li><a href='doors.php'><span>Doors</span></a>
						 <li><a href='conservatories.php'><span>Conservatories</span></a>
						 <li><a href='roofline-products.php'><span>Roof Line</span></a>
						<!-- <li class='has-sub'><a href='#'><span>menu</span></a>
							<ul>
							   <li><a href='#'><span>Sub Item</span></a></li>
							   <li class='last'><a href='#'><span>Sub Item</span></a></li>
							</ul> -->
						 </li>
					  </ul>
				   </li>
				 <li><a href='servicing.php'><span>Servicing</span></a></li>
				 <li class='last'><a href='#contact'><span>Contact Us</span></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
</header>