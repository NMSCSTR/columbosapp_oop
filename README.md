:

🔑 Login Flow Summary

1. Login Form (pages/login.php)

Posts to: controllers/auth/login.php

Accepts: email, password, optional remember checkbox.

Displays input fields and pre-fills with PHP variables.

2. User Authorization Middleware (middleware/auth.php)

Function authorize() checks if a user is logged in and role is allowed.

Redirects unauthorized users to unauthorized.php.

3. Role-Based Redirect (middleware/role_redirect.php)

Redirects user to dashboard based on their role.

Assumes session is already started and role is set.

4. User Model (models/usersModel.php)

Can get user by email.

Can create and delete users.

Passwords are hashed using bcrypt.



Each insurance plan is linked to an applicant (via applicant_id), and the table stores details about the plan, such as:

Plan code: A unique identifier for the plan.

Face value: The total coverage amount provided by the plan.

Years of contribution: How long the policyholder needs to pay premiums.

Years of protection: How long the insurance covers the applicant.

Years to maturity: The period until the plan matures (e.g., the point at which the policyholder can claim or receive benefits).

Contribution amount: The amount paid by the policyholder periodically.


<!-- INSURANCE -->
Example Flow:
Applicant Enters Information:

An individual applies for insurance by filling out the details in the applicants table.

Choose a Plan:

They choose an insurance plan from the available options in the plans table. For instance, they may choose a plan with a specific face value, contribution amount, and protection years.

Assign Beneficiaries:

The applicant may list one or more beneficiaries (e.g., spouse, children) in the beneficiaries table. These are the people who will receive the benefits if the applicant passes away or faces an insurable event.

Payment and Coverage:

The applicant pays premiums based on the plan details (as stored in the plans table).

If something happens to the applicant, the insurance company will process the claim, and the beneficiaries will receive the coverage amount (based on the face value of the plan).

Summary:
The applicants table holds personal info.

The plans table holds details about the insurance plans they select, such as coverage amount and duration.

The beneficiaries table holds the individuals who will receive the insurance benefits.

<!-- INSURANCE CALCULATION -->
Data Breakdown for Plan:
Plan Code: BP100

Face Value (Coverage): ₱100,000.00 (This is the amount the insurance will pay to the beneficiaries when the policy matures or is claimed.)

Years of Contribution: 5 years (The applicant must contribute to the plan for 5 years.)

Years of Protection: 10 years (The insurance coverage is active for 10 years.)

Years to Maturity: 15 years (The policy will mature in 15 years, which is when the full coverage might be paid out.)

Payment Mode: Monthly (The applicant will pay premiums monthly.)

Contribution Amount: ₱1,000.00 per month (This is the premium the applicant will pay each month.)

Currency: PHP (Philippine Peso)

How it Works:
Monthly Premium:
The applicant pays ₱1,000.00 per month.

Total Contribution Over Years:
Since the plan requires the applicant to contribute for 5 years, we can calculate the total contributions over that period:

Total Contribution
=
Monthly Contribution
×
Months per Year
×
Years of Contribution
Total Contribution=Monthly Contribution×Months per Year×Years of Contribution
Total Contribution
=
1
,
000
 
PHP
×
12
×
5
=
60
,
000
 
PHP
Total Contribution=1,000PHP×12×5=60,000PHP
So, the total premium paid over 5 years is ₱60,000.00.

Face Value / Coverage:
The applicant will have a face value of ₱100,000.00. This is the amount the insurance company will pay out to the beneficiaries, typically upon the applicant’s death or after the protection period, depending on the specific terms of the policy.

Policy Duration:

The policy lasts for 15 years in total, but the applicant only pays premiums for 5 years.

The applicant is covered for 10 years (the years of protection) and the policy matures after 15 years.

In this case, the applicant pays the total premiums (₱60,000.00) over 5 years, but after the 5 years of contributions, they remain protected for an additional 5 years without any further payments.

Example Calculation of Insurance Cost vs. Coverage:
Total Contributions: ₱60,000.00 (paid over 5 years)

Coverage/Face Value: ₱100,000.00

The applicant essentially gets ₱100,000.00 in coverage for ₱60,000.00 worth of premiums, which is a great return on investment, as the applicant pays for just 5 years of protection but is covered for a total of 15 years.

This type of plan might be attractive because the cost of premiums is spread out over a relatively short period, and the coverage lasts much longer.

Simple Summary of the Calculation:
Monthly premium: ₱1,000.00

Total paid over 5 years: ₱60,000.00

Coverage after 15 years: ₱100,000.00

So, the applicant pays ₱60,000.00 in total, but the policyholder’s beneficiaries or the applicant themselves (if they survive the policy term) would receive ₱100,000.00 as the face value when the policy matures or the claim occurs.


