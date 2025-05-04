<?php
    if (isset($_SESSION['success'])) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '" . addslashes($_SESSION['success']) . "',
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
            text: '" . addslashes($_SESSION['error']) . "',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Try Again'
        });
        </script>";
        unset($_SESSION['error']);
    }
?>