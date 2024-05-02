@component('mail::message')
  
Dear Applicant,<br />
  
Your request for a seat withdrawal process is successful. Please find below the details of the withdrawl.
  
**Applicant Name:**  {{$merit_list->application->full_name ?? "NA"}}<br />
**Registration No:**  {{$merit_list->student_id ?? "NA"}}<br />
**Application No:**  {{$merit_list->application_no ?? "NA"}}<br />
**CRL No:**  {{$merit_list->student_rank ?? "NA"}}<br />
**Program Name:**  {{$merit_list->course->name ?? "NA"}}<br />
**Admission Category:**  {{$merit_list->admissionCategory->name ?? "NA"}} {{$merit_list->is_pwd ? "(PWD)" : ""}}<br />

<br /><br />
  
***Note : Admission Fees will be refunded as per Tezpur University Policy.***  
  
This is for your information<br />

Thank You
Best Wishes
Tezpur University Online Support Team

@endcomponent
