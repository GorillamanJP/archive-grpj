<nav class="navbar navbar-expand-xl bg-secondary fixed-top" data-bs-theme="dark">
    <div class="container-fluid">
        <!-- 狭い画面用のボタン -->
        <button onclick="toggleNavbar()" class="navbar-toggler position-relative" type="button">
            <span class="navbar-toggler-icon"></span>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                id="common_badge" style="display: none;">
                <span id="common_badge_text">0</span>
                <span class="visually-hidden">通知/注文あり</span>
            </span>
        </button>
        <!-- 狭い画面用のFavicon -->
        <a class="navbar-brand d-xl-none mx-auto nav-b-a"><img src="/favicon.ico" alt="Favicon"> レジ</a>
        <!-- 広い画面用のFavicon -->
        <a class="navbar-brand d-none d-xl-block nav-b-b"><img src="/favicon.ico" alt="Favicon"> レジ</a>
        <!-- ナビゲーションメニュー -->
        <div class="navbar-collapse collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item me-3">
                    <a class="nav-link text-white position-relative" href="/regi/">
                        <span><i class="fas fa-cash-register"></i> レジ画面</span>
                    </a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link text-white position-relative" href="/regi/order/list/">
                        <span><i class="fas fa-shopping-cart"></i> モバイルオーダー</span>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            id="order_count_badge" style="display: none;">
                            <span id="order_count_badge_text">0</span>
                            <span class="visually-hidden">注文あり</span>
                        </span>
                    </a>
                </li>
                <li class="nav-item  me-3">
                    <a class="nav-link text-white position-relative" href="/regi/sales/list/">
                        <span><i class="fas fa-list-alt"></i> 会計一覧</span>
                    </a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link text-white position-relative" href="/regi/products/list/">
                        <span><i class="fas fa-cubes"></i> 商品管理</span>
                    </a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link text-white position-relative" href="/regi/users/list/">
                        <span><i class="fas fa-user"></i> ユーザー管理</span>
                    </a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link text-white position-relative" href="/regi/notify/history/">
                        <span><i class="fas fa-bell"></i> 通知</span>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            id="notify_count_badge" style="display: none;">
                            <span id="notify_count_badge_text">0</span>
                            <span class="visually-hidden">通知あり</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a class="nav-link text-white position-relative" href="/regi/manual/">
                        <span><i class="fas fa-book"></i> マニュアル</span>
                    </a>
                </li>
            </ul>
            <!-- 右寄せユーザー名とログアウトボタン -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <p class="nav-link mb-0 text-white user-name">ユーザー名：<?= $user->get_user_name() ?></p>
                </li>
                <li class="nav-item">
                    <button class="btn btn-danger"><a href="/regi/users/logout/" class="btn-logout">ログアウト</a></button>
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

    .btn-logout {
        color: #fff;
        text-decoration: none;
    }

    .btn-logout:hover {
        color: #fff;
        text-decoration: none;
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

    // 注文バッジの数字表示
    async function get_order_count() {
        try {
            const resp = await fetch("/common/get_order_count.php");
            if (resp.ok) {
                const data = await resp.json();
                const order_count = data.order_count;
                if (order_count > 0) {
                    document.getElementById("order_count_badge").style = "";
                } else {
                    document.getElementById("order_count_badge").style = "display: none;";
                }
                document.getElementById("order_count_badge_text").innerText = order_count;
            } else {
                if (resp.status === 403) {
                    location.reload();
                }
                console.error("Error: " + resp.statusText);
            }
        } catch (error) {
            console.error("Error: " + error);
        }
    }

    // 通知バッジの数字表示
    async function get_notify_count() {
        try {
            const resp = await fetch("/common/get_notify_count.php");
            if (resp.ok) {
                const data = await resp.json();
                const notify_count = data.notify_count;
                if (notify_count > 0) {
                    document.getElementById("notify_count_badge").style = "";
                } else {
                    document.getElementById("notify_count_badge").style = "display: none;";
                }
                document.getElementById("notify_count_badge_text").innerText = notify_count;
            } else {
                if (resp.status === 403) {
                    location.reload();
                }
                console.error("Error: " + resp.statusText);
            }
        } catch (error) {
            console.error("Error: " + error);
        }
    }

    async function set_common_badge() {
        await get_notify_count();
        await get_order_count();
        const notify_count = document.getElementById("notify_count_badge_text").innerText;
        const order_count = document.getElementById("order_count_badge_text").innerText;
        const common_count = parseInt(notify_count) + parseInt(order_count);
        if (common_count > 0) {
            document.getElementById("common_badge").style = "";
        } else {
            document.getElementById("common_badge").style = "display: none;";
        }
        document.getElementById("common_badge_text").innerText = common_count;
    }
    set_common_badge();
    setInterval(set_common_badge, 5000);
</script>