<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
        Remove this if you use the .htaccess -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <title><?php echo $title; ?></title>
        <meta name="description" content="" />
        <meta name="author" content="elferrus7" />

        <!--<meta name="viewport" content="width=device-width; initial-scale=1.0" />-->

        <!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
        <link type="text/css" href="<?php echo base_url(); ?>bootstrap/css/bootstrap.css" rel="stylesheet" />
        <link type="text/css" href="<?php echo base_url(); ?>bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />
        <script src="<?php echo base_url(); ?>bootstrap/js/jquery-2.0.3.min.js"></script>
        <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
        <?php if(isset($sorteable)): ?>
            <!--Sorteable CSS and JS-->
            <link type="text/css" href="<?php echo base_url(); ?>bootstrap/css/sorteable.css" rel="stylesheet" />
            <script src="<?php echo base_url(); ?>bootstrap/js/jquery.sortable.min.js"></script>
            <script>
                $(function() {
                    $('.connected').sortable({
                        connectWith: '.connected'
                    });
                });
            </script>
        <?php endif; ?>
        
        <?php if(isset($chosen)):?>
            <link type="text/css" href="<?php echo base_url(); ?>bootstrap/js/chosen/chosen.min.css" rel="stylesheet" />
            <script src="<?php echo base_url(); ?>bootstrap/js/chosen/chosen.jquery.js"></script>
        <?php endif; ?>
        
    </head>
    <body>
        <div class="row-fluid">
            <!-- Whole row container div -->
            <div class="span12">
                <!-- Whole span container div -->

                <header>
                    <h1>Evaluate.me</h1>
                </header>

                <?php $this -> load -> view('navbar'); ?>

                <div class="row-fluid">
                    <!-- Content Row -->
                        <?php echo $this -> load -> view($content); ?>
                </div><!-- Content row -->
                <?php $this -> load -> view('footer'); ?>
            </div><!-- Whole span container div -->
        </div><!-- Whole row container div -->
    </body>
</html>