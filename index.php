<?php
include 'includes/header.php';
?>

<!-- Main content of the page -->
<main>
    <section id="hero">
        <div class="container">
            <div class="container col-xxl-12 px-4 py-5">
                <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                    <div class="col-10 col-sm-8 col-lg-6">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8ujnW0R1rWq_afJbQNpOf859evcPH4PiJVd2jrjrAS-wRhJ2LeVTJN9o6Zu1qYMdYPlM&usqp=CAU%20alt="
                            class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500"
                            loading="lazy">
                    </div>
                    <div class="col-lg-6">
                        <h1 class="display-5 fw-bolder text-body-emphasis lh-1 mb-3">Knights of Columbus Fraternal
                            Association of the Philippines Inc.</h1>
                        <p class="lead">Providing life protection and financial security to our Brother Knights and
                            their families. Get your Benefit Certificate from KCFAPI now! Contact the Fraternal
                            Counselor nearest you or leave us here a message!</p>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                            <button type="button" class="btn btn-primary btn-lg px-4 me-md-2">Get Started</button>
                            <button type="button" class="btn btn-outline-secondary btn-lg px-4">Visit Site</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about">
        <div class="bg-body-tertiary p-5 rounded">
            <div class="col-sm-8 py-5 mx-auto">
                <h1 class="display-5 fw-normal">About Knights of Columbus Fraternal Association of the Philippines Inc.
                </h1>
                <p class="fs-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam amet odit ipsa
                    distinctio, laboriosam error tempore ad id perspiciatis. Amet vel inventore fugit in eius minima
                    cumque, tenetur quia voluptatem!</p>
                <p>From the top down, you'll see a dark navbar, light navbar and a responsive navbar—each with
                    offcanvases built in. Resize your browser window to the large breakpoint to see the toggle for the
                    offcanvas.</p>
                <p>
                    <a class="btn btn-primary" href="/docs/5.3/components/navbar/#offcanvas" role="button">Learn more
                        about offcanvas navbars &raquo;</a>
                </p>
            </div>
        </div>
    </section>

    <section id="fraternal-benifits">
        <div class="container px-4 py-5" id="featured-3">
            <h2 class="pb-2 border-bottom">Columns with icons</h2>
            <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
                <div class="feature col">
                    <div
                        class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-2 mb-3">
                        <svg class="bi" width="1em" height="1em">
                            <use xlink:href="#collection" />
                        </svg>
                    </div>
                    <h3 class="fs-2 text-body-emphasis">Featured title</h3>
                    <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another
                        sentence and probably just keep going until we run out of words.</p>
                    <a href="#" class="icon-link">
                        Call to action
                        <svg class="bi">
                            <use xlink:href="#chevron-right" />
                        </svg>
                    </a>
                </div>
                <div class="feature col">
                    <div
                        class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-2 mb-3">
                        <svg class="bi" width="1em" height="1em">
                            <use xlink:href="#people-circle" />
                        </svg>
                    </div>
                    <h3 class="fs-2 text-body-emphasis">Featured title</h3>
                    <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another
                        sentence and probably just keep going until we run out of words.</p>
                    <a href="#" class="icon-link">
                        Call to action
                        <svg class="bi">
                            <use xlink:href="#chevron-right" />
                        </svg>
                    </a>
                </div>
                <div class="feature col">
                    <div
                        class="feature-icon d-inline-flex align-items-center justify-content-center text-bg-primary bg-gradient fs-2 mb-3">
                        <svg class="bi" width="1em" height="1em">
                            <use xlink:href="#toggles2" />
                        </svg>
                    </div>
                    <h3 class="fs-2 text-body-emphasis">Featured title</h3>
                    <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another
                        sentence and probably just keep going until we run out of words.</p>
                    <a href="#" class="icon-link">
                        Call to action
                        <svg class="bi">
                            <use xlink:href="#chevron-right" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="vh-100 w-100 bg-light text-dark" id="contact">
        <div class="py-4 py-lg-5 px-3 container">
            <h2 class="mb-4 text-center display-5 fw-bold text-dark">Contact Us</h2>
            <p class="mb-4 mb-lg-5 text-center text-secondary fs-5">Got a technical issue? Want to send feedback about a
                beta feature? Need details about our Business plan? Let us know.</p>
            <form action="#" class="row g-3">
                <div class="col-12">
                    <label for="email" class="form-label">Your email</label>
                    <input type="email" id="email" class="form-control" placeholder="name@example.com" required>
                </div>
                <div class="col-12">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" id="subject" class="form-control" placeholder="Let us know how we can help you"
                        required>
                </div>
                <div class="col-12">
                    <label for="message" class="form-label">Your message</label>
                    <textarea id="message" rows="6" class="form-control" placeholder="Leave a comment..."></textarea>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary px-5 py-3">Send message</button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php
include 'includes/footer.php';
?>