<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-M6J2V2S5');</script>
    <!-- End Google Tag Manager -->

    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon/favicon.ico">
    <link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri(); ?>/images/favicon/favicon.svg">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/images/favicon/favicon-96x96.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/images/favicon/apple-touch-icon.png">
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/images/favicon/site.webmanifest">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M6J2V2S5"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php wp_body_open(); ?>

    <!-- ヘッダー -->
    <?php
    // ヘッダー表示設定を取得
    $header_display = get_theme_mod('header_display', true);
    $header_class = $header_display ? 'header' : 'header header-hidden';
    ?>
    <header class="<?php echo esc_attr($header_class); ?>" id="header">
        <div class="header-inner">
            <div class="logo">
                <?php
                $custom_logo_id = get_theme_mod('custom_logo');
                $dark_logo_id = get_theme_mod('dark_mode_logo');

                if ($custom_logo_id) :
                    $light_logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                    $dark_logo_url = $dark_logo_id ? wp_get_attachment_image_url($dark_logo_id, 'full') : $light_logo_url;
                    $light_logo_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
                    $dark_logo_alt = $dark_logo_id ? get_post_meta($dark_logo_id, '_wp_attachment_image_alt', true) : $light_logo_alt;
                    if (empty($light_logo_alt)) {
                        $light_logo_alt = get_bloginfo('name');
                    }
                    if (empty($dark_logo_alt)) {
                        $dark_logo_alt = get_bloginfo('name');
                    }
                ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
                        <img src="<?php echo esc_url($light_logo_url); ?>" class="custom-logo logo-light" alt="<?php echo esc_attr($light_logo_alt); ?>">
                        <img src="<?php echo esc_url($dark_logo_url); ?>" class="custom-logo logo-dark" alt="<?php echo esc_attr($dark_logo_alt); ?>">
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <h1 class="logo-text"><?php bloginfo('name'); ?></h1>
                        <span class="logo-subtitle">わしょうえん</span>
                    </a>
                <?php endif; ?>
            </div>
            <nav class="nav-menu" id="navMenu">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'nav-menu-list',
                    'container' => false,
                    'fallback_cb' => 'washouen_default_menu',
                ));
                ?>
            </nav>
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>

<?php
// デフォルトメニューのフォールバック
function washouen_default_menu() {
    // トップページが最新投稿一覧を兼ねる場合でも「お知らせ」は非アクティブにする
    $news_active = !is_front_page() && (is_home() || is_singular('post') || is_category() || is_tag() || is_date() || is_author());
    $guide_active = is_page_template('page-usage-guide.php')
        || is_page_template('page-lp.php')
        || is_page('guide');
    ?>
    <ul class="nav-menu-list">
        <li><a href="<?php echo esc_url(home_url('/')); ?>" <?php echo is_front_page() ? 'class="active"' : ''; ?>>ホーム</a></li>
        <li><a href="<?php echo esc_url(home_url('/first-visit/')); ?>" <?php echo (is_page('first-visit') || is_page_template('page-first-visit.php')) ? 'class="active"' : ''; ?>>ご挨拶</a></li>
        <li><a href="<?php echo esc_url(home_url('/fukunaka-menu/')); ?>" <?php echo (is_page('fukunaka-menu') || is_page_template('page-fukunaka-menu.php')) ? 'class="active"' : ''; ?>>福中店 お品書き</a></li>
        <li><a href="<?php echo esc_url(home_url('/shiomachi-menu/')); ?>" <?php echo (is_page('shiomachi-menu') || is_page_template('page-shiomachi-menu.php')) ? 'class="active"' : ''; ?>>塩町店 お品書き</a></li>
        <li class="menu-item-news menu-item-has-news-dropdown">
            <a href="<?php echo esc_url(washouen_get_news_page_url()); ?>" <?php echo $news_active ? 'class="active"' : ''; ?>>お知らせ</a>
            <?php echo washouen_get_news_dropdown_markup(3); ?>
        </li>
        <li class="menu-item-has-guide-dropdown">
            <a href="<?php echo esc_url(washouen_get_usage_guide_url()); ?>" <?php echo $guide_active ? 'class="active"' : ''; ?>>ご利用案内</a>
            <?php echo washouen_get_guide_dropdown_markup(); ?>
        </li>
        <li><a href="<?php echo esc_url(home_url('/access/')); ?>" <?php echo (is_page('access') || is_page_template('page-access.php')) ? 'class="active"' : ''; ?>>当店への道案内</a></li>
    </ul>
    <?php
}
?>
