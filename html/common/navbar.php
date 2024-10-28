<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <button onclick="toggleNavbar()" class="navbar-toggler" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                <a class="nav-link" href="/regi/index.php"><i class="fas fa-cash-register"></i>レジ画面</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-shopping-cart"></i> モバイルオーダー</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/regi/sales/list/"><i class="fas fa-list-alt"></i> 会計一覧</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/regi/products/list/"><i class="fas fa-cubes"></i> 商品管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/regi/users/list/"><i class="fas fa-user"></i> ユーザー管理</a>
                </li>
                <!-- 新たにユーザー名とログアウトボタンをここに追加 -->
                <li class="nav-item">
                    <p class="nav-link mb-0 mr-3 text-white user-name">ユーザー名：<?= $user->get_user_name() ?></p>
                </li>
                <li class="nav-item">
                    <button class="btn btn-danger"><a href="./users/logout/" style="color: #fff;">ログアウト</a></button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    function toggleNavbar() {
        const navbar = document.getElementById('navbarNav');
        navbar.classList.toggle('show');
    }
</script>