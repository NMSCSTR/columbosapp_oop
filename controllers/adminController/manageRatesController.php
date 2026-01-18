<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/fraternalBenefitsModel.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $plan_id = $_POST['plan_id'];
    $model = new fraternalBenefitsModel($conn);

    // --- ACTION: ADD NEW COLUMN (CATEGORY) ---
    if ($action === 'add_category') {
        $name = $_POST['name']; // e.g., "50K - <100K"
        $min_face = !empty($_POST['min_face']) ? $_POST['min_face'] : 0;
        $max_face = !empty($_POST['max_face']) ? $_POST['max_face'] : NULL;
        $is_adb = isset($_POST['is_adb']) ? 1 : 0;

        if ($model->addRateCategory($plan_id, $name, $min_face, $max_face, $is_adb)) {
            $_SESSION['success'] = "Category '$name' added successfully.";
        } else {
            $_SESSION['error'] = "Failed to add category.";
        }
    }

    // --- ACTION: ADD RATE (ROW) ---
    elseif ($action === 'add_rate') {
        $category_id = $_POST['category_id'];
        $min_age = $_POST['min_age'];
        $max_age = $_POST['max_age'];
        $rate_val = $_POST['rate'];

        if ($model->addRate($category_id, $min_age, $max_age, $rate_val)) {
            $_SESSION['success'] = "Rate added for Age $min_age-$max_age.";
        } else {
            $_SESSION['error'] = "Failed to add rate.";
        }
    }

    // --- ACTION: DELETE CATEGORY ---
    elseif ($action === 'delete_category') {
        $cat_id = $_POST['category_id'];
        if ($model->deleteRateCategory($cat_id)) {
            $_SESSION['success'] = "Category deleted.";
        } else {
            $_SESSION['error'] = "Failed to delete category.";
        }
    }

    // Redirect back to the Manage Rates page
    header("Location: " . BASE_URL . "views/admin/manageRates.php?id=" . $plan_id);
    exit;
}
?>