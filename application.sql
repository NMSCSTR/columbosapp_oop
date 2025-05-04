INSERT INTO applicants (
    applicant_id, lastname, firstname, middlename, birthdate, birthplace, gender, marital_status, tin_sss, nationality,
    fraternal_counselor_id, created_at, status, application_status
) VALUES (
    1, 'Dela Cruz', 'Juan', 'Santos', '1991-04-14', 'Manila', 'Male', 'Single', '123-456-789', 'Filipino',
    1, NOW(), 'active', 'pending'
);

INSERT INTO contact_info (
    applicant_id, street, barangay, city_prov, mobile, email
) VALUES (
    1, '123 Sampaguita St.', 'Barangay 1', 'Quezon City', '09171234567', 'juan.delacruz@example.com'
);

INSERT INTO employment (
    applicant_id, occupation, employment_status, duties, employer, work, nature_business
) VALUES (
    1, 'Software Developer', 'Employed', 'Develops software applications.', 'TechCorp Inc.', 'IT Department', 'Information Technology'
);

INSERT INTO membership (
    applicant_id, council_address, first_degree_date, council_number, present_degree, good_standing
) VALUES (
    1, '456 Council St.', '2017-11-08', 'C-001', '3rd Degree', 'Yes'
);

INSERT INTO plans (
    applicant_id, plan_name, plan_code, face_value, yrs_contribute, yrs_protect, yrs_mature, payment_mode, currency, contribution_amount
) VALUES (
    1, 'Basic Plan', 'BP100', 100000.00, 5, 10, 15, 'Monthly', 'PHP', 1000.00
);

INSERT INTO beneficiaries (
    applicant_id, benefit_type, benefit_name, benefit_dob, relationship
) VALUES
    (1, 'Primary', 'Maria Dela Cruz', '2009-12-08', 'Daughter'),
    (1, 'Secondary', 'Pedro Dela Cruz', '1996-02-26', 'Son');

INSERT INTO family_background (
    applicant_id, father_lastname, father_firstname, father_mi, mother_lastname, mother_firstname, mother_mi,
    siblings_living, siblings_deceased, children_living, children_deceased
) VALUES (
    1, 'Dela Cruz', 'Jose', 'S', 'Reyes', 'Maria', 'G', 2, 1, 2, 0
);

INSERT INTO family_health (
    applicant_id, father_living_age, father_health, mother_living_age, mother_health,
    siblings_living_age, siblings_health, children_living_age, children_health,
    father_death_age, father_cause, mother_death_age, mother_cause,
    siblings_death_age, siblings_cause, children_death_age, children_cause
) VALUES (
    1, 70, 'Healthy', 68, 'Diabetic',
    '45,40', 'Healthy,Asthma', '12,10', 'Healthy,Healthy',
    NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL
);

INSERT INTO physician (
    applicant_id, physician_name, physician_address
) VALUES (
    1, 'Dr. Ana Santos', '789 Medical Ave., Makati City'
);

INSERT INTO health_questions (
    applicant_id, question_code, response, yes_details
) VALUES
    (1, 'p3_q3', 'No', NULL),
    (1, 'p3_q4', 'Yes', 'Had chickenpox at age 10'),
    (1, 'p4_q10', 'No', NULL);

INSERT INTO personal_details (
    applicant_id, height, weight, signature_file, pregnant_question
) VALUES (
    1, 170.5, 65.0, 'uploads/signatures/juan_signature.png', NULL
);


