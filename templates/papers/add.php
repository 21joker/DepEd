<h2>Submit Paper</h2>

<?= $this->Form->create($paper) ?>

<?= $this->Form->control('title', [
    'label' => 'Paper Title'
]) ?>

<?= $this->Form->control('content', [
    'type' => 'textarea',
    'label' => 'Paper Content'
]) ?>

<?= $this->Form->button('Submit') ?>
<?= $this->Form->end() ?>
