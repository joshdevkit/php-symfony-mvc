<?php
title('Profile Page');
extend('layouts.guest-layout');
?>

<div class="container mt-3">
    <div class="jumbotron">
        <h1>Profile Information</h1>
        <form action="/update-profile" method="POST">
            <?php
            if (isset($_SESSION['message'])) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['message']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
                unset($_SESSION['message']);
            endif;
            ?>

            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?= $user->name ?>">
                <?php if (isset($errors['name'])) : ?>
                    <div class="invalid-feedback">
                        <?php echo $errors['name'][0]; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?= $user->email ?>">
                <?php if (isset($errors['email'])) : ?>
                    <div class="invalid-feedback">
                        <?php echo $errors['email'][0]; ?>
                    </div>
                <?php endif; ?>
            </div>
            <hr>
            <div class="mb-3">
                <label for="currentPassword" class="form-label">Current Password:</label>
                <input type="password" class="form-control" id="currentPassword" name="currentPassword">
            </div>
            <div class="mb-3">
                <label for="newPassword" class="form-label">New Password:</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword">
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm New Password:</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
            <a href="/user-info" class="btn btn-secondary">View User Information</a>
        </form>
    </div>
</div>