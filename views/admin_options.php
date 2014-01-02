<div class="wrap wpe-wrap">
    <script type="text/javascript">var __namespace = '<?php echo $namespace; ?>';</script>

    <h2>Environments</h2>

    <p>This plugin is made to easily setup domains and associate them with environments such as Local, Staging, and Production.</p>

    <p><strong>Version: <?php echo $this->version; ?></strong></p>

    <hr/>


    <?php
    if ( isset( $_GET[ 'message' ] ) && count( $this->admin_options_errors ) === 0 ):
        ?>
        <div id="message" class="updated below-h2"><p>Options successfully updated!</p></div>
    <?php
    endif;
    ?>

    <form action="" method="post" id="<?php echo $namespace; ?>-admin-form">
        <?php wp_nonce_field( $namespace . "-update-options" ); ?>

        <table class="form-table">
            <tbody>
            <h3>Domains per Environment</h3>

            <tr valign="top">
                <th scope="row">
                    Local
                </th>
                <td>
                    <?php foreach ( $this->clean_array( $this->get_option( 'local_domains' ) ) as $key => $opt ) { ?>
                        <input type="text" class="domain-option" name='data[local_domains][<?php print $key; ?>]'
                               value="<?php print $opt; ?>"> <br/>
                    <?php } ?>
                    <input type="text" class="domain-option" name='data[local_domains][<?php print $key + 1; ?>]'
                           value="">
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    Staging
                </th>
                <td>
                    <?php foreach ( $this->clean_array( $this->get_option( 'staging_domains' ) ) as $key => $opt ) { ?>
                        <input type="text" class="domain-option" name='data[staging_domains][<?php print $key; ?>]'
                               value="<?php print $opt; ?>"> <br/>
                    <?php } ?>
                    <input type="text" class="domain-option" name='data[staging_domains][<?php print $key + 1; ?>]'
                           value="">
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    Production
                </th>
                <td>
                    <?php foreach ( $this->clean_array( $this->get_option( 'production_domains' ) ) as $key => $opt ) { ?>
                        <input type="text" class="domain-option" name='data[production_domains][<?php print $key; ?>]'
                               value="<?php print $opt; ?>"> <br/>
                    <?php } ?>
                    <input type="text" class="domain-option" name='data[production_domains][<?php print $key + 1; ?>]'
                           value="">
                </td>
            </tr>
            </tbody>
        </table>

        <?php submit_button(); ?>
    </form>

    <hr/>

    <h3>Constants</h3>

    <p>
        The plugin creates 4 constants. 3 that represent one for dev, staging, and production. The last one is <i>WPE_ENV</i>
        which is defined as one of the first 3 constants.
    </p>

    <pre>
        WPE_ENV_DEV
        WPE_ENV_STAGING
        WPE_ENV_PROD
        WPE_ENV
    </pre>


    <h3>Functions</h3>

    <p>
        The plugin creates multiple functions to easily see what environment you're in. Function names explain
        themselves..
    </p>

    <pre>
        WPE_isLocal()
        WPE_isStaging()
        WPE_isProd()
    </pre>

    <hr/>

    <table class="form-table">
        <tbody>
        <h3>Variables/Function Tests</h3>

        <tr valign="top">
            <th scope="row">
                $_SERVER['HTTP_HOST'] <br>
                WPE_ENV <br>
                WPE_isLocal() <br>
                WPE_isStaging() <br>
                WPE_isProd()
            </th>
            <td>
                <?php print $_SERVER[ 'HTTP_HOST' ]; ?> <br>
                <?php print WPE_ENV; ?> <br>
                <?php echo WPE_isLocal() ? 'true' : 'false' ; ?> <br>
                <?php echo WPE_isStaging() ? 'true' : 'false' ; ?> <br>
                <?php echo WPE_isProd() ? 'true' : 'false' ; ?>
            </td>
        </tr>
        </tbody>
    </table>

    <hr/>


    <h3>How Does It Work?</h3>

    <p>
        On Wordpress's 'plugins_loaded' hook the PHP variable <i>$_SERVER['HTTP_HOST']</i> is inspected. Upon matching one of the
        domains you've added, <i>WPE_ENV</i> is set to the environment.
    </p>

    <hr/>

    <h3>Github</h3>

    <p>You can view the source <a href="https://github.com/pixelbacon/wp-environment" target="_blank">GitHub</a>.</p>

    <hr/>

    <h3>Docs</h3>

    <p>The documentation uses <a href="http://www.phpdoc.org/" target="_blank">phpDocumentor</a> and can be viewed <a href="<?php print WPE_URLPATH; ?>docs" target="_blank">here</a>.</p>
</div>