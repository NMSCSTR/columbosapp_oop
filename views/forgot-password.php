<?php
    session_start();
    include '../includes/config.php';
    include '../includes/header.php';

    if (isset($_SESSION['success'])) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '" . $_SESSION['success'] . "',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>";
        unset($_SESSION['success']);
    }

    if (isset($_SESSION['error'])) {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '" . $_SESSION['error'] . "',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Try Again'
        });
    </script>";
        unset($_SESSION['error']);
    }
?>

<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
            <img class="w-8 h-8 mr-2" src="https://visitaiglesia.net/wp-content/uploads/2019/04/11.jpg" alt="logo">
            Columbos App
        </a>
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Reset your password
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Enter your email address and we'll send you a link to reset your password.
                </p>
                <form class="space-y-4 md:space-y-6" action="<?php echo BASE_URL ?>controllers/auth/forgot_password.php" method="POST">
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                        <input type="email" name="email" id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="name@company.com" required="">
                    </div>
                    <button type="submit" name="submit"
                        class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                        Send Reset Link
                    </button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Remember your password? <a href="login.php" class="font-medium text-primary-600 hover:underline dark:text-blue-500">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?> 