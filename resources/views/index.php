<?php

use Core\Facades\Auth;

title('Homepage');
extend('layouts.guest-layout');
?>

<div class="container mt-3">
    <div class="jumbotron">
        <h1>Homepage</h1>
        <p>This is the home page content.</p>
        <?php if ($users) : ?>
            <?php foreach ($users as $user) : ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <?php if (Auth::check()) : ?>
                            <form action="/user/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <input type="hidden" name="id" value="<?= $user->id ?>">
                                <input type="hidden" name="csrf_token" value="">
                                <button type="submit" class="btn btn-danger float-right">Remove</button>
                            </form>
                        <?php endif; ?>
                        <h5 class="card-title"><?php echo $user->name; ?></h5>
                        <p class="card-text">Email: <?php echo $user->email; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>