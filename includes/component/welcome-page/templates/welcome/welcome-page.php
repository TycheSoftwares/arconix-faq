<?php
/**
 * Welcome page on activate or updation of the plugin
 */

$faq_array = get_query_var( 'faq_array' );

$badge_url = $faq_array['badge_url'];

$ts_dir_image_path = $faq_array['ts_dir_image_path'];

?>
<style>
    .feature-section .feature-section-item {
        float:left;
        width:48%;
    }
</style>
<div class="wrap about-wrap">

    <?php echo $faq_array[ 'get_welcome_header'] ?>

    <div style="float:left;width: 80%;">
    <p class="about-text" style="margin-right:20px;"><?php
        printf(
            __( "Thank you for activating or updating to the latest version of Arconix FAQ! If you're a first time user, welcome! You're well on your way to explore the FAQ's functionality for your WordPress store." )
        );
        ?></p>
    </div>
    <div class="faq-badge"><img src="<?php echo $badge_url; ?>" style="width:150px;"/></div>

    <p>&nbsp;</p>

    <div class="feature-section clearfix introduction">

        <h3><?php esc_html_e( "Get Started with Arconix FAQ", 'arconix-faq' ); ?></h3>

        <div class="video feature-section-item" style="float:left;padding-right:10px;">
            <img src="<?php echo $ts_dir_image_path . 'add_faq.png' ?>"
                    alt="<?php esc_attr_e( 'Arconix FAQ', 'arconix-faq' ); ?>" style="width:600px;">
        </div>

        <div class="content feature-section last-feature">
            <h3><?php esc_html_e( 'Add new FAQ', 'arconix-faq' ); ?></h3>

            <p><?php esc_html_e( 'To add frequently asked questions and their answers, you can click on Add New page under the FAQ menu.', 'arconix-faq' ); ?></p>
            <a href="post-new.php?post_type=faq" target="_blank" class="button-secondary">
                <?php esc_html_e( 'Click Here to to Add new FAQ', 'arconix-faq' ); ?>
                <span class="dashicons dashicons-external"></span>
            </a>
        </div>
    </div>

    <div class="content">

        <div class="feature-section clearfix">
            <div class="content feature-section-item">

                <h3><?php esc_html_e( 'Display all FAQ.', 'arconix-faq' ); ?></h3>

                    <p><?php esc_html_e( 'You can create a page or post and add the shortcode as [faq] there.', 'arconix-faq' ); ?></p>
                    <!-- <a href="admin.php?page=wc-settings&tab=wcdn-settings" target="_blank" class="button-secondary">
                        <?php //esc_html_e( 'Click Here to Add new FAQ', 'arconix-faq' ); ?>
                        <span class="dashicons dashicons-external"></span>
                    </a> -->
            </div>

            <div class="content feature-section-item last-feature">
                <img src="<?php echo $ts_dir_image_path . 'display_faq.png'; ?>" alt="<?php esc_attr_e( 'Arconix FAQ', 'arconix-faq' ); ?>" style="width:500px;">
            </div>
        </div>

        <div class="feature-section clearfix introduction">
            <div class="video feature-section-item" style="float:left;padding-right:10px;">
                <img src="<?php echo $ts_dir_image_path . 'link_faq.png'; ?>" alt="<?php esc_attr_e( 'Arconix FAQ', 'arconix-faq' ); ?>" style="width:500px;">
            </div>

            <div class="content feature-section-item last-feature">
                <h3><?php esc_html_e( 'Link FAQ', 'arconix-faq' ); ?></h3>

                <p><?php esc_html_e( 'If you want to share a link to another FAQ then you can apply the web address as {{yourdomain.com/pagename}}/#faq-title-of-another-faq. You can see the example in screenshot.', 'arconix-faq' ); ?></p>
                <!-- <a href="admin.php?page=wc-settings&tab=wcdn-settings" target="_blank" class="button-secondary">
                    <?php //esc_html_e( 'Click Here to Enable Invoice Numbering', 'arconix-faq' ); ?>
                    <span class="dashicons dashicons-external"></span>
                </a> -->
            </div>
        </div>
    </div>

    <div class="content">

    <div class="feature-section clearfix">
        <div class="content feature-section-item">

            <h3><?php esc_html_e( 'FAQ Setting', 'arconix-faq' ); ?></h3>

                <p><?php esc_html_e( 'There is a setting "Load FAQ Open" which will allow you to load your FAQ in the open state. If there is any long FAQ so you can enable the setting "Show Return to Top". This will add the link at the bottom of that FAQ and will return the user to the top of that particular question.', 'arconix-faq' ); ?></p>
                <a href="post-new.php?post_type=faq&#faq_settings" target="_blank" class="button-secondary">
                    <?php esc_html_e( 'Click Here to check Setting', 'arconix-faq' ); ?>
                    <span class="dashicons dashicons-external"></span>
                </a>
        </div>

        <div class="content feature-section-item last-feature">
            <img src="<?php echo $ts_dir_image_path . 'faq_setting.png'; ?>" alt="<?php esc_attr_e( 'Arconix FAQ', 'arconix-faq' ); ?>" style="width:500px;">
        </div>
    </div>

    <div class="feature-section clearfix">

        <div class="content feature-section-item">

            <h3><?php esc_html_e( 'Getting to Know Tyche Softwares', 'arconix-faq' ); ?></h3>

            <ul class="ul-disc">
                <li><a href="https://tychesoftwares.com/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=FAQPlugin" target="_blank"><?php esc_html_e( 'Visit the Tyche Softwares Website', 'arconix-faq' ); ?></a></li>
                <li><a href="https://tychesoftwares.com/premium-woocommerce-plugins/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=FAQPlugin" target="_blank"><?php esc_html_e( 'View all Premium Plugins', 'arconix-faq' ); ?></a>
                <ul class="ul-disc">
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=FAQPlugin" target="_blank">Abandoned Cart Pro Plugin for WooCommerce</a></li>
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/woocommerce-booking-plugin/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=FAQPlugin" target="_blank">Booking & Appointment Plugin for WooCommerce</a></li>
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=FAQPlugin" target="_blank">Order Delivery Date for WooCommerce</a></li>
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=FAQPlugin" target="_blank">Product Delivery Date for WooCommerce</a></li>
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/deposits-for-woocommerce/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=FAQPlugin" target="_blank">Deposits for WooCommerce</a></li>
                </ul>
                </li>
                <li><a href="https://tychesoftwares.com/about/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=FAQ" target="_blank"><?php esc_html_e( 'Meet the team', 'arconix-faq' ); ?></a></li>
            </ul>
        </div>
    </div>            
    <!-- /.feature-section -->
</div>
