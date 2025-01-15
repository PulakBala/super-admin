<?php
ob_start();
include('statement_functions.php');
include('modal.php');


$month = isset($_GET['month']) ? (int)$_GET['month'] : 0;
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y'); // Add year parameter

if ($month < 1 || $month > 12) {
	echo "Invalid month selected.";
	exit;
}

// Update function calls to include year parameter
$revenueData = getRevenueDataByCategory($conn, $month, $year);
$expenseData = getExpenseDataByCategory($conn, $month, $year);

if (!$revenueData && !$expenseData) {
	echo "No data available for the selected month and year.";
	exit;
}

// Convert the month number to a month name
$monthName = date('F', mktime(0, 0, 0, $month, 10));

// Calculate total revenue for the month
$total_revenue = $revenueData['total_sales'] + $revenueData['total_investment'] + $revenueData['total_loan_received'] + $revenueData['total_others'];


// Calculate total expenses for the month
$total_expenses = $expenseData['total_salaries'] + $expenseData['total_product_purchase'] + $expenseData['total_desk_rent'] + $expenseData['total_loan'] + $expenseData['total_cash_out'] +
    $expenseData['total_gift'] + $expenseData['total_mobile_recharge'] + $expenseData['total_teacher_payment'] + $expenseData['total_repaid_payment'] +$expenseData['total_marketing_payment'] + $expenseData['total_transport'] + $expenseData['total_utilities'] + $expenseData['total_maintenance_repairs'] +
	$expenseData['total_official_documents_cost'] + $expenseData['total_asset_purchase'] + $expenseData['total_others'];

// Calculate net profit/loss for the month
$net_profit_loss = $total_revenue - $total_expenses;

