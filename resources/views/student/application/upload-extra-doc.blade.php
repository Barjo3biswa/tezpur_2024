@extends('student.layouts.auth')
@section('css')
    <style>
        ol.mb-2 li, ul.mb-2 li{
            margin-bottom: 10px;
        }
    </style>    
@endsection
@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Upload Documents for : {{$application->application_no}}
                </div>
                <div class="panel-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>Important Instructions: </h3>
                                <div class="alert alert-warning">
                                    {{--<strong>Please upload your latest mark-sheet, extra curricular certificate. Merge all
                                        documents into single file and upload. <span class="glyphicon text-danger">Max allowed file size 1MB</span>. </strong>
                                        Please note the following carefully. --}}
                                        {{-- <ul>
                                            <li>Document 1 is compulsory. Here please merge the Class X and Class XII final mark-sheets into one .pdf document and
                                            upload. The file size should not exceed 1 MB. Incase the file size exceeds 1 MB, please upload this merged mark-sheet in
                                            Document 2 or 3.</li>
                                            <li>Document 2 is not compulsory. If you have the grading conversion formula, please upload it here.</li>
                                            <li>Document 3 - If you have not uploaded the Caste certificate / PWD certificate / Extra curricular activity certificate, please upload it here.</li>
                                            <li>For all uploaded documents, please mention the name of the document in the Remarks column in brief.</li>
                                            <li>Supported file types are pdf, jpg, png.</li>
                                        </ul> --}}
                                        <ol class="mb-2">
                                            <li>Eight boxes have been provided below for document upload. You can merge the marksheets into one or two .pdf documents and upload.</li>
                                            
                                            {{-- <li>Please upload the marksheets of Class X and XII in one box. The file size should not exceed 1 MB.</li>
                                            
                                            <li>Upload the marksheets of degree and higher exams in the next box. For Bachelors degree, students are required to upload from 1st to 6th Semester (if the final semester result is not declared, then upload upto 5th semester). The file size should not exceed 1 MB.</li> --}}
                                            
                                            <li>In case of grades, please upload grades to equivalent percentage as per conversion formula issued by your University/ Board/ Council (formula available behind your marksheet). In case the conversion formula is not available, you are advised to upload the conversion formula to be certified by the competent authority of the University/ Board/ Council.</li>
                                            
                                            <li>If you have not uploaded the Caste certificate, PWD certificate, Extra curricular activity certificate etc
                                            please upload it. The file size should not exceed 1 MB.</li>
                                            <li>Supported file types are pdf, jpg, png.</li>
                                            <li>Remarks must not exceed 500 character and at-least minimum 10 character required.</li>
                                            {{-- @if(isMasterInDesign($application))
                                                <li>For M.Des programme maximum portfolio size is 5MB.</li>
                                            @endif --}}
                                        </ol>
                                </div>
                                <div class="alert alert-info">
                                    <ul class="mb-2">
                                        <li><strong>For Integrated / B.Tech Programs</strong>
                                            <ul>
                                                <li>Please upload the marksheets of Class X and XII in Document 1 and Document 2 respectively.</li>
                                            </ul>
                                        </li>
                                        
                                        <li><strong>For Master Degree Programs</strong>
                                            <ul>
                                                <li>Please upload the marksheets of Class X and XII in Document 1 and Document 2 respectively.</li>
                                                <li>Upload the marksheets of degree and higher exams in the next available boxes. Students are required to upload marksheets of Bachelors degree from 1st to 6th Semesters (if the final semester result is not declared, then upload upto 5th semester). The file size should not exceed 1 MB.</li>
                                                <li>In case of candidates having Integrated B.Sc B.Ed/ B.A. B.Ed background, uploading of marksheets from 1st to 8th semester is required (if the final semester result is not declared, then uploading up to 7th semester is required). The file size should not exceed 1MB.</li>
                                            </ul>
                                        </li>

                                        <li><strong>For Ph.D Programs</strong>
                                            <ul>
                                                <li>Please upload the marksheets of Class X and XII in Document 1 and Document 2 respectively.</li>
                                                <li>Upload the marksheets of degree and higher exams in the next available boxes. Students are required to upload marksheets of Master degree from 1st to 4th Semesters (if the final semester result is not declared, then upload upto 3rd semester). The file size should not exceed 1 MB.</li>
                                                <li>In case of candidates having Integrated MA/MCom/MSc background, uploading of marksheets from 1st to 10th semester is required (if the final semester result is not declared, then uploading up to 9th semester is required. The file size should not exceed 1MB.</li>
                                            </ul>
                                        </li>
                                        <li><strong>For Ph. D. admission under ADF Scheme Please upload the following documents.</strong>
                                            <ul>
                                                <li>Proof of Age </li>
                                                <li>Marksheet of 10th Examination</li>
                                                <li>Marksheet of (10+2)th Examination</li>
                                                <li>Transcript of Undergraduate Degree (or all the Marksheets/Grade Cards of all the semester in one PDF file)</li>
                                                <li>Certificate of Undergraduate Degree</li>
                                                <li>Transcript of Postgraduate Degree (or all the Marksheets/Grade Cards of all the semester in one PDF file)</li>
                                                <li>Certificate of Postgraduate Degree</li>
                                                <li>Proof of Qualifying GATE/NET in last five year</li>
                                                <li>Category Certificate (as applicable)</li>
                                                <li>Copy of the Publications (Compiled in one PDF file)</li>
                                                <li>Experience Certificate</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            
                            <div class="col-sm-6">
                                <form action="" method="POST" id="upload_doc" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    {{-- @if(isMasterInDesign($application))
                                        <div class="docs">
                                            <div class="form-group {{ $errors->has('docs.0') ? ' has-error' : '' }}">
                                                <label for="doc1" class="control-label">CEED score card for M.Des programme <small class="glyphicon text-danger">*</small></label>
                                                <input type="file" class="form-control" name="docs[0]" id="doc1" >
                                                @if ($errors->has('docs.0'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('docs.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group {{ $errors->has('remarks.0') ? ' has-error' : '' }}">
                                                <label for="remarks1" class="control-label sr-only">Document Remarks <small class="glyphicon text-danger">*</small></label>
                                                
                                                <textarea maxlength="500" minlength="10" name="remarks[0]" id="remarks0" class="form-control sr-only" rows="3"
                                                    placeholder="Remarks">{{old('remarks.0', "CEED score card")}}</textarea>
                                                @if ($errors->has('remarks.0'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('remarks.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="docs">
                                            <div class="form-group {{ $errors->has('docs.5') ? ' has-error' : '' }}">
                                                <label for="doc1" class="control-label">Upload candidate's Portfolio
                                                    (with 5 MB file) for M.Des programme <small class="glyphicon text-danger">*</small></label>
                                                <input type="file" class="form-control" name="docs[5]" id="doc1" >
                                                @if ($errors->has('docs.5'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('docs.5') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="form-group {{ $errors->has('remarks.5') ? ' has-error' : '' }}">
                                                <label for="remarks1" class="control-label sr-only">Document Remarks</label>
                                                
                                                <textarea maxlength="500" minlength="10" name="remarks[5]" id="remarks5" class="form-control sr-only" rows="3"
                                                    placeholder="Remarks">{{old('remarks.5', "candidate's portfolio")}}</textarea>
                                                @if ($errors->has('remarks.5'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('remarks.5') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                    @endif --}}
                                    @php
                                        $required = $application->extra_documents->count() ? false : true;
                                    @endphp
                                    <div class="docs">
                                        <div class="form-group {{ $errors->has('docs.1') ? ' has-error' : '' }}">
                                            <label for="doc1" class="control-label">Document 1 
                                                @if($required)
                                                    <small class="glyphicon text-danger">*</small>
                                                @endif
                                            </label>
                                            <input type="file" class="form-control" name="docs[1]" id="doc1" >
                                            @if ($errors->has('docs.1'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('docs.1') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('remarks.1') ? ' has-error' : '' }}">
                                            <label for="remarks1" class="control-label">Document Remarks 
                                                @if($required)
                                                    <small class="glyphicon text-danger">*</small>
                                                @endif
                                            </label>
                                            <textarea maxlength="500" minlength="10" name="remarks[1]" id="remarks1" class="form-control " rows="3"
                                                 placeholder="Remarks">{{old('remarks.1')}}</textarea>
                                            @if ($errors->has('remarks.1'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('remarks.1') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="docs">
                                        <div class="form-group {{ $errors->has('docs.2') ? ' has-error' : '' }}">
                                            <label for="doc2" class="control-label">Document 2</label>
                                            <input type="file" class="form-control " name="docs[2]" id="doc2">
                                            @if ($errors->has('docs.2'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('docs.2') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('remarks.2') ? ' has-error' : '' }}">
                                            <label for="remarks2" class="control-label">Document Remarks</label>
                                            
                                            <textarea maxlength="500" minlength="10" name="remarks[2]" id="remarks2" class="form-control" rows="3"
                                                 placeholder="Remarks">{{old('remarks.2')}}</textarea>
                                            @if ($errors->has('remarks.2'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('remarks.2') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="docs">
                                        <div class="form-group {{ $errors->has('docs.3') ? ' has-error' : '' }}">
                                            <label for="doc3" class="control-label">Document 3</label>
                                            <input type="file" class="form-control " name="docs[3]" id="doc3">
                                            @if ($errors->has('docs.3'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('docs.3') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div
                                            class="form-group {{ $errors->has('remarks.3') ? ' has-error' : '' }}">
                                            <label for="remarks3" class="control-label">Document Remarks</label>
                                            <textarea maxlength="500" minlength="10" name="remarks[3]" id="remarks3" class="form-control {{ $errors->has('remarks.3') ? ' has-error' : '' }}" rows="3"
                                                 placeholder="Remarks">{{old('remarks.3')}}</textarea>
                                            @if ($errors->has('remarks.3'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('remarks.3') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="docs">
                                        <div class="form-group {{ $errors->has('docs.4') ? ' has-error' : '' }}">
                                            <label for="doc4" class="control-label">Document 4</label>
                                            <input type="file" class="form-control " name="docs[4]" id="doc4">
                                            @if ($errors->has('docs.4'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('docs.4') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div
                                            class="form-group {{ $errors->has('remarks.4') ? ' has-error' : '' }}">
                                            <label for="remarks4" class="control-label">Document Remarks</label>
                                            <textarea maxlength="500" minlength="10" name="remarks[4]" id="remarks4" class="form-control {{ $errors->has('remarks.4') ? ' has-error' : '' }}" rows="3"
                                                 placeholder="Remarks">{{old('remarks.4')}}</textarea>
                                            @if ($errors->has('remarks.4'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('remarks.4') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="docs">
                                        <div class="form-group {{ $errors->has('docs.5') ? ' has-error' : '' }}">
                                            <label for="doc5" class="control-label">Document 5</label>
                                            <input type="file" class="form-control " name="docs[5]" id="doc5">
                                            @if ($errors->has('docs.5'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('docs.5') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('remarks.5') ? ' has-error' : '' }}">
                                            <label for="remarks5" class="control-label">Document Remarks</label>
                                            <textarea maxlength="500" minlength="10" name="remarks[5]" id="remarks5" class="form-control {{ $errors->has('remarks.5') ? ' has-error' : '' }}" rows="3"
                                                 placeholder="Remarks">{{old('remarks.5')}}</textarea>
                                            @if ($errors->has('remarks.5'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('remarks.5') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="docs">
                                        <div class="form-group {{ $errors->has('docs.6') ? ' has-error' : '' }}">
                                            <label for="doc6" class="control-label">Document 6</label>
                                            <input type="file" class="form-control " name="docs[6]" id="doc6">
                                            @if ($errors->has('docs.6'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('docs.6') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('remarks.6') ? ' has-error' : '' }}">
                                            <label for="remarks6" class="control-label">Document Remarks</label>
                                            <textarea maxlength="600" minlength="10" name="remarks[6]" id="remarks6" class="form-control {{ $errors->has('remarks.6') ? ' has-error' : '' }}" rows="3"
                                                 placeholder="Remarks">{{old('remarks.6')}}</textarea>
                                            @if ($errors->has('remarks.6'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('remarks.6') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="docs">
                                        <div class="form-group {{ $errors->has('docs.7') ? ' has-error' : '' }}">
                                            <label for="doc7" class="control-label">Document 7</label>
                                            <input type="file" class="form-control " name="docs[7]" id="doc7">
                                            @if ($errors->has('docs.7'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('docs.7') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('remarks.7') ? ' has-error' : '' }}">
                                            <label for="remarks7" class="control-label">Document Remarks</label>
                                            <textarea maxlength="500" minlength="10" name="remarks[7]" id="remarks7" class="form-control {{ $errors->has('remarks.7') ? ' has-error' : '' }}" rows="3"
                                                 placeholder="Remarks">{{old('remarks.7')}}</textarea>
                                            @if ($errors->has('remarks.7'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('remarks.7') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="docs">
                                        <div class="form-group {{ $errors->has('docs.8') ? ' has-error' : '' }}">
                                            <label for="doc8" class="control-label">Document 8</label>
                                            <input type="file" class="form-control " name="docs[8]" id="doc8">
                                            @if ($errors->has('docs.8'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('docs.8') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group {{ $errors->has('remarks.8') ? ' has-error' : '' }}">
                                            <label for="remarks8" class="control-label">Document Remarks</label>
                                            <textarea maxlength="500" minlength="10" name="remarks[8]" id="remarks8" class="form-control {{ $errors->has('remarks.8') ? ' has-error' : '' }}" rows="3"
                                                 placeholder="Remarks">{{old('remarks.8')}}</textarea>
                                            @if ($errors->has('remarks.8'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('remarks.8') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">Would you like to partially save the documents and upload more documents later?<br />
                                            {{-- <span class="label label-danger">(Payment page will not unlocked. )</span> --}}
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="gridCheck1" name="partial_upload" value="Upload and Partially save">
                                                <label class="form-check-label" for="gridCheck1">
                                                    Yes
                                                </label>
                                            </div>
                                            <span class="label label-danger">Note : Partially saved/ uploaded documents will not be eligible for payments.</span>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-top: 30px;">
                                        {{-- <input type="submit" class="btn btn-sm btn-block btn-primary" name="partial_upload" value="Upload and Partially save"/> --}}
                                        <button type="submit" class="btn btn-sm btn-block btn-success">Upload and Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $("#upload_doc").on("submit", function(e){
                if(!confirm('Are you sure ?')){
                    return false;
                }
                var button = $(this).find("button[type='submit']");
                var text = button.text();
                button.text("Submitting...");
                button.prop({
                    disabled: true
                });
                setTimeout(() => {
                    button.text(text);
                    button.prop({
                        disabled: false
                    });
                }, 8000);
            });
            $("#gridCheck1").change(function(){
                if($(this).is(":checked")){
                    var confirm = window.confirm("Note : Partially saved/ uploaded documents will not be eligible for payments.\n Do you want to continue?");
                    if(!confirm){
                        $(this).prop({
                            "checked": false
                        });
                    }
                }
            });
        });
    </script>
@endsection