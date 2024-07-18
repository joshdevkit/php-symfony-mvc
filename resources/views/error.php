<?php
title('Error');
extend('layouts.guest-layout');
?>
<style>
    .card.shadow {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #dc3545;
        color: white;
        font-size: 2rem;
        text-align: center;
        border-radius: 10px 10px 0 0;
        padding: 1.5rem;
    }

    .custom-alert {
        border: 2px solid #dc3545;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .error-message {
        font-size: 1.2rem;
        margin-top: 10px;
    }
</style>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <h2 class="card-header text-white text-center py-4">
                    Oops! Sorry,
                </h2>
                <div class="card-body">
                    <?php if (isset($method) && isset($class)) : ?>
                        <div class="alert alert-danger custom-alert" role="alert">
                            <h4 class="alert-heading">Method Not Found</h4>
                            <p class="error-message">The <strong><?= htmlspecialchars($method); ?></strong> method was not found in <strong><?= htmlspecialchars($class); ?></strong>.</p>
                        </div>
                    <?php elseif (isset($MethodNotAllowed)) : ?>
                        <div class="alert alert-danger custom-alert" role="alert">
                            <h4 class="alert-heading">Method Not Allowed</h4>
                            <p class="error-message">The following methods are not allowed for this request: <strong><?= htmlspecialchars(implode(', ', $MethodNotAllowed)); ?></strong>.</p>
                        </div>
                    <?php elseif (isset($errorMessage)) : ?>
                        <div class="alert alert-danger custom-alert" role="alert">
                            <h4 class="alert-heading">View file not Found</h4>
                            <p class="error-message"><strong><?= htmlspecialchars($errorMessage); ?></strong>.</p>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-danger custom-alert" role="alert">
                            <h4 class="alert-heading">Page Not Found</h4>
                            <p class="error-message">The page you are looking for does not exist.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>