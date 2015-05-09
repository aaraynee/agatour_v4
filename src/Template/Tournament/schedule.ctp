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
            <tr>
                <td><?= $tournament->display_date; ?></td>
                <td>
                    <?= $this->Html->link($tournament->name,['controller' => 'Tournament', 'action' => 'single', $tournament->slug]) ?><br>
                    <?= $tournament->course->name; ?>
                </td>
                <td><?= $tournament->winner ?>(<?= $tournament->score ?>)</td>
                <td><?= $tournament->points; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>