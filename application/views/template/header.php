<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en" ng-app="txtapsy">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Txtapsy | <?php echo $title;?></title>

    <!-- Bootstrap core CSS -->
    <link href="public_html/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="public_html/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="public_html/css/styles.css" rel="stylesheet">
    
    <script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
    <script type="text/javascript">
         var BASE_URL = '<?php echo base_url(); ?>';
    </script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://unpkg.com/angular-toastr/dist/angular-toastr.css" />
  </head>
<!--  -->
  <body>
	
    <!-- Begin page content -->
<!--     <div class="topbar">
        <div class="container">
            <div class="wrap-social">
                <a href="#"><img src="public_html/images/social-fb-white.png" alt=""></a>
                <a href="#"><img src="public_html/images/social-twitter-white.png" alt=""></a>
                <a href="#"><img src="public_html/images/social-insta-white.png" alt=""></a>
                <a href="#"><img src="public_html/images/social-ln-white.png" alt=""></a>
                <span class="promoted-by">Service promoted by:</span>&nbsp; <span class="address">Psychic-Contact.com: 4261 20th Ave NE, Naples, FL 34120 USA</span>
                <div class="sign_reg pull-right"><a href="#">REGISTER</a> <a href="#" class="signin">SIGN IN</a></div>
            </div>

        </div>
    </div> -->

     <?php
        foreach($this->system_vars->get_pages('banner') as $p){
         
           if($p['url'] == 'banner') {
               echo html_entity_decode($p['content']);
           }
        }   
    ?>


    <div class="container">
        <!-- Static navbar -->
    <nav class="navbar navbar-default nav-main">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="public_html/images/logo.png" alt=""></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                      foreach($this->system_vars->get_pages('footer') as $p){
                        echo "<li><a href=\"/{$p['url']}\">{$p['title']}</a></li>";
                      }   
                    ?>
                    <li>
                        <form class="form-inline">
                            <div class="form-group wrap-search">
                                <div class="input-group  search-group">
                                    <input type="text" class="form-control" placeholder="Search">
                                  <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><img src="public_html/images/icon-search.png" alt=""></button>
                                  </span>
                                </div><!-- /input-group -->
                            </div>
                        </form>
                    </li>
                </ul>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>
</div>