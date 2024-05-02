<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Tezpur University</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <meta name="format-detection" content="telephone=no" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/icofont/icofont.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/venobox/venobox.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/line-awesome/css/line-awesome.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/owl.carousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/aos/aos.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <style>

.close {
    float: right;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
    position: absolute !important;
    right: 19px !important;
}
    </style>
  
</head>
<body>


  <!-- ======= Hero Section ======= -->
  <section id="hero">
    <div class="hero-container" data-aos="fade-up">
        {{-- <div class="logo-wrap">
          <a href="index.html"><img src="{{asset('assets/img/logo.png')}}" alt="Tezpur University" class="img-fluid"></a>
        </div>
        <h1>Tezpur University </h1>
        <h2>Admissions 2021</h2> --}}
    </div>
  </section><!-- End Hero -->

  <main id="main">

  <section class="guideline-box">
    <div class="container-fluid">
      <div class="row">

        <div class="col-md-2 no-guttter">

        </div>


        <div class="col-md-8 no-guttter">
          <div class="box-wrap">
          <div class="box">


              <h3 class="text-center"> How To Apply </h3> 
              <br/>
              
              <div class="guidline-text text-center">
             
              <h3><strong>STEP 1 – New Registrations</strong></h3>
              <p>Click ‘New Registration’ <br>
              Read guidelines carefully and click proceed <br>
              Register and verify account with Mobile OTP </p>
              <p>Note: <span class="note">Phone number &amp; email address used during registration must belong to the applicant. 
              Communications will only be sent to registered phone number or email address. </span></p>
               
              <h3><strong>STEP 2 – Submitting Online Applications </strong></h3>
              <p>
              Login to your <strong>TEZU ADMISSION </strong> dashboard  <br>
              Click ‘New Application’ <br>
              Select course <br>
              Check Eligibility  <br>
              Proceed with Application process  <br>
              Carefully fill-out all details  <br> 
              Upload necessary self-attested documents <br>
              Pay Application fee using online payment gateway  <br>
              Submit Application <br>
              </p>
              <p>Note: <span class="note">Take print-out of the application form. Students are required to carry it along with original 
              documents at the time of admission. </span></p>
              </div>                            
              </div>
            </div>
            </div>

            

            <div class="col-md-2 no-guttter">

          </div>
        </div>  
   </section>


   
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">


          <div class="col-md-6 footer-links">
            
            <ul>
              <li><a href="#">Terms & Conditions</a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Refund Policy</a></li>
            </ul>
          </div>

        <div class="col-md-6">
          <div class="copyright text-right">
        &copy; Copyright <strong><span>Tezpur University</span></strong>. All Rights Reserved
      </div>
    </div>

        

        </div>
      </div>
    </div>

   <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="color:#000">Registration Guidelines</h4>
      </div>
      <div class="modal-body">
      <video width="770px" controls>
        <source src="{{asset('20210306_163230.mp4')}}" type="video/mp4" />
        
    </video>

      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>

  </footer><!-- End Footer -->



  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{asset('assets/vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/jquery.easing/jquery.easing.min.js')}}"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('assets/vendor/waypoints/jquery.waypoints.min.js')}}"></script>
  <script src="{{asset('assets/vendor/counterup/counterup.min.js')}}"></script>
  <script src="{{asset('assets/vendor/venobox/venobox.min.js')}}"></script>
  <script src="{{asset('assets/vendor/owl.carousel/owl.carousel.min.js')}}"></script>
  <script src="{{asset('assets/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
  <script src="{{asset('assets/vendor/aos/aos.js')}}"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('assets/js/main.js')}}"></script>
  <script>
      $('#video_guide').click(function(){
          $('#myModal').modal('show');
      })
  </script>
</body>

</html>