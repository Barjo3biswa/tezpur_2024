<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <form
          name="step-one-update"
          class="form-horizontal form-class application-form"
        >
          <br />
          <div class="step personal_information">
            <fieldset id="personal_information">
              <legend>
                Resubmit Documents 
               
              </legend>
             
              <div class="row">
                <div class="col-md-6" v-if="!application.is_foreign">
                    <div class="form-group" >
                      <label for="document_prc" class="col-md-4 margin-label">
                        PRC *(for B.Tech and M.Sc in MBBT for North-East quota)
                        :
                      </label>
                      <div class="col-md-7">
                        <input
                          type="file"
                          ref="document_prc"
                          class="form-control input-sm input-sm"
                          @change="prcFileChanged"
                          accept=".jpg,.jpeg,.png"
                        />
                        <small class="text-info"
                          ><strong
                            >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                          >
                        </small>
                        <small
                          class="text-danger"
                          v-if="documents.errors.prc"
                          >{{ documents.errors.prc }}</small
                        >
                      </div>
                    </div>
                </div> 
                <div class="col-md-6" v-if="!application.is_foreign">   
                    <div class="form-group" >
                      <label for="document_prc" class="col-md-4 margin-label">
                        GATE Score card (if applicable) :
                      </label>
                      <div class="col-md-7">
                        <input
                          type="file"
                          ref="document_gate_card"
                          class="form-control input-sm input-sm"
                          @change="gateCardFileChanged"
                          accept=".jpg,.jpeg,.png"
                        />
                        <small class="text-info"
                          ><strong
                            >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                          >
                        </small>
                        <small
                          class="text-danger"
                          v-if="documents.errors.gate_score_card"
                          >{{ documents.errors.gate_score_card }}</small
                        >
                      </div>
                    </div>
                </div>
                <div class="col-md-6" v-if="!application.is_foreign">    
                    <div class="form-group">
                      <label for="document_caste" class="col-md-4 margin-label"
                        >Category Certificate (if applicable) :</label
                      >
                      <div class="col-md-7">
                        <input
                          type="file"
                          ref="document_caste"
                          class="form-control input-sm input-sm"
                          @change="casteCertificateChanged"
                          accept=".jpg,.jpeg,.png"
                        />
                        <small class="text-info"
                          ><strong
                            >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                          >
                        </small>
                        <small
                          class="text-danger"
                          v-if="documents.errors.caste_certificate"
                          >{{ documents.errors.caste_certificate }}</small
                        >
                      
                      </div>
                    </div>
                </div>    
                <div class="col-md-6" v-if="application.is_bpl == true && !application.is_foreign">
                    <div class="form-group" >
                      <label for="document_bpl" class="col-md-4 margin-label"
                        >BPL/AAY Card :</label
                      >
                      <div class="col-md-7">
                        <input
                          type="file"
                          ref="document_bpl"
                          class="form-control input-sm input-sm"
                          @change="bplFileChanged"
                          accept=".jpg,.jpeg,.png"
                        />
                        <small class="text-info"
                          ><strong
                            >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                          >
                        </small>
                        <small
                          class="text-danger"
                          v-if="documents.errors.bpl_card"
                          >{{ documents.errors.bpl_card }}</small
                        >
                        
                      </div>
                    </div>
                </div>  
                <div class="col-md-6" v-if="is_ncl == true && !application.is_foreign">  
                    <div class="form-group" >
                      <label
                        for="document_obc_ncl"
                        class="col-md-4 margin-label"
                        >OBC(NCL) Certificate (if applicable) :</label
                      >
                      <div class="col-md-7">
                        <input
                          type="file"
                          ref="document_obc_ncl"
                          class="form-control input-sm input-sm"
                          @change="obcFileChanged"
                          accept=".jpg,.jpeg,.png"
                        />
                        <small class="text-info"
                          ><strong
                            >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                          >
                        </small>
                        <small
                          class="text-danger"
                          v-if="documents.errors.obc_ncl"
                          >{{ documents.errors.obc_ncl }}</small
                        >
                        
                      </div>
                    </div>
                </div>
                 
                <div class="col-md-6" v-if="application.is_foreign">
                  <div class="form-group">
                    <label for="passport" class="col-md-4 margin-label">
                      Passport :
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-7">
                      <input
                        type="file"
                        ref="passport"
                        class="form-control input-sm input-sm"
                        @change="passportFileChanged"
                        accept=".jpg,.jpeg,.png"
                      />
                      <small class="text-info"
                        ><strong
                          >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                        >
                      </small>
                      <small
                        class="text-danger"
                        v-if="documents.errors.passport"
                        >{{ documents.errors.passport }}</small
                      >
                      
                    </div>
                  </div>
                </div>
                <div class="col-md-6"  v-if="application.is_sport_represented == true">
                  <div
                    class="form-group">
                    <label
                      for="document_sport_representation"
                      class="col-md-4 margin-label"
                      >Certificate representing Country/State in sports :</label
                    >
                    <div class="col-md-7">
                      <input
                        type="file"
                        ref="document_sport_representation"
                        class="form-control input-sm input-sm"
                        @change="sportCertificateChanged"
                        accept=".jpg,.jpeg,.png"
                      />
                      <small class="text-info"
                        ><strong
                          >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                        >
                      </small>
                      <small
                        class="text-danger"
                        v-if="documents.errors.sport_certificate"
                        >{{ documents.errors.sport_certificate }}</small
                      >
                     
                      >
                    </div>
                  </div>
                </div> 
                <div class="col-md-6" v-if="application.is_pwd == true"> 
                  <div class="form-group" >
                    <label for="document_pwd" class="col-md-4 margin-label">
                      PWD Ceritficate :
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-7">
                      <input
                        type="file"
                        ref="document_pwd"
                        class="form-control input-sm input-sm"
                        @change="pwdCertificateChanged"
                        accept=".jpg,.jpeg,.png"
                      />
                      <small class="text-info"
                        ><strong
                          >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                        >
                      </small>
                      <small
                        class="text-danger"
                        v-if="documents.errors.pwd_certificate"
                        >{{ documents.errors.pwd_certificate }}</small
                      >
                     
                    </div>
                  </div>
                </div>  
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="document_noc" class="col-md-4 margin-label">
                      NOC :
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-7">
                      <input
                        type="file"
                        ref="document_noc"
                        class="form-control input-sm input-sm"
                        @change="nocFileChanged"
                        accept=".jpg,.jpeg,.png"
                      />
                      <small class="text-info"
                        ><strong
                          >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                        >
                      </small>
                      <small
                        class="text-danger"
                        v-if="documents.errors.noc_certificate"
                        >{{ documents.errors.noc_certificate }}</small
                      >
                     
                    </div>
                  </div>
                </div>
                <div class="col-md-6" v-if="application.is_btech == true && !application.is_foreign">
                  <div
                    class="form-group"
                    
                  >
                    <label
                      for="document_admit_jee"
                      class="col-md-4 margin-label"
                    >
                      Admit Card of JEE (for B.Tech Programme) :
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-7">
                      <input
                        type="file"
                        ref="document_admit_jee"
                        class="form-control input-sm input-sm"
                        @change="admiCardChanged"
                        accept=".jpg,.jpeg,.png"
                      />
                      <small class="text-info"
                        ><strong
                          >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                        >
                      </small>
                      <small
                        class="text-danger"
                        v-if="documents.errors.jee_admit"
                        >{{ documents.errors.jee_admit }}</small
                      >
                    </div>
                  </div>
                </div>
                <div class="col-md-6" v-if="!application.is_foreign">
                    <div class="form-group">
                      <label
                        for="document_net_slet"
                        class="col-md-4 margin-label"
                      >
                        NET/SLET/GATE/JRF/MPhil Certificate (if applicable) :
                      </label>
                      <div class="col-md-7">
                        <input
                          type="file"
                          ref="document_net_slet"
                          class="form-control input-sm input-sm"
                          @change="netSletFileChanged"
                          accept=".jpg,.jpeg,.png"
                        />
                        <small class="text-info"
                          ><strong
                            >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                          >
                        </small>
                        <small
                          class="text-danger"
                          v-if="documents.errors.net_slet_certificate"
                          >{{
                            documents.errors.net_slet_certificate
                          }}</small
                        >
                      </div>
                    </div>
                </div>
                <div class="col-md-6" v-if="!application.is_foreign && application.is_ex_serviceman == true">
                    <div
                      class="form-group"
                    >
                      <label
                        for="document_ex_servicement"
                        class="col-md-4 margin-label"
                      >
                        Ex-Servicement/Widow/Ward Certificate :
                        <span class="text-danger">*</span>
                      </label>
                      <div class="col-md-7">
                        <input
                          type="file"
                          ref="document_ex_servicement"
                          class="form-control input-sm input-sm"
                          @change="exservicemanFileChanged"
                          accept=".jpg,.jpeg,.png"
                        />
                        <small class="text-info"
                          ><strong
                            >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                          >
                        </small>
                        <small
                          class="text-danger"
                          v-if="documents.errors.ex_serviceman_certificate"
                          >{{
                            documents.errors.ex_serviceman_certificate
                          }}</small
                        >
                       
                      </div>
                    </div>
                </div>
                <div class="col-md-6" v-if="application.is_foreign">
                  <div class="form-group">
                    <label
                      for="driving_license"
                      class="col-md-4 margin-label"
                    >
                      Driving License (if applicable):
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-7">
                      <input
                        type="file"
                        ref="driving_license"
                        class="form-control input-sm input-sm"
                        @change="drivingLicenseFileChanged"
                        accept=".jpg,.jpeg,.png"
                      />
                      <small class="text-info"
                        ><strong
                          >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                        >
                      </small>
                      <small
                        class="text-danger"
                        v-if="documents.errors.driving_license"
                        >{{ documents.errors.driving_license }}</small
                      >
                    </div>
                  </div>
                </div>
                <div class="col-md-6" v-if="application.is_foreign">  
                  <div class="form-group">
                    <label for="ssn_equivalent" class="col-md-4 margin-label">
                      SSN or equivalent (if applicable):
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-7">
                      <input
                        type="file"
                        ref="ssn_equivalent"
                        class="form-control input-sm input-sm"
                        @change="ssnFileChanged"
                        accept=".jpg,.jpeg,.png"
                      />
                      <small class="text-info"
                        ><strong
                          >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                        >
                      </small>
                      <small
                        class="text-danger"
                        v-if="documents.errors.ssn_certificate"
                        >{{ documents.errors.ssn_certificate }}</small
                      >
                    </div>
                  </div>
                </div>
                <div class="col-md-6" v-if="application.is_foreign">
                  <div class="form-group">
                    <label
                      for="passing_certificate"
                      class="col-md-4 margin-label"
                    >
                      Passing Certificate of qualifying exam:
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-7">
                      <input
                        type="file"
                        ref="passing_certificate"
                        class="form-control input-sm input-sm"
                        @change="passingCertificateFileChanged"
                        accept=".jpg,.jpeg,.png"
                      />
                      <small class="text-info"
                        ><strong
                          >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                        >
                      </small>
                      <small
                        class="text-danger"
                        v-if="documents.errors.passing_certificate"
                        >{{
                          documents.errors.passing_certificate
                        }}</small
                      >
                    
                      >
                    </div>
                  </div>
                </div>  
                <div class="col-md-6" v-if="application.is_foreign">
                  <div class="form-group">
                    <label for="marksheet" class="col-md-4 margin-label">
                      Mark sheet of qualifying exam:
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-7">
                      <input
                        type="file"
                        ref="marksheet"
                        class="form-control input-sm input-sm"
                        @change="markSheetFileChanged"
                        accept=".jpg,.jpeg,.png"
                      />
                      <small class="text-info"
                        ><strong
                          >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                        >
                      </small>
                      <small
                        class="text-danger"
                        v-if="documents.errors.qualifying_marksheet"
                        >{{
                          documents.errors.qualifying_marksheet
                        }}</small
                      >
                      
                    </div>
                  </div>
                </div>
                <div class="col-md-6" v-if="application.is_foreign">  
                  <div class="form-group">
                    <label for="marksheet" class="col-md-4 margin-label">
                      Proficiency in English Certificate :
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-7">
                      <input
                        type="file"
                        ref="english_proficiency_certificate"
                        class="form-control input-sm inpu0t-sm"
                        @change="proficiencyFileChanged"
                        accept=".jpg,.jpeg,.png"
                      />
                      <small class="text-info"
                        ><strong
                          >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                        >
                      </small>
                      <small
                        class="text-danger"
                        v-if="
                          documents.errors
                            .english_proficiency_certificate
                        "
                        >{{
                          documents.errors
                            .english_proficiency_certificate
                        }}</small
                      >
                      
                      >
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="driving_license" class="col-md-4 margin-label">
                      Additional Documents (if applicable):
                    </label>
                    <div class="col-md-7">
                      <input
                        type="file"
                        ref="additional_document"
                        class="form-control input-sm input-sm"
                        @change="additionalFileChanged"
                        accept=".jpg,.jpeg,.png"
                      />
                      <small class="text-info"
                        ><strong
                          >Max-size: 1MB , File type: JPEG,JPG, PNG</strong
                        >
                      </small>
                      <small
                        class="text-danger"
                        v-if="documents.errors.additional_document"
                        >{{ documents.errors.additional_document }}</small
                      >
                    </div>
                  </div>
                </div>
              </div>
              
            </fieldset>
            <div class="row btn-row" style="margin-top:18px;">
              <div class="col-md-6">
                
                <button
                  type="button"
                  class="btn btn-success next"
                  id="next"
                  @click="submitForm"
                 
                >
                  <section v-if="documents.uploading">
                    <i
                      class="fa fa-circle-o-notch fa-spin"
                      style="font-size:14px;color:white;"
                    ></i>
                    <span class="text-white">Uploading ..</span>
                  </section>
                  <section v-else>
                     <span>Upload</span>
                    <i class="fa fa-send"></i>
                  </section>
                </button>
              </div>
              <div class="col-md-6">
                <div class="progress">
                  <div
                    class="progress-bar"
                    :class="
                    is_progress_error
                        ? 'progress-bar-danger'
                        : 'progress-bar-success'
                    "
                    role="progressbar"
                    aria-valuenow="1"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    :style="progress_style"
                  >
                    {{ progress_style.width }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
const axios = require("axios");
export default {
  props:['application'],
  data() {
    return {
        // base_url:'https://www.tezuadmissions.in/public',
        // base_url: 'https://www.tezuadmissions.in/public',
        base_url : 'http://127.0.0.1:8000/',
        // base_url: "http://localhost/dec/tezuadmissions_in/public/",
        old_application:'',
        application_is:{
          is_bpl:false,
          is_ncl:false,
          is_foreign:false,
          is_sport_represented:false,
          is_pwd:false,
          is_ex_serviceman:false,
          is_pwd:false,
          is_btech:false,
        },
        documents:{
            errors: [],
            success_msg: "",
            document_prc: "",
            document_gate_score_card: "",
            document_caste: "",
            document_bpl: "",
            document_obc_ncl: "",
            document_sport_representation: "",
            document_ews: "",
            document_admit_jee: "",
            document_pwd: "",
            document_net_slet: "",
            document_noc: "",
            document_ex_servicement: "",
            document_passport: "",
            document_driving_license: "",
            document_ssn_equivalent: "",
            document_passing_certificate: "",
            document_marksheet: "",
            document_english_proficiency_certificate: "",
            document_additional: "",
            submitted: false,
            
        },
        is_foreign:false,
        progress_style:{
            width:'0%',
        },
        uploading:false,
        is_progress_error:false,
    };
  },
  mounted(){
   
  },
  computed: {
    is_ncl(){
        if(this.application.caste_id == '4'){
          return true;
        }else{
          return false;
        }
    },
  },
  methods: {
    additionalFileChanged(){
      this.documents.document_additional = this.$refs.additional_document.files[0];
    },
    proficiencyFileChanged() {
      this.documents.document_english_proficiency_certificate = this.$refs.english_proficiency_certificate.files[0];
    },
    passportPhotoFileChanged() {
      this.documents.document_passport_photo = this.$refs.document_passport_photo.files[0];
      this.documents.passport_photo_url = URL.createObjectURL(this.documents.document_passport_photo);
    },
    signatureFileChanged() {
      this.documents.document_signature = this.$refs.document_signature.files[0];
    },
    prcFileChanged() {
      this.documents.document_prc = this.$refs.document_prc.files[0];
    },
    gateCardFileChanged() {
      this.documents.document_gate_score_card = this.$refs.document_gate_card.files[0];
    },
    casteCertificateChanged() {
      this.documents.document_caste = this.$refs.document_caste.files[0];
    },
    bplFileChanged() {
      this.documents.document_bpl = this.$refs.document_bpl.files[0];
    },
    obcFileChanged() {
      this.documents.document_obc_ncl = this.$refs.document_obc_ncl.files[0];
    },
    sportCertificateChanged() {
      this.documents.document_sport_representation = this.$refs.document_sport_representation.files[0];
    },
    ewsFileChanged() {
      this.documents.document_ews = this.$refs.document_ews.files[0];
    },
    admiCardChanged() {
      this.documents.document_admit_jee = this.$refs.document_admit_jee.files[0];
    },
    pwdCertificateChanged() {
      this.documents.document_pwd = this.$refs.document_pwd.files[0];
    },
    netSletFileChanged() {
      this.documents.document_net_slet = this.$refs.document_net_slet.files[0];
    },
    nocFileChanged() {
      this.documents.document_noc = this.$refs.document_noc.files[0];
    },
    exservicemanFileChanged() {
      this.documents.document_ex_servicement = this.$refs.document_ex_servicement.files[0];
    },
    passportFileChanged() {
      this.documents.document_passport = this.$refs.passport.files[0];
    },
    drivingLicenseFileChanged() {
      this.documents.document_driving_license = this.$refs.driving_license.files[0];
    },
    ssnFileChanged() {
      this.documents.document_ssn_equivalent = this.$refs.ssn_equivalent.files[0];
    },
    passingCertificateFileChanged() {
      this.documents.document_passing_certificate = this.$refs.passing_certificate.files[0];
    },
    markSheetFileChanged() {
      this.documents.document_marksheet = this.$refs.marksheet.files[0];
    },
    submitForm(){
      var formdata = this.createFormData();
      formdata.append("application_id", this.application.id);
      this.documents.uploading = true;
      this.is_progress_error = false;
      axios({
        method: "post",
        url: "student/application/resubmit",
        data: formdata,
        onUploadProgress: function( progressEvent ) {
          this.progress_style.width = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))+'%';
        }.bind(this)

      })
      .then(response => {
       
        this.documents.success_msg =
          "Final step has been completed successfully !!";
        this.documents.uploading = false;
        this.documents.submitted = true;
        this.documents.is_progress_error = false;
        this.alertMessage("success", "You have successfully completed your online application form!!");
        window.location.href = this.base_url+"/student/application";
      })
      .catch(error => {
        console.log(error);
        this.is_progress_error = true;
        this.alertMessage("danger", "There are some errors . Please check!!");
        this.documents.uploading = false;
        this.documents.errors = error.response.data.errors;
      })
      .then(() => {
        this.documents.uploading = false;
      });
        
    },
    createFormData(){
       var formdata = new FormData();
      if(this.documents.document_additional){
        formdata.append('additional_document',this.documents.document_additional);
        
      }
      if (this.documents.document_english_proficiency_certificate) {
        formdata.append(
          "english_proficiency_certificate",
          this.documents.document_english_proficiency_certificate
        );
      }
      if (this.documents.document_passport_photo) {
        formdata.append("passport_photo", this.documents.document_passport_photo);
      }
      if (this.documents.document_signature) {
        formdata.append("signature", this.documents.document_signature);
      }
      if (this.documents.document_prc) {
        formdata.append("prc", this.documents.document_prc);
      }
      if (this.documents.document_caste) {
        formdata.append("caste_certificate", this.documents.document_caste);
      }
      if (this.documents.document_bpl) {
        formdata.append("bpl_card", this.documents.document_bpl);
      }
      if (this.documents.document_obc_ncl) {
        formdata.append("obc_ncl", this.documents.document_obc_ncl);
      }
      if (this.documents.document_sport_representation) {
        formdata.append(
          "sport_certificate",
          this.documents.document_sport_representation
        );
      }
      if (this.documents.document_ews) {
        formdata.append("ews_certificate", this.documents.document_ews);
      }
      if (this.documents.document_admit_jee) {
        formdata.append("jee_admit", this.documents.document_admit_jee);
      }
      if (this.documents.document_pwd) {
        formdata.append("pwd_certificate", this.documents.document_pwd);
      }
      if (this.documents.document_net_slet) {
        formdata.append("net_slet_certificate", this.documents.document_net_slet);
      }
      if (this.documents.document_noc) {
        formdata.append("noc_certificate", this.documents.document_noc);
      }
      if (this.documents.document_ex_servicement) {
        formdata.append(
          "ex_serviceman_certificate",
          this.documents.document_ex_servicement
        );
      }
      if (this.documents.document_gate_score_card) {
        formdata.append("gate_score_card", this.documents.document_gate_score_card);
      }

      if (this.documents.document_passport) {
        formdata.append("passport", this.documents.document_passport);
      }
      if (this.documents.document_driving_license) {
        formdata.append("driving_license", this.documents.document_driving_license);
      }
      if (this.documents.document_ssn_equivalent) {
        formdata.append("ssn_certificate", this.documents.document_ssn_equivalent);
      }
      if (this.documents.document_passing_certificate) {
        formdata.append(
          "passing_certificate",
          this.documents.document_passing_certificate
        );
      }
      if (this.documents.document_marksheet) {
        formdata.append("qualifying_marksheet", this.documents.document_marksheet);
      }
      return formdata;
    },
    
    alertMessage(mode, msg) {
      $.notify(
        {
          message: msg
        },
        {
          type: mode
        }
      );
    }
  }
}
</script>
