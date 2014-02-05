<style>
	label .sub {
		display: block;
		font-weight: normal;
		font-size: .7em;
	}
</style>

<div class="wrap wpe-wrap">
	<script type="text/javascript">var __namespace = '<?php echo $namespace; ?>';</script>

	<?php
	if ( isset( $_GET['message'] ) && count( $this->admin_options_errors ) === 0 ):
		?>
		<div id="message" class="updated below-h2"><p>Options successfully updated!</p></div>
	<?php
	endif;
	?>

	<h2>Environments</h2>

	<p>This plugin is made to easily setup domains and associate them with environments such as Local, Staging, and Production.</p>

	<p><strong>Version: <?php echo $this->version; ?></strong></p>

	<hr />

	<form action="" method="post" id="<?php echo $namespace; ?>-admin-form">
		<?php wp_nonce_field( $namespace . "-update-options" ); ?>

		<table class="form-table">
			<tbody>
			<h3>Domains per Environment</h3>

			<?php
				$environments = array(
					'local_domains'          => 'Local <span class="sub">Running on your machine...</span>',
					'staging_domains'        => 'Staging <span class="sub">Running on your company\'s server...</span>',
					'pre_production_domains' => 'Pre-Production <span class="sub">Running on your client\'s server...</span>',
					'production_domains'     => 'Production <span class="sub">Running on your client\'s server...</span>',
				);

				foreach ($environments as $env => $label): ?>
					<tr valign="top">
						<th scope="row">
							<label for=""><?php print $label; ?></label>
						</th>

						<td>
							<?php foreach ( $this->clean_array( $this->_get_option( $env ) ) as $key => $opt ) { ?>
								<input type="text" class="domain-option <?php print $this->_is_domain_valid( $opt ) ? 'valid' : 'invalid'; ?>" name='data[<?php print $env; ?>][<?php print $key; ?>]'
											 value="<?php print $opt; ?>"> <br />
							<?php } ?>
							<input type="text" class="domain-option" name='data[<?php print $env; ?>][<?php print $key + 1; ?>]' value="">
						</td>
					</tr>
			<?php endforeach; ?>
		</table>

		<?php submit_button(); ?>
	</form>

	<hr />

	<h3>Constants</h3>

	<p>
		The plugin creates 4 constants. 3 that represent one for dev, staging, and production. The last one is
		<i>WPE_ENV</i>
		which is defined as one of the first 3 constants.
	</p>

    <pre>
        WPE_ENV_DEV
        WPE_ENV_STAGING
        WPE_ENV_PREPROD
        WPE_ENV_PROD
        WPE_ENV
    </pre>


	<h3>Functions</h3>

	<p>
		The plugin creates multiple functions to easily see what environment you're in. Function names explain
		themselves..
	</p>

    <pre>
		WPE_getOptionsUrl()
        WPE_isLocal()
        WPE_isStaging()
        WPE_isProd()
        WPE_isPreProd()
        WPE_addLocalDomain( string|array $domain )
		WPE_addStagingDomain( string|array $domain )
		WPE_addPreProdDomain( string|array $domain )
		WPE_addProdDomain( string|array $domain )
    </pre>

	<hr />

	<table class="form-table">
		<tbody>
			<h3>Variables/Function Tests</h3>

			<tr valign="top">
				<th scope="row">
					$_SERVER['HTTP_HOST'] <br>
					WPE_ENV <br>
					WPE_isLocal() <br>
					WPE_isStaging() <br>
					WPE_isPreProd() <br>
					WPE_isProd() <br>
				</th>
				<td>
					<?php print $_SERVER['HTTP_HOST']; ?> <br>
					<?php print WPE_ENV; ?> <br>
					<?php echo WPE_isLocal() ? 'true' : 'false'; ?> <br>
					<?php echo WPE_isStaging() ? 'true' : 'false'; ?> <br>
					<?php echo WPE_isPreProd() ? 'true' : 'false'; ?> <br>
					<?php echo WPE_isProd() ? 'true' : 'false'; ?>
				</td>
			</tr>
		</tbody>
	</table>

	<hr />


	<h3>How Does It Work?</h3>

	<p>
		On Wordpress's 'plugins_loaded' hook the PHP variable
		<i>$_SERVER['HTTP_HOST']</i> is inspected. Upon matching one of the
		domains you've added, <i>WPE_ENV</i> is set to the environment.
	</p>

	<hr />

	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row">
					<h3>Prebaked Code</h3>
				</th>

				<td>
					<p>In some cases you might want to declare the environment right away; below is code that will do that and match with the plugin.</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">&nbsp;</th>

				<td>
					<?php $environments = array(
						WPE_ENV_LOCAL   => 'local_domains',
						WPE_ENV_PROD    => 'production_domains',
						WPE_ENV_PREPROD => 'pre_production_domains',
						WPE_ENV_STAGING => 'production_domains',
					);
					?>
					<pre>
						if(isset($_SERVER['HTTP_HOST'])){
						&nbsp;switch($_SERVER['HTTP_HOST']) {
						<?php
							foreach ($environments as $key => $value):
								if(array_filter($this->_get_option($value)) != NULL):
									foreach ( array_filter($this->_get_option($value)) as $index => $domain): if(!empty($domain)): ?>
											&nbsp;&nbsp;case "<?php print $domain; ?>":
									<?php endif; endforeach; ?>
						&nbsp;&nbsp;&nbsp;WPE_ENV = '<?php print $key; ?>';
						&nbsp;&nbsp;&nbsp;break;

						<?php
							endif; endforeach; ;
						?>
						&nbsp;&nbsp;default:
						&nbsp;&nbsp;&nbsp;WPE_ENV = '<?php print WPE_ENV_LOCAL; ?>';
						&nbsp;&nbsp;&nbsp;break;
						&nbsp;}
						};
					</pre>
				</td>
			</tr>
		</tbody>
	</table>

	<hr />

	<table class="form-table">
		<h3>Change log</h3>

		<tbody>
			<tr valign="top">
				<th scope="row">0.0.5r5</th>
				<td>
					<ul>
						<li>Arrays can now be used when adding domains.</li>
						<li>Added pre-production environment; running on a client's staging environment for example.</li>
						<li>Cleaned up code for displaying options.</li>
						<li>Added Prebaked Code section if the person would like to use this in <i>wp-config.php.</i></li>
						<li>New docs.</li>
					</ul>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">0.0.4r4</th>
				<td>
					<ul>
						<li>New docs.</li>
					</ul>
				</td>
			</tr>
		</tbody>
	</table>

	<hr />

	<h3>Github</h3>

	<p>You can view the source
		<a href="https://github.com/pixelbacon/wp-environment" target="_blank">GitHub</a>, when it comes to forking this is not code golf.
	</p>

	<hr />

	<h3>Docs</h3>

	<p>The documentation uses <a href="http://www.phpdoc.org/" target="_blank">phpDocumentor</a> and can be viewed
		<a href="<?php print WPE_URLPATH; ?>docs/packages/Default.html" target="_blank">here</a>.</p>

	<hr>

	<h3>Donate</h3>

	<p>If you find this plugin useful, don't hesitate to click the button(s) below.</p>

	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="K3AUXUPR4GSHN">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>

</div>