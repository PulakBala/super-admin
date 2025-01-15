<?php 
include('header.php');

function getRevenueDataByCategory($conn, $month, $year) {
    $company_id = $_SESSION['company_id'];

    $sql = "SELECT 
        SUM(CASE WHEN category = 'sales' THEN amount ELSE 0 END) as total_sales,
        SUM(CASE WHEN category = 'investment' THEN amount ELSE 0 END) as total_investment,
        SUM(CASE WHEN category = 'loan received' THEN amount ELSE 0 END) as total_loan_received,
        SUM(CASE WHEN category = 'others' THEN amount ELSE 0 END) as total_others
        FROM revenue
        WHERE MONTH(date) = :month AND YEAR(date) = :year AND company_id = :company_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':month' => $month,
        ':year' => $year,
        ':company_id' => $company_id
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getExpenseDataByCategory($conn, $month, $year) {
    $company_id = $_SESSION['company_id'];

    $sql = "SELECT 
        SUM(CASE WHEN category = 'salaries' THEN amount ELSE 0 END) as total_salaries,
        SUM(CASE WHEN category = 'Product Purchase' THEN amount ELSE 0 END) as total_product_purchase,
        SUM(CASE WHEN category = 'desk rent' THEN amount ELSE 0 END) as total_desk_rent,
        SUM(CASE WHEN category = 'loan' THEN amount ELSE 0 END) as total_loan,
        SUM(CASE WHEN category = 'cash out' THEN amount ELSE 0 END) as total_cash_out,
        SUM(CASE WHEN category = 'Gift' THEN amount ELSE 0 END) as total_gift,
        SUM(CASE WHEN category = 'Mobile Recharge' THEN amount ELSE 0 END) as total_mobile_recharge,
        SUM(CASE WHEN category = 'Teacher Payment' THEN amount ELSE 0 END) as total_teacher_payment,
        SUM(CASE WHEN category = 'Repaid' THEN amount ELSE 0 END) as total_repaid_payment,
        SUM(CASE WHEN category = 'Marketing' THEN amount ELSE 0 END) as total_marketing_payment,
        SUM(CASE WHEN category = 'transport' THEN amount ELSE 0 END) as total_transport,
        SUM(CASE WHEN category = 'utilities' THEN amount ELSE 0 END) as total_utilities,
        SUM(CASE WHEN category = 'maintenance and repairs' THEN amount ELSE 0 END) as total_maintenance_repairs,
        SUM(CASE WHEN category = 'official documents cost' THEN amount ELSE 0 END) as total_official_documents_cost,
        SUM(CASE WHEN category = 'asset purchase' THEN amount ELSE 0 END) as total_asset_purchase,
        SUM(CASE WHEN category = 'others' THEN amount ELSE 0 END) as total_others
        FROM expense
        WHERE MONTH(date) = :month AND YEAR(date) = :year AND company_id = :company_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':month' => $month,
        ':year' => $year,
        ':company_id' => $company_id
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
