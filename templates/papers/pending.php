<h2>Pending Papers</h2>

<table>
    <tr>
        <th>Title</th>
        <th>Submitted</th>
        <th>Action</th>
    </tr>

    <?php foreach ($papers as $paper): ?>
    <tr>
        <td><?= h($paper->title) ?></td>
        <td><?= $paper->created ?></td>
        <td>
            <?= $this->Html->link(
                'Approve',
                ['action' => 'approve', $paper->id],
                ['class' => 'btn btn-success']
            ) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
