<?php
title('User Information');
extend('layouts.guest-layout');

?>

<div class="container mt-3">
    <div class="jumbotron">
        <h1>User Information</h1>
        <?php if (!empty($users)) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Citizenship</th>
                        <th>Profile Picture</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?= $user->user_info_id ?></td>
                            <td><?= $user->name ?></td>
                            <td><?= $user->email ?></td>
                            <td><?= $user->contact ?></td>
                            <td><?= $user->address ?></td>
                            <td><?= $user->citizenship ?></td>
                            <td>
                                <?php if ($user->profile_picture) : ?>
                                    <img src="<?= $user->profile_picture ?>" alt="Profile Picture" style="max-width: 100px; max-height: 100px;">
                                <?php else : ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No users found.</p>
            <form action="/user/add" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="contact">Contact:</label>
                    <input type="text" class="form-control" id="contact" name="contact">
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="citizenship">Citizenship:</label>
                    <input type="text" class="form-control" id="citizenship" name="citizenship">
                </div>
                <div class="form-group">
                    <label for="profile_picture">Profile Picture:</label>
                    <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        <?php endif; ?>


    </div>
</div>