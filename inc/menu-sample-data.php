<?php
/**
 * サンプルメニューデータ生成機能
 * 
 * @package Washouen
 * @since 1.0.0
 */

// 管理画面にサンプルデータ生成ボタンを追加
function washouen_add_sample_data_menu() {
    add_submenu_page(
        'edit.php?post_type=fukunaka_menu',
        'サンプルデータ生成',
        'サンプルデータ生成',
        'manage_options',
        'fukunaka-sample-data',
        'washouen_fukunaka_sample_data_page'
    );
    
    add_submenu_page(
        'edit.php?post_type=shiomachi_menu',
        'サンプルデータ生成',
        'サンプルデータ生成',
        'manage_options',
        'shiomachi-sample-data',
        'washouen_shiomachi_sample_data_page'
    );
}
add_action('admin_menu', 'washouen_add_sample_data_menu');

// 福中店サンプルデータページ
function washouen_fukunaka_sample_data_page() {
    if (isset($_POST['generate_sample']) && wp_verify_nonce($_POST['sample_nonce'], 'generate_fukunaka_sample')) {
        washouen_generate_fukunaka_sample_data();
        echo '<div class="notice notice-success"><p>サンプルデータを生成しました。</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>福中店メニュー - サンプルデータ生成</h1>
        <p>サンプルメニューデータを生成します。既存のデータは削除されません。</p>
        <form method="post">
            <?php wp_nonce_field('generate_fukunaka_sample', 'sample_nonce'); ?>
            <p class="submit">
                <input type="submit" name="generate_sample" class="button-primary" value="サンプルデータを生成">
            </p>
        </form>
    </div>
    <?php
}

// 塩町店サンプルデータページ
function washouen_shiomachi_sample_data_page() {
    if (isset($_POST['generate_sample']) && wp_verify_nonce($_POST['sample_nonce'], 'generate_shiomachi_sample')) {
        washouen_generate_shiomachi_sample_data();
        echo '<div class="notice notice-success"><p>サンプルデータを生成しました。</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>塩町店メニュー - サンプルデータ生成</h1>
        <p>サンプル寿司メニューデータを生成します。既存のデータは削除されません。</p>
        <form method="post">
            <?php wp_nonce_field('generate_shiomachi_sample', 'sample_nonce'); ?>
            <p class="submit">
                <input type="submit" name="generate_sample" class="button-primary" value="サンプルデータを生成">
            </p>
        </form>
    </div>
    <?php
}

// 福中店サンプルデータ生成
function washouen_generate_fukunaka_sample_data() {
    $sample_menus = array(
        // お造り
        array(
            'title' => '瀬戸内産 天然鯛の姿造り',
            'category' => 'sashimi',
            'price' => '時価',
            'description' => '瀬戸内海で獲れた新鮮な天然鯛を豪快に姿造りに。プリプリとした食感と上品な甘みをお楽しみください。',
            'is_seasonal' => false,
            'order' => 1
        ),
        array(
            'title' => '活きアワビの薄造り',
            'category' => 'sashimi',
            'price' => '3,800',
            'description' => '水槽から上げたての活きアワビを薄造りに。コリコリとした食感と磯の香りが絶品です。',
            'is_seasonal' => false,
            'order' => 2
        ),
        array(
            'title' => '天然ヒラメの昆布締め',
            'category' => 'sashimi',
            'price' => '2,500',
            'description' => '脂の乗った天然ヒラメを昆布で締めることで、旨みを凝縮させました。',
            'is_seasonal' => false,
            'order' => 3
        ),
        array(
            'title' => '本日の刺身盛り合わせ（5種）',
            'category' => 'sashimi',
            'price' => '2,800',
            'description' => '市場直送の新鮮な魚介を5種盛り合わせ。その日のおすすめをご提供します。',
            'is_seasonal' => false,
            'order' => 4
        ),
        
        // 焼き物
        array(
            'title' => 'のどぐろの塩焼き',
            'category' => 'grilled',
            'price' => '時価',
            'description' => '脂の乗った高級魚のどぐろを、シンプルに塩焼きで。皮はパリッと、身はふっくらと焼き上げます。',
            'is_seasonal' => false,
            'order' => 5
        ),
        array(
            'title' => '穴子の白焼き',
            'category' => 'grilled',
            'price' => '2,200',
            'description' => '瀬戸内産の穴子を香ばしく白焼きに。山葵醤油でさっぱりとお召し上がりください。',
            'is_seasonal' => false,
            'order' => 6
        ),
        array(
            'title' => '車海老の鬼殻焼き',
            'category' => 'grilled',
            'price' => '1,800',
            'description' => '大ぶりの車海老を殻ごと豪快に焼き上げました。香ばしい殻の風味もお楽しみください。',
            'is_seasonal' => false,
            'order' => 7
        ),
        
        // 煮付け
        array(
            'title' => '金目鯛の煮付け',
            'category' => 'simmered',
            'price' => '3,200',
            'description' => '脂の乗った金目鯛を甘辛く煮付けました。身はホロホロと柔らかく、絶品の味わいです。',
            'is_seasonal' => false,
            'order' => 8
        ),
        array(
            'title' => 'カレイの唐揚げ甘酢あんかけ',
            'category' => 'simmered',
            'price' => '1,800',
            'description' => 'カラッと揚げたカレイに特製の甘酢あんをかけました。お子様にも人気の一品です。',
            'is_seasonal' => false,
            'order' => 9
        ),
        
        // 揚げ物
        array(
            'title' => '天然海老の天ぷら',
            'category' => 'fried',
            'price' => '2,400',
            'description' => '大ぶりの天然海老をサクッと軽い衣で天ぷらに。塩または天つゆでどうぞ。',
            'is_seasonal' => false,
            'order' => 10
        ),
        array(
            'title' => '季節野菜の天ぷら盛り合わせ',
            'category' => 'fried',
            'price' => '1,500',
            'description' => '旬の野菜を丁寧に天ぷらに。素材の味を活かした優しい味わいです。',
            'is_seasonal' => false,
            'order' => 11
        ),
        array(
            'title' => 'フグの唐揚げ',
            'category' => 'fried',
            'price' => '2,800',
            'description' => 'プリプリのフグを香ばしく唐揚げに。ポン酢と紅葉おろしでさっぱりと。',
            'is_seasonal' => true,
            'order' => 12
        ),
        
        // 季節の特選料理
        array(
            'title' => '松茸の土瓶蒸し',
            'category' => 'special',
            'price' => '3,500',
            'description' => '香り高い松茸を贅沢に使った土瓶蒸し。秋の味覚をご堪能ください。',
            'is_seasonal' => true,
            'order' => 13
        ),
        array(
            'title' => '鱧の湯引き',
            'category' => 'special',
            'price' => '2,800',
            'description' => '夏の風物詩、鱧を丁寧に骨切りして湯引きに。梅肉でさっぱりとお召し上がりください。',
            'is_seasonal' => true,
            'order' => 14
        ),
        array(
            'title' => '寒ブリのしゃぶしゃぶ',
            'category' => 'special',
            'price' => '4,200',
            'description' => '脂の乗った寒ブリを贅沢にしゃぶしゃぶで。ポン酢と紅葉おろしでどうぞ。',
            'is_seasonal' => true,
            'order' => 15
        )
    );
    
    foreach ($sample_menus as $menu) {
        $post_id = wp_insert_post(array(
            'post_title' => $menu['title'],
            'post_type' => 'fukunaka_menu',
            'post_status' => 'publish',
            'menu_order' => $menu['order']
        ));
        
        if ($post_id) {
            update_post_meta($post_id, '_menu_price', $menu['price']);
            update_post_meta($post_id, '_menu_description', $menu['description']);
            update_post_meta($post_id, '_menu_category', $menu['category']);
            update_post_meta($post_id, '_menu_is_seasonal', $menu['is_seasonal'] ? '1' : '0');
            wp_set_object_terms($post_id, $menu['category'], 'fukunaka_category');
        }
    }
}

// 塩町店サンプルデータ生成
function washouen_generate_shiomachi_sample_data() {
    $sample_menus = array(
        // 握り
        array(
            'title' => '本マグロ 大トロ',
            'category' => 'nigiri',
            'price' => '時価',
            'description' => '最高級の本マグロの大トロ。口の中でとろける極上の味わい。',
            'origin' => '青森県大間産',
            'order' => 1
        ),
        array(
            'title' => '本マグロ 中トロ',
            'category' => 'nigiri',
            'price' => '1,200',
            'description' => '程よい脂のりの中トロ。赤身と脂のバランスが絶妙です。',
            'origin' => '青森県大間産',
            'order' => 2
        ),
        array(
            'title' => '本マグロ 赤身',
            'category' => 'nigiri',
            'price' => '800',
            'description' => 'マグロ本来の旨みを堪能できる赤身。さっぱりとした味わい。',
            'origin' => '青森県大間産',
            'order' => 3
        ),
        array(
            'title' => 'コハダ',
            'category' => 'nigiri',
            'price' => '600',
            'description' => '江戸前の伝統、丁寧に〆たコハダ。酢の加減が絶妙です。',
            'origin' => '東京湾産',
            'order' => 4
        ),
        array(
            'title' => '車海老',
            'category' => 'nigiri',
            'price' => '800',
            'description' => '活き〆の新鮮な車海老。プリプリの食感と甘みが特徴。',
            'origin' => '愛知県産',
            'order' => 5
        ),
        array(
            'title' => '真鯛',
            'category' => 'nigiri',
            'price' => '700',
            'description' => '瀬戸内産の天然真鯛。上品な甘みと旨みが広がります。',
            'origin' => '瀬戸内海産',
            'order' => 6
        ),
        array(
            'title' => 'ヒラメ',
            'category' => 'nigiri',
            'price' => '900',
            'description' => '淡白ながら上品な味わいの天然ヒラメ。昆布〆でご提供。',
            'origin' => '青森県産',
            'order' => 7
        ),
        array(
            'title' => '穴子',
            'category' => 'nigiri',
            'price' => '800',
            'description' => 'ふっくらと煮上げた江戸前穴子。甘辛いタレが絶品。',
            'origin' => '東京湾産',
            'order' => 8
        ),
        
        // 軍艦・巻物
        array(
            'title' => 'うに',
            'category' => 'gunkan',
            'price' => '時価',
            'description' => '濃厚でクリーミーな北海道産の雲丹。甘みが口いっぱいに広がります。',
            'origin' => '北海道産',
            'order' => 9
        ),
        array(
            'title' => 'いくら',
            'category' => 'gunkan',
            'price' => '900',
            'description' => 'プチプチとした食感の北海道産いくら。特製醤油漬け。',
            'origin' => '北海道産',
            'order' => 10
        ),
        array(
            'title' => 'ねぎとろ巻き',
            'category' => 'gunkan',
            'price' => '1,200',
            'description' => '本マグロの中落ちを贅沢に使用。ねぎの風味がアクセント。',
            'origin' => '',
            'order' => 11
        ),
        array(
            'title' => '鉄火巻き',
            'category' => 'gunkan',
            'price' => '800',
            'description' => '本マグロの赤身を使った定番の巻物。山葵がピリッと効いています。',
            'origin' => '',
            'order' => 12
        ),
        
        // ちらし・丼
        array(
            'title' => '特選ちらし',
            'category' => 'chirashi',
            'price' => '3,500',
            'description' => '旬の魚介を贅沢に盛り込んだ特選ちらし寿司。',
            'origin' => '',
            'order' => 13
        ),
        array(
            'title' => '海鮮丼',
            'category' => 'chirashi',
            'price' => '2,800',
            'description' => '新鮮な魚介をたっぷりと乗せた海鮮丼。',
            'origin' => '',
            'order' => 14
        ),
        array(
            'title' => '鉄火丼',
            'category' => 'chirashi',
            'price' => '2,500',
            'description' => '本マグロの赤身をたっぷりと使用した鉄火丼。',
            'origin' => '',
            'order' => 15
        ),
        
        // おまかせコース
        array(
            'title' => '特選おまかせコース',
            'category' => 'omakase',
            'price' => '8,000',
            'description' => '前菜、お造り、焼き物、握り12貫、椀物、デザート',
            'origin' => '',
            'order' => 16
        ),
        array(
            'title' => 'おまかせコース',
            'category' => 'omakase',
            'price' => '5,000',
            'description' => '前菜、握り10貫、巻物、椀物、デザート',
            'origin' => '',
            'order' => 17
        ),
        array(
            'title' => 'お手軽コース',
            'category' => 'omakase',
            'price' => '3,500',
            'description' => '握り8貫、巻物、椀物',
            'origin' => '',
            'order' => 18
        ),
        
        // 一品料理
        array(
            'title' => '茶碗蒸し',
            'category' => 'side',
            'price' => '600',
            'description' => '出汁の効いた優しい味わいの茶碗蒸し。',
            'origin' => '',
            'order' => 19
        ),
        array(
            'title' => 'あら汁',
            'category' => 'side',
            'price' => '500',
            'description' => '魚のあらから取った出汁が効いた味噌汁。',
            'origin' => '',
            'order' => 20
        ),
        array(
            'title' => '枝豆',
            'category' => 'side',
            'price' => '400',
            'description' => '塩茹でした枝豆。お酒のお供に。',
            'origin' => '',
            'order' => 21
        )
    );
    
    foreach ($sample_menus as $menu) {
        $post_id = wp_insert_post(array(
            'post_title' => $menu['title'],
            'post_type' => 'shiomachi_menu',
            'post_status' => 'publish',
            'menu_order' => $menu['order']
        ));
        
        if ($post_id) {
            update_post_meta($post_id, '_menu_price', $menu['price']);
            update_post_meta($post_id, '_menu_description', $menu['description']);
            update_post_meta($post_id, '_menu_category', $menu['category']);
            update_post_meta($post_id, '_menu_origin', $menu['origin']);
            wp_set_object_terms($post_id, $menu['category'], 'shiomachi_category');
        }
    }
}