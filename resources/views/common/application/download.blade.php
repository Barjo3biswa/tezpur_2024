<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="charset=utf-8" />
  <style type="text/css">
    * { font-family: DejaVu Sans, sans-serif; }
        .bold {
            font-weight: bold;
        }
        table td, table th{
            padding: 7px;
            /* border-color: #ddd; */
        }
        table{
            /* border-color: #ddd; */
        }
        *{
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 11px;
        }
        .fa-facheck-square-o::before{
            content:"\2713"
        }
        .fa-square-o::before{
            content: '\2612';;
        }
    </style>
    </head>
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet"> --}}
    <body>
    <div class="container" id="page-content">
        <div class="row">
            <table width="100%">
                <tbody>
                    <tr class="text-center">
                        <td width="20%" class="padding-xs">
                            <img style="max-width: 80px" width="80" class="avatar avatar-xxl"
                                {{-- {{base_path('public/logo.jpg')}}  --}}
                                src="{{base_path('public/logo.png')}}">
                        </td>
                        <td class="padding-xs" style="text-align: center;">
                            <h3 class="mb-3 text-uppercase">TEZPUR UNIVERSITY</h3>
                            <p class="mb-4 bold">
                                NAPAAM, TEZPUR - 784028, ASSAM<br> <strong>APPLICATION FORM</strong><br>
                                {{$application->session->name ?? "NA"}}
                                {{-- For admission to {{$application->session->name ?? "NA"}} --}}
                            </p>
                        </td>
                        <td width="20%" class="padding-xs">
                        @if ($application->passport_photo())
                        <img width="80" style="max-width: 80px;"
                        class="avatar avatar-xxl"
                        {{-- src="{{route("common.download.image", [$application->student_id, $application->id, $application->passport_photo()->file_name])}}"> --}}
                        src="{{base_path("public/uploads/".$application->student_id."/".$application->id."/".$application->passport_photo()->file_name)}}">
                        @else
                            Passport Photo Not Uploaded
                        @endif
                        </td>
                    </tr>
                    {{-- <tr>
                        <td></td>
                        <td></td>
                        <td class="text-right" style="text-align:right;">
                                @if ($application->signature())
                                    <img  style="width: 160px; height: 60px; max-width: 160px; max-height: 60px;"
                                src="{{base_path("public/uploads/".$application->student_id."/".$application->id."/".$application->signature()->file_name)}}">
                                @else
                                    Signature Not Uploaded
                                @endif
                        </td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-xs-12">
                @include('common.application.form-information')
                <table class="table"  width="100%">
                    <tbody>
                        <tr>
                            <td>
                                &nbsp;&nbsp;&nbsp;&nbsp;I, <u>{{$application->fullname}}</u> declare that I shall abide by the Statues, Ordinances, Rules and Orders etc. of the University that will be in force from time to time. I will submit myself to the disciplinary jurisdiction of the Vice-Chancellor and the authorities of the University who may be vested with such power under the Acts, Statutes, Ordinances and the Rules that have been framed thereunder by the University.
                                <br>&nbsp;&nbsp;&nbsp;&nbsp;I also declare that the information given above is true and complete to the best of my knowledge and belief, and if any of it is found to be incorrect, my admission shall be liable to be cancelled and I shall be liable to such disciplinary action as may be decided by the University.
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right" style="text-align:right;">
                                    @if ($application->signature())
                                        <img  style="width: 160px; height: 60px; max-width: 160px; max-height: 60px;"
                                    src="{{base_path("public/uploads/".$application->student_id."/".$application->id."/".$application->signature()->file_name)}}">
                                    @else
                                        Signature Not Uploaded
                                    @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                @include("common.application.form-enclosures")
                {{-- @php
                                $url=env('APP_URL');
                                // dd($url);
                                $id=Crypt::encrypt($application->id);
                                $qr_string = $url."/qrcode/".$id;   
                                $qr_code = QrCode();
                                $qr_code
                                    ->setText($qr_string)
                                    ->setSize(300)
                                    ->setPadding(10)
                                    ->setErrorCorrection('high')
                                    ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
                                    ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
                                    // ->setLabel('Scan Qr Code')
                                    ->setLabelFontSize(16)
                                    ->setImageType(QrCode()::IMAGE_TYPE_PNG);
                            @endphp
                            {!!'<img style="width:200px;" src="data:'.$qr_code->getContentType().';base64,'.$qr_code->generate().'" />'!!}
                <div class="page-break-after" style="page-break-before: always;"></div> --}}

                @php
                    $url=env('APP_URL');
                    $id=Crypt::encrypt($application->id);
                    $qr_string = $url."/qrcode/".$id;   
                    $qr = QrCode::generate($qr_string);
                @endphp
                {!! $qr !!}
                
                @if(!isset($dontLoadAttachment))
                    @php
                        $qr_string = "Name: ". $application->fullname;   
                        $qr_string .= " App No: ". $application->application_no;   
                        $qr_string .= " DOB: ". $application->dob;   
                    @endphp
                    @if($application->attachment_others()->count())
                        @forelse ($application->attachment_others() as $attachment)
                            {{-- @php
                                $code = new \CodeItNow\BarcodeBundle\Utils\QrCode();
                                $code
                                            ->setText($qr_string)
                                            ->setSize(300)
                                            ->setPadding(10)
                                            ->setErrorCorrection('high')
                                            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
                                            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
                                            // ->setLabel('Scan Qr Code')
                                            ->setLabelFontSize(16)
                                            ->setImageType(QrCode()::IMAGE_TYPE_PNG);
                            @endphp --}}
                            
                            @if ( $attachment->doc_name!="class_x_documents" &&  $attachment->doc_name!="class_XII_documents" && $attachment->doc_name!="graduation_documents" && $attachment->doc_name!="post_graduation_documents"
                               && $attachment->doc_name!="class_x_grade_conversion" &&  $attachment->doc_name!="class_XII_grade_conversion" && $attachment->doc_name!="graduation_grade_conversion" && $attachment->doc_name!="post_graduation_grade_conversion")
                               <div class="page-break-after" style="page-break-before: always;"></div>
                                <div class="card-body">
                                    <table width="100%" style="margin-bottom:20px;">
                                        <tbody>  
                                            <tr>
                                                <td align="left">Application No: <b>{{$application->application_no}}</b></td>
                                                <td align="center">Document Name: <b>{{ucwords(str_replace("_", " ", $attachment->doc_name))}}</b></td>
                                                {{-- <td align="right">
                                                    <img style="max-width: 120px;" height="120px" width="120px"
                                                        src="data:{{$code->getContentType()}};base64, {!! $code->generate()!!}" />
                                                </td> --}}
                                            </tr>                                         
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="certificate-wrapper">
                                                <img width="100%" src="{{base_path("public/uploads/".$application->student_id."/".$application->id."/".$attachment->file_name)}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
    </body>
</html>