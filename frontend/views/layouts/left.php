<aside class="main-sidebar">

    <section class="sidebar">
        <?php
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Транзакции', 'url' => ['/transaction/index']],
            ['label' => 'Плановые Транзакции', 'url' => ['/transaction/plan']],
            ['label' => 'Теги', 'url' => ['/tag/tree']],
            ['label' => 'Счета', 'url' => ['/account/index']],
            ['label' => 'Типы счетов', 'url' => ['/account-type/index']],
            ['label' => 'Валюты', 'url' => ['/currency/index']],
        ];

        echo dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu'],
                    'items' => $menuItems
                ]
        )
        ?>

    </section>

</aside>