?>
<html lang="zxx">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>
		Accounts - DIT SECTOR3
	</title>
	<link rel="icon" href="img/fav.png" type="image/png">
	<link rel="stylesheet" href="css/bootstrap1.min.css">
	<link rel="stylesheet" href="vendors/themefy_icon/themify-icons.css">
	<link rel="stylesheet" href="vendors/swiper_slider/css/swiper.min.css">
	<link rel="stylesheet" href="vendors/select2/css/select2.min.css">
	<link rel="stylesheet" href="vendors/niceselect/css/nice-select.css">
	<link rel="stylesheet" href="vendors/owl_carousel/css/owl.carousel.css">
	<link rel="stylesheet" href="vendors/gijgo/gijgo.min.css">
	<link rel="stylesheet" href="vendors/font_awesome/css/all.min.css">
	<link rel="stylesheet" href="vendors/tagsinput/tagsinput.css">
	<link rel="stylesheet" href="vendors/datatable/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="vendors/datatable/css/responsive.dataTables.min.css">
	<link rel="stylesheet" href="vendors/datatable/css/buttons.dataTables.min.css">
	<link rel="stylesheet" href="vendors/text_editor/summernote-bs4.css">
	<link rel="stylesheet" href="vendors/morris/morris.css">
	<link rel="stylesheet" href="vendors/material_icon/material-icons.css">
	<link rel="stylesheet" href="css/metisMenu.css">
	<link rel="stylesheet" href="css/style1.css">
	<link rel="stylesheet" href="css/colors/default.css" id="colorSkinCSS">

	<style>
		.tooltip .tooltiptext {
			visibility: hidden;
			width: 237px;
			background-color: black;
			color: #fff;
			text-align: center;
			border-radius: 6px;
			padding: 10px 10px;
			position: absolute;
			z-index: 1;
			right: 29%;
			top: 65px;
		}

		.tooltip:hover .tooltiptext {
			visibility: visible;
		}

		.tooltip {
			z-index: 0;
		}

		.marquee_p {
			background: #f8f8f8;
			padding: 12px 19px;
			border-left: 3px solid #444;
			height: 41px;
			overflow: hidden;
			font-size: 18px;
		}

		.pagination {
			gap: 6px;
			margin: 18px 0px;
		}

		ul.pagination li.details {
			color: #AD2D2D;
		}

		ul.pagination li a {
			border: solid 1px;
			border-radius: 3px;
			-moz-border-radius: 3px;
			-webkit-border-radius: 3px;
			padding: 6px 9px 6px 9px;
		}

		ul.pagination li {
			padding-bottom: 1px;
		}

		ul.pagination li a:hover,
		ul.pagination li a.current {
			color: #FFFFFF;
			box-shadow: 0px 1px #EDEDED;
			-moz-box-shadow: 0px 1px #EDEDED;
			-webkit-box-shadow: 0px 1px #EDEDED;
		}

		ul.pagination li a {
			color: #E92F2F;
			border-color: #FFA5A5;
			background: #FFF8F8;
		}

		ul.pagination li a:hover,
		ul.pagination li a.current {
			text-shadow: 0px 1px #B72E2E;
			border-color: #AD2D2D;
			background: #E43838;
			background: -moz-linear-gradient(top, #FF9B9B 1px, #FE5555 1px, #E43838);
			background: -webkit-gradient(linear, 0 0, 0 100%, color-stop(0.02, #FF9B9B), color-stop(0.02, #FE5555), color-stop(1, #E43838));
		}

		.follow_head {
			text-align: center;
			width: 100%;
			background: #fa6511;
			padding: 9px 17px;
			font-size: 20px;
			color: #ffeb3b;
			border-top-left-radius: 8px;
			border-top-right-radius: 8px;
			margin-right: 5px;
		}

		.trdv {
			width: 100%;
			margin-bottom: 0;
			padding: 10px 10px;
			color: white;
			border-top-left-radius: 7px;
			border-top-right-radius: 7px;
			text-align: center;
		}

		.trdvt {
			background: #eeeeee;
			height: 44px;
			box-sizing: border-box;
			color: black;
			padding: 10px 10px;
			text-align: -webkit-center;
			border: 1px solid #4caf50;
			font-weight: bold;

		}

		.sants {
			text-align: center;
			width: 100%;
			float: left;
			color: #222222;
			border: 2px solid red;
			padding: 3px 0;
			border-radius: 5px;
			margin: 0 auto;
		}



		/* porgress start */
		.sa-vsnLbl span {
			background: #14b91b;
			font-size: 13px;
			padding: 5px 6px;
			color: white;
			border-radius: 10px;
		}

		.sa-vsnLbl {
			text-align: center;
			background: #FFEB3B;
			width: max-content;
			padding: 9px 26px;
			margin: 0 auto;
			margin-top: 12px;
			color: #673AB7;
			border-radius: 4px;
			margin-bottom: 5px;
		}

		.vefs-milestone-wrapper .milestone-container {
			display: -webkit-box;
			display: flex;
			-webkit-box-orient: vertical;
			-webkit-box-direction: normal;
			flex-direction: column;
			position: relative;
			width: 96%;
			height: 50px;
		}

		.vefs-milestone-wrapper .milestone-container .chart-container {
			display: -webkit-box;
			display: flex;
			-webkit-box-orient: horizontal;
			-webkit-box-direction: normal;
			flex-flow: row;
			-webkit-box-align: center;
			align-items: center;
			-webkit-box-flex: 1;
			flex: 1 50%;
		}

		.vefs-milestone-wrapper .milestone-container .chart-container .line-container {
			position: absolute;
			display: -webkit-box;
			display: flex;
			-webkit-box-align: center;
			align-items: center;
			width: 100%;
			height: 17.5px;
		}

		.vefs-milestone-wrapper .milestone-container .chart-container .line-container .line {
			align-self: center;
			position: absolute;
			top: 8.75px;
			-webkit-transform: translateY(-50%);
			transform: translateY(-50%);
			-webkit-box-ordinal-group: 2;
			order: 1;
			border-top-left-radius: 10px;
			border-bottom-left-radius: 10px;
			border-bottom-right-radius: 10px;
			width: 100%;
			border-top-right-radius: 10px;
			height: 15px;
			background-color: #cccccc;
			background-color: rgba(204, 204, 204, 0.5);
		}

		.vefs-milestone-wrapper .milestone-container .chart-container .line-container .line.left {
			-webkit-box-ordinal-group: 1;
			border-top-left-radius: 10px;
			border-bottom-left-radius: 10px;
			border-top-right-radius: 10px;
			order: 0;
			border-bottom-right-radius: 10px;
			background-color: #bbbbbb;
			background-color: #4CAF50;
			transition: all 2s ease;
		}

		.vefs-milestone-wrapper .milestone-container .chart-container .dot-container {
			position: absolute;
			height: 17.5px;
			width: 100%;
		}

		.vefs-milestone-wrapper .milestone-container .chart-container .dot-container .dot {
			position: absolute;
			width: 40px;
			height: 40px;
			border-radius: 50%;
			background-color: #cccccc;
			-webkit-transform: translateX(-50%);
			transform: translateX(-50%);
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
			top: -12px;
			border: 3px solid #fff;
			box-shadow: 0 0 4px #d6d3d3;
		}

		.vefs-milestone-wrapper .milestone-container .chart-container .dot-container .dot.completed {
			background-color: #bbbbbb;
		}

		.vefs-milestone-wrapper .milestone-container .label-container {
			display: -webkit-box;
			display: flex;
			-webkit-box-orient: horizontal;
			-webkit-box-direction: normal;
			flex-flow: row nowrap;
			-webkit-box-align: start;
			align-items: flex-start;
			-webkit-box-flex: 1;
			flex: 1 50%;
		}

		.vefs-milestone-wrapper .milestone-container .label-container .label {
			position: relative;
			font-size: 1.1rem;
			font-weight: 600;
			color: #cccccc;
			top: 10px;
			text-align: center;
		}

		.vefs-milestone-wrapper .milestone-container .label-container .label.colored {
			color: #bbbbbb;
		}

		.vefs-milestone-wrapper .milestone-container .milestones {
			position: absolute;
			-webkit-transform: translate(-50%, 0);
			transform: translate(-50%, 0);
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__1 {
			left: 1%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__1 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__2 {
			left: 2%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__2 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__3 {
			left: 3%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__3 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__4 {
			left: 4%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__4 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__5 {
			left: 5%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__5 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__6 {
			left: 6%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__6 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__7 {
			left: 7%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__7 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__8 {
			left: 8%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__8 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__9 {
			left: 9%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__9 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__10 {
			left: 10%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__10 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__11 {
			left: 11%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__11 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__12 {
			left: 12%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__12 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__13 {
			left: 13%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__13 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__14 {
			left: 14%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__14 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__15 {
			left: 15%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__15 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__16 {
			left: 16%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__16 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__17 {
			left: 17%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__17 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__18 {
			left: 18%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__18 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__19 {
			left: 19%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__19 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__20 {
			left: 20%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__20 .dot.colored {
			background-color: #ffbc42;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__21 {
			left: 21%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__21 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__22 {
			left: 22%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__22 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__23 {
			left: 23%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__23 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__24 {
			left: 24%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__24 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__25 {
			left: 25%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__25 .dot.colored {
			background-color: #03A9F4;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__26 {
			left: 26%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__26 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__27 {
			left: 27%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__27 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__28 {
			left: 28%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__28 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__29 {
			left: 29%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__29 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__30 {
			left: 30%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__30 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__31 {
			left: 31%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__31 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__32 {
			left: 32%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__32 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__33 {
			left: 33%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__33 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__34 {
			left: 34%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__34 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__35 {
			left: 35%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__35 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__36 {
			left: 36%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__36 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__37 {
			left: 37%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__37 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__38 {
			left: 38%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__38 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__39 {
			left: 39%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__39 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__40 {
			left: 40%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__40 .dot.colored {
			background-color: #d81159;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__41 {
			left: 41%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__41 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__42 {
			left: 42%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__42 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__43 {
			left: 43%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__43 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__44 {
			left: 44%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__44 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__45 {
			left: 45%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__45 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__46 {
			left: 46%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__46 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__47 {
			left: 47%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__47 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__48 {
			left: 48%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__48 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__49 {
			left: 49%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__49 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__50 {
			left: 50%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__50 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__51 {
			left: 51%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__51 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__52 {
			left: 52%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__52 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__53 {
			left: 53%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__53 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__54 {
			left: 54%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__54 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__55 {
			left: 55%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__55 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__56 {
			left: 56%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__56 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__57 {
			left: 57%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__57 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__58 {
			left: 58%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__58 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__59 {
			left: 59%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__59 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__60 {
			left: 60%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__60 .dot.colored {
			background-color: #8f2d56;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__61 {
			left: 61%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__61 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__62 {
			left: 62%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__62 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__63 {
			left: 63%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__63 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__64 {
			left: 64%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__64 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__65 {
			left: 65%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__65 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__66 {
			left: 66%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__66 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__67 {
			left: 67%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__67 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__68 {
			left: 68%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__68 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__69 {
			left: 69%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__69 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__70 {
			left: 70%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__70 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__71 {
			left: 71%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__71 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__72 {
			left: 72%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__72 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__73 {
			left: 73%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__73 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__74 {
			left: 74%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__74 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__75 {
			left: 75%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__75 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__76 {
			left: 76%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__76 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__77 {
			left: 77%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__77 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__78 {
			left: 78%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__78 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__79 {
			left: 79%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__79 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__80 {
			left: 80%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__80 .dot.colored {
			background-color: #218380;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__81 {
			left: 81%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__81 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__82 {
			left: 82%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__82 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__83 {
			left: 83%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__83 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__84 {
			left: 84%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__84 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__85 {
			left: 85%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__85 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__86 {
			left: 86%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__86 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__87 {
			left: 87%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__87 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__88 {
			left: 88%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__88 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__89 {
			left: 89%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__89 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__90 {
			left: 90%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__90 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__91 {
			left: 91%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__91 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__92 {
			left: 92%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__92 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__93 {
			left: 93%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__93 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__94 {
			left: 94%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__94 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__95 {
			left: 95%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__95 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__96 {
			left: 96%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__96 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__97 {
			left: 97%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__97 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__98 {
			left: 98%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__98 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__99 {
			left: 99%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__99 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__100 {
			left: 100%;
		}

		.vefs-milestone-wrapper .milestone-container .milestones.milestone__100 .dot.colored {
			background-color: #73d2de;
			-webkit-transition: all 0.25s ease-out;
			transition: all 0.25s ease-out;
		}

		.dot label {
			padding: 3px 6px;
			font-size: 19px;
			width: 100%;
			text-align: center;

			color: white;
			text-shadow: 0 0 8px;
		}

		/* porgress end */
		.sa-ntf-danger {
			background: #f443366e;
		}

		.sa-ntf {
			max-width: 500px;
			color: #ffffff;
			margin: 0 auto;
			background: #f443366e;
			padding: 10px 6px;
			border-radius: 4px;
			font-size: 16px;
			line-height: 16px;
			box-shadow: 3px 3px 7px 0px #d1d1d185;
			margin: 3px auto;
		}

		.sa-ntf-success {
			background: #4CAF50;
		}

		table {
			-webkit-overflow-scrolling: auto;
			-webkit-overflow-scrolling: auto;
			overscroll-behavior: contain;
			overflow: scroll;
			display: block;
			width: 100%;
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
			-ms-overflow-style: -ms-autohiding-scrollbar;
		}

		@keyframes moveArrow {

			0%,
			100% {
				transform: translateX(0);
			}

			50% {
				transform: translateX(5px);
			}
		}

		.category span {

			display: inline-block;
			transition: background-color 0.3s ease;
		}

		
		.category:hover span {
			padding: 0px 6px;
			background-color: #cdccd2; /* Light black */
			color: black; /* Font color white */
			cursor: pointer;
		}

	
	</style>
	<style type="text/css">
		.apexcharts-canvas {
			position: relative;
			user-select: none;
			/* cannot give overflow: hidden as it will crop tooltips which overflow outside chart area */
		}


		/* scrollbar is not visible by default for legend, hence forcing the visibility */
		.apexcharts-canvas ::-webkit-scrollbar {
			-webkit-appearance: none;
			width: 6px;
		}

		.apexcharts-canvas ::-webkit-scrollbar-thumb {
			border-radius: 4px;
			background-color: rgba(0, 0, 0, .5);
			box-shadow: 0 0 1px rgba(255, 255, 255, .5);
			-webkit-box-shadow: 0 0 1px rgba(255, 255, 255, .5);
		}

		.apexcharts-canvas.apexcharts-theme-dark {
			background: #424242;
		}

		.apexcharts-inner {
			position: relative;
		}

		.apexcharts-text tspan {
			font-family: inherit;
		}

		.legend-mouseover-inactive {
			transition: 0.15s ease all;
			opacity: 0.20;
		}

		.apexcharts-series-collapsed {
			opacity: 0;
		}

		.apexcharts-tooltip {
			border-radius: 5px;
			box-shadow: 2px 2px 6px -4px #999;
			cursor: default;
			font-size: 14px;
			left: 62px;
			opacity: 0;
			pointer-events: none;
			position: absolute;
			top: 20px;
			overflow: hidden;
			white-space: nowrap;
			z-index: 12;
			transition: 0.15s ease all;
		}

		.apexcharts-tooltip.apexcharts-active {
			opacity: 1;
			transition: 0.15s ease all;
		}

		.apexcharts-tooltip.apexcharts-theme-light {
			border: 1px solid #e3e3e3;
			background: rgba(255, 255, 255, 0.96);
		}

		.apexcharts-tooltip.apexcharts-theme-dark {
			color: #fff;
			background: rgba(30, 30, 30, 0.8);
		}

		.apexcharts-tooltip * {
			font-family: inherit;
		}


		.apexcharts-tooltip-title {
			padding: 6px;
			font-size: 15px;
			margin-bottom: 4px;
		}

		.apexcharts-tooltip.apexcharts-theme-light .apexcharts-tooltip-title {
			background: #ECEFF1;
			border-bottom: 1px solid #ddd;
		}

		.apexcharts-tooltip.apexcharts-theme-dark .apexcharts-tooltip-title {
			background: rgba(0, 0, 0, 0.7);
			border-bottom: 1px solid #333;
		}

		.apexcharts-tooltip-text-value,
		.apexcharts-tooltip-text-z-value {
			display: inline-block;
			font-weight: 600;
			margin-left: 5px;
		}

		.apexcharts-tooltip-text-z-label:empty,
		.apexcharts-tooltip-text-z-value:empty {
			display: none;
		}

		.apexcharts-tooltip-text-value,
		.apexcharts-tooltip-text-z-value {
			font-weight: 600;
		}

		.apexcharts-tooltip-marker {
			width: 12px;
			height: 12px;
			position: relative;
			top: 0px;
			margin-right: 10px;
			border-radius: 50%;
		}

		.apexcharts-tooltip-series-group {
			padding: 0 10px;
			display: none;
			text-align: left;
			justify-content: left;
			align-items: center;
		}

		.apexcharts-tooltip-series-group.apexcharts-active .apexcharts-tooltip-marker {
			opacity: 1;
		}

		.apexcharts-tooltip-series-group.apexcharts-active,
		.apexcharts-tooltip-series-group:last-child {
			padding-bottom: 4px;
		}

		.apexcharts-tooltip-series-group-hidden {
			opacity: 0;
			height: 0;
			line-height: 0;
			padding: 0 !important;
		}

		.apexcharts-tooltip-y-group {
			padding: 6px 0 5px;
		}

		.apexcharts-tooltip-candlestick {
			padding: 4px 8px;
		}

		.apexcharts-tooltip-candlestick>div {
			margin: 4px 0;
		}

		.apexcharts-tooltip-candlestick span.value {
			font-weight: bold;
		}

		.apexcharts-tooltip-rangebar {
			padding: 5px 8px;
		}

		.apexcharts-tooltip-rangebar .category {
			font-weight: 600;
			color: #777;
		}

		.apexcharts-tooltip-rangebar .series-name {
			font-weight: bold;
			display: block;
			margin-bottom: 5px;
		}

		.apexcharts-xaxistooltip {
			opacity: 0;
			padding: 9px 10px;
			pointer-events: none;
			color: #373d3f;
			font-size: 13px;
			text-align: center;
			border-radius: 2px;
			position: absolute;
			z-index: 10;
			background: #ECEFF1;
			border: 1px solid #90A4AE;
			transition: 0.15s ease all;
		}

		.apexcharts-xaxistooltip.apexcharts-theme-dark {
			background: rgba(0, 0, 0, 0.7);
			border: 1px solid rgba(0, 0, 0, 0.5);
			color: #fff;
		}

		.apexcharts-xaxistooltip:after,
		.apexcharts-xaxistooltip:before {
			left: 50%;
			border: solid transparent;
			content: " ";
			height: 0;
			width: 0;
			position: absolute;
			pointer-events: none;
		}

		.apexcharts-xaxistooltip:after {
			border-color: rgba(236, 239, 241, 0);
			border-width: 6px;
			margin-left: -6px;
		}

		.apexcharts-xaxistooltip:before {
			border-color: rgba(144, 164, 174, 0);
			border-width: 7px;
			margin-left: -7px;
		}

		.apexcharts-xaxistooltip-bottom:after,
		.apexcharts-xaxistooltip-bottom:before {
			bottom: 100%;
		}

		.apexcharts-xaxistooltip-top:after,
		.apexcharts-xaxistooltip-top:before {
			top: 100%;
		}

		.apexcharts-xaxistooltip-bottom:after {
			border-bottom-color: #ECEFF1;
		}

		.apexcharts-xaxistooltip-bottom:before {
			border-bottom-color: #90A4AE;
		}

		.apexcharts-xaxistooltip-bottom.apexcharts-theme-dark:after {
			border-bottom-color: rgba(0, 0, 0, 0.5);
		}

		.apexcharts-xaxistooltip-bottom.apexcharts-theme-dark:before {
			border-bottom-color: rgba(0, 0, 0, 0.5);
		}

		.apexcharts-xaxistooltip-top:after {
			border-top-color: #ECEFF1
		}

		.apexcharts-xaxistooltip-top:before {
			border-top-color: #90A4AE;
		}

		.apexcharts-xaxistooltip-top.apexcharts-theme-dark:after {
			border-top-color: rgba(0, 0, 0, 0.5);
		}

		.apexcharts-xaxistooltip-top.apexcharts-theme-dark:before {
			border-top-color: rgba(0, 0, 0, 0.5);
		}

		.apexcharts-xaxistooltip.apexcharts-active {
			opacity: 1;
			transition: 0.15s ease all;
		}

		.apexcharts-yaxistooltip {
			opacity: 0;
			padding: 4px 10px;
			pointer-events: none;
			color: #373d3f;
			font-size: 13px;
			text-align: center;
			border-radius: 2px;
			position: absolute;
			z-index: 10;
			background: #ECEFF1;
			border: 1px solid #90A4AE;
		}

		.apexcharts-yaxistooltip.apexcharts-theme-dark {
			background: rgba(0, 0, 0, 0.7);
			border: 1px solid rgba(0, 0, 0, 0.5);
			color: #fff;
		}

		.apexcharts-yaxistooltip:after,
		.apexcharts-yaxistooltip:before {
			top: 50%;
			border: solid transparent;
			content: " ";
			height: 0;
			width: 0;
			position: absolute;
			pointer-events: none;
		}

		.apexcharts-yaxistooltip:after {
			border-color: rgba(236, 239, 241, 0);
			border-width: 6px;
			margin-top: -6px;
		}

		.apexcharts-yaxistooltip:before {
			border-color: rgba(144, 164, 174, 0);
			border-width: 7px;
			margin-top: -7px;
		}

		.apexcharts-yaxistooltip-left:after,
		.apexcharts-yaxistooltip-left:before {
			left: 100%;
		}

		.apexcharts-yaxistooltip-right:after,
		.apexcharts-yaxistooltip-right:before {
			right: 100%;
		}

		.apexcharts-yaxistooltip-left:after {
			border-left-color: #ECEFF1;
		}

		.apexcharts-yaxistooltip-left:before {
			border-left-color: #90A4AE;
		}

		.apexcharts-yaxistooltip-left.apexcharts-theme-dark:after {
			border-left-color: rgba(0, 0, 0, 0.5);
		}

		.apexcharts-yaxistooltip-left.apexcharts-theme-dark:before {
			border-left-color: rgba(0, 0, 0, 0.5);
		}

		.apexcharts-yaxistooltip-right:after {
			border-right-color: #ECEFF1;
		}

		.apexcharts-yaxistooltip-right:before {
			border-right-color: #90A4AE;
		}

		.apexcharts-yaxistooltip-right.apexcharts-theme-dark:after {
			border-right-color: rgba(0, 0, 0, 0.5);
		}

		.apexcharts-yaxistooltip-right.apexcharts-theme-dark:before {
			border-right-color: rgba(0, 0, 0, 0.5);
		}

		.apexcharts-yaxistooltip.apexcharts-active {
			opacity: 1;
		}

		.apexcharts-yaxistooltip-hidden {
			display: none;
		}

		.apexcharts-xcrosshairs,
		.apexcharts-ycrosshairs {
			pointer-events: none;
			opacity: 0;
			transition: 0.15s ease all;
		}

		.apexcharts-xcrosshairs.apexcharts-active,
		.apexcharts-ycrosshairs.apexcharts-active {
			opacity: 1;
			transition: 0.15s ease all;
		}

		.apexcharts-ycrosshairs-hidden {
			opacity: 0;
		}

		.apexcharts-selection-rect {
			cursor: move;
		}

		.svg_select_boundingRect,
		.svg_select_points_rot {
			pointer-events: none;
			opacity: 0;
			visibility: hidden;
		}

		.apexcharts-selection-rect+g .svg_select_boundingRect,
		.apexcharts-selection-rect+g .svg_select_points_rot {
			opacity: 0;
			visibility: hidden;
		}

		.apexcharts-selection-rect+g .svg_select_points_l,
		.apexcharts-selection-rect+g .svg_select_points_r {
			cursor: ew-resize;
			opacity: 1;
			visibility: visible;
		}

		.svg_select_points {
			fill: #efefef;
			stroke: #333;
			rx: 2;
		}

		.apexcharts-canvas.apexcharts-zoomable .hovering-zoom {
			cursor: crosshair
		}

		.apexcharts-canvas.apexcharts-zoomable .hovering-pan {
			cursor: move
		}

		.apexcharts-zoom-icon,
		.apexcharts-zoomin-icon,
		.apexcharts-zoomout-icon,
		.apexcharts-reset-icon,
		.apexcharts-pan-icon,
		.apexcharts-selection-icon,
		.apexcharts-menu-icon,
		.apexcharts-toolbar-custom-icon {
			cursor: pointer;
			width: 20px;
			height: 20px;
			line-height: 24px;
			color: #6E8192;
			text-align: center;
		}

		.apexcharts-zoom-icon svg,
		.apexcharts-zoomin-icon svg,
		.apexcharts-zoomout-icon svg,
		.apexcharts-reset-icon svg,
		.apexcharts-menu-icon svg {
			fill: #6E8192;
		}

		.apexcharts-selection-icon svg {
			fill: #444;
			transform: scale(0.76)
		}

		.apexcharts-theme-dark .apexcharts-zoom-icon svg,
		.apexcharts-theme-dark .apexcharts-zoomin-icon svg,
		.apexcharts-theme-dark .apexcharts-zoomout-icon svg,
		.apexcharts-theme-dark .apexcharts-reset-icon svg,
		.apexcharts-theme-dark .apexcharts-pan-icon svg,
		.apexcharts-theme-dark .apexcharts-selection-icon svg,
		.apexcharts-theme-dark .apexcharts-menu-icon svg,
		.apexcharts-theme-dark .apexcharts-toolbar-custom-icon svg {
			fill: #f3f4f5;
		}

		.apexcharts-canvas .apexcharts-zoom-icon.apexcharts-selected svg,
		.apexcharts-canvas .apexcharts-selection-icon.apexcharts-selected svg,
		.apexcharts-canvas .apexcharts-reset-zoom-icon.apexcharts-selected svg {
			fill: #008FFB;
		}

		.apexcharts-theme-light .apexcharts-selection-icon:not(.apexcharts-selected):hover svg,
		.apexcharts-theme-light .apexcharts-zoom-icon:not(.apexcharts-selected):hover svg,
		.apexcharts-theme-light .apexcharts-zoomin-icon:hover svg,
		.apexcharts-theme-light .apexcharts-zoomout-icon:hover svg,
		.apexcharts-theme-light .apexcharts-reset-icon:hover svg,
		.apexcharts-theme-light .apexcharts-menu-icon:hover svg {
			fill: #333;
		}

		.apexcharts-selection-icon,
		.apexcharts-menu-icon {
			position: relative;
		}

		.apexcharts-reset-icon {
			margin-left: 5px;
		}

		.apexcharts-zoom-icon,
		.apexcharts-reset-icon,
		.apexcharts-menu-icon {
			transform: scale(0.85);
		}

		.apexcharts-zoomin-icon,
		.apexcharts-zoomout-icon {
			transform: scale(0.7)
		}

		.apexcharts-zoomout-icon {
			margin-right: 3px;
		}

		.apexcharts-pan-icon {
			transform: scale(0.62);
			position: relative;
			left: 1px;
			top: 0px;
		}

		.apexcharts-pan-icon svg {
			fill: #fff;
			stroke: #6E8192;
			stroke-width: 2;
		}

		.apexcharts-pan-icon.apexcharts-selected svg {
			stroke: #008FFB;
		}

		.apexcharts-pan-icon:not(.apexcharts-selected):hover svg {
			stroke: #333;
		}

		.apexcharts-toolbar {
			position: absolute;
			z-index: 11;
			max-width: 176px;
			text-align: right;
			border-radius: 3px;
			padding: 0px 6px 2px 6px;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.apexcharts-menu {
			background: #fff;
			position: absolute;
			top: 100%;
			border: 1px solid #ddd;
			border-radius: 3px;
			padding: 3px;
			right: 10px;
			opacity: 0;
			min-width: 110px;
			transition: 0.15s ease all;
			pointer-events: none;
		}

		.apexcharts-menu.apexcharts-menu-open {
			opacity: 1;
			pointer-events: all;
			transition: 0.15s ease all;
		}

		.apexcharts-menu-item {
			padding: 6px 7px;
			font-size: 12px;
			cursor: pointer;
		}

		.apexcharts-theme-light .apexcharts-menu-item:hover {
			background: #eee;
		}

		.apexcharts-theme-dark .apexcharts-menu {
			background: rgba(0, 0, 0, 0.7);
			color: #fff;
		}

		@media screen and (min-width: 768px) {
			.apexcharts-canvas:hover .apexcharts-toolbar {
				opacity: 1;
			}
		}

		.apexcharts-datalabel.apexcharts-element-hidden {
			opacity: 0;
		}

		.apexcharts-pie-label,
		.apexcharts-datalabels,
		.apexcharts-datalabel,
		.apexcharts-datalabel-label,
		.apexcharts-datalabel-value {
			cursor: default;
			pointer-events: none;
		}

		.apexcharts-pie-label-delay {
			opacity: 0;
			animation-name: opaque;
			animation-duration: 0.3s;
			animation-fill-mode: forwards;
			animation-timing-function: ease;
		}

		.apexcharts-canvas .apexcharts-element-hidden {
			opacity: 0;
		}

		.apexcharts-hide .apexcharts-series-points {
			opacity: 0;
		}

		.apexcharts-gridline,
		.apexcharts-annotation-rect,
		.apexcharts-tooltip .apexcharts-marker,
		.apexcharts-area-series .apexcharts-area,
		.apexcharts-line,
		.apexcharts-zoom-rect,
		.apexcharts-toolbar svg,
		.apexcharts-area-series .apexcharts-series-markers .apexcharts-marker.no-pointer-events,
		.apexcharts-line-series .apexcharts-series-markers .apexcharts-marker.no-pointer-events,
		.apexcharts-radar-series path,
		.apexcharts-radar-series polygon {
			pointer-events: none;
		}


		/* markers */

		.apexcharts-marker {
			transition: 0.15s ease all;
		}

		@keyframes opaque {
			0% {
				opacity: 0;
			}

			100% {
				opacity: 1;
			}
		}


		/* Resize generated styles */

		@keyframes resizeanim {
			from {
				opacity: 0;
			}

			to {
				opacity: 0;
			}
		}

		.resize-triggers {
			animation: 1ms resizeanim;
			visibility: hidden;
			opacity: 0;
		}

		.resize-triggers,
		.resize-triggers>div,
		.contract-trigger:before {
			content: " ";
			display: block;
			position: absolute;
			top: 0;
			left: 0;
			height: 100%;
			width: 100%;
			overflow: hidden;
		}

		.resize-triggers>div {
			background: #eee;
			overflow: auto;
		}

		.contract-trigger:before {
			width: 200%;
			height: 200%;
		}
	</style>
	<link href="data:text/css,%3Ais(%5Bid*%3D'google_ads_iframe'%5D%2C%5Bid*%3D'taboola-'%5D%2C.taboolaHeight%2C.taboola-placeholder%2C%23credential_picker_container%2C%23credentials-picker-container%2C%23credential_picker_iframe%2C%5Bid*%3D'google-one-tap-iframe'%5D%2C%23google-one-tap-popup-container%2C.google-one-tap-modal-div%2C%23amp_floatingAdDiv%2C%23ez-content-blocker-container)%20%7Bdisplay%3Anone!important%3Bmin-height%3A0!important%3Bheight%3A0!important%3B%7D" rel="stylesheet" type="text/css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			const downloadButton = document.getElementById('download-pdf');
			downloadButton.addEventListener('click', function() {
				const { jsPDF } = window.jspdf;
				const doc = new jsPDF();

				// Capture the content of the entire section including the header
			html2canvas(document.querySelector('.main_content'), {
					ignoreElements: function(element) {
						return element.id === 'download-pdf';
					}
				}).then(canvas => {
					const imgData = canvas.toDataURL('image/png');
					const imgWidth = 190; // Adjust width as needed
					const pageHeight = 295; // A4 page height in mm
					const imgHeight = canvas.height * imgWidth / canvas.width;
					let heightLeft = imgHeight;
					let position = 10;

					doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
					heightLeft -= pageHeight;

					while (heightLeft >= 0) {
						position = heightLeft - imgHeight;
						doc.addPage();
						doc.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
						heightLeft -= pageHeight;
					}

					doc.save('income_statement.pdf');
				});
			});
		});
	</script>
</head>

<body class="crm_body_bg" data-new-gr-c-s-check-loaded="8.912.0" data-gr-ext-installed="" cz-shortcut-listen="true">

	<section class="main_content dashboard_part">
		<div class="main_content_iner ">
			<div class="container-fluid p-0">
				<div class="row justify-content-center">
					<div class="col-12" style="background:white; padding:50px;">
						<div class="statement_heading" style="text-align:center; margin-bottom:30px;">
							<!-- <h2>DIT SECTOR3</h2> -->
							<h2>STATEMENT</h2>
							<p style="color:black;"> 
								Month Ended <span style="border-bottom:1px dashed red;"><?php echo $monthName; ?> <?php echo $year; ?></span>
							</p>
							<div>
								<!-- Add download icon -->
								<button id="download-pdf" style="margin-top: 20px;">
									<i class="fas fa-download"></i> Download PDF
								</button>
							</div>
						</div>
						<div class="statement_main">
							<div style="border:0px solid black; overflow:hidden;" class="statement_row">
								<div style="float:left; width:80%; background:#CDCCD2;color:black; text-align:center; font-size:24px; padding:5px;">
									Details

								</div>
								<div style="float:left; width:20%; background:#E2E9E2;color:black; text-align:center; font-size:24px; padding:5px;">
									<?php echo $monthName; ?>
								</div>
							</div>


							<div style="border:0px solid black; overflow: hidden;" class="statement_row">
								<div style="float:left; width:80%;">
									<h2 style="border-bottom:1px solid black; width:170px; margin-top:20px; margin-bottom:20px; font-size: 24px;"> Revenues : </h2>
									<!-- Revenue categories -->
									<p class="category"
										data-category="Sales"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="revenue"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Sales Revenue</span>
									</p>

									<p class="category"
										data-category="Investment"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="revenue"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span></i>Investment</span>
									</p>
									<p class="category"
										data-category="Loan Received"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="revenue"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span></i>Loan Received</span>
									</p>
									<p class="category"
										data-category="Others"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="revenue"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Others</span>
									</p>
									<p style="padding-left:140px; border-bottom:1px dotted white; font-weight:bold; color:black;">Total Revenues </p>
								</div>
								<div style="float:left; width:20%;">
									<h2 style="border-bottom:1px solid white; width:50px; color:white; margin-top:20px; margin-bottom:20px; font-size: 24px;">.</h2>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($revenueData['total_sales']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($revenueData['total_investment']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($revenueData['total_loan_received']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($revenueData['total_others']); ?></p>

									<p style="padding-left:50px; border-bottom:1px dotted black; font-weight:bold; color:black; width:180px;"><?php echo number_format($total_revenue); ?></p>
								</div>
							</div>

							<div style="border:0px solid black; overflow: hidden;" class="statement_row">
								<div style="float:left; width:80%;">
									<h2 style="border-bottom:1px solid black; width:320px; margin-top:20px; margin-bottom:20px; font-size: 24px;"> Operating Expenses : </h2>
									<!-- Expense categories -->
									<p class="category"
										data-category="Salaries"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Salaries</span>
									</p>
									
									<p class="category"
										data-category="Product Purchase"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Product Purchase</span>
									</p>

									<p class="category"
										data-category="Desk Rent"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Desk Rent</span>
									</p>

									<p class="category"
										data-category="Loan"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Loan paid</span>
									</p>
									
									<p class="category"
										data-category="Cash Out"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Cash Out</span>
									</p>
									
										<p class="category"
										data-category="Gift"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Gift</span>
									</p>
									
									<p class="category"
										data-category="Mobile Recharge"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Mobile Recharge</span>
									</p>
									
									<p class="category"
										data-category="Teacher Payment"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Teacher Payment</span>
									</p>
									
									<p class="category"
										data-category="Repaid"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Repaid</span>
									</p>
									
									<p class="category"
										data-category="Marketing"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Marketing</span>
									</p>

									<p class="category"
										data-category="Transport"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Transport</span>
									</p>

									<p class="category"
										data-category="Utilities"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Utilities</span>
									</p>

									<p class="category"
										data-category="Maintenance and repairs"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Maintenance and Repairs</span>
									</p>

									<p class="category"
										data-category="Official Documents Cost"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Official Documents Cost</span>
									</p>

									<p class="category"
										data-category="Asset Purchase"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Asset Purchase</span>
									</p>

									<p class="category"
										data-category="Others"
										data-month="<?php echo $month; ?>"
										data-year="<?php echo $year; ?>"
										data-type="expense"
										style="padding-left:140px; border-bottom:1px dotted gray;">
										<span>Others</span>
									</p>
									<p style="padding-left:140px; border-bottom:1px dotted white; font-weight:bold; color:black;">Total Operating Cost </p>
								</div>
								<div style="float:left; width:20%;">
									<h2 style="border-bottom:1px solid white; width:50px; color:white; margin-top:20px; margin-bottom:20px; font-size: 24px;">.</h2>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_salaries']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_product_purchase']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_desk_rent']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_loan']); ?></p>
										<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_cash_out']); ?></p>
										<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_gift']); ?></p>
										<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_mobile_recharge']); ?></p>
										<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_teacher_payment']); ?></p>
										<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_repaid_payment']); ?></p>
										<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_marketing_payment']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_transport']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_utilities']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_maintenance_repairs']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_official_documents_cost']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_asset_purchase']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted gray;"><?php echo number_format($expenseData['total_others']); ?></p>
									<p style="padding-left:50px; border-bottom:1px dotted black; font-weight:bold; color:black; width:180px;"><?php echo number_format($total_expenses); ?></p>
								</div>
							</div>
							<div style="border:0px solid black; overflow: hidden;" class="statement_row">
								<div style="float:left; width:80%;">
									<h2 style="border-bottom:1px solid black; width:320px; margin-top:20px; margin-bottom:20px; font-size: 24px;"> Net Profit/Loss : </h2>
									<p style="padding-left:140px; border-bottom:1px dotted white;">(Total Revenue - Total Operating Expense) </p>
								</div>
								<div style="float:left; width:20%;">
									<h2 style="border-bottom:1px solid white; width:50px; color:white; margin-top:20px; margin-bottom:20px; font-size: 24px;">.</h2>
									<p style="padding-left:50px; border-bottom:double; font-weight:bold; color:black; width:180px;">
										<?php echo number_format($net_profit_loss); ?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script src="js/statement_details.js"></script>
	<script src="js/jquery1-3.4.1.min.js"></script>
	<script src="js/popper1.min.js"></script>
	<script src="js/bootstrap1.min.js"></script>
	<script src="js/metisMenu.js"></script>
	<script src="vendors/count_up/jquery.waypoints.min.js"></script>
	<script src="vendors/chartlist/Chart.min.js"></script>
	<script src="vendors/count_up/jquery.counterup.min.js"></script>
	<script src="vendors/swiper_slider/js/swiper.min.js"></script>
	<script src="vendors/niceselect/js/jquery.nice-select.min.js"></script>
	<script src="vendors/owl_carousel/js/owl.carousel.min.js"></script>
	<script src="vendors/gijgo/gijgo.min.js"></script>
	<script src="vendors/datatable/js/jquery.dataTables.min.js"></script>
	<script src="vendors/datatable/js/dataTables.responsive.min.js"></script>
	<script src="vendors/datatable/js/dataTables.buttons.min.js"></script>
	<script src="vendors/datatable/js/buttons.flash.min.js"></script>
	<script src="vendors/datatable/js/jszip.min.js"></script>
	<script src="vendors/datatable/js/pdfmake.min.js"></script>
	<script src="vendors/datatable/js/vfs_fonts.js"></script>
	<script src="vendors/datatable/js/buttons.html5.min.js"></script>
	<script src="vendors/datatable/js/buttons.print.min.js"></script>
	<script src="js/chart.min.js"></script>
	<!--<script src="vendors/progressbar/jquery.barfiller.js"></script>-->
	<script src="vendors/tagsinput/tagsinput.js"></script>
	<script src="vendors/text_editor/summernote-bs4.js"></script>
	<script src="vendors/apex_chart/apexcharts.js"></script>
	<script src="js/custom.js"></script>
	<script src="vendors/apex_chart/bar_active_1.js"></script>
	<script src="vendors/apex_chart/apex_chart_list.js"></script>

	<svg id="SvgjsSvg1001" width="2" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" style="overflow: hidden; top: -100%; left: -100%; position: absolute; opacity: 0;">
		<defs id="SvgjsDefs1002"></defs>
		<polyline id="SvgjsPolyline1003" points="0,0"></polyline>
		<path id="SvgjsPath1004" d="M0 0 "></path>
	</svg>



</body><grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration>

</html>
<?php include('footer.php') ?>