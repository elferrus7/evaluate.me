<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
        Remove this if you use the .htaccess -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <title>Evaluate.me</title>
        <meta name="description" content="" />
        <meta name="author" content="elferrus7" />


        <!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
        <link rel="shortcut icon" href="/favicon.ico" />
        <link type="text/css" href="<?php echo base_url(); ?>bootstrap/css/bootstrap.css" rel="stylesheet" />
        <link type="text/css" href="<?php echo base_url(); ?>bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />
        <script src="<?php echo base_url(); ?>bootstrap/js/jquery-2.0.3.min.js"></script>
        <script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
        <style type="text/css">
            #login{
                position: fixed;
                top: 50%;
                left: 50%;
                margin-left: -100px;
                margin-top: -50px;
            }
        </style>
    </head>

    <body>
        <div class="row-fluid">
            <div class="span8 offset4" id="login">
                <h1>Evaluate.me</h1>
                <?php echo form_open('auth/login'); ?>
                   <input type="text" name="username" placeholder="Username" /> <br />
                   <input type="password" name="password" placeholder="Password" /> <br />
                   <input type="submit" class="btn" style="width: 220px;" value="Login" />
                </form>
            </div>
        </div>
    </body>
</html>