<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <!-- ヘッダー -->
    <header class="header" id="header">
        <div class="header-inner">
            <div class="logo">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
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
    $current_page = get_post_field('post_name', get_post());
    ?>
    <ul class="nav-menu-list">
        <li><a href="<?php echo esc_url(home_url('/')); ?>" <?php echo is_front_page() ? 'class="active"' : ''; ?>>ホーム</a></li>
        <li><a href="<?php echo esc_url(home_url('/first-visit/')); ?>" <?php echo (is_page('first-visit') || is_page_template('page-first-visit.php')) ? 'class="active"' : ''; ?>>初めての方へ</a></li>
        <li><a href="<?php echo esc_url(home_url('/fukunaka-menu/')); ?>" <?php echo (is_page('fukunaka-menu') || is_page_template('page-fukunaka-menu.php')) ? 'class="active"' : ''; ?>>福中店メニュー</a></li>
        <li><a href="<?php echo esc_url(home_url('/shiomachi-menu/')); ?>" <?php echo (is_page('shiomachi-menu') || is_page_template('page-shiomachi-menu.php')) ? 'class="active"' : ''; ?>>塩町店メニュー</a></li>
        <li><a href="<?php echo esc_url(home_url('/access/')); ?>" <?php echo (is_page('access') || is_page_template('page-access.php')) ? 'class="active"' : ''; ?>>当店への道案内</a></li>
    </ul>
    <?php
}
?>