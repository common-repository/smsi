<?php

class SmsiBuildHtml{

	public function __construct(){
		defined( 'ABSPATH' ) or die( 'Oops! dont look like your are allowed to be here silly!' );

	}

	/**
	*Build the settings page in admin
	*@return void
	*/
	public static function buildSettingsPage(){
    define('constantCheck', true);

		echo "<h1>Smsi Settings</h1>
          <h3>SMS gateway settings</h1>
          <form method='post' action=";echo esc_url( admin_url('admin-post.php') ); echo ">
          <input type='hidden' name='action' value='save_settings'>
            <select name='currentSmsProvider' class='smsiSelect'>
              <option value='0' selected disabled>Choose your preffered SMS provider</option>
              <option value='Clickatell' data-provider='clickatell'>Clickatell</option>
							<!--<option value='SureSms' data-provider='suresms'>SureSMS</option>-->
							<option value='InMobile' data-provider='inmobile'>INMOBILE</option>
							<option value='Txty' data-provider='txty'>Txty</option>
            </select>

						<div id='clickatell' class='providerDiv'>
						<label>Your clickatell API key this can be obtained in your admin panel.</label>
            	<input type='text' name='gatewayAuthId' class='smsiInput' placeholder='Authorization Id' />
						</div>

						<div id='inmobile' class='providerDiv'>
						<label>Your INMobile API key this can be obtained in your admin panel.</label>
            	<input type='text' name='gatewayApiKey' class='smsiInput' placeholder='Api Key' />
						</div>

						<div id='txty' class='providerDiv'>
						<label>Your Txty username and password.</label>
            	<input type='text' name='gatewayUsername' class='smsiInput' placeholder='Username' />
							<input type='text' name='gatewayPassword' class='smsiInput' placeholder='Password' />
						</div>

            <input type='submit' name='submitGatewayDetatils' class='smsiInput' value='Save' />
						"; if( isset( $_GET['status'] ) ): echo "<p>Something went wrong please try again!</p>"; endif; echo"
          </form>
    ";
	}

	/**
	*Build the errors page in admin
	*@return void
	*/
	public static function buildErrorLogPage()
	{
		global $wpdb;

		$errors = $wpdb->get_results("SELECT * FROM smsi_error_log");

		echo
		"
		<div class='smsiErrorLogWrapper'>
		<table>
			<tr>
				<th>Error code</th>
				<th>Error severity</th>
				<th>Occured at</th>
			</tr>
		";
		foreach( $errors as $error ){
			echo
			"
			<tr>
				<td>$error->error_code</td>
				<td>$error->severity</td>
				<td>$error->created_at</td>
			</tr>
			";
		}
		echo "</table></div>";
	}

