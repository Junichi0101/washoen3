/**
 * 和招縁 - メインJavaScript
 * WordPress Theme Version
 * 
 * 機能:
 * - ヘッダーのスクロール制御
 * - ハンバーガーメニュー
 * - スムーススクロール
 * - スクロールアニメーション
 */

// ==========================================
// DOM要素の取得
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // iOS系インアプリの100vh問題: --vh を設定
    // ==========================================
    (function setupViewportFix() {
        function setVhUnit() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }
        setVhUnit();
        // リサイズやUI表示変化で更新
        window.addEventListener('resize', setVhUnit);
        window.addEventListener('orientationchange', setVhUnit);
        window.addEventListener('pageshow', function(e){ if (e.persisted) setVhUnit(); });
    })();
    const header = document.getElementById('header') || document.querySelector('.header');
    const hamburger = document.getElementById('hamburger') || document.querySelector('.hamburger');
    const navMenu = document.getElementById('navMenu') || document.querySelector('.nav-menu');
    const adminBar = document.getElementById('wpadminbar');
    
    // ==========================================
    // ヘッダーのスクロール制御
    // ==========================================
    if (header) {
        let lastScrollY = 0;
        let ticking = false;
        
        function updateHeaderOnScroll() {
            const currentScrollY = window.scrollY;
            
            // スクロール位置によってヘッダーのスタイルを変更
            if (currentScrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            lastScrollY = currentScrollY;
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                window.requestAnimationFrame(updateHeaderOnScroll);
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestTick);
    }
    
    // ==========================================
    // ハンバーガーメニュー
    // ==========================================
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
            
            // メニューが開いている間はスクロールを無効化
            if (navMenu.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
        
        // メニューリンクをクリックしたらメニューを閉じる
        const navLinks = navMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
    }
    
    // ==========================================
    // スムーススクロール
    // ==========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            if (targetId === '#' || targetId === '#!') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                const headerHeight = header ? header.offsetHeight : 0;
                const targetPosition = targetElement.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // ==========================================
    // スクロールアニメーション
    // ==========================================
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const fadeInObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                // 一度表示したら監視を解除（パフォーマンス向上）
                fadeInObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // アニメーション対象の要素を監視
    const sections = document.querySelectorAll('.section, section');
    sections.forEach(section => {
        if (!section.classList.contains('fade-in')) {
            section.classList.add('fade-in');
        }
        fadeInObserver.observe(section);
    });
    
    // メニューアイテムにもアニメーションを適用
    const animatedItems = document.querySelectorAll('.menu-category, .course-item, .store-card, .gallery-item');
    animatedItems.forEach((item, index) => {
        item.classList.add('fade-in');
        item.style.transitionDelay = `${index * 0.1}s`;
        fadeInObserver.observe(item);
    });
    
    // ==========================================
    // WordPress管理バーの高さを考慮
    // ==========================================
    if (adminBar && header) {
        const adminBarHeight = adminBar.offsetHeight;
        header.style.top = adminBarHeight + 'px';
        
        // スクロール時のオフセットも調整
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#' || targetId === '#!') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    const headerHeight = header.offsetHeight;
                    const targetPosition = targetElement.offsetTop - headerHeight - adminBarHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
    
    // ==========================================
    // ウィンドウリサイズ時の処理
    // ==========================================
    function adjustFirstVisitIntroHeight() {
        const body = document.body;
        if (!body.classList.contains('page-template-page-first-visit') &&
            !body.classList.contains('page-template-page-first-visit-php')) {
            return;
        }

        const pageHeader = document.querySelector('.page-header');
        const welcomeSection = document.querySelector('.welcome-message');
        if (!pageHeader || !welcomeSection) return;

        const headerHeight = header ? header.offsetHeight : 0;
        const adminBarHeight = adminBar ? adminBar.offsetHeight : 0;
        const viewportHeight = window.innerHeight;
        const availableHeight = viewportHeight - headerHeight - adminBarHeight;
        const pageHeaderStyles = window.getComputedStyle(pageHeader);
        const pageHeaderMargins =
            (parseFloat(pageHeaderStyles.marginTop) || 0) +
            (parseFloat(pageHeaderStyles.marginBottom) || 0);
        const pageHeaderHeight = pageHeader.offsetHeight + pageHeaderMargins;
        const leftoverHeight = availableHeight - pageHeaderHeight;

        if (leftoverHeight > 0) {
            welcomeSection.classList.add('first-visit-hero-active');
            const computedStyle = window.getComputedStyle(welcomeSection);
            const paddingTop = parseFloat(computedStyle.paddingTop) || 0;
            const paddingBottom = parseFloat(computedStyle.paddingBottom) || 0;
            const verticalPadding = paddingTop + paddingBottom;
            const contentHeight = leftoverHeight - verticalPadding;

            if (contentHeight > 20) {
                welcomeSection.style.minHeight = `${contentHeight}px`;
            } else {
                welcomeSection.style.removeProperty('min-height');
                welcomeSection.classList.remove('first-visit-hero-active');
            }
        } else {
            welcomeSection.style.removeProperty('min-height');
            welcomeSection.classList.remove('first-visit-hero-active');
        }
    }

    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // リサイズ完了後の処理
            if (window.innerWidth > 768) {
                // PCサイズになったらモバイルメニューを閉じる
                if (hamburger) hamburger.classList.remove('active');
                if (navMenu) navMenu.classList.remove('active');
                document.body.style.overflow = '';
            }

            adjustFirstVisitIntroHeight();
        }, 250);
    });
    
    // ==========================================
    // ローディング完了後の処理
    // ==========================================
    window.addEventListener('load', function() {
        // ローディングアニメーションなどがあればここで処理
        document.body.classList.add('loaded');

        adjustFirstVisitIntroHeight();
    });

    // 初期実行
    adjustFirstVisitIntroHeight();

    // ==========================================
    // ギャラリースライダー
    // ==========================================
    const gallerySliderWrapper = document.querySelector('.gallery-slider-wrapper');
    const gallerySlider = document.querySelector('.gallery-slider');
    const gallerySlides = document.querySelectorAll('.gallery-slide');
    const galleryDotsContainer = document.querySelector('.gallery-dots');

    if (gallerySlider && gallerySlides.length > 0) {
        let currentSlide = 0;
        const totalSlides = gallerySlides.length;
        let autoPlayInterval;

        // カスタマイザーから切替秒数を取得（デフォルト: 4.0秒）
        let intervalSeconds = 4.0;
        if (gallerySliderWrapper && gallerySliderWrapper.dataset.interval) {
            intervalSeconds = parseFloat(gallerySliderWrapper.dataset.interval);
            // 値の範囲チェック（1.0〜10.0秒）
            if (isNaN(intervalSeconds) || intervalSeconds < 1.0 || intervalSeconds > 10.0) {
                intervalSeconds = 4.0;
            }
        }
        const intervalMs = Math.round(intervalSeconds * 1000); // ミリ秒に変換

        // ドットナビゲーションを生成
        if (galleryDotsContainer) {
            galleryDotsContainer.innerHTML = '';
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('span');
                dot.classList.add('gallery-dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                galleryDotsContainer.appendChild(dot);
            }
        }

        const galleryDots = document.querySelectorAll('.gallery-dot');

        function updateSlider() {
            const offset = -currentSlide * 100;
            gallerySlider.style.transform = `translateX(${offset}%)`;

            // ドットを更新
            galleryDots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSlider();
            resetAutoPlay();
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        }

        // 自動再生
        function startAutoPlay() {
            autoPlayInterval = setInterval(nextSlide, intervalMs); // カスタマイザーで設定した秒数で切り替え
        }

        function stopAutoPlay() {
            if (autoPlayInterval) {
                clearInterval(autoPlayInterval);
            }
        }

        function resetAutoPlay() {
            stopAutoPlay();
            startAutoPlay();
        }

        // スワイプ対応（タッチデバイス）
        let touchStartX = 0;
        let touchEndX = 0;

        gallerySlider.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        gallerySlider.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            if (touchStartX - touchEndX > swipeThreshold) {
                // 左スワイプ（次へ）
                nextSlide();
                resetAutoPlay();
            } else if (touchEndX - touchStartX > swipeThreshold) {
                // 右スワイプ（前へ）
                prevSlide();
                resetAutoPlay();
            }
        }

        // マウスホバーで自動再生を一時停止
        gallerySlider.addEventListener('mouseenter', stopAutoPlay);
        gallerySlider.addEventListener('mouseleave', startAutoPlay);

        // 初期化
        updateSlider();
        startAutoPlay();
    }

    // ==========================================
    // WordPress Contact Form 7 サポート
    // ==========================================
    if (typeof wpcf7 !== 'undefined') {
        document.addEventListener('wpcf7mailsent', function(event) {
            // フォーム送信成功時の処理
            alert('お問い合わせありがとうございます。内容を確認の上、ご連絡させていただきます。');
        }, false);
        
        document.addEventListener('wpcf7invalid', function(event) {
            // バリデーションエラー時の処理
            alert('入力内容に不備があります。赤く表示された項目をご確認ください。');
        }, false);
    }
    
    // ==========================================
    // ご挨拶ページのスライダー
    // ==========================================
    const greetingSliderWrapper = document.querySelector('.greeting-slider-wrapper');
    const greetingSlider = document.querySelector('.greeting-slider');
    const greetingSlides = document.querySelectorAll('.greeting-slide');
    const greetingDotsContainer = document.querySelector('.greeting-slider-dots');

    if (greetingSlider && greetingSlides.length > 0) {
        let currentSlide = 0;
        const totalSlides = greetingSlides.length;
        let autoPlayInterval;

        // カスタマイザーから切替秒数を取得（デフォルト: 4.0秒）
        let intervalSeconds = 4.0;
        if (greetingSliderWrapper && greetingSliderWrapper.dataset.interval) {
            intervalSeconds = parseFloat(greetingSliderWrapper.dataset.interval);
            if (isNaN(intervalSeconds) || intervalSeconds < 1.0 || intervalSeconds > 10.0) {
                intervalSeconds = 4.0;
            }
        }
        const intervalMs = Math.round(intervalSeconds * 1000);

        // ドットナビゲーションを生成
        if (greetingDotsContainer) {
            greetingDotsContainer.innerHTML = '';
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('span');
                dot.classList.add('greeting-slider-dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                greetingDotsContainer.appendChild(dot);
            }
        }

        const greetingDots = document.querySelectorAll('.greeting-slider-dot');

        function updateSlider() {
            const offset = -currentSlide * 100;
            greetingSlider.style.transform = `translateX(${offset}%)`;

            greetingDots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSlider();
            resetAutoPlay();
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        }

        function startAutoPlay() {
            autoPlayInterval = setInterval(nextSlide, intervalMs);
        }

        function stopAutoPlay() {
            if (autoPlayInterval) {
                clearInterval(autoPlayInterval);
            }
        }

        function resetAutoPlay() {
            stopAutoPlay();
            startAutoPlay();
        }

        // スワイプ対応
        let touchStartX = 0;
        let touchEndX = 0;

        greetingSlider.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        greetingSlider.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            if (touchStartX - touchEndX > swipeThreshold) {
                nextSlide();
                resetAutoPlay();
            } else if (touchEndX - touchStartX > swipeThreshold) {
                prevSlide();
                resetAutoPlay();
            }
        }

        // マウスホバーで自動再生を一時停止
        greetingSlider.addEventListener('mouseenter', stopAutoPlay);
        greetingSlider.addEventListener('mouseleave', startAutoPlay);

        // 初期化
        updateSlider();
        startAutoPlay();
    }

    // ==========================================
    // コンソールメッセージ
    // ==========================================
    console.log('%c和招縁へようこそ', 'font-size: 24px; font-weight: bold; color: #8b7355;');
    console.log('伝統と革新が織りなす、二つの味わい');
});
