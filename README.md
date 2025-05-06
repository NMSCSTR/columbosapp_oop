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

