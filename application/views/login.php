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

        <meta name="viewport" content="width=device-width; initial-scale=1.0" />

        <!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
        <link type="text/css" href="bootstrap/css/bootstrap.css" rel="stylesheet" />
        <link type="text/css" href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />
        <script src="bootstrap/js/jquery-2.0.3.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
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
                <form>
                   <input type="text" placeholder="Username" /> <br />
                   <input type="password" placeholder="Password" /> <br />
                   <?php echo anchor('events','Login','class="btn" style="width: 195px;"'); ?>
                </form>
            </div>
        </div>
    </body>
</html>