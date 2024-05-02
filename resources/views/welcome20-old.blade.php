@extends('layouts.website2')
@section('content')
<style type="text/css">
    .text-info {
    color: #7b7a7a!important;
}

span.socio i {
    padding: 8px 10px;
    font-size: 15px;
}

.fa-facebook {
    background-color: #4267B2;
    color: #fff;
}

.fa-twitter {
    background-color: #55ACEE;
    color: #fff;
}
.fa-youtube {
    background-color: #c4302b;
    color: #fff;
}

</style>
    <div class="container landing_block">
        <div class="row">
            <div class="col-md-8">
                <div class="faq_block">

                    <div class="header text-center">
                        <h1> ADMISSIONS 2020 </h1>
                            <h3>TEZPUR UNIIVERSITY </h3>
                            <p>One of the most sought-after central universities in India and ranked globally for excellence in academia, 
                                Tezpur University has been catering to a wide range of courses since the past 27 years. </p>
                    
                            <div class="last-date">
                                <h4>
                                    <a target="_blank" style="color:white;"
                                        href="{{ asset("btech_last_date_extended.pdf") }}"><strong>Last Date
                                        to Apply – 31st August 2020(B.Tech)</strong></a>
                                </h4>
                                <h4 style="border-top: 1px solid white; margin-top: 5px; padding-top: 6px;">
                                    <a target="_blank" style="color:white;" href="{{asset("TUEE-Extension.pdf")}}"> Last Date to Apply – 30th June 2020(Others).</a>  
                                </h4>
                            </div>

                    
                    </div>

                    <div class="silider">
                        <div class="owl-carousel">
                            <div> <img class="img-responive" src="{{asset('slider/landing1.jpg')}}" /> </div>
                            <div> <img class="img-responive" src="{{asset('slider/landing2.jpg')}}" /> </div>
                            <div> <img class="img-responive" src="{{asset('slider/landing3.jpg')}}" /> </div>
                            <div> <img class="img-responive" src="{{asset('slider/landing4.jpg')}}" /> </div>
                        </div>
                    </div>

                   

                    
                   
                    <div class="scroll_vertical" style="background:#fff">
                        <div class="box box-default">
                            <!-- /.box-header -->
                            <div class="box-body">
                               @include('new_guidelines2')
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                </div>
            </div>
            <div class="col-md-4">

                <div class="news_updates">
                    <h2 class="title" > Latest News & Updates </h2>
                    {{-- <marquee direction="up"  scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();"> --}}
                        <ul class="news_list" style="overflow-y: scroll; max-height: 202px">
                            {{-- M.Tech programm --}}
<li><a target="_blank" href="{{asset("notifications/Rank-List-PGDT-CRG.pdf")}}">Notification for Rank list for admission to LLM, Certificate in Chinese, PGD in Hindi, Child Right and Governance, Women Studies<span class="badge badge-pill badge-danger">New</span> <span class="text-info">29-Sept-2020</span> </a></li>

                            <li><a target="_blank" href="{{asset("notifications/MTech Energy Technology List 1.pdf")}}">Common Rank List for MTech Energy Technology List. 2020-21. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">26-Sept-2020</span> </a></li>
                            <li><a target="_blank" href="{{asset("notifications/M.TECH IN FOOD ENGG AND TECHNOLOGY List 1.pdf")}}">Common Rank List for M.TECH IN FOOD ENGG AND TECHNOLOGY List. 2020-21. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">26-Sept-2020</span> </a></li>
                            <li><a target="_blank" href="{{asset("notifications/M. Tech. IT List 1.pdf")}}">Common Rank List for M. Tech. IT List. 2020-21. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">26-Sept-2020</span> </a></li>
                            <li><a target="_blank" href="{{asset("notifications/M. Tech. CSE List 1.pdf")}}">Common Rank List for M. Tech. CSE List. 2020-21. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">26-Sept-2020</span> </a></li>
                            <li><a target="_blank" href="{{asset("notifications/M. Tech in Civil Engg List 1.pdf")}}">Common Rank List for M. Tech in Civil Engg. 2020-21. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">26-Sept-2020</span> </a></li>
                            <li><a target="_blank" href="{{asset("notifications/Admission Notice -2020 (M. Tech).pdf")}}">Admission Notice for M.Tech programme. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">26-Sept-2020</span> </a></li>
                            
                            {{-- M. Com, Integrated M.Com and MTTM programme --}}
                            <li><a target="_blank" href="{{asset("notifications/MASTER IN TRAVEL AND TOURISM MANAGEMENT.pdf")}}">Common Rank List for Master in Travel And Tourism Management. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">24-Sept-2020</span> </a></li>
                            <li><a target="_blank" href="{{asset("notifications/M. COM 2020-21.pdf")}}">Common Rank List for M. COM 2020-21. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">24-Sept-2020</span> </a></li>
                            <li><a target="_blank" href="{{asset("notifications/INTEGRATED M. COM 2020-21.pdf")}}">Common Rank List for INTEGRATED M. COM 2020-21. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">24-Sept-2020</span> </a></li>
                            <li><a target="_blank" href="{{asset("notifications/Admission Notice -2020.pdf")}}">Admission Notice for M. Com, Integrated M.Com and MTTM programme. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">24-Sept-2020</span> </a></li>
                            {{-- <li><a target="_blank" href="{{asset("notifications/Notice of selected candidate -AU_20.pdf")}}">Provisionally selected candidates for admission to the Ph.D. programme in the Autumn Semester, 2020-21. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">18-Sept-2020</span> </a></li>
                            <li><a target="_blank" href="http://www.tezu.ernet.in/">Notification for Integrated M.Sc and Integrated B.Sc B.Ed applicant to submit the required documents through the Google Form by 16-09-2020. Ignore if already submitted. For more details please visit - <span class="badge badge-pill badge-info">http://www.tezu.ernet.in</span>. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">12-Sept-2020</span> </a></li>
                            <li><a target="_blank" href="{{asset("notifications/Notification Integrated MSc in Life Sciences.pdf")}}"
                                style="background: red; padding:2px; color:white !important;"
                                >Notification for Notification Integrated MSc in Life Sciences regarding documents uploads.<span class="badge badge-pill badge-info">New</span> <span class="text-default">14-Sept-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("notifications/PhD-Admission-Notice Autumn-2020.pdf")}}">PhD admission notice autumn-2020.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">12-Sept-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("notifications/List of of provisionally selected candidates for PhD adm.pdf")}}">List of of provisionally selected candidates for PhD admission.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">12-Sept-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("notifications/btech-doc-upload-notification-2.pdf")}}"
                                style="background: red; padding:2px; color:white !important;"
                                >Notification for B.Tech students regarding documents uploads.<span class="badge badge-pill badge-info">Updated</span> <span class="text-default">02-Sept-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("notifications/MTech (GATE) admission notification.pdf")}}">Notification for admission to M. Tech. programme.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">04-Aug-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("notifications/GATE LIST ME.pdf")}}">Provisionally selected for admission/ waitlisted candidates for M.Tech in Mechanical Engineering.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">04-Aug-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("notifications/GATE LIST IT.pdf")}}">Provisionally selected for admission/ waitlisted candidates for M.Tech in Information Technology.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">04-Aug-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("notifications/GATE LIST FET.pdf")}}">Provisionally selected for admission/ waitlisted candidates for M.Tech in Food Engineering and Technology.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">04-Aug-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("notifications/GATE LIST Energy.pdf")}}">Provisionally selected for admission/ waitlisted candidates for M.Tech in Energy Technology.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">04-Aug-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("notifications/GATE LIST ELDT.pdf")}}">Provisionally selected for admission/ waitlisted candidates for M.Tech in Electronics Design and Technology.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">04-Aug-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("notifications/GATE LIST CSE.pdf")}}">Provisionally selected for admission/ waitlisted candidates for M.Tech in Computer Sc. & Engineering.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">04-Aug-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("notifications/GATE LIST BIO.pdf")}}">Provisionally selected for admission/ waitlisted candidates for M.Tech in Bioelectronics.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">04-Aug-2020</span></a> </li>
                            
                            <li><a target="_blank"
                                    href="{{ asset("Academic-Notice.pdf") }}" > Extension of Deadlines for Document Upload and Other Admission Related Information .<span class="badge badge-pill badge-info">New</span> <span
                                        class="text-default">24-July-2020</span></a> </li>
                            <li><a target="_blank"
                                    href="{{ asset("FAQ _updated_21july2020.pdf") }}"
                                    style="background: red; padding:2px; color:white !important;"> FAQ updated.<span class="badge badge-pill badge-info">New</span> <span
                                        class="text-default">22-July-2020</span></a> </li> --}}
{{--                             <li><a target="_blank"
                                    href="{{ asset("btech_last_date_extended.pdf") }}">last date of form submission extended for B.Tech.<span
                                        class="badge badge-pill badge-danger">New</span> <span
                                        class="text-info">16-July-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("Notification regarding selection for admission2020.pdf")}}">Notification regarding selection for admission 2020.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">08-July-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("Guide for B.Tech application form 2020.pdf")}}" style="background: red; padding:2px; color:white !important;"> Guide for filling B.Tech
                                    application form 2020.<span class="badge badge-pill badge-info">New</span> <span class="text-default">03-July-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("TUEE-Extension.pdf")}}"> Last date for submission of online
                            applications for admission to all programmes of study other than B. Tech programme,
                            has been further extended from 15.06.2020 to 30th June 2020. <span class="badge badge-pill badge-danger">New</span> <span class="text-info">16-June-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("Integrated and PhD exam.pdf")}}"> Notification regarding Entrance Examination.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">28-May-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("Withdrawal of admission process of MSC in MBBT from TUEE -2020.pdf")}}"> Withdrawal of admission to MSc in Molecular Biology and Biotechnolgy program through TUEE.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">22-May-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("Extension of dates.pdf")}}"> Due to prevailing situation across the country, the TUEE 2020 is postponed till further order. The deadline of application is extended to 15th June 2020 for All programmes (except B.Tech) & 15th July 2020 for B.Tech Programmes.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">19-May-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("undertaking.pdf")}}"> Format of Undertaking document in Lieu of Caste / PWD / Wards-Widows of Defence Personnel Certificate.<span class="badge badge-pill badge-danger">New</span> <span class="text-info">11-May-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("Extension_Notification_TUEE2020.pdf")}}"> Due to prevailing situation across the country, the TUEE 2020 is postponed till further order. The deadline of application is extended to 20th May 2020. <span class="text-info">21-April-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("Certificate in Chinese_ModelQ_TUEE2020.pdf")}}"> Model questions for Certificate in Chinese. <span class="text-info">18-April-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("Syll_Model Q_MA in Assamese.pdf")}}"> Syllabus and model questions for MA in Assamese. <span class="text-info">11-April-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset("Syll_Model Q_Engineering_TUEE2020.pdf")}}"> Syllabus and model questions for different programmes under School of Engineering.</a> </li>
                            <li><a target="_blank" href="{{asset("Syll_Model Q_Management_TUEE2020.pdf")}}"> Syllabus and model questions for different programmes under School of Management Sciences. </a> </li>
                            <li><a target="_blank" href="{{asset("Syll_Model Q_HSS_TUEE2020.pdf")}}"> Syllabus and model questions for different programmes under School of Humanities and Social Sciences. </a> </li>
                            <li><a target="_blank" href="{{asset("Notification-27032020.pdf")}}"> Last date of Online submission of application is extended to 30-April-2020 due to COVID-19 lockdown.</a> </li>
                            <li><a target="_blank" href="{{asset("Syllabus_Science_TUEE2020.pdf")}}"> Syllabus and Model question for School of Science.</a> </li>
                            <li><a href="#"> Online submission of application for B.Tech (North East quota)
                            is now open.</a> </li>
                            <li><a target="_blank" href="{{asset('Final Prospectus _final 03032020.pdf')}}"> Prospectus 2020 <span class="badge badge-pill badge-danger">Updated</span> <span class="text-info">04-March-2020</span></a> </li>
                            <li><a target="_blank" href="{{asset('Entrance Exam_Schedule_2020c.pdf')}}"> New Examination Schedule <span class="badge badge-pill badge-danger">Updated</span> <span class="text-info">04-March-2020</span> </a> </li>
                            <li><a target="_blank" href="{{asset('TUEE2020_03Feb2020_final.pdf')}}"> Admission Notice 2020 </a> </li>
                            <li><a target="_blank" href="{{asset('exam_centers')}}" class=""> Examination Centers </a> </li>
                            <li><a target="_blank" href="{{asset('Tezpur-University-Admissions-guidelines.pdf')}}"> Admission Guidelines 2020 </a> </li>
 --}}                            
                        </ul>
                    {{-- </marquee> --}}
                {{-- <p>
                    <i><h6 class="text-center">Disclaimer : The eligibility criteria shown against few programmes is
                        subject to ratification.</h6></i>
                </p> --}}
            </div>
                <div class="news_updates" style="margin-top: 20px; position: relative; overflow: unset;">
                    <h2 class="title" style="position: absolute ; z-index: 999; "> Contact </h2>


                    <div class="contact-block">
                    <span> Helpline : <i class="fa fa-phone 2x"></i> <a href=""> {{env("CONTACT_NO", "+91 9999999999")}} </a> </span>
                    <span> General Queries : <i class="fa fa-phone 2x"></i> <a href=""> {{env("GEN_QUERY", "+91 9999999999")}} </a> </span>
                    <br/>
                        <span> Mail <i class="fa fa-envelope-o 2x"></i> <a href="mailto:tuee2020@gmail.com">  {{env("CONTACT_EMAIL")}} </a> </span>
                    </div>

                        <p>
                            Please Note: Our landline numbers are available from 10.00 AM to 4.00 PM (Monday to Friday)
                        </p>
                
            </div>


            <div class="news_updates" style="margin-top: 20px; position: relative; overflow: unset;">
                <h2 class="title" style="position: absolute ; z-index: 999; "> Follow us on Social Media </h2>


                <div class="contact-block text-center">
                    <span class="socio"> <a target="_blank" href="https://www.facebook.com/TezpurUniversity/"> <i class="fa fa-facebook 2x"></i>  </a> </span>
                    <span class="socio"> <a target="_blank" href="https://twitter.com/TezpurUniv"> <i class="fa fa-twitter 2x"> </i> </a> </span>
                    <span class="socio"> <a target="_blank" href="https://www.youtube.com/c/TezpurUniversity94"> <i class="fa fa-youtube 2x"> </i> </a> </span>
                </div>

        
            
        </div>

            
            
        </div>
    </div>
@endsection