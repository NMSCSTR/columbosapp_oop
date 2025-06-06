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

📋 How It Works:
1. Payment Phase (Years 1–5):
You pay ₱1,000 every month for 5 years.

Total premium paid = ₱60,000.

2. Insurance Coverage (Years 1–10):
You are covered for ₱100,000 life insurance during the first 10 years.

If you die within those 10 years, your beneficiary receives ₱100,000.

3. Maturity Phase (End of Year 15):
If you survive the full 15 years, you will receive a maturity benefit.

This could be:

The ₱100,000 face value (if it's structured as an endowment), or

A guaranteed amount (possibly with dividends or bonuses if it's a participating policy)


Here’s how you can estimate allocation using a formula based on the given insurance structure:
📌 Key Variables:
P = Monthly premium = ₱1,000

n = Number of months = 60

FV = Face Value / Maturity = ₱100,000

r = Annual interest rate used for fund growth (assumed: 4%)

t = Years fund grows after payment = 10 years (Year 6 to Year 15)

Total Contributions = P×n=₱1,000×60=₱60,000

🧠 2. Assume Allocation Percentages (Industry Estimate):

| Component              | Symbol | Approx. % | Formula              |
| ---------------------- | ------ | --------- | -------------------- |
| Insurance Cost         | IC     | 10%       | IC = 0.10 X P        |
| Admin Fees             | AF     | 5%        | AF = 0.5 X P         |
| Savings (Reserve Fund) | SF     | 85%       | SF = 0.85 X P        |


💰 3. Monthly Allocation Formula:
Monthly Insurance Cost (IC) = 0.10×1,000 = ₱100     - So for each month, ₱850 goes into a growing fund.
Monthly Admin Fee (AF) =0.5×1,000 = ₱50
Monthly Savings Fund (SF) =0.85×1,000 = ₱850
​

<tr class="bg-gray-50 text-sm text-gray-600">
    <td colspan="8" class="px-4 py-2 pl-10">
        <strong>Allocation (Annualized: ₱<?= number_format($annualContribution, 2) ?>):</strong><br>
        Insurance Cost (10%): ₱<?= number_format($insuranceCost, 2) ?> |
        Admin Fees (5%): ₱<?= number_format($adminFees, 2) ?> |
        Savings Fund (85%): ₱<?= number_format($savingsFund, 2) ?>
    </td>
</tr>



✅ 1. Core Structure Summary (Based on Your Example)
| Element                      | Value                                      | Explanation                                      |
| ---------------------------- | ------------------------------------------ | ------------------------------------------------ |
| Monthly Premium (`P`)        | ₱1,000                                     | Paid by applicant                                |
| Contribution Period (`n`)    | 5 years (60 months)                        | Payment years                                    |
| Total Contributions          | ₱60,000 = ₱1,000 × 60                      | What applicant pays                              |
| Face Value / Coverage (`FV`) | ₱100,000                                   | What applicant gets at maturity or death benefit |
| Growth Period (`t`)          | 10 years                                   | Time savings fund grows                          |
| Interest Rate (`r`)          | 4% compounded annually                     | Estimated growth rate                            |
| Allocation                   | Insurance (10%), Admin (5%), Savings (85%) | Industry estimate                                |

✅ 2. Allocation Model (per month)
Given a ₱1,000 monthly premium:
| Component      | Formula      | Monthly Value |
| -------------- | ------------ | ------------- |
| Insurance Cost | 0.10 × 1,000 | ₱100          |
| Admin Fee      | 0.05 × 1,000 | ₱50           |
| Savings Fund   | 0.85 × 1,000 | ₱850          |

✅ 3. Savings Fund Future Value Estimation
FV = PV × (1 + r)^t
PV = ₱51,000
r = 4% = 0.04
t = 10 years

FV = 51,000 × (1 + 0.04)^10
FV ≈ 51,000 × 1.48024
FV ≈ ₱75,492.24



                            <tr class="bg-gray-50 text-sm text-gray-600">
                                <td colspan="8" class="px-4 py-2 pl-10">
                                    <strong>Total Contribution: ₱<?= $result['total_contribution'] ?></strong><br>
                                    Insurance Cost (10%): ₱<?= $result['insurance_cost'] ?> |
                                    Admin Fees (5%): ₱<?= $result['admin_fee'] ?> |
                                    Savings Fund (85%): ₱<?= $result['savings_fund'] ?> |
                                    Savings Fund Future Value (4% growth): ₱<?= $result['savings_fund_fv'] ?>
                                </td>
                            </tr>