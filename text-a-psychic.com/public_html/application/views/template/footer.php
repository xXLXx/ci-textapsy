<div class="articles">
        <div class="container">
            <h2 class="text-center">Articles</h2>
            <p class="text-center">Latest from the Blog</p>
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <img src="public_html/images/img1.jpg" alt="" class="img-responsive">
                        <div class="caption">
                            <h5 class="date">February 18</h5>
                            <h3 class="title">How to ask a question and get the most out of your reading.</h3>
                            <p>On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and...</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <img src="public_html/images/img2.jpg" alt="" class="img-responsive">
                        <div class="caption">
                            <h5 class="date">February 16</h5>
                            <h3 class="title">Find and Keeping Love</h3>
                            <p>On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and...</p>
                        </div>
                    </div>
                </div>
                <div class="clearfix visible-sm-block"></div>
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <img src="public_html/images/img3.jpg" alt="" class="img-responsive">
                        <div class="caption">
                            <h5 class="date">February 13</h5>
                            <h3 class="title">What is a Soul Mate</h3>
                            <p>On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and...</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <div class="cert">
        <div class="container text-center">
            <img src="public_html/images/cert.png" alt="">
        </div>
    </div>

   <footer class="footer">
        <div class="footer-nav">
            <div class="container">
                <div class="indenter">
                    <ul class="nav navbar-nav">
                        <?php
                          foreach($this->system_vars->get_pages('footer') as $p){
                            echo "<li><a href=\"/{$p['url']}\"  class=\"bottom_nav\">{$p['title']}</a></li>";
                          }   
                        ?>
                    </ul>
                    <div class="wrap-social">
                        <a href="#"><img src="public_html/images/social-fb.png" alt=""></a>
                        <a href="#"><img src="public_html/images/social-twitter.png" alt=""></a>
                        <a href="#"><img src="public_html/images/social-insta.png" alt=""></a>
                        <a href="#"><img src="public_html/images/social-ln.png" alt=""></a>
                    </div>
                </div>
            </div>

        </div>

      <?php
        foreach($this->system_vars->get_pages('sub-footer') as $p){
         
           if($p['url'] == 'sub-footer') {
               echo html_entity_decode($p['content']);
           }
        }   
      ?>
  

      
    </footer>


    <div id="signin-form">
        <form name="signin">
            <input type="text" name="username" class="form-control" placeholder="Username/Email">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <button type="submit" class="btn btn-default navbar-btn inline-block">Sign in</button>
            <small class="signin-text label"></small>
        </form>
    </div>


	<script type="text/javascript" src="public_html/js/jquery.min.js"></script>
    <!-- Bootstrap core JavaScript
   ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="public_html/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for   /desktop Windows 8 bug -->
    <script src="public_html/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script type="text/javascript">
        var $carousel = $('#myCarousel');
        var $carouselCaptions = $carousel.find('.item .carousel-caption');
        var $carouselImages = $carousel.find('.item img');
        var carouselTimeout;

        $carousel.on('slid', function () {
            var $item = $carousel.find('.item.active');
            carouselTimeout = setTimeout(function() { // start the delay
                carouselTimeout = false;
                $item.find('.carousel-caption').animate({'opacity': 1}, 500);
//                $item.find('img').animate({'opacity': 0.2}, 500);
            }, 2000);
        }).on('slide', function () {
                    if(carouselTimeout) { // Carousel is sliding, stop pending animation if any
                        clearTimeout(carouselTimeout);
                        carouselTimeout = false;
                    }
                    // Reset styles
                    $carouselCaptions.animate({'opacity': 0}, 500);
                    $carouselImages.animate({'opacity': 1}, 500);
                });;

        $carousel.carousel({
            interval: 1200000,
            cycle: true
        }).trigger('slid'); // Make the carousel believe that it has just been slid so that the first item gets the animation
    </script>
    
    <script src="public_html/js/script.js"></script>
    <script src="public_html/js/angular.min.js"></script>
    <script src="public_html/js/app/app.js"></script>
    <script src="public_html/js/app/constant.js"></script>
    <script src="public_html/js/app/controllers/bulletin_ctrl.js"></script>

    

  </body>
</html>