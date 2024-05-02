
@php
   $count = count($application->attachment_others());
@endphp
@if ($count>0)
    

                        <h4 style="">Enclosures check list (Document Uploaded)</h4>
                        <table class="table table-bordered"   width="100%" border="1" style="border-collapse: collapse">
                            <tbody>
                                @if($application->is_foreign)
                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "passport") ? "checked" : ""}} /> Passport. <small style="font-weight:bold"></small></td>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "driving_license") ? "checked" : ""}} /> Driving License <small style="font-weight:bold">(if applicable)</small></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "ssn_certificate") ? "checked" : ""}} /> SSN or equivalent. <small style="font-weight:bold">(if applicable)</small></td>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "passing_certificate") ? "checked" : ""}} /> Passing certificate of qualifying exam <small style="font-weight:bold"></small></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "qualified_marksheet") ? "checked" : ""}} /> Marksheet of qualifying exam. <small style="font-weight:bold"></small></td>
                                        
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "noc_certificate") ? "checked" : ""}} /> NOC. <small style="font-weight:bold">(if applicable)</small></td>
                                    </tr>
                                @elseif(!$application->is_mba)
                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "prc_certificate") ? "checked" : ""}} /> PRC <small style="font-weight:bold">(for B.Tech. and M.Sc. in MBBT for North-East quota)</small></td>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "jee_admit_card") ? "checked" : ""}} /> Admit Card of JEE <small style="font-weight:bold">(for B.Tech. Programme)</small></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "caste_certificate") ? "checked" : ""}} /> Category Certificate(SC/ST/EWS/OBC) <small style="font-weight:bold">(if applicable)</small></td>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "pwd_certificate") ? "checked" : ""}} /> PWD <small style="font-weight:bold">(if applicable)</small></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "gate_score_card") ? "checked" : ""}} /> GATE score card <small style="font-weight:bold">(if applicable)</small></td>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "net_slet_certificate") ? "checked" : ""}} /> NET/SLET/GATE/JRF/MPhil Certificate. <small style="font-weight:bold">(if applicable)</small></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "bpl_card") ? "checked" : ""}} /> BPL/AAY Card <small style="font-weight:bold">(if applicable)</small></td>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "noc_certificate") ? "checked" : ""}} /> NOC. <small style="font-weight:bold">(if applicable)</small></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "sport_certificate") ? "checked" : ""}} /> Certificate representing Country/State in Sports. <small style="font-weight:bold">(if applicable)</small></td>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "ex_serviceman_certificate") ? "checked" : ""}} /> Ex-Serviceman/Widow/Ward Certificate <small style="font-weight:bold">(if applicable)</small></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "gate_b_score_card") ? "checked" : ""}} /> GAT-B SCORE CARD. <small style="font-weight:bold"></small></td>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "ceed_score_card") ? "checked" : ""}} /> CEED SCORE CARD. <small style="font-weight:bold"></small></td>
                                        {{-- <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "obc_ncl") ? "checked" : ""}} /> OBC(Non Creamy Layer) Certificate <small style="font-weight:bold">(if applicable)</small></td> --}}
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "cuet_admit_card") ? "checked" : ""}} /> CUET ADMIT CARD. <small style="font-weight:bold"></small></td>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "cuet_score_card") ? "checked" : ""}} /> CUET SCORE CARD. <small style="font-weight:bold"></small></td>
                                        
                                    </tr>


                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "class_x_documents") ? "checked" : ""}} /> CLASS X Documents. <small style="font-weight:bold"></small></td>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "class_XII_documents") ? "checked" : ""}} /> CLASS XII Documents. <small style="font-weight:bold"></small></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "graduation_documents") ? "checked" : ""}} /> GRADUATION ATTACHMENTS. <small style="font-weight:bold"></small></td>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "post_graduation_documents") ? "checked" : ""}} /> POST GRADUATION ATTACHMENTS. <small style="font-weight:bold"></small></td>
                                    </tr>


                                    <tr>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "obc_ncl") ? "checked" : ""}} /> OBC NCL. <small style="font-weight:bold"></small></td>
                                        <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "undertaking_pass_appear") ? "checked" : ""}} /> UNDERTAKING FOR PASS/APPEAR. <small style="font-weight:bold"></small></td>
                                        {{-- <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "portfolio") ? "checked" : ""}} /> Portfolio. <small style="font-weight:bold"></small></td> --}}
                                        {{-- <td></td> --}}
                                        {{-- <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "obc_ncl") ? "checked" : ""}} /> OBC(Non Creamy Layer) Certificate <small style="font-weight:bold">(if applicable)</small></td> --}}
                                    </tr>
                                @endif
                                @if(isMbaStudent($application))

                                @if (document_uploaded_check($application->attachment_others(), "prc_certificate"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "prc_certificate") ? "checked" : ""}} /> PRC <small style="font-weight:bold">(for B.Tech. and M.Sc. in MBBT for North-East quota)</small></td>
                                </tr>
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "jee_admit_card"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "jee_admit_card") ? "checked" : ""}} /> Admit Card of JEE <small style="font-weight:bold">(for B.Tech. Programme)</small></td>
                                </tr>
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "caste_certificate"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "caste_certificate") ? "checked" : ""}} /> Category Certificate <small style="font-weight:bold">(if applicable)</small></td>
                                </tr>
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "pwd_certificate"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "pwd_certificate") ? "checked" : ""}} /> PWD <small style="font-weight:bold">(if applicable)</small></td>
                                </tr> 
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "gate_score_card"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "gate_score_card") ? "checked" : ""}} /> GATE score card <small style="font-weight:bold">(if applicable)</small></td>
                                </tr>
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "net_slet_certificate"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "net_slet_certificate") ? "checked" : ""}} /> NET/SLET/GATE/JRF/MPhil Certificate. <small style="font-weight:bold">(if applicable)</small></td>
                                </tr>
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "bpl_card"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "bpl_card") ? "checked" : ""}} /> BPL/AAY Card <small style="font-weight:bold">(if applicable)</small></td>
                                </tr>
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "noc_certificate"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "noc_certificate") ? "checked" : ""}} /> NOC. <small style="font-weight:bold">(if applicable)</small></td>
                                </tr>
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "sport_certificate"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "sport_certificate") ? "checked" : ""}} /> Certificate representing Country/State in Sports. <small style="font-weight:bold">(if applicable)</small></td>
                                </tr> 
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "ex_serviceman_certificate"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "ex_serviceman_certificate") ? "checked" : ""}} /> Ex-Serviceman/Widow/Ward Certificate <small style="font-weight:bold">(if applicable)</small></td>
                                </tr>
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "gate_b_score_card"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "gate_b_score_card") ? "checked" : ""}} /> GAT-B SCORE CARD. <small style="font-weight:bold"></small></td>
                                </tr>
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "ceed_score_card"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "ceed_score_card") ? "checked" : ""}} /> CEED SCORE CARD. <small style="font-weight:bold"></small></td>
                                </tr>  
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "cuet_admit_card"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "cuet_admit_card") ? "checked" : ""}} /> CUET ADMIT CARD. <small style="font-weight:bold"></small></td>
                                </tr>  
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "cuet_score_card"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "cuet_score_card") ? "checked" : ""}} /> CUET SCORE CARD. <small style="font-weight:bold"></small></td>    
                                </tr>
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "class_x_documents"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "class_x_documents") ? "checked" : ""}} /> CLASS X Documents. <small style="font-weight:bold"></small></td>
                                </tr>  
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "class_XII_documents"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "class_XII_documents") ? "checked" : ""}} /> CLASS XII Documents. <small style="font-weight:bold"></small></td>
                                </tr>  
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "graduation_documents"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "graduation_documents") ? "checked" : ""}} /> GRADUATION ATTACHMENTS. <small style="font-weight:bold"></small></td>
                                </tr>   
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "post_graduation_documents"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "post_graduation_documents") ? "checked" : ""}} /> POST GRADUATION ATTACHMENTS. <small style="font-weight:bold"></small></td>
                                </tr>   
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "obc_ncl"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "obc_ncl") ? "checked" : ""}} /> OBC NCL. <small style="font-weight:bold"></small></td>
                                </tr>   
                                @endif
                                @if (document_uploaded_check($application->attachment_others(), "undertaking_pass_appear"))
                                <tr>
                                    <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), "undertaking_pass_appear") ? "checked" : ""}} /> UNDERTAKING FOR PASS/APPEAR. <small style="font-weight:bold"></small></td>
                                </tr>  
                                @endif
                                

                                
                                    @foreach (collect(getMBAExams())->chunk(1) as $items)   
                                        @foreach ($items as $item)
                                            @if(document_uploaded_check($application->attachment_others(), $item))
                                            <tr>
                                                <td><input type="checkbox" readonly onclick="return false" {{document_uploaded_check($application->attachment_others(), $item) ? "checked" : ""}} /></span> {{strtoupper(str_replace("_", " ", $item))}} (if Applicable). <small style="font-weight:bold"></small></td>
                                            </tr> 
                                            @endif    
                                        @endforeach                                                                     
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
@endif