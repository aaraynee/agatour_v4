<script>
    $(function(){
        Dashboard.init();
    });

    var Dashboard = {
        el: {
            typeFilter: null
        },
        init: function(){
            var time = new Date();
            year = time.getFullYear();
            type = 2;
            this.el.typeFilter = $('.type');
            this.el.yearFilter = $('.year');

            this.el.typeFilter.find('li a').click(this.switchType);
            this.el.yearFilter.find('li a').click(this.switchYear);
        },
        switchType: function(){
            type = $(this).data('tab');
            $('.uk-table').find('tbody tr').fadeOut().addClass('uk-hidden');
            $('.uk-table').find('tbody tr.type'+type+'.year'+year).fadeIn().removeClass('uk-hidden');

            $('.type').children('li').removeClass('uk-active');
            $(this).parents('li').addClass('uk-active');
        },
        switchYear: function(){
            year = $(this).data('tab');
            $('.uk-table').find('tbody tr').fadeOut().addClass('uk-hidden');
            $('.uk-table').find('tbody tr.type'+type+'.year'+year).fadeIn().removeClass('uk-hidden');

            $('.year').children('li').removeClass('uk-active');
            $(this).parents('li').addClass('uk-active');
        }
    }
</script>
        <ul class="uk-subnav uk-subnav-pill type">
            <li class="uk-active"><a data-tab="2">Tour</a></li>
            <li><a data-tab="4">Practice</a></li>
            <li><a data-tab="3">Exhibition</a></li>
        </ul>

        <ul class="uk-subnav uk-subnav-pill year">
            <li class="uk-active"><a data-tab="2015">2015</a></li>
            <li><a data-tab="2014">2014</a></li>
            <li><a data-tab="2013">2013</a></li>
        </ul>

<table class="uk-table">
    <thead>
    <tr>
        <th></th>
        <th></th>
        <th>Winner</th>
        <th>Points</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($tournaments as $tournament): ?>
            <tr class="type<?= $tournament->type ?> year<?= date('Y', strtotime($tournament->date)); ?> <?= (($tournament->type != 2 || date('Y', strtotime($tournament->date)) != date('Y')) ? 'uk-hidden' : '') ?>">
                <td><?= $tournament->display_date ?></td>
                <td>
                    <?= $this->Html->link($tournament->name,['controller' => 'Tournament', 'action' => 'single', $tournament->slug]) ?><br>
                    <?= $tournament->course->name ?>
                </td>
                <td><?= $tournament->winner ?></td>
                <td><?= $tournament->points ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>