{% extends 'templates/fullwidth.php' %}

{% block content %}
<header>
  <div class="container">
      <div class="header-content">
          <div class="header-content-inner">
              <img src="{{baseUrl()}}/images/icon.png" height="100" alt="logo" />
              <h1 class="brand-font">Spendee</h1>
              <p>Track your spending and other aspects of your finances effortlessly.</p>
              <a href="{{urlFor('login')}}" class="btn btn-primary btn-xl">Login</a>
          </div>
      </div>
    </div>
  </header>
    <section id="services">
       <div class="container">
           <div class="row">
               <div class="col-lg-12 text-center">
                   <h2 class="section-heading">Cut your Spending Meaningfully</h2>
                   <hr class="primary">
               </div>
           </div>
       </div>
       <div class="container">
           <div class="row">
               <div class="col-lg-4 col-md-6 text-center">
                   <div class="service-box">
                       <i class="fa fa-4x fa-line-chart text-primary sr-icons"></i>
                       <h3>Track your expenses</h3>
                       <p class="text-muted">Tracking spending is important if you don't know where your money is going, you can't make intelligent choices about how to divert it for maximum benefit.
                         </p>
                   </div>
               </div>
               <div class="col-lg-4 col-md-6 text-center">
                   <div class="service-box">
                       <i class="fa fa-4x fa-pie-chart text-primary sr-icons"></i>
                       <h3>Detailed Overview</h3>
                       <p class="text-muted">With the help of detailed inforgraphics of your expenditure you will begin to notice where to make appropriate cuts, good places to shift your resources and other goals you might want to make.</p>
                   </div>
               </div>
               <div class="col-lg-4 col-md-6 text-center">
                   <div class="service-box">
                       <i class="fa fa-4x fa-cloud text-primary sr-icons"></i>
                       <h3>Cloud</h3>
                       <p class="text-muted">Instantly synchronize your transactions across all your devices and you will never lose your data again. </p>
                   </div>
               </div>
           </div>
       </div>
   </section>
   <section class="bg-primary">
    <div class="container">
      <div class="row">
          <div class="col-lg-8 col-lg-offset-2 text-center">
              <h2 class="section-heading">Gorgeous Intuitive Android App!</h2>
              <hr class="light">
              <p class="text-faded">Managing your personal finances has never been easier. Quickly add transactions and view detailed overviews of transactions in a beautiful easy to use interface.</p>
          </div>
      </div>
        <div class="row app-gallery">
            <div class="col-lg-4 col-sm-6">
                <a href="images/app_main_screen.png" class="portfolio-box">
                    <img src="images/app_main_screen.png" class="img-responsive" alt="">
                </a>
            </div>
            <div class="col-lg-4 col-sm-6">
                <a href="images/app_add_inc.png" class="portfolio-box">
                    <img src="images/app_add_inc.png" class="img-responsive" alt="">
                </a>
            </div>
            <div class="col-lg-4 col-sm-6">
                <a href="images/app_graph.png" class="portfolio-box">
                    <img src="images/app_graph.png" class="img-responsive" alt="">
                </a>
            </div>
        </div>
        <aside style="margin-bottom:10px;">
             <div class="container text-center">
                 <div class="call-to-action">
                     <h2>Free Download</h2>
                     <a href="https://github.com/tawazz/Spendee-Android/releases/download/v1.0/Spendee.apk" class="btn btn-default btn-xl sr-button">Download Now!</a>
                 </div>
             </div>
         </aside>
    </div>
</section>
<section id="web-services">
   <div class="container">
       <div class="row">
           <div class="col-lg-12 text-center">
               <h2 class="section-heading">Powerfull, clean web interface!</h2>
               <hr class="primary">
           </div>
           <div class="col-lg-6 col-sm-12">
                 <img src="images/web_macbook.png" height="400" class="img-responsive" alt="">
           </div>
           <div class="col-lg-6 col-sm-12 text-center">
              <h3 class="section-heading">Know where your money is going.</h3>
             <p>
               Tracking spending is important! If you don't know where your money is going, you can't make intelligent choices about how to divert it for maximum benefit.
               The beautifully designed web interface helps you to track and analize your expenses with a record of your transactions, easy to analize inforgraphics that
               helps you to make inteligent decisions on where to cut your spending.
             </p>
               <a href="{{urlFor('login')}}" class="btn btn-primary btn-xl">Log in</a>
               <a href="{{urlFor('register')}}" class="btn btn-primary btn-xl">Register</a>
           </div>
       </div>
   </div>
 </section>

{% endblock %}
