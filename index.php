<?php
include 'includes/header.php';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/landing.css">

<main>
    <section class="hero-section py-5" id="hero">
        <div class="container">
            <div class="row align-items-center min-vh-100 py-5">
                <div class="col-lg-6 col-12 order-2 order-lg-1 text-center text-lg-start" data-aos="fade-right" data-aos-duration="1000">
                    <h1 class="hero-title display-3 fw-bold">Knights of Columbus Fraternal Association of the Philippines Inc.</h1>
                    <p class="hero-subtitle lead mb-4">Providing life protection and financial security to our Brother Knights and their families. Get your Benefit Certificate from KCFAPI now! Contact the Fraternal Counselor nearest you or leave us here a message!</p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
                        <a href="#contact" class="btn-primary-custom btn btn-lg px-4">Get Started</a>
                        <a href="#about" class="btn-secondary-custom btn btn-lg px-4">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6 col-12 order-1 order-lg-2 text-center mb-5 mb-lg-0" data-aos="fade-left" data-aos-duration="1000">
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                        class="img-fluid rounded shadow-lg" alt="Knights of Columbus Fraternal Association" width="700" height="500"
                        loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section class="about-section py-5 bg-light" id="about">
        <div class="container">
            <div class="about-content text-center py-4" data-aos="fade-up" data-aos-duration="1000">
                <h2 class="about-title display-5 fw-bold mb-4">About Knights of Columbus Fraternal Association of the Philippines Inc.</h2>
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <p class="about-text lead">We are a fraternal benefit society dedicated to providing life insurance and financial security to our members and their families. Our mission is to strengthen Catholic families and communities through our charitable works and fraternal brotherhood.</p>
                        <p class="about-text lead mb-5">Founded on the principles of charity, unity, fraternity, and patriotism, we have been serving Filipino families for decades, offering comprehensive protection plans and supporting our communities through various charitable initiatives.</p>
                    </div>
                </div>
                <a href="#fraternal-benefits" class="btn-primary-custom btn btn-lg px-5">Our Benefits</a>
            </div>
        </div>
    </section>

    ---

    <section class="features-section py-5" id="fraternal-benefits">
        <div class="container py-4">
            <h2 class="features-title text-center display-5 fw-bold mb-5" data-aos="zoom-in">Our Fraternal Benefits</h2>
            <div class="row g-5">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card p-4 rounded shadow-sm h-100 text-center">
                        <div class="feature-icon mb-3 mx-auto" style="width: 40px;">
                            <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <h3 class="feature-title fw-bold mb-3">Life Protection</h3>
                        <p class="feature-description mb-4">Comprehensive life insurance coverage designed to protect your family's financial future with flexible payment options and competitive rates.</p>
                        <a href="#" class="feature-link text-decoration-none fw-bold">
                            Learn More
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" class="ms-1">
                                <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card p-4 rounded shadow-sm h-100 text-center">
                        <div class="feature-icon mb-3 mx-auto" style="width: 40px;">
                            <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <h3 class="feature-title fw-bold mb-3">Financial Security</h3>
                        <p class="feature-description mb-4">Secure your family's financial stability with our range of investment and savings products tailored to meet your long-term financial goals.</p>
                        <a href="#" class="feature-link text-decoration-none fw-bold">
                            Learn More
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" class="ms-1">
                                <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mx-md-auto mx-lg-0" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card p-4 rounded shadow-sm h-100 text-center">
                        <div class="feature-icon mb-3 mx-auto" style="width: 40px;">
                            <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.54 8H17c-.8 0-1.54.37-2.01.99L14 10.5c-.47-.62-1.21-.99-2.01-.99H9.46c-.8 0-1.54.37-2.01.99L6 10.5c-.47-.62-1.21-.99-2.01-.99H2.46c-.8 0-1.54.37-2.01.99L0 10.5v8.5h2v6h2v-6h2v6h2v-6h2v6h2v-6h2v6h2z"/>
                            </svg>
                        </div>
                        <h3 class="feature-title fw-bold mb-3">Fraternal Brotherhood</h3>
                        <p class="feature-description mb-4">Join a community of like-minded individuals committed to charity, unity, and fraternity while supporting Catholic values and community service.</p>
                        <a href="#" class="feature-link text-decoration-none fw-bold">
                            Learn More
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" class="ms-1">
                                <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    ---

    <section class="contact-section py-5 bg-primary" id="contact">
        <div class="container py-4">
            <div class="contact-content text-white text-center" data-aos="fade-up" data-aos-duration="1000">
                <h2 class="contact-title display-5 fw-bold mb-3">Contact Us</h2>
                <p class="contact-subtitle lead mb-5">Ready to secure your family's future? Get in touch with us today to learn more about our fraternal benefits and protection plans.</p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <form class="contact-form p-4 rounded shadow-lg bg-white text-start" action="#" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label text-dark fw-bold">Your Email</label>
                                <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="name@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label text-dark fw-bold">Subject</label>
                                <input type="text" id="subject" name="subject" class="form-control form-control-lg" placeholder="How can we help you?" required>
                            </div>
                            <div class="mb-4">
                                <label for="message" class="form-label text-dark fw-bold">Your Message</label>
                                <textarea id="message" name="message" rows="6" class="form-control form-control-lg" placeholder="Tell us about your needs..." required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn-submit btn btn-success btn-lg w-100">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        // Optional: define the distance to trigger the animation (0% to 100%)
        offset: 120,
        // Optional: animate once
        once: true
    });
</script>

<script src="assets/js/landing.js"></script>

<?php
include 'includes/footer.php';
?>