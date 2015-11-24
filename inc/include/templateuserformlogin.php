<?php

global $_cID_;
global $CLang;

if ($_cID_!="" || $_cID_>0) {
	$_accion_ = "confirmrecord";
} else {
	$_accion_ = "";
}
/*
$resstr = '
		<div id="login">
		<form id="formlogin" name="formlogin" method="post" action="/perfil/login">
			
			<input type="hidden" value="'.$previous_url_to_go.'" name="previous_url"/>
			
			<div id="message">'.$CLang->Get('LOGINMESSAGE').'</div>
			
			<div>
			
				<label id="usermail">'.$CLang->Get('USEREMAIL').'</label>
			
				<div id="usermailinput"><input name="_email_" type="text" value=""></div>
			
			</div>
			
			<div>
			
			<label id="userpassword">'.$CLang->Get('PASSWORD').'</label>
			
			<div id="usermailinput"><input name="_password_" type="password" value=""></div>
			
			</div>
			
			<div id="loginsend"><input class="inputbutton" name="submit" type="submit" value="'.$CLang->Get('LOGIN').'"></div>
			
			<div id="forgotpassword">			
				<a href="/perfil/forgotpassword">'.$CLang->Get('FORGOTPASSWORD').'</a>
			</div>

			<div id="register">			
				<a href="/perfil/register">'.$CLang->Get('SIGNUP').'</a>
			</div>			
			
			';
*/			

$resstr = '
<div id="login">
	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-4 col-md-offset-4">
				<h1 class="text-center login-title">{LOGINMESSAGE}</h1>
				<div class="account-wall">
				<!--<img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120" alt="">-->
					<form class="form-signin" name="formlogin" method="post" action="/perfil/login">
					<input name="_email_" type="text" class="form-control" placeholder="{USEREMAIL}" required autofocus>
					<input name="_password_" type="password" class="form-control" placeholder="{PASSWORD}" required>
					<button class="btn btn-lg btn-primary btn-block" type="submit">{SIGNIN}</button>
					<label class="checkbox pull-left">
						<input type="checkbox" value="remember-me">
						{REMEMBERME}
					</label>
					<!--<a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span>-->
					
					
					<div id="div_debug" class="debugdetails">
						<div id="id_pedido">
							<input type="text" value="'.$_cID_.'" name="_cID_">
							<input type="text" value="'.$_accion_.'" name="_accion_">
						</div>
					</div>
					</form>
				</div>
				
				<div id="forgotpassword">			
					<a href="/perfil/forgotpassword" class="text-center new-account">{FORGOTPASSWORD}</a>
				</div>

				<div id="register">			
					<a href="/perfil/register" class="text-center new-account">{SIGNUP}</a>
				</div>
				
			</div>
		</div>
	</div>
</div>
';

$CLang->Translate( $resstr );

$resstr.= '';



?>