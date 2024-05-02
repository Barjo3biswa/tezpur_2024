<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> {{env("APP_NAME_2")}} </title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/bootstrap/css/style3.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
        href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">

</head>

<body>

    <header id="header" style="position:relative;">
        <div class="container">


            <div class="top-logo-part">
                <div class="row">
                    <div class="col-md-12 main-logo text-left" style="margin-top:0px;">
                        <a class="navbar-brand" href="{{ url("/") }}"> <img
                                src="vendor/bootstrap/images/logo.png" alt="logo"></a>
                    </div>

                    <!--         <div class="col-md-10 call-no text-right">
<span> For Information  <a href=""> +91 99999 99999 </a> </span>
        </div> -->

                </div>
            </div>

        </div>
    </header>

    <section class="content_block">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="sub_header">
                        <h1> Archive  - News & Updates  </h1>
                    </div>

                    <div class="main_text">
                        @php
                        use App\NewsAndUpdate;
                        $news=NewsAndUpdate::where('is_archive',1)->orderby('id')->get();
                        // dd($news);
                        @endphp
                            <div class="news_updates">
                                <ul class="news_list">

                                    
                        
                                    <li><a target="_blank" href="{{asset("notifications/2022/phd_physics-spring.pdf")}}">List of selected candidates for PhD admission in Physics, Spring Semester, 2021-2022 &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">11-Mar-2022</span> </a></li>


                                    <li><a target="_blank" href="{{asset("notifications/2022/notice_candidate-phd-SP_22.pdf")}}">Notification for admission to the Ph.D. programme in the Spring Semester, 2021-2022 &nbsp;<span class="text-info date">04-Mar-2022</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2022/admission_notice_spring-session-updtd2.pdf")}}">Admission Notice for Ph.D. (Spring Semester) Programme- 2022 &nbsp;<span class="text-info date">05-Jan-2022</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/time_slot-2nd_counselling.pdf")}}">Time-Slot for 2nd online counselling and admission on 26/11/2021 B. Tech programme 2021 under NE quota at School of Engineering, Tezpur University &nbsp;<span class="text-info date">24-Nov-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/undertaking_for_admission_pass_appearing_mba-22-23.pdf")}}">Undertaking for passed/appeared candidates for MBA Programme &nbsp;<span class="text-info date">30-Oct-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/AICTE-ADF.pdf")}}">List of provisionally selected candidates for admission to PhD programmes under AICTE Doctoral Fellowship (ADF) Scheme &nbsp;<span class="text-info date">30-Oct-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/admission_announcent_MBA_2022_23.pdf")}}">Online applications are invited from the eligible candidates for admission to the MBA programme for the academic year 2022-23 &nbsp;<span class="text-info date">28-Oct-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Time-Slot-for-SC-and-ST-Category_B_TechOctober_11_2021.pdf")}}">Time slot for counseling for B.Tech for 11th October, 2021 at SoE TU &nbsp;<span class="text-info date">09-Oct-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/timeslot-for-oct-8-9-2021.pdf")}}">Time-Slot for Oct 8,9, 2021 for Online counselling and admission into B.Tech programme 2021 at SoE TU &nbsp;<span class="text-info date">07-Oct-2021</span> </a></li>  
                      
                                     
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Advertisement_07102021.pdf")}}">Admission notification : M. Tech. in Energy Technology (GATE qualified)&nbsp;<span class="text-info date">07-Oct-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/time_slot-btech-admn.pdf")}}">Time Slot for Oct 6, 2021 for Online counselling and admission into B.Tech programme 2021 at SoE TU &nbsp;<span class="text-info date">04-Oct-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/short_listed-candidates_counseling-btech.pdf")}}">Open Short-listed Candidates  for Counseling and admission into B.Tech programme at Tezpur University_2021 &nbsp;<span class="text-info date">01-Oct-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/info_guidelines_admission-btech.pdf")}}">Info and guideline for B.Tech admission &nbsp;<span class="text-info date">01-Oct-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Expanded_listMA_English_PI.pdf")}}">Expanded List of MA English candidates invited for PI &nbsp;<span class="text-info date">28-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/URGENT_Incomplete_Applications_for_B_Tech_Programme.pdf")}}">URGENT:: B.TECH PROGRAMME APPLICANTS 2021 &nbsp;<span class="text-info date">27-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/B_Tech_applicants.pdf")}}">Important Notice for B.Tech. Applicants &nbsp;<span class="text-info date">24-Sep-2021</span> </a></li>
                      
                                      <li><a target="_blank" href="{{asset("notifications/2021/Admission_Notification_with_rank_list_23.09.2021.pdf")}}">Admission Notification for Common Rank List for admission to academic year 2021-22 &nbsp;<span class="text-info date">23-Sep-2021</span> </a></li>
                                    <!-- <li><a target="_blank" href="{{asset("notifications/2021/CRL_BScBEd_chemistry.pdf")}}">Integrated B.Sc. B.Ed. In Chemistry &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">23-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/CRL_IntegratedMSc_Chemistry.pdf")}}">Integrated M.Sc. In Chemistry &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">23-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/CRL_IntegratedMA_English.pdf")}}">Integrated MA in English &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">23-Sep-2021</span> </a></li> 
                                    
                                    <li><a target="_blank" href="{{asset("notifications/2021/CRL_BScBEd_Mathematics.pdf")}}">Integrated B.Sc.B.Ed. in Mathematics &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">23-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/CRL_Int_MSc_Mathematics.pdf")}}">Integrated M.Sc. In Mathematics &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">23-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/CRL_IntegratedMSc_LifeScience.pdf")}}">Integrated M.Sc. In Life Science &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">23-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/CRL_Integrated_MCom.pdf")}}">Integrated M.COM &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">23-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/CRL_PG_Diploma_Womens_studies.pdf")}}">Post Graduate Diploma in Women Studies &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">23-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Merit_list_MBBT_Based_on_GAT-B.pdf")}}">M. Sc. in Molecular Biology and Biotechnology &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">23-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/CRL_PhD_Rank_List_Commerce.pdf")}}">Ph.D In Commerce &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">23-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/PhD_Rank_List_Civil_Engg.pdf")}}">PhD in Civil Engineering &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">23-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/PhD_Rank_list_Electrical_Engineering.pdf")}}">PhD in Electrical Engineering &nbsp;<span class="badge badge-danger blink">New</span><span class="text-info date">23-Sep-2021</span> </a></li> -->
                      
                                      <li><a target="_blank" href="{{asset("notifications/2021/LIST-OF-PI-COMM.pdf")}}">Shortlisted Candidates for Personal Interview for admission into the
                                            Ph.D. Programme in Commerce, Autumn Semester 2021 &nbsp;<span class="text-info date">15-Sep-2021</span> </a></li>
                                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/List_Phd_PI_students_EL.pdf")}}">List of Shortlisted candidates for Ph. D entrance in to the Department of Electronics and Communication Engineering &nbsp;<span class="text-info date">14-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Notification_PhD_PI_Physics_AppSc.pdf")}}">List of candidates and notification for written test and personal interview for admission in Ph.D. in Physics in the Department of Applied Sciences &nbsp;<span class="text-info date">14-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Notification_PhD_PI_Chemical_Sciences_AppSc.pdf")}}">List of candidates and notification for written test and personal interview for admission in Ph.D. in Chemical Sciences in the Department of Applied Sciences &nbsp;<span class="text-info date">14-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Notification_PhD_PI_Mathematical_Sciences_AppSc.pdf")}}">List of candidates and notification for written test and personal interview for admission in Ph.D. in Mathematical Sciences in the Department of Applied Sciences &nbsp;<span class="text-info date">14-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Shortlist_candidates_PI_PhD_MBBT.pdf")}}">List of candidates for personal interview for admission in Ph.D. in MBBT&nbsp;<span class="text-info date">14-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Notification-PhD Selection-Aut-21_physics.pdf")}}">List of candidates and notification for written test for admission in Ph.D. in Physics&nbsp;<span class="text-info date">13-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Notification_PI_PhD_Hindi.pdf")}}">Notification on Personal Interview for Admission in Ph.D. in Hindi, Autumn Semester 2021&nbsp;<span class="text-info date">13-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/shorlisted_candidates_PI_PhD_admission_chemical.pdf")}}">List of candidates and notification for personal interview for PhD in Chemical Sciences &nbsp;<span class="text-info date">13-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/PhD_entrance_CE_dept.pdf")}}">List of candidates and notification for personal interview for PhD in Civil Engineering &nbsp;<span class="text-info date">13-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/PhD_FET-pi-2021.pdf")}}">List of candidates and notification for written test and personal interview for PhD in Food Engineering and Technology &nbsp;<span class="text-info date">13-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/pi_lateral_btech-fet.pdf")}}">Notice for PI (Personal Interview) for admission into Lateral Entry to 2nd year of B.Tech in Food Engineering and Technology &nbsp;<span class="text-info date">13-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Sociology_PhD_Admissions_Autumn_11-9-2021.pdf")}}">List of candidates and notification for personal interview for PhD in Sociology &nbsp;<span class="text-info date">11-Sep-2021</span> </a></li>
                      
                                     <li><a target="_blank" href="{{asset("notifications/2021/phd_mathematical_sc_interview_announcement.pdf")}}">List of candidates for written test for Ph.D. in Mathematical Sciences &nbsp;<span class="text-info date">11-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/phd_business_admn_interview_announcement.pdf")}}">List of candidates for submitting the SOP for the PhD programme Department of Business Administration &nbsp;<span class="text-info date">11-Sep-2021</span> </a></li>
                                    
                                    <li><a target="_blank" href="{{asset("notifications/2021/notification_guidelines_PhD_mechanical_engineering.pdf")}}">Written Test Notification & Guidelines for PhD admission 2021 in Mechanical Engineering Department,TU <span class="text-info date">11-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/phdcommerce_interview_announcement.pdf")}}">List of Candidates shortlisted for submission of SOP and PI for the Ph. D Programme (Autumn 2021) in the Department of Commerce <span class="text-info date">11-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/phdmcj_interview_announcement.pdf")}}">List of candidates and notification for personal interview for PhD in MCJ &nbsp;<span class="text-info date">11-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/guidelines_tentative_admission-GAT-B_2021.pdf")}}">Guidelines and tentative schedule of activities regarding admissions to DBT supported Post Graduate (PG) Programmes in Biotechnology/allied areas, in academic session 2021-23, under Graduate Aptitude Test-Biotechnology (GAT-B) 2021.<span class="text-info date">11-Sep-2021</span> </a></li>
                      
                                    <li><a href="javascript:void(0)">Last date for submission of application for MSc MBBT is extended upto 20.09.2021 <span class="text-info date">11-September-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/PhD_in_Multidisciplinary_Studies.pdf")}}">Admission Announcement for Ph.D. in Multidisciplinary Research <span class="text-info date">06-Sep-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/admission_mscmbbt_programme.pdf")}}">Admission notification for MSc in Molecular Biology and Biotechnology (Session 2021-2023) <span class="text-info date">31-Aug-2021</span> </a></li>
                      
                                    <li><a href="javascript:void(0)"> Last date for submission of online application forms is extended up to 9th September for B.Tech Programme <span class="text-info date">30-August-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Admission announcement for 2021-22_all-updated.pdf")}}">ADMISSION ANNOUNCEMENT (Academic year 2021-22) <span class="badge badge-danger blink">Updated</span>  <span class="text-info date">18-Aug-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/admission announcement_18082021.pdf")}}"> Admission notification for the academic year 2021-22 (Last date extended) &nbsp;<span class="text-info date">18-August-2021</span> <span class="badge badge-danger blink">Updated</span>   </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/TU-Prospectus-Autumn-2021.pdf")}}"> Prospectus for Autumn Season 2021  <span class="badge badge-danger blink">New</span> <span class="badge badge-danger blink">Updated</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/addendum_cum_clarification.pdf")}}"> Addendum cum Clarification &nbsp;<span class="text-info date">10-August-2021</span>  </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Admission announcement 2021-22_latest.pdf")}}"> Admission notification for the academic year 2021-22 (Last date extended) &nbsp; <span class="badge badge-danger blink">Updated</span>  </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Guideline_Online_Application_filling.pdf")}}">Guideline Online application filling <span class="text-info date">04-Aug-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Admission announcement for 2021-22_all.pdf")}}">ADMISSION ANNOUNCEMENT (Academic year 2021-22)  </a></li>
                                    
                                    <li><a target="_blank" href="{{asset("notifications/2021/infn_gdln-btech.pdf")}}"> Information and Guidelines for B.Tech applicants <span class="text-info date">22-July-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/demo_app-filling-2021.pdf")}}"> Demo for filling Application for B.Tech Programme 2021 <span class="text-info date">22-July-2021</span> </a></li>
                                    
                                    <li><a target="_blank" href="{{asset("notifications/2021/Area_of_research_PhD_Mechanical_Engineering_updated10.07.2021.pdf")}}"> Notification for PhD programme in Mechanical Engineering  <span class="text-info date">10-July-2021</span> </a></li>
                                    
                      
                                    <li><a href="javascript:void(0)"> Applications are invited for Ph.D admission in Applied Sciences - Chemistry/Physics/ Mathematics <span class="text-info date">06-July-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/Admission announcement 2021-22_latest-old.pdf")}}"> Admission notification for the academic year 2021-22 &nbsp;<span class="text-info date">06-July-2021</span>   </a></li>
                                    
                                    <li><a target="_blank" href="{{asset("notifications/2021/undertaking_for_admission_2021-22_pass_appearing.pdf")}}"> Undertaking for passed/appeared candidates  <span class="text-info date">06-July-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/undertaking-category_2021_22.pdf")}}"> Undertaking format for Category/ PRC <span class="text-info date">06-July-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/M.Des admission notification.pdf")}}"> Notification for admission to Master of Design (M.Des.) for the session 2021-22  <span class="text-info date">02-July-2021</span> </a></li>
                      
                                    <li><a target="_blank" href="{{asset("notifications/2021/undertaking.pdf")}}"> Undertaking format   <span class="text-info date">01-May-2021</span> </a></li>
                                    <li><a target="_blank" href="{{asset("notifications/2021/DoD TU M.Des Admission Poster.pdf")}}"> Important Dates for M.Des   <span class="text-info date">01-May-2021</span> </a></li>
                                    <li><a target="_blank" href="{{asset("notifications/2021/DoD TU M.Des Admission Brochure Final 30-4-21.pdf")}}"> M.Des. 2021 Admission Brochure   <span class="text-info date">01-May-2021</span> </a></li>
                                    <li><a target="_blank" href="{{asset("notifications/2021/Admission notice for M.Des.pdf")}}"> Admission Notice for M.Des.   <span class="text-info date">01-May-2021</span> </a></li>
                	                <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Notification for admission to MBA programme.pdf"> Registration now open for MBA-2021  <span class="text-info date">21-Apr-2021</span> </a></li>



					                <li> <span class="a-span"><span style="padding-left: 33px; ">
					                  Notification for candidates regarding PI for PhD admission(Spring semester,2021)/shortlisted candidates may refer to the Tezpur University website: <a style="display: inline-block; padding-left: 10px" target="_blank" href="http://tezu.ernet.in/"><u>www.tezu.ernet.in</u></a> 

					                  <span class="text-info date">09-Feb-2021</span></li>

					                <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Ph.d. Notification.pdf"> Ph.D. Admission date extended   <span class="text-info date">28-Jan-2021</span> </a></li>

					                <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Admission Notice (Spring Semester) Phd Programme 2021.pdf"> Admission Notice for Ph.D. (Spring Semester) Programme- 2021 <span class="badge badge-pill badge-primary">Updated</span> <span class="text-info date">27-Jan-2021</span> </a></li>

					                <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Prospectus_Spring_PHD_2021_FINAL_08012021.pdf"> Prospectus for Ph.D. Programme Spring 2021  <span class="text-info date">08-Jan-2021</span> </a></li>    

                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Branch-Preferences and Withdrawal-of-Seat Information of Newly Admitted BTech students, till 14-Nov 2020.pdf"> Branch-Preferences and Withdrawal-of-Seat Information of Newly Admitted BTech students, till 14-Nov 2020.  <span class="text-info date">25-Nov-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Notification for already admitted students into BTech programme 2020.pdf"> Notification for already admitted students into BTech programme 2020. <span class="text-info date">25-Nov-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Online counselling and admission into BTech programme 2020 for seats falling vacant.pdf"> Online counselling and admission into BTech programme 2020 for seats falling vacant.   <span class="text-info date">25-Nov-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/3nd Waiting list_16112020.pdf"> 3rd waiting list for M.Sc. in MBBT programme.  <span class="text-info date">17-Nov-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Sports quota list.pdf"> Sports quota list for various programmes.  <span class="text-info date">12-Nov-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Waiting list_Modified M. Sc. in MBBT.pdf"> Waiting list candidates for admission into MSc. in MBBT through GAT-B.  <span class="text-info date">09-Nov-2020</span> </a></li>
                                    <li><a target="_blank" href="public/notifications/Notice_of_selected_candidate_-AU_20__4_.pdf"> Notification for PHD candidates.  <span class="text-info date">31-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Notification for admission to MBA programme.pdf"> Notification for admission to MBA programme  <span class="text-info date">30-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/GAT-B admission Notice.pdf"> Notice for admission to MSc in Molecular Biology and Biotechnology through GAT-B  <span class="text-info date">21-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/Prospectus 2021-MBA.pdf"> Prospectus 2021 M.B.A.  <span class="text-info date">21-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/phd-english-list.pdf"> List of provisionally selected candidates for admission to PhD programme.  <span class="text-info date">14-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/SC and ST catagory_Time Slot for Online counselling and admission into BTech programme 2020 at SoE TU on October 12 2020.pdf"> SC and ST Category Time Slot for Online counselling and admission into BTech programme 2020 at SoE TU on October 12 2020.  <span class="text-info date">10-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Notification EVS withdrawan.pdf"> Notification EVS withdrawn.  <span class="text-info date">08-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Updated EWS and OBC-NCL catagory_Time Slot for Online counselling and admission into BTech programme 2020 at SoE TU on October 9 2020.pdf"> EWS and OBC-NCL category Time Slot for Online counselling and admission into BTech programme 2020 at SoE TU on October 9 2020. <span class="badge badge-pill badge-primary">Updated</span> <span class="text-info date">08-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Notice for BTech  EWS and OBC-NCL Catagory students on Oct 9 2020.pdf"> Notice for BTech  EWS and OBC-NCL Catagory students.  <span class="text-info date">07-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Notification MCA and B.Sc.BEd Math.pdf"> Notification for MCA and B.Sc.BEd Math.  <span class="text-info date">07-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/MCA  List  1.pdf"> Common rank list 1 for MCA.  <span class="text-info date">07-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/B.Sc.B.Ed. in Mathematics List 1.pdf"> Common Rank list 1 for B.Sc.B.Ed. in Mathematics.  <span class="text-info date">06-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Payment Options for B.Tech admission at SoE TU.pdf"> Payment Options for B.Tech admission at School of Engineering TU.  <span class="text-info date">06-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Information_and_Guidelines_BTech_updated.pdf"> Information and Guidelines BTech updated.  <span class="text-info date">05-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Time Slot for Online counselling and admission into BTech programme 2020 at SoE TU on October 7 2020.pdf">Time Slot for Online counselling and admission into BTech programme 2020 at SoE TU on October 7 2020.  <span class="text-info date">05-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Notification MSC Physics.pdf">Notification for MSC Physics candidates.  <span class="text-info date">05-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/M.Tech+non-GATE_ME_Final.pdf">Provisionally selected candidates for admission to M.Tech in Mechanical Engineering (non-GATE)  <span class="text-info date">05-Oct-2020</span> </a></li>
                                    <li><a href="#">Admissions for different programs under Tezpur University for the Academic Session 2020-21, first the OPEN category student would be admitted followed by rest of the OTHER categories as per selection list. <span class="badge badge-pill badge-warning">Information</span> <span class="text-info date">03-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/MSc_Mathematics List  1.pdf">MSc_Mathematics List  1 <span class="text-info date">02-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/MA in Education.pdf">MA in Education.pdf <span class="text-info date">02-Oct-2020</span> </a></li>
                                    
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Integrated MA in English List  1.pdf">Integrated MA in English List  1 <span class="text-info date">02-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Integrated M. Sc. in Life Science List  1.pdf">Integrated M. Sc. in Life Science List  1 <span class="text-info date">02-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Int-MSc-Physics  List  1.pdf">Int-MSc-Physics  List  1 <span class="text-info date">02-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/INT-MSc in MATH Rank list.pdf">INT-MSc in MATH Rank list.pdf <span class="text-info date">02-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/BSc-BEd Physics List  1.pdf">BSc-BEd Physics List  1 <span class="text-info date">02-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Admission Notice -2020 Intg -MSc, BScBEd, MSc.pdf">Admission Notice -2020 Intg -MSc, BScBEd, MSc <span class="text-info date">02-Oct-2020</span> </a></li>
                    
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Notification for Admission into the BTech 2020-updated.pdf">Notification for Admission into the BTech 2020<span class="badge badge-pill badge-primary">Updated</span> <span class="text-info date">02-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Information_and_Guidelines_BTech.pdf">Information and Guidelines BTech<span class="badge badge-pill badge-primary">Updated</span> <span class="text-info date">02-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/B.Tech Admission_Final Merit List and Wait List.pdf">B.Tech Merit List and Wait List <span class="text-info date">01-Oct-2020</span> </a></li>
                                    
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Notification for Admission into the BTech 2020.pdf">Notification for Admission into the BTech 2020 <span class="text-info date">01-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Bioelectronics List 1.pdf">Common Rank List for Bioelectronics List 1 <span class="text-info date">30-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/ELDT List 1.pdf">Common Rank List for M.Tech in Electronics Design and Technology List 1 <span class="text-info date">30-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Integrated B.Sc. BEd. Chemistry List 1.pdf">Common Rank List for Integrated B.Sc. BEd. Chemistry List 1 <span class="text-info date">30-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Intg._Msc._Chemistry List 1 (1).pdf">Common Rank List for Intg._Msc._Chemistry List 1 <span class="text-info date">30-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/MA Hindi List 1.pdf">Common Rank List for MA Hindi List 1 <span class="text-info date">30-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/MA in Assamese.pdf">Common Rank List for MA in Assamese <span class="text-info date">30-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/MA_Cultural Studies 2020 List 1.pdf">Common Rank List for MA_Cultural Studies 2020 List 1 <span class="text-info date">30-Sept-2020</span> </a></li>
                    
                                    
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/MSc Chemistry_ 2020 List 1.pdf">MSc Chemistry_ 2020 List 1 <span class="text-info date">30-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Admission Notice -2020 (M. Tech -MA-MSc.pdf">Admission Notice 2020 for (M.Tech, MA, MSC) <span class="text-info date">30-Sept-2020</span> </a></li>
                    
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/PGDT Hindi List - 1.pdf"> Common Rank List 1 for P G Diploma Translation in Hindi.  <span class="text-info date">01-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/PGDIPLOMA IN CRG List 1.pdf"> Common Rank List 1 for Post Graduate Diploma in Child Rights &amp; Governance <span class="text-info date">01-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/LLM List 1.pdf"> Common Rank List 1 for LLM <span class="text-info date">01-Oct-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Certificate in Chinese Lisi 1.pdf"> Common Rank List 1 for Certificate in Chinese <span class="text-info date">01-Oct-2020</span> </a></li>
                                    
                    
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Rank-List-PGDT-CRG.pdf">Notification for Rank list for admission to LLM, Certificate in Chinese, PGD in Hindi, Child Right and Governance, Women Studies <span class="text-info date">29-Sept-2020</span> </a></li>
                                    
                    
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/MTech Energy Technology List 1.pdf">Common Rank List for MTech Energy Technology List. 2020-21.  <span class="text-info date">26-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/M.TECH IN FOOD ENGG AND TECHNOLOGY List 1.pdf">Common Rank List for M.TECH IN FOOD ENGG AND TECHNOLOGY List. 2020-21.  <span class="text-info date">26-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/M. Tech. IT List 1.pdf">Common Rank List for M. Tech. IT List. 2020-21.  <span class="text-info date">26-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/M. Tech. CSE List 1.pdf">Common Rank List for M. Tech. CSE List. 2020-21.  <span class="text-info date">26-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/M. Tech in Civil Engg List 1.pdf">Common Rank List for M. Tech in Civil Engg. 2020-21.  <span class="text-info date">26-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Admission Notice -2020 (M. Tech).pdf">Admission Notice for M.Tech programme.  <span class="text-info date">26-Sept-2020</span> </a></li>
                                    
                                    
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/MASTER IN TRAVEL AND TOURISM MANAGEMENT.pdf">Common Rank List for Master in Travel And Tourism Management.  <span class="text-info date">24-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/M. COM 2020-21.pdf">Common Rank List for M. COM 2020-21.  <span class="text-info date">24-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/INTEGRATED M. COM 2020-21.pdf">Common Rank List for INTEGRATED M. COM 2020-21.  <span class="text-info date">24-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="https://www.tezuadmissions.in/public/notifications/Admission Notice -2020.pdf">Admission Notice for M. Com, Integrated M.Com and MTTM programme.  <span class="text-info date">24-Sept-2020</span> </a></li>
                    

                                    <li><a target="_blank"
                                            href="{{ asset("notifications/Notice of selected candidate -AU_20.pdf") }}">Provisionally
                                            selected candidates for admission to the Ph.D. programme in the Autumn
                                            Semester, 2020-21. <span class="badge badge-pill badge-danger">New</span>
                                            <span class="text-info">18-Sept-2020</span> </a></li>
                                    <li><a target="_blank" href="http://www.tezu.ernet.in/">Notification for Integrated
                                            M.Sc and Integrated B.Sc B.Ed applicant to submit the required documents
                                            through the Google Form by 16-09-2020. Ignore if already submitted. For more
                                            details please visit - <span
                                                class="badge badge-pill badge-info">http://www.tezu.ernet.in</span>.
                                            <span class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">12-Sept-2020</span> </a></li>
                                    <li><a target="_blank"
                                            href="{{ asset("notifications/Notification Integrated MSc in Life Sciences.pdf") }}"
                                            style="background: red; padding:2px; color:white !important;">Notification
                                            for Notification Integrated MSc in Life Sciences regarding documents
                                            uploads.<span class="badge badge-pill badge-info">New</span> <span
                                                class="text-default">14-Sept-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("notifications/PhD-Admission-Notice Autumn-2020.pdf") }}">PhD
                                            admission notice autumn-2020.<span
                                                class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">12-Sept-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("notifications/List of of provisionally selected candidates for PhD adm.pdf") }}">List
                                            of of provisionally selected candidates for PhD admission.<span
                                                class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">12-Sept-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("notifications/btech-doc-upload-notification-2.pdf") }}"
                                            style="background: red; padding:2px; color:white !important;">Notification
                                            for B.Tech students regarding documents uploads.<span
                                                class="badge badge-pill badge-info">Updated</span> <span
                                                class="text-default">02-Sept-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("notifications/MTech (GATE) admission notification.pdf") }}">Notification
                                            for admission to M. Tech. programme.<span
                                                class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">04-Aug-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("notifications/GATE LIST ME.pdf") }}">Provisionally
                                            selected for admission/ waitlisted candidates for M.Tech in Mechanical
                                            Engineering.<span class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">04-Aug-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("notifications/GATE LIST IT.pdf") }}">Provisionally
                                            selected for admission/ waitlisted candidates for M.Tech in Information
                                            Technology.<span class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">04-Aug-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("notifications/GATE LIST FET.pdf") }}">Provisionally
                                            selected for admission/ waitlisted candidates for M.Tech in Food Engineering
                                            and Technology.<span class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">04-Aug-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("notifications/GATE LIST Energy.pdf") }}">Provisionally
                                            selected for admission/ waitlisted candidates for M.Tech in Energy
                                            Technology.<span class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">04-Aug-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("notifications/GATE LIST ELDT.pdf") }}">Provisionally
                                            selected for admission/ waitlisted candidates for M.Tech in Electronics
                                            Design and Technology.<span class="badge badge-pill badge-danger">New</span>
                                            <span class="text-info">04-Aug-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("notifications/GATE LIST CSE.pdf") }}">Provisionally
                                            selected for admission/ waitlisted candidates for M.Tech in Computer Sc. &
                                            Engineering.<span class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">04-Aug-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("notifications/GATE LIST BIO.pdf") }}">Provisionally
                                            selected for admission/ waitlisted candidates for M.Tech in
                                            Bioelectronics.<span class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">04-Aug-2020</span></a> </li>

                                    <li><a target="_blank" href="{{ asset("Academic-Notice.pdf") }}">
                                            Extension of Deadlines for Document Upload and Other Admission Related
                                            Information .<span class="badge badge-pill badge-info">New</span> <span
                                                class="text-default">24-July-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("FAQ _updated_21july2020.pdf") }}"
                                            style="background: red; padding:2px; color:white !important;"> FAQ
                                            updated.<span class="badge badge-pill badge-info">New</span> <span
                                                class="text-default">22-July-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("btech_last_date_extended.pdf") }}">last
                                            date of form submission extended for B.Tech.<span
                                                class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">16-July-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Notification regarding selection for admission2020.pdf") }}">Notification
                                            regarding selection for admission 2020.<span
                                                class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">08-July-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Guide for B.Tech application form 2020.pdf") }}"
                                            style="background: red; padding:2px; color:white !important;"> Guide for
                                            filling B.Tech
                                            application form 2020.<span class="badge badge-pill badge-info">New</span>
                                            <span class="text-default">03-July-2020</span></a> </li>
                                    <li><a target="_blank" href="{{ asset("TUEE-Extension.pdf") }}">
                                            Last date for submission of online
                                            applications for admission to all programmes of study other than B. Tech
                                            programme,
                                            has been further extended from 15.06.2020 to 30th June 2020. <span
                                                class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">16-June-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Integrated and PhD exam.pdf") }}">
                                            Notification regarding Entrance Examination.<span
                                                class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">28-May-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Withdrawal of admission process of MSC in MBBT from TUEE -2020.pdf") }}">
                                            Withdrawal of admission to MSc in Molecular Biology and Biotechnolgy program
                                            through TUEE.<span class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">22-May-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Extension of dates.pdf") }}"> Due to
                                            prevailing situation across the country, the TUEE 2020 is postponed till
                                            further order. The deadline of application is extended to 15th June 2020 for
                                            All programmes (except B.Tech) & 15th July 2020 for B.Tech Programmes.<span
                                                class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">19-May-2020</span></a> </li>
                                    <li><a target="_blank" href="{{ asset("undertaking.pdf") }}">
                                            Format of Undertaking document in Lieu of Caste / PWD / Wards-Widows of
                                            Defence Personnel Certificate.<span
                                                class="badge badge-pill badge-danger">New</span> <span
                                                class="text-info">11-May-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Extension_Notification_TUEE2020.pdf") }}">
                                            Due to prevailing situation across the country, the TUEE 2020 is postponed
                                            till further order. The deadline of application is extended to 20th May
                                            2020. <span class="text-info">21-April-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Certificate in Chinese_ModelQ_TUEE2020.pdf") }}">
                                            Model questions for Certificate in Chinese. <span
                                                class="text-info">18-April-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Syll_Model Q_MA in Assamese.pdf") }}">
                                            Syllabus and model questions for MA in Assamese. <span
                                                class="text-info">11-April-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Syll_Model Q_Engineering_TUEE2020.pdf") }}">
                                            Syllabus and model questions for different programmes under School of
                                            Engineering.</a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Syll_Model Q_Management_TUEE2020.pdf") }}">
                                            Syllabus and model questions for different programmes under School of
                                            Management Sciences. </a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Syll_Model Q_HSS_TUEE2020.pdf") }}">
                                            Syllabus and model questions for different programmes under School of
                                            Humanities and Social Sciences. </a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Notification-27032020.pdf") }}"> Last date
                                            of Online submission of application is extended to 30-April-2020 due to
                                            COVID-19 lockdown.</a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset("Syllabus_Science_TUEE2020.pdf") }}">
                                            Syllabus and Model question for School of Science.</a> </li>
                                    <li><a href="#"> Online submission of application for B.Tech (North East quota)
                                            is now open.</a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset('Final Prospectus _final 03032020.pdf') }}">
                                            Prospectus 2020 <span class="badge badge-pill badge-danger">Updated</span>
                                            <span class="text-info">04-March-2020</span></a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset('Entrance Exam_Schedule_2020c.pdf') }}">
                                            New Examination Schedule <span
                                                class="badge badge-pill badge-danger">Updated</span> <span
                                                class="text-info">04-March-2020</span> </a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset('TUEE2020_03Feb2020_final.pdf') }}">
                                            Admission Notice 2020 </a> </li>
                                    <li><a target="_blank" href="{{ asset('exam_centers') }}"
                                            class=""> Examination Centers </a> </li>
                                    <li><a target="_blank"
                                            href="{{ asset('Tezpur-University-Admissions-guidelines.pdf') }}">
                                            Admission Guidelines 2020 </a> </li>
                                    <hr>
                                    @foreach($news as $updates)
                                        <li><a target="_blank" href="{{asset("notifications").'/'.$updates->folder_name.'/'.$updates->file_name}}">{{$updates->heading}} &nbsp;{{-- <span class="badge badge-danger blink">{{$updates->is_new==1?"New":""}}</span> --}}<span class="text-info date">{{\Carbon\Carbon::parse($updates->date)->format('Y-M-d')}}</span> </a></li>
                                    @endforeach

                                </ul>



                            </div>


                    </div>
                </div>
    </section>

    <footer>
        <div class="container">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <ul class="footer_links">
                            <li><a href="{{ route("terms") }}">Terms & Conditions</a></li>
                            <li><a href="{{ route("privacy") }}">Privacy Policy</a></li>
                            <li><a href="{{ route("refund") }}">Refund Policy</a></li>
                            <li><a href="#">Copyright © {{ env("INSTITUTE_NAME") }} . All rights
                                    reserved</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
