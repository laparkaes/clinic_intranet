<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $this->lang->line($code.'_title') ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url() ?>resources/images/favicon.png">
    <link href="<?= base_url() ?>resources/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>resources/css/style.css">
</head>
<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-5">
                    <div class="form-input-content text-center error-page">
                        <h1 class="error-text font-weight-bold"><?= $this->lang->line($code.'_code') ?></h1>
                        <h4><i class="fa <?= $icon ?>"></i> <?= $this->lang->line($code.'_header') ?></h4>
                        <p><?= $this->lang->line($code.'_msg') ?></p>
						<div>
                            <a class="btn btn-primary" href="<?= base_url() ?>"><?= $this->lang->line('btn_go_home') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="<?= base_url() ?>resources/vendor/global/global.min.js"></script>
<script src="<?= base_url() ?>resources/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="<?= base_url() ?>resources/js/custom.min.js"></script>
<script src="<?= base_url() ?>resources/js/deznav-init.js"></script>
</body>
</html>