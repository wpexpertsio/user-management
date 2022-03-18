<div class="wrap">
    <h1><?php echo __('Import / Export Users', IEUW_TEXT_DOMAIN); ?></h1>
    <form action="" method="post">
    <h2 class="nav-tab-wrapper">
    <?php
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : '';
    ?>
        <a href="?page=import-export-users-wpexperts" class="nav-tab <?php echo ($tab == '') ? 'nav-tab-active' : '' ?> "><?php echo __('Export', IEUW_TEXT_DOMAIN); ?></a>
        <a href="?page=import-export-users-wpexperts&tab=import" class="nav-tab <?php echo ($tab == 'import') ? 'nav-tab-active' : '' ?> "><?php echo __('Import', IEUW_TEXT_DOMAIN); ?></a>
        <a href="?page=import-export-users-wpexperts&tab=settings" class="nav-tab <?php echo ($tab == 'settings') ? 'nav-tab-active' : '' ?> "><?php echo __('Settings', IEUW_TEXT_DOMAIN); ?></a>
    </h2>
    <?php
        if( isset( $_GET[ 'tab' ] ) ) {
            $active = sanitize_text_field($_GET[ 'tab' ]);
            if( $active === 'import' ) { ?>
                <div class="import-container">
                    <?php
                        settings_fields( 'import-export-users' );
                        do_settings_sections( 'import-export-users-wpexperts&tab=import' );
                    ?>
                </div>
            <?php 
            } else if( $active === 'settings' ) {
                settings_fields( 'import-export-users' );
                do_settings_sections( 'import-export-users-wpexperts&tab=settings' );
            }
        } else { ?>
            <div class="export-container">
                <?php
                    settings_fields( 'import-export-users' );
                    do_settings_sections( 'import-export-users-wpexperts' ); 
                ?>
            </div>
        <?php }
        ?>
    </form>
</div>