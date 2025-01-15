<?php
include('header.php') 

?>
			<main class="content">
				<div class="container-fluid p-0">
					<div class="row">
						<div class="col-12">
							<div class="row g-4">
								<!-- Revenue Card -->
								<div class="col-md-6">
									<div class="card h-100 shadow-lg">
										<div class="card-body p-4">
											<h5 class="card-title mb-4">Monthly Revenue</h5>
											<h1 class="mt-1 mb-3">
												<?php 
											
													$company_id = $_SESSION['company_id'] ?? 1; // Get company_id from session or default to 1
												
													$current_month = date('m');
													$current_year = date('Y');
													$stmt = $conn->prepare("SELECT SUM(amount) as total FROM revenue WHERE MONTH(date) = ? AND YEAR(date) = ? AND company_id = ?");
													$stmt->execute([$current_month, $current_year, $company_id]);
													$row = $stmt->fetch(PDO::FETCH_ASSOC);
													echo number_format($row['total'] ?? 0, 2) . " Tk";
												?>
											</h1>
										</div>
									</div>
								</div>

								<!-- Expense Card -->
								<div class="col-md-6">
									<div class="card h-100 shadow-lg">
										<div class="card-body p-4">
											<h5 class="card-title mb-4">Monthly Expense</h5>
											<h1 class="mt-1 mb-3">
												<?php 
													$stmt = $conn->prepare("SELECT SUM(amount) as total FROM expense WHERE MONTH(date) = ? AND YEAR(date) = ? AND company_id = ?");
													$stmt->execute([$current_month, $current_year, $company_id]);
													$row = $stmt->fetch(PDO::FETCH_ASSOC);
													echo number_format($row['total'] ?? 0, 2) . " Tk";
												?>
											</h1>
										</div>
									</div>
								</div>

								<!-- Profit Card -->
								<div class="col-md-6">
									<div class="card h-100 shadow-lg">
										<div class="card-body p-4">
											<h5 class="card-title mb-4">Monthly Profit</h5>
											<h1 class="mt-1 mb-3">
												<?php 
													$stmt = $conn->prepare("SELECT 
														(SELECT COALESCE(SUM(amount), 0) FROM revenue WHERE MONTH(date) = ? AND YEAR(date) = ? AND company_id = ?) -
														(SELECT COALESCE(SUM(amount), 0) FROM expense WHERE MONTH(date) = ? AND YEAR(date) = ? AND company_id = ?) as profit");
													$stmt->execute([$current_month, $current_year, $company_id, $current_month, $current_year, $company_id]);
													$row = $stmt->fetch(PDO::FETCH_ASSOC);
													$profit = $row['profit'] > 0 ? $row['profit'] : 0;
													echo number_format($profit, 2) . " Tk";
												?>
											</h1>
										</div>
									</div>
								</div>

								<!-- Loss Card -->
								<div class="col-md-6">
									<div class="card h-100 shadow-lg">
										<div class="card-body p-4">
											<h5 class="card-title mb-4">Monthly Loss</h5>
											<h1 class="mt-1 mb-3">
												<?php 
													$stmt = $conn->prepare("SELECT 
														(SELECT COALESCE(SUM(amount), 0) FROM expense WHERE MONTH(date) = ? AND YEAR(date) = ? AND company_id = ?) -
														(SELECT COALESCE(SUM(amount), 0) FROM revenue WHERE MONTH(date) = ? AND YEAR(date) = ? AND company_id = ?) as loss");
													$stmt->execute([$current_month, $current_year, $company_id, $current_month, $current_year, $company_id]);
													$row = $stmt->fetch(PDO::FETCH_ASSOC);
													$loss = $row['loss'] > 0 ? $row['loss'] : 0;
													echo number_format($loss, 2) . " Tk";
												?>
											</h1>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</main>
<?php include('footer.php')?>
