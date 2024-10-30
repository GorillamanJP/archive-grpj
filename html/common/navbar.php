<nav class="navbar navbar-expand-lg bg-secondary fixed-top" data-bs-theme="dark">
    <div class="container-fluid">
        <!-- 狭い画面用のボタン -->
        <button onclick="toggleNavbar()" class="navbar-toggler" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- 狭い画面用のFavicon -->
        <a class="navbar-brand d-lg-none mx-auto nav-b-a"><img src="/favicon.ico" alt="Favicon"> レジ</a>
        <!-- 広い画面用のFavicon -->
        <a class="navbar-brand d-none d-lg-block nav-b-b"><img src="/favicon.ico" alt="Favicon"> レジ</a>
        <!-- ナビゲーションメニュー -->
        <div class="navbar-collapse collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/regi/index.php"><i class="fas fa-cash-register"></i> レジ画面</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/regi/order/"><i class="fas fa-shopping-cart"></i> モバイルオーダー</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/regi/sales/list/"><i class="fas fa-list-alt"></i> 会計一覧</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/regi/products/list/"><i class="fas fa-cubes"></i> 商品管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/regi/users/list/"><i class="fas fa-user"></i> ユーザー管理</a>
                </li>
            </ul>
            <!-- 右寄せユーザー名とログアウトボタン -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <p class="nav-link mb-0 text-white user-name">ユーザー名：<?= $user->get_user_name() ?></p>
                </li>
                <li class="nav-item">
                    <button class="btn btn-danger"><a href="/regi/users/logout/" style="color: #fff;">ログアウト</a></button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar-brand img {
        height: 100%;
        /* ナビゲーションバーの高さに合わせる */
        max-height: 2rem;
        /* ナビゲーションバーの高さ */
    }

    /* リストアイテムの高さを統一する */
    .navbar-nav .nav-item {
        display: flex;
        align-items: center;
        /* 中央揃えにする */
    }

    /* Faviconの位置調整 */
    .nav-b-a {
        position: relative;
        left: -30px;
        /* お好みに合わせて調整 */
    }

    /* Faviconの位置調整 */
    .nav-b-b {
        position: relative;
        left: 10px;
        /* お好みに合わせて調整 */
    }

    /* 右寄せユーザー名とログアウトボタン */
    .ml-auto {
        margin-left: auto;
    }

    .user-name {
        font-size: 1.25rem;
        font-weight: bold;
        /* フォントを太字にして強調 */
        color: #ffeb3b;
        /* フォントカラーを変更 */
    }
</style>

<script>
    function toggleNavbar() {
        const navbar = document.getElementById('navbarNav');
        navbar.classList.toggle('show');
    }

    // ページロード時にナビゲーションバーを折りたたみ状態にする
    document.addEventListener('DOMContentLoaded', function () {
        const navbar = document.getElementById('navbarNav');
        navbar.classList.remove('show');
    });
</script>