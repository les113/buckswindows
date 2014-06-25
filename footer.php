<footer class="row" ><a name="contact"></a>
	<div class="grid_12 foot" style="margin-top:20px;">	
		<div class="row" >
			<div class="grid_3" >	
				<h5>We supply &amp; install:</h5>
				<p>UPVC Windows<br/>
				External Doors<br/>
				Patio Doors<br/>
				Conservatories<br/>
				Facias, Soffits &amp; Guttering</p>
				<div class="lamtha2logo hide-phone"></div>
			</div>
			<div class="grid_5">
				<h5>Contact:</h5>
				<p>Tel: <?php echo $telno; ?><br/>
				Email: <?php echo $email; ?></p>		
			</div>
			
			<div id="enquiry_form" class="contactForm show-screen">
		
			<form action="includes/YouGotEmail.php" method="post" enctype="multipart/form-data" name="enquiry_form" >   

				<h5>Request a consultation</h5>					
				<label>Name:</label>
					<input type="text" name="name" maxlength="25" class="required"/><br/>			  
				<label>Telephone:</label>
					<input type="text" name="tel" maxlength="16" class="required" /><br/>			  
				<label>Email:</label> 
					<input type="text" name="email" class="required email" /><br/>		   
				<div class="submitbutton">
					<input name="btnSubmit" type="submit" value = "Submit" />
				</div>
				
			</form>		
			
			</div>
		</div>
	</div>
</footer>


	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<script type="text/javascript">
		var browser			= navigator.userAgent;
		var browserRegex	= /(Android|BlackBerry|IEMobile|Nokia|iP(ad|hone|od)|Opera M(obi|ini))/;
		var isMobile		= false;
		if(browser.match(browserRegex)) {
			isMobile			= true;
			addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
			function hideURLbar(){
				window.scrollTo(0,1);
			}
		}
	</script>

	<!-- jquery -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js" type="text/javascript"></script>

	<!-- feature slide animation 
	<script>
	$(document).ready(function(){
	  $('.featslide').mouseover(function() {
			  $(this).find('.featOvly').stop().animate({ left:'0' }, 600);						
		});
	  $('.featslide').mouseout(function() {
			  $(this).find('.featOvly').stop().animate({ left:'170' }, 600);						
		});
	});
	</script>-->
		
	<!-- To Top scripts -->
	<script src="includes/smoothscroll.js" type="text/javascript" ></script>
	<script src="includes/jquery.easing.1.3.js" type="text/javascript"></script>
	<script src="includes/jquery.ui.totop.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$().UItoTop({ easingType: 'easeOutQuart' });
		});
	</script>
	
	<!-- menu -->
 	<script src="menu/menu_jquery.js" type="text/javascript" ></script>

	<!-- lightsout - dim slideshow when menu hovered -->
 	<script src="includes/lightsout.js" type="text/javascript" ></script>
		
	<!-- include jquery cycle - http://www.malsup.com/jquery/cycle/ -->
	<script type="text/javascript" src="includes/jquery.cycle.all.min.js"></script>
	<script type="text/javascript" src="includes/jquery.cycle.settings.js"></script>
	<script type="text/javascript" src="includes/touchwipe.js"></script>

	<!-- jquery.colorbox -->
	<script type="text/javascript" src="colorbox/jquery.colorbox-min.js"></script>
	<link rel="stylesheet" type="text/css" href="colorbox/colorbox.css" media="screen" />
	<script type="text/javascript">
		$(document).ready(function() {
			$('.colorbox').colorbox();
		});
	</script> 

	<!-- form validate -->
	<script type="text/javascript" src="includes/easyValidate/js/jquery.easyValidate.js"></script>
	<link rel="stylesheet" type="text/css" href="includes/easyValidate/styles/easyValidate.default.css" media="screen">
	<script>
	$(function(){
		// DOM READY...
		
		// INIT EASY VALIDATE PLUGIN
		$('#enquiry_form').easyValidate();	
	});
	</script>
	
	<!-- Start of StatCounter Code 
	<script type="text/javascript">
	var sc_project=9610088; 
	var sc_invisible=1; 
	var sc_security="c02c38af"; 
	var scJsHost = (("https:" == document.location.protocol) ?
	"https://secure." : "http://www.");
	document.write("<sc"+"ript type='text/javascript' src='" +
	scJsHost+
	"statcounter.com/counter/counter.js'></"+"script>");
	</script>
	<noscript><div class="statcounter"><a title="web stats"
	href="http://statcounter.com/free-web-stats/"
	target="_blank"><img class="statcounter"
	src="http://c.statcounter.com/9610088/0/c02c38af/1/"
	alt="web stats"></a></div></noscript>
	<!-- End of StatCounter Code -->

</body>
</html>