	/**
	*Build the contact page in admin
	*@return void
	*/
	public static function buildContactPage()
	{
		echo
		"
			<div class='smsiHelpWrapper'>
				<div class='smsiHelpWrapperInner'>
					<p class='helpOverHeadline'>Providers:</p>
					<p class='helpHeadline'>Txty</p>
					<div class='smsiLine'></div>

 					<div class='smsiSubHelp'>
						<p class='helpSubHeadline'>Startup guide</p>
						<div class='smsiLine'></div>

						<ol>
							<li>Go to: <a href='https://txty.dk/'>https://txty.dk/</a> to buy a Txty subscription.</li>
							<li>Go to: Smsi settings in your wordpress admin panel.</li>
							<li>Choose Txty in the select box.</li>
							<li>Fill in your username and password and press save.</li>
							<li>Go to: Woocommerce -> settings -> Smsi Settings tab and fill out your texts!</li>
							<li>You are now ready to start sending SMS'es with Smsi and Txty! :-)</li>
						</ol>
					</div>


					<p class='helpHeadline'>InMobile</p>
					<div class='smsiLine'></div>

 					<div class='smsiSubHelp'>
						<p class='helpSubHeadline'>Startup guide</p>
						<div class='smsiLine'></div>

						<ol>
							<li>Go to: <a href='https://www.inmobile.dk/'>https://www.inmobile.dk/</a> to buy a InMobile subscription.</li>
							<li>Go to: <a href='https://mm.inmobile.dk/login'>https://mm.inmobile.dk/login</a> and login</li>
							<li>In the top right corner hover over account and choose Development-API</li>
							<li>Copy your API key</li>
							<li>Go to: Smsi settings in your wordpress admin panel.</li>
							<li>Choose InMobile in the select box.</li>
							<li>Paste the API key you just copied and press save.</li>
							<li>Go to: Woocommerce -> settings -> Smsi Settings tab and fill out your texts!</li>
							<li>You are now ready to start sending SMS'es with Smsi and InMobile! :-)</li>
						</ol>
					</div>


					<p class='helpHeadline'>Clickatell</p>
					<div class='smsiLine'></div>

 					<div class='smsiSubHelp'>
						<p class='helpSubHeadline'>Startup guide</p>
						<div class='smsiLine'></div>

						<ol>
							<li>Go to: <a href='https://www.clickatell.com/'>https://www.clickatell.com/</a> to buy a Clickatell subscription.</li>
							<li>Go to: <a href='https://www.clickatell.com/login/'>https://www.clickatell.com/login/</a> and login</li>
							<li>In the top left corner of the menu click APIs and choose Set up a new API</li>
							<li>In the list of API's find the one named REST and click on the button saying add REST API</li>
							<li>Copy the Text in the Auth Token box</li>
							<li>Go to: Smsi settings in your wordpress admin panel.</li>
							<li>Choose Clickatell in the select box.</li>
							<li>Paste the Auth Token you just copied and press save.</li>
							<li>Go to: Woocommerce -> settings -> Smsi Settings tab and fill out your texts!</li>
							<li>You are now ready to start sending SMS'es with Smsi and Clickatell! :-)</li>
						</ol>
					</div>

					<p class='helpOverHeadline'>About The plugin SMSI:</p>

					<p class='helpHeadline'>General info</p>
					<div class='smsiLine'></div>

 					<div class='smsiSubHelp'>
						<p class='helpSubHeadline'>Description</p>
						<div class='smsiLine'></div>
						<p>SMSI is a plugin that allows you as a shop owner to offer your customers the ability to follow their order through SMS</p>
						<p>With SMSI you can send a SMS to your customers on every single order status which grants your customers total overview of their order.</p>
						<p>As of January 2017 we offer SMS support from 3 different providers, we do have plans of adding more providers to list later on.</p>
					</div>

					<div class='smsiSubHelp'>
						<p class='helpSubHeadline'>Affiliation with providers</p>
						<div class='smsiLine'></div>
						<p>Please note that we are in NO way affiliated with any of the providers on our site.</p>
						<p>This also means that you have to buy a plan from one of the providers to be able to have any use of SMSI</p>
					</div>

					<div class='smsiSubHelp'>
						<p class='helpSubHeadline'>Support</p>
						<div class='smsiLine'></div>
						<p>SMSI can only offer support related to our plugin this means that we can not help you with non working API keys, wrong passwords etc.</p>
						<p>You will have to contact your given provider for that.</p>
					</div>



					<p class='helpOverHeadline'>SMSI security and licensing</p>

					<p class='helpHeadline'>General info</p>
					<div class='smsiLine'></div>

 					<div class='smsiSubHelp'>
						<p class='helpSubHeadline'>Security</p>
						<div class='smsiLine'></div>
						<p>SMSI takes <u><b>NO</b></u> for any kind of security breaches on your wordpress installation nor any provider accounts you may have entered here</p>
						<p>We do however do our best to prevent any kind of security breaches and always tries to stay up to date with the newest wordpress flaws.</p>
						<p>We encrypt all passwords, API keys, and Auth Keys with the AES encryption algorithm which is known to be amongst the best.</p>
						<p>We do however store all information on YOUR server which may expose data like encryption keys in the event of a hacker attack.</p>
						<p>Final note is that by using this plugin you agree that you in any way use this plugin on your own risk</p>
					</div>

					<div class='smsiSubHelp'>
						<p class='helpSubHeadline'>Licensing</p>
						<div class='smsiLine'></div>
						<p>SMSI is a free plugin! it's distributed under the GPLv2 license <a href='https://www.gnu.org/licenses/old-licenses/gpl-2.0.html'>https://www.gnu.org/licenses/old-licenses/gpl-2.0.html</a></p>
					</div>

					<p class='helpHeadline'>Contact</p>
					<div class='smsiLine'></div>

					<div class='smsiSubHelp'>
						<p class='helpSubHeadline'>Providers</p>
						<div class='smsiLine'></div>
						<p>The different providers may be contacted on their respective website.</p>
					</div>

					<div class='smsiSubHelp'>
						<p class='helpSubHeadline'>SMSI</p>
						<div class='smsiLine'></div>
						<p>SMSI may be contacted at <a href='mailto:patrick.hansen@hotmail.com'>patrick.hansen@hotmail.com</a></p>
					</div>

				</div>
			</div>

		";
	}


}
