<?php
title('Auth - Login');
extend('layouts.auth-layout');
?>
<style>
    .card {
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #007bff;
        color: #fff;
        border-bottom: none;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: none;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }
</style>
<div class="container mt-5 py-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Login to Your Account</h4>
                </div>
                <div class="card-body">
                    <form action="/login" method="POST">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Enter email" value="<?php echo isset($oldInput['email']) ? htmlspecialchars($oldInput['email']) : ''; ?>">
                            <?php if (isset($errors['email'])) : ?>
                                <?php if (is_array($errors['email'])) : ?>
                                    <?php foreach ($errors['email'] as $error) : ?>
                                        <div class="invalid-feedback">
                                            <?php echo $error; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['email']; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Enter password">
                            <?php if (isset($errors['password'])) : ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['password'][0]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>


                </div>
                <div class="card-footer bg-light">
                    <p class="text-center mb-0">Don't have an account? <a href="/register">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>