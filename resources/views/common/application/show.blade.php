@section("css")
<style>
    .bold {
        font-weight: bold;
    }
</style>
@endsection

<div class="container" id="page-content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" style="padding-bottom: 5px;">Application Details @if($application->payment_status)<span class="pull-right dont-print" style="margin-bottom:2px;"><a
                            href="{{request()->fullUrlWithQuery(['download-pdf' => 1])}}"><button class="btn btn-sm btn-default"><i
                                    class="fa fa-download"></i> Download</button></a></span> @endif</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <table width="100%">
                        <tbody>
                            <tr class="text-center">
                                <td width="20%" class="padding-xs">
                                    <img style="max-width: 80px" width="80" class="avatar avatar-xxl"
                                        src="{{asset('logo.png')}}">
                                </td>
                                <td class="padding-xs" style="text-align: center;">
                                    <h3 class="mb-3 text-uppercase">TEZPUR UNIVERSITY</h3>
                                    <p class="mb-4 bold">
                                        NAPAAM, TEZPUR - 784028, ASSAM<br> <strong>APPLICATION FORM</strong><br>
                                        <strong>{{$application->session->name ?? ""}}</strong><br>
                                        @if($application->exam_through!=null)
                                        Through- {{$application->exam_through}}
                                        @endif
                                        {{-- For admission to {{$application->session->name ?? "NA"}} --}}
                                    </p>
                                </td>
                                <td width="20%" class="padding-xs">{{-- {{$application->passport_photo()->file_name}} --}}
                                    @if ($application->passport_photo())
                                    <img width="80" style="max-width: 80px;" class="avatar avatar-xxl"
                                        src="{{route("common.download.image", [$application->student_id, $application->id, $application->passport_photo()->file_name])}}">
                                    @else
                                    Passport Photo Not Uploaded
                                    @endif
                                <br/><br/>
                                    @if ($application->signature())
                                    <p><img style="width: 160px; height: 60px; max-width: 160px; max-height: 60px;"
                                        src="{{route("common.download.image", [$application->student_id, $application->id, $application->signature()->file_name])}}"></p>
                                    @else
                                    Signature Not Uploaded
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {{-- <table class="table" border="0">
                        <tbody>
                            <tr>
                                <td class="text-right">
                                    @if ($application->signature())
                                    <img style="width: 160px; height: 60px; max-width: 160px; max-height: 60px;"
                                        src="{{route("common.download.image", [$application->student_id, $application->id, $application->signature()->file_name])}}">
                                </td>
                                @else
                                Signature Not Uploaded
                                @endif
                            </tr>
                        </tbody>
                    </table> --}}
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        @include("common.application.form-information")
                        
                        @include("common.application.form-enclosures")
                        @if($application->attachment_others()->count())
                        <table class="table table-bordered table-attachments">
                            <caption>
                                <h4><strong>Attachments</strong></h4>
                            </caption>
                            <tr>
                                {{-- printing heading --}}
                                @forelse ($application->attachment_others() as $attachment)
                                <td>
                                    <a
                                        href="{{route("common.download.image", [$application->student_id, $application->id, $attachment->file_name])}}">
                                        {{str_replace(["Disablity", "Anm", "Prc", "Bpl"], ["Disability", "ANM", "PRC", "BPL"], ucwords(str_replace("_"," ",$attachment->doc_name)))}}
                                    </a>
                                </td>
                                @empty
                                <td class="text-danger">No Attachment Found</td>
                                @endforelse
                            </tr>
                        </table>
                        @endif
                            {{--Extra Document--}}
                            @if($application->attachment_extra_document()->count())
                            <table class="table table-bordered table-attachments">
                                <caption>
                                    <h4><strong>Additional Documents(Compulsory)</strong></h4>
                                </caption>
                                <tr>
                                    {{-- printing heading --}}
                                    @forelse ($application->attachment_extra_document as $attachments)
                                    <td>
                                        <a
                                            href="{{route("common.download.image", [$application->student_id, $application->id, $attachments->file_name])}}" target="_blank">
                                            {{str_replace(["Disablity", "Anm", "Prc", "Bpl"], ["Disability", "ANM", "PRC", "BPL"], ucwords(str_replace("_"," ",$attachments->remark)))}}
    
                                        </a>
                                    </td>
                                    @empty
                                    <td class="text-danger">No Attachment Found</td>
                                    @endforelse
                                </tr>
                            </table>
                            @endif
                            @if($application->misc_documents()->count())
                            <table class="table table-bordered table-attachments">
                                <caption>
                                    <h4><strong>Miscellaneous Documents</strong></h4>
                                </caption>
                                <tr>
                                    {{-- printing heading --}}
                                    @forelse ($application->misc_documents as $attachments)
                                    <td>
                                        <a
                                        href="{{route("common.download.image", [$application->student_id, $application->id, $attachments->file_name])}}" target="_blank">
                                            {{-- href="{{$attachments->document_path.'/'.$attachments->file_name}}"> --}}
                                            {{$attachments->document_name}}
    
                                        </a>
                                    </td>
                                    @empty
                                    <td class="text-danger">No Attachment Found</td>
                                    @endforelse
                                </tr>
                            </table>
                            @endif
                               {{--End Extra Document--}}
                        <div class="form-group">
                            @php
                                $url=env('APP_URL');
                                $id=Crypt::encrypt($application->id);
                                $qr_string = $url."/qrcode/".$id;   
                                $qr = QrCode::generate($qr_string);
                            @endphp
                            {!! $qr !!}
                            {{-- @php
                                $url=env('APP_URL');
                                
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
                                   
                                    ->setLabelFontSize(16)
                                    ->setImageType(QrCode()::IMAGE_TYPE_PNG);
                            @endphp
                            {!!'<img style="width:300px;" src="data:'.$qr_code->getContentType().';base64,'.$qr_code->generate().'" />'!!} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>