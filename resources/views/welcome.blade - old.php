@extends('layouts.website')
@section('content')

<section class="banner_part">
    <div class="container">
        <div class="row align-content-center">
            <div class="col-lg-6 col-xl-6">
                <div class="banner_text">
                    <h1><span>Welcome</span><br>
                        VKNRL SCHOOL OF NURSING</h1>
                    <p> Please read the guidelines before filling up your online application form at the website
                        www.vknrlnursingschool.edu.in</p>
                    <a href="{{asset("VKNRL School of Nursing Prospectus 2019-20.pdf")}}" target="_blank" class="btn_1"> Download Prospectus <span class="ti-angle-right"></span> </a>
                </div>
            </div>

            <div class="col-lg-6 col-xl-6">
                <div class="box effect5">
                    <h3>Notification</h3>

                    <ol class="steps">
                        <li><a href="{{asset("Provisional Merit List 2019-20.pdf")}}" target="_blank"> Provisional Merit List 2019-20. <span class="new-tag">Result</span> </a> </li>
                        <li><a href="{{asset("Waiting List 2019-20.pdf")}}" target="_blank"> Waiting List 2019-20. <span class="new-tag">Result</span> </a> </li>
                        <li><a href="{{asset("Notice regarding change of important dates.pdf")}}" target="_blank"> Notice regarding change of important dates.</a> </li>
                        <li><a href="{{asset("Notice for OBC, MOBC candidates.pdf")}}" target="_blank"> Notice for OBC, MOBC candidates.</a> </li>
                        <li><a href="{{asset("Syllabus for entrance examination.pdf")}}" target="_blank"> Syllabus for entrance examination.</a> </li>
                        <li><a href="{{asset("Admission Notice 1.pdf")}}" target="_blank"> Information to all applicants applying for GNM Course session 2019-20 </a> </li>
                        {{-- <li><a href="{{asset_public("Advertisement of admission 2019-20.pdf")}}" target="_blank"> Online Admission for GNM Advertise link. </a> </li> --}}
                        {{-- <li><a href="#"> Online Admission for GNM Advertise link. </a> </li>
                        <li><a href="#"> Online Admission for GNM Advertise link. </a> </li>
                        <li><a href="#"> Online Admission for GNM Advertise link. </a> </li>
                        <li><a href="#"> Online Admission for GNM Advertise link. </a> </li> --}}
                        {{-- <li><a href="#"> Dice the potatoes and boil them in lightly salted water, until tender when
                                jabbed with a fork. </a> </li>
                        <li><a href="#"> Dice the potatoes and boil them in lightly salted water, until tender when
                                jabbed with a fork. </a> </li>
                        <li><a href="#"> Dice the potatoes and boil them in lightly salted water, until tender when
                                jabbed with a fork. </a> </li> --}}

                    </ol>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection