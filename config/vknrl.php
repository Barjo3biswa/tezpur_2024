<?php
/**
 * Add project related configurations here.
 */
return [
    /*
     *  OTP Resend Limitation
     */
    "otp-limit"                   => 3,
    "admit_qr_code"               => true,
    "admit-registration-required" => true,
    /**
     * optionaal active session value should be comma separated id
     */
    "optional_active_session_ids" => env("OPTIONAL_ACTIVE_SESSION_IDS", null),

    /**
     * supported driver razorpay, payabbhi
     */
    "payment_gateway"             => env("PAYMENT_GATEWAY", "razorpay"),

    /**
     * razorpay credentials
     */
    "RAZORPAY_KEY"                => env("RAZORPAY_KEY", null),
    "RAZORPAY_SECRET"             => env("RAZORPAY_SECRET", null),

    /**
     * razorpay credentials
     */
    "PAYMENT_ACCESS_ID"           => env("PAYMENT_ACCESS_ID", null),
    "PAYMENT_SECRET_KEY"          => env("PAYMENT_SECRET_KEY", null),
    "ALLOW_AFTER_CLOSING_APP_IDS" => env("ALLOW_AFTER_CLOSING_APP_IDS", ""),
    "ADMISSION_REPORTING_EXPIRY_HOUR" => env("ADMISSION_REPORTING_EXPIRY_HOUR", 24),
    "ADMISSION_EXPIRY_HOUR"        => env("ADMISSION_REPORTING_EXPIRY_HOUR", 48),
    "ADMISSION_ACC_RAZORPAY"       => env("RAZORPAY_ADMISSION_ACC", null),
    "TERMS_AND_COND"               => "
        I, Mr./ MS. ##name## (Application No . ##application_no##) do hereby declare that my admission will be provisional and give an undertaking as under that:
    
    (1) I shall produce the marksheet of all the semesters, pass certificate of the qualifying degree/certificate and the other pending documents on or before 31-10-2023 failing which my admission will be cancelled. My admission may also be cancelled if I fail to meet the  minimum qualifying marks or CGPA for the admission to the Programme.

    (2) I shall also produce all the certificates in original for physical verification when I am asked to visit the University. My admission shall be cancelled if any of the certificates is found to be not valid.
    
    (3) I also declare that there is no pending examination/ assignment / practical / backlog course(s) etc. of the previous semesters  of the
    qualifying examinations as per the eligibility criteria.

    (4) In case my admission is cancelled/ withdrawn, refund will be subject to UGC/ TU Rules & regulations.
    
    I declare that the information given in the application form/ admission
     record form is true and complete to the best of my knowledge and belief.
     And if any of it is found to be incorrect, my admission shall be
     cancelled, and I shall be liable to such disciplinary action that the
    University may deem fit.
    
     I also declare that I shall abide by the Statutes, Ordinances, Rules,
     Regulations, and Orders etc. of the University that will be in force from
    time to time. I shall submit myself to the disciplinary jurisdiction of
    the University, if I am found to violate the Statutes, Ordinances, Rules,
    Regulations, Orders etc. of the University during the period of my study
    in Tezpur University.
    
     I have gone through the undertaking and accepted all the conditions.
    ",
    "withdrawal_seat_module"    => env("ENABLE_WITHDRAWAL_MODULE",  false),
    "sms_templates" => [
        [
            "name"  => "Approved For Payment",
            "template_id"  => "1207162521403708453",
            "template"  => "Please login to the panel and make the fees payment by {#var#} for completing the provisional admission. Tezpur University",
        ],
        [
            "name"  => "Approveed For Reporting",
            "template_id"  => "1207162507953112192",
            "template"  => "You are advised to make the payment to book your seat for the program by {#var#}. Login to the registration portal to make the payment. Tezpur University.",
        ],
    ],
    "free_application_course_ids"   => env("FREE_APPLICATION_COURSE_IDS", "")
];
