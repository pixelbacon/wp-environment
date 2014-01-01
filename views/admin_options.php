<div class="wrap">
    <script type="text/javascript">var __namespace = '<?php echo $namespace; ?>';</script>

    <h2>Environments</h2>

    <span><?php echo $this->version; ?></span>

    <p>This plugin is made to easily setup domains and associate them with environments such as Local, Staging, and Production.</p>

    <p>View this plugin on <a href="https://github.com/pixelbacon/wp-environment" target="_blank">GitHub</a>.</p>

    <hr/>


    <?php
    if( isset($_GET['message']) && count($this->admin_options_errors) === 0 ):
        ?>
        <div id="message" class="updated below-h2"><p>Options successfully updated!</p></div>
    <?php
    endif;
    ?>

    <form action="" method="post" id="<?php echo $namespace; ?>-admin-form">
        <?php wp_nonce_field( $namespace . "-update-options" ); ?>

        <table class="form-table">
            <tbody>
                <h3>Domains</h3>

                <tr valign="top">
                    <th scope="row">
                        Local
                    </th>
                    <td>
                        <?php foreach ( $this->clean_array( $this->get_option('local_domains') ) as $key => $opt ) { ?>
                        <input type="text" class="domain-option" name='data[local_domains][<?php print $key; ?>]' value="<?php print $opt; ?>"> <br/>
                        <?php } ?>
                        <input type="text" class="domain-option" name='data[local_domains][<?php print $key + 1; ?>]' value="">
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Staging</th>
                    <td>
                        <?php foreach ( $this->clean_array( $this->get_option('staging_domains') ) as $key => $opt ) { ?>
                            <input type="text" class="domain-option" name='data[staging_domains][<?php print $key; ?>]' value="<?php print $opt; ?>"> <br/>
                        <?php } ?>
                        <input type="text" class="domain-option" name='data[staging_domains][<?php print $key + 1; ?>]' value="">
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Production</th>
                    <td>
                        <?php foreach ( $this->clean_array( $this->get_option('production_domains') ) as $key => $opt ) { ?>
                            <input type="text" class="domain-option" name='data[production_domains][<?php print $key; ?>]' value="<?php print $opt; ?>"> <br/>
                        <?php } ?>
                        <input type="text" class="domain-option" name='data[production_domains][<?php print $key + 1; ?>]' value="">
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button(); ?>
    </form>

    <hr/>

    <h3>Usage</h3>

    <p>
        The plugin creates 4 constants. 3 that represent one for dev, staging, and production. The last one is <i>WPE_ENV</i> which is defined by one of the first 3 constants.
    </p>

    <ul>
        <li>WPE_ENV_DEV = 'dev'</li>
        <li>WPE_ENV_STAGING = 'staging'</li>
        <li>WPE_ENV_PROD = 'production'</li>
        <li>WPE_ENV = WPE_ENV_DEV | WPE_ENV_STAGING | WPE_ENV_PROD</li>
    </ul>

    <hr/>

    <h3>How Does It Work?</h3>

    <p>
        The server's HTTP host is inspected by PHP, before the theme loads, and defines <i>WPE_ENV</i> accordingly when it finds one of your domains you have listed.
    </p>

    <hr/>
</div>