SELECT
    jee_year,
    jee_roll_no,
    jee_form_no
FROM
    `application_academics`
WHERE
    (
        jee_year is not null
        and jee_year != 'null'
    )
    and (
        jee_roll_no is null
        or jee_roll_no = 'null'
    )
SELECT
    concat_ws(
        applications.first_name,
        " ",
        applications.middle_name,
        " ",
        applications.last_name
    ) as full_name,
    student_id as registration_no,
    applications.application_no,
    applications.status,
    applications.form_step,
    application_id,
    jee_year,
    jee_roll_no,
    jee_form_no
FROM
    `application_academics`
    join applications ON applications.id = application_academics.application_id
WHERE
    (
        jee_year is not null
        and jee_year != 'null'
    )
    and (
        jee_roll_no is null
        or jee_roll_no = 'null'
    )
    and applications.application_no is not null
SELECT
    applications.id,
    concat_ws(
        applications.first_name,
        " ",
        applications.middle_name,
        " ",
        applications.last_name
    ) as full_name,
    applications.application_no,
    users.mobile_no,
    users.email,
    courses.name
FROM
    `applications`
    JOIN users ON users.id = applications.student_id
    JOIN applied_courses ON applications.id = applied_courses.application_id
    JOIN courses ON applied_courses.course_id = courses.id
where
    status in ("payment_done", "accepted", "on_hold")
    and EXISTS(
        select
            *
        from
            applied_courses
        WHERE
            applications.id = applied_courses.application_id
            and EXISTS (
                select
                    *
                from
                    courses
                where
                    applied_courses.course_id = courses.id
                    and courses.name LIKE "%integrated%"
            )
    )
    and applications.deleted_at is null
SELECT
    applications.id,
    courses.name,
    applications.student_id,
    applications.session_id,
    applications.deleted_at,
    applications.application_no,
    applications.status,
    applications.created_at
FROM
    `applications`
    JOIN applied_courses ON applied_courses.application_id = applications.id
    JOIN courses ON applied_courses.course_id = courses.id
WHERE
    EXISTS (
        select
            *
        from
            applied_courses
        where
            applied_courses.application_id = applications.id
            and course_id = 80
    )
    and users.email not in(
        'payushnibhuyan75@gmail.com',
        'samarjyoti142@gmail.com',
        'TRISHACHAUDHURI16@GMAIL.COM',
        'sumik615@gmail.com'
    )
    and users.mobile_no not in ('7002114239')
SELECT
    id,
    JSON_EXTRACT(question_details, '$**.question') as text
FROM
    `eligibility_questions`
WHERE
    `type` LIKE 'options'
    and JSON_EXTRACT(question_details, '$**.question') LIKE "%Honor%"
    and deleted_at is null