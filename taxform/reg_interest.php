<script type="text/javascript">
   /* function do_onsubmit(form) {
        form.action = form_action;
    }
    */
    // Submit form with id function
    function regSubmit() {
  /*var plot = document.getElementById("plot").value;
    var fname = document.getElementById("fname").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone").value;
    var prefplan = document.getElementById("prefplan").value;
    var message = document.getElementById("message").value;
     */
    document.getElementById("regform_id").action = "http://www.corporealproperties.com/sendmail.php"; // Setting form action to "success.php" page
    $("#reg-interest-modal").modal("hide");
    document.getElementById("regform_id").submit(); // Submitting form
   // document.getElementById("regform_id").action = "update-reg.php"; // Setting form action to "success.php" page
   // document.getElementById("regform_id").submit();
    
    }
</script>
<link rel="stylesheet" href="css/regint.css" type="text/css">
<div class="modal fade" id="reg-interest-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-center" id="myModalLabel">Register your Interest in a Property</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="modal-agent-info-box">
					<div class="img-holder">
						<img src="img/interestpic.png" alt="">
					</div>
					<div class="content">
						<h3><span class="name">Secure a Land</span> <span class="position">The selected property together with your details shall be saved and we shall be contacting you</span></h3>
						<p>Phone : +234-809-111-6428 &emsp; Email : info@corporealproperties.com</p>
					</div>
				</div>
				<div class="modal-contact-form">
					<!-- .contact-form-wrap -->
					<div class="contact-form-wrap anim-5-all">
						<form id="regform_id" method="post" name="myform" class="contact-form row"> <!-- action="sendmail.php" onsubmit="do_onsubmit(this);" -->
							<div class="col-md-6">
								<p><input id="plot" name="plot" type="text" placeholder="Plot Number" value="25"></p>
                <p><input id="fname" name="fname" type="text" placeholder="Name"></p>
								<p><input id="phone" name="phone" type="text" placeholder="Phone"></p>
								<p><input id="email" name="email" type="text" placeholder="Email"></p>
							</div>
							<div class="col-md-6">
                <p><input id="prefplan" name="prefplan" type="text" placeholder="Prefered Payment Plan"></p>
								<p><textarea id="message" name="message" placeholder="Comments"></textarea></p>
                <p><input name="MM_insert" type="hidden" value="MapsForm"></p>
						    <!-- <p><input name="MM_prev" type="hidden" value="<? echo $_SERVER['PHP_SELF']; ?>"></p> -->
								<p><input  onclick="regSubmit()" type="button" value="Register your Interest"></p>
							</div>
						</form>
					</div><!-- /.contact-form-wrap -->					
				</div>
			</div>
		</div>
	</div>
</div>