<!-- Member Applicant Controller -->
1. applicant Data Insertion:
In your controller, you're using the insertApplicant method, which passes values like user_id, fraternal_counselor_id, firstname, lastname, middlename, birthdate, age, gender, marital_status, tin_sss, and nationality.

Your database schema for the applicants table includes these columns:

user_id, fraternal_counselor_id, firstname, lastname, middlename, birthdate, gender, marital_status, tin_sss, and nationality — these match the fields you're inserting. However, you also have applicant_id in the table, but you're passing it as null in the controller, which may be automatically set (as applicant_id should be an auto-increment field). Ensure that applicant_id is indeed set as AUTO_INCREMENT in your schema.

2. applicant_contact_info Data Insertion:
The controller has the insertApplicantContactDetails method, which inserts values such as street, barangay, city_province, mobile_number, and email_address.

These fields correspond to the contact_info table:

Columns applicant_id, user_id, street, barangay, city_province, mobile_number, and email_address — these match the fields you're inserting. This part seems fine.

3. employment Data Insertion:
The controller has the insertEmploymentDetails method, which inserts fields like occupation, employment_status, duties, employer, work, nature_business, employer_mobile_number, employer_email_address, and monthly_income.

These fields map to the employment table:

Columns like applicant_id, user_id, occupation, employment_status, duties, employer, work, nature_business, employer_mobile_number, employer_email_address, and monthly_income — this matches your schema, so no issues here.

4. plans Data Insertion:
The insertPlanInformation method in the controller inserts fraternal_benefits_id, council_id, payment_mode, contribution_amount, and currency.

These fields align with the plans table schema:

The plans table has columns like fraternal_benefits_id, council_id, payment_mode, contribution_amount, and currency — this is correct. Just ensure that plan_id is set as AUTO_INCREMENT in your schema, as it’s not passed in the controller.

5. beneficiaries Data Insertion:
The addBeneficiaries method in the controller inserts arrays of benefit_type, benefit_name, benefit_birthdate, and benefit_relationship.

These fields align with the beneficiaries table schema:

Columns like applicant_id, user_id, benefit_type, benefit_name, benefit_birthdate, and benefit_relationship match, but ensure that you correctly handle multiple beneficiaries (since you're inserting arrays).

6. family_background Data Insertion:
The controller inserts family details like father_lastname, father_firstname, father_mi, mother_lastname, mother_firstname, mother_mi, siblings_living, siblings_deceased, children_living, and children_deceased.

These fields match the family_background table:

The family_background table has columns like applicant_id, user_id, father_lastname, father_firstname, father_mi, mother_lastname, mother_firstname, mother_mi, siblings_living, siblings_deceased, children_living, and children_deceased. This is consistent with your schema.

7. medical_history Data Insertion:
The controller has the insertMedicalHistory method, which inserts fields like past_illness and current_medication.

These fields match the medical_history table:

The medical_history table has columns like applicant_id, user_id, past_illness, and current_medication, so this part seems fine.

8. family_health Data Insertion:
The controller inserts fields like father_living_age, father_health, mother_living_age, mother_health, siblings_living_age, siblings_health, children_living_age, children_health, father_death_age, father_cause, mother_death_age, mother_cause, siblings_death_age, siblings_cause, children_death_age, and children_cause.

These fields map to the family_health table:

The family_health table has columns like applicant_id, user_id, father_living_age, father_health, mother_living_age, mother_health, siblings_living_age, siblings_health, children_living_age, children_health, father_death_age, father_cause, mother_death_age, mother_cause, siblings_death_age, siblings_cause, children_death_age, and children_cause. This matches your schema.

9. physician Data Insertion:
The controller inserts physician_name, contact_number, and clinic_address.

These fields align with the physician table:

The physician table has columns like applicant_id, user_id, physician_name, contact_number, and clinic_address, which match the controller's code.

10. health_questions Data Insertion:
The controller gathers responses for health questions and then calls the insertHealthQuestions method.

This involves the columns applicant_id, user_id, and responses (which is an associative array containing the answers for questions).

Ensure that the table health_questions can handle these responses properly. Your schema appears to expect a question_id for each response, which isn't explicitly mentioned in the controller but can be derived from the questions (q1 to q12).

11. personal_and_membership_details Data Insertion:
The controller inserts fields such as height, weight, pregnant_question, council_id, first_degree_date, present_degree, good_standing, and signature_file.

These fields align with the personal_details and membership tables:

The personal_details table has applicant_id, height, weight, and so on, while the membership table has the council_id, first_degree_date, present_degree, and good_standing. Just make sure that signature_file is handled correctly and uploaded to the server before insertion.

