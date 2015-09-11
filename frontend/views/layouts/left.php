<aside class="main-sidebar">

    <section class="sidebar">
        <?php
        if (Yii::$app->user->isGuest) {
            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
                ['label' => 'Signup', 'url' => ['/user/registration/register']],
                ['label' => 'Login', 'url' => ['/user/security/login']],
            ];
        } else {
            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'Транзакции', 'url' => ['/transaction/index']],
                ['label' => 'Теги', 'url' => ['/tag/tree']],
                ['label' => 'Счета', 'url' => ['/account/index']],
                ['label' => 'Типы счетов', 'url' => ['/account-type/index']],
                ['label' => 'Валюты', 'url' => ['/currency/index']],
            ];
        }


        echo dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => $menuItems
                ]
        )
        ?>

    </section>

</aside>
