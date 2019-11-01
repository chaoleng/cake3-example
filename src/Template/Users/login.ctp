<h1>Login</h1>
<?= $this->Form->create() ?>
<?= $this->Form->control('email') ?>
<?= $this->Form->control('password') ?>
<?= $this->Form->button('Login') ?>
<?= $this->Form->end() ?>
<?php foreach ($users as $user): ?>
<?= h($user->email) ?>
<?= h($user->password) ?>
<?php endforeach; ?>