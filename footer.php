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

	<!-- jquery -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js" type="text/javascript"></script>

	<!-- feature slides -->
 	<script src="includes/featureboxsliders.js" type="text/javascript" ></script>
	
	<!-- lightsout - dim slideshow when menu hovered -->
 	<script src="includes/lightsout.js" type="text/javascript" ></script>	
	
	<!-- menu -->
 	<script src="menu/menu_jquery.js" type="text/javascript" ></script>

</body>
</html>