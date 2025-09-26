<?php
include 'includes/header.php';
?>

<!-- Include custom landing page styles -->
<link rel="stylesheet" href="assets/css/landing.css">
<!-- Main content of the page -->
<main>
    <!-- Hero Section -->
    <section class="hero-section" id="hero">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title">Knights of Columbus Fraternal Association of the Philippines Inc.</h1>
                    <p class="hero-subtitle">Providing life protection and financial security to our Brother Knights and their families. Get your Benefit Certificate from KCFAPI now! Contact the Fraternal Counselor nearest you or leave us here a message!</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#contact" class="btn-primary-custom">Get Started</a>
                        <a href="#about" class="btn-secondary-custom">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6 hero-image">
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                        class="img-fluid" alt="Knights of Columbus Fraternal Association" width="700" height="500"
                        loading="lazy">
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="about-content">
                <h2 class="about-title">About Knights of Columbus Fraternal Association of the Philippines Inc.</h2>
                <p class="about-text">We are a fraternal benefit society dedicated to providing life insurance and financial security to our members and their families. Our mission is to strengthen Catholic families and communities through our charitable works and fraternal brotherhood.</p>
                <p class="about-text">Founded on the principles of charity, unity, fraternity, and patriotism, we have been serving Filipino families for decades, offering comprehensive protection plans and supporting our communities through various charitable initiatives.</p>
                <div class="text-center">
                    <a href="#fraternal-benefits" class="btn-primary-custom">Our Benefits</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features/Benefits Section -->
    <section class="features-section" id="fraternal-benefits">
        <div class="container">
            <h2 class="features-title">Our Fraternal Benefits</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="40" height="40" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">Life Protection</h3>
                        <p class="feature-description">Comprehensive life insurance coverage designed to protect your family's financial future with flexible payment options and competitive rates.</p>
                        <a href="#" class="feature-link">
                            Learn More
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="40" height="40" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">Financial Security</h3>
                        <p class="feature-description">Secure your family's financial stability with our range of investment and savings products tailored to meet your long-term financial goals.</p>
                        <a href="#" class="feature-link">
                            Learn More
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg width="40" height="40" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.54 8H17c-.8 0-1.54.37-2.01.99L14 10.5c-.47-.62-1.21-.99-2.01-.99H9.46c-.8 0-1.54.37-2.01.99L6 10.5c-.47-.62-1.21-.99-2.01-.99H2.46c-.8 0-1.54.37-2.01.99L0 10.5v8.5h2v6h2v-6h2v6h2v-6h2v6h2v-6h2v6h2z"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">Fraternal Brotherhood</h3>
                        <p class="feature-description">Join a community of like-minded individuals committed to charity, unity, and fraternity while supporting Catholic values and community service.</p>
                        <a href="#" class="feature-link">
                            Learn More
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="contact-content">
                <h2 class="contact-title">Contact Us</h2>
                <p class="contact-subtitle">Ready to secure your family's future? Get in touch with us today to learn more about our fraternal benefits and protection plans.</p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <form class="contact-form" action="#" method="POST">
                            <div class="form-group">
                                <label for="email" class="form-label">Your Email</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" required>
                            </div>
                            <div class="form-group">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" id="subject" name="subject" class="form-control" placeholder="How can we help you?" required>
                            </div>
                            <div class="form-group">
                                <label for="message" class="form-label">Your Message</label>
                                <textarea id="message" name="message" rows="6" class="form-control" placeholder="Tell us about your needs..." required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn-submit">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Include custom landing page JavaScript -->
<script src="assets/js/landing.js"></script>

<?php
include 'includes/footer.php';
?>