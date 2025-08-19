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
    const header = document.getElementById('header') || document.querySelector('.header');
    const hamburger = document.getElementById('hamburger') || document.querySelector('.hamburger');
    const navMenu = document.getElementById('navMenu') || document.querySelector('.nav-menu');
    
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
    const adminBar = document.getElementById('wpadminbar');
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
        }, 250);
    });
    
    // ==========================================
    // ローディング完了後の処理
    // ==========================================
    window.addEventListener('load', function() {
        // ローディングアニメーションなどがあればここで処理
        document.body.classList.add('loaded');
    });
    
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
    // コンソールメッセージ
    // ==========================================
    console.log('%c和招縁へようこそ', 'font-size: 24px; font-weight: bold; color: #8b7355;');
    console.log('伝統と革新が織りなす、二つの味わい');
});