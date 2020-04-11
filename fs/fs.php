<?php
require_once('config/config.inc.php');

require_once(DOC_ROOT . '/libs/CommonFunctions.php');

// DB::$user = DB_USER;
// DB::$password = DB_PASSWORD;
// DB::$dbName = DB_NAME;

$pageName = 'Finance Statement';

$query = "CALL sp_sw_fs_select_fs();";
$resultSetArr = getResultsFromSP("localhost", DB_USER, DB_PASSWORD, DB_NAME, $query);

list($earnedIncomeArr,
	$totalIncome,
	$expenseTypeArr,
	$totalExpense,
	$stockInfoArr,
	$stockTotal,
	$notesPayableArr,
	$notesPayableTotal,
	$receivablesNameInfoArr,
	$receivablesTotalInfoArr,
	$bankNameInfoArr,
	$bankTotalArr,
	$mutualFundInfoArr,
	$mutualFundTotalArr,
	$providentFundInfoArr,
	$providentFundTotalArr,
	$doodadsInfoArr,
	$doodadsTotalArr)
	= parseFSResultSet($resultSetArr);

$bankTotal = !empty($bankTotalArr[0]['amount']) ? $bankTotalArr[0]['amount'] : 0;
$stockTotal = !empty($stockTotal[0]['total_stock_price']) ? $stockTotal[0]['total_stock_price'] : 0;
$mutualFundTotal = !empty($mutualFundTotalArr[0]['current_value']) ? $mutualFundTotalArr[0]['current_value'] : 0;
$receivablesTotal = !empty($receivablesTotalInfoArr[0]['remaining_amount']) ? $receivablesTotalInfoArr[0]['remaining_amount'] : 0;
$providentFundTotal = !empty($providentFundTotalArr[0]['current_value']) ? $providentFundTotalArr[0]['current_value'] : 0;

$totalAssetArr = $bankTotal + $stockTotal + $mutualFundTotal + $receivablesTotal + $providentFundTotal;

function numFormat($num) {
	return number_format(floatval($num));
}

function parseFSResultSet($result = array()) {
	$earnedIncomeArr = $result[0];
	$totalIncome = $result[1];
	$expenseTypeArr = $result[2];
	$totalExpense = $result[3];
	$stockInfoArr = $result[4];
	$stockTotal = $result[5];
	$notesPayableArr = $result[6];
	$notesPayableTotal = $result[7];
	$receivablesNameInfoArr = $result[8];
	$receivablesTotalInfoArr = $result[9];
	$bankNameInfoArr = $result[10];
	$bankTotalArr = $result[11];
	$mutualFundInfoArr = $result[12];
	$mutualFundTotalArr = $result[13];
	$providentFundInfoArr = $result[14];
	$providentFundTotalArr = $result[15];
	$doodadsInfoArr = $result[16];
	$doodadsTotalArr = $result[17];

	$realEstateNameArr = array();
	$realEstateTotalArr = array();

	return array($earnedIncomeArr, $totalIncome, $expenseTypeArr, $totalExpense, $stockInfoArr, $stockTotal, $notesPayableArr, $notesPayableTotal, $receivablesNameInfoArr, $receivablesTotalInfoArr, $bankNameInfoArr, $bankTotalArr, $mutualFundInfoArr, $mutualFundTotalArr, $providentFundInfoArr, $providentFundTotalArr, $doodadsInfoArr, $doodadsTotalArr);
}
?>

<!DOCTYPE html>
<html>
<head>

	<?php echo getPageMeta();?>
  	<title><?php echo $pageName; ?> | FS</title>
  	<?php echo getPageCss();?>

	<style type="text/css">
		.cont {
			border:1px solid #80ccff;
		}

		.subheader {
			font-weight: bold;
			/*border:1px solid #80ccff;*/
			/*background-color: #b3ffe0;*/
		}

		.secheader {
			font-weight: bold;
			text-align: center;
			background-color: #99d6ff;
			border:1px solid #80ccff;
		}

		.total {
			font-weight: bold;
			font-style: italic;
		}
		
	</style>

</head>
<body>
	<div class="container cont">
		<!-- Left and right Container -->
		<div class="row">			
			<!-- left box -->
			<div class="col">			
				<!-- Income Box Starts -->
				<div class="row">
					<div class="col">
						<div class="row">
							<div class="col secheader">
								INCOME
							</div>
						</div>
						<?php if (isset($earnedIncomeArr)) {
						?>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">Earned Income</div>
									<div class="col-md-2"></div>
								</div>
								<?php
									foreach ($earnedIncomeArr as $earnedIncomeDetails) {
								?>
								<div class="row">
									<div class="col-md-10"><?php echo $earnedIncomeDetails['type'];?></div>
									<div class="col-md-2"><?php echo numFormat($earnedIncomeDetails['amount']);?></div>
								</div>
								<?php
									}
								?>
							</div>
						</div>
						<?php
							}
						?>
						<?php if (isset($passiveIncomeArr)) {
						?>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">Passive Income</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Affinity</div>
									<div class="col-md-2">123123</div>
								</div>
								<div class="row">
									<div class="col-md-10">Income #2</div>
									<div class="col-md-2">123123</div>
								</div>
								<div class="row">
									<div class="col-md-10">Total Income</div>
									<div class="col-md-2">123123</div>
								</div>
							</div>
						</div>
						<?php
							}
						?>
						<?php if (isset($portfolioIncomeArr)) {
						?>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">Portfolio Income</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Affinity</div>
									<div class="col-md-2">123123</div>
								</div>
								<div class="row">
									<div class="col-md-10">Income #2</div>
									<div class="col-md-2">123123</div>
								</div>
								<div class="row">
									<div class="col-md-10">Total Income</div>
									<div class="col-md-2">123123</div>
								</div>
							</div>
						</div>
						<?php
							}
						?>
						<div class="row">
							<div class="col">
								<div class="row total">
									<div class="col-md-10">Total Income</div>
									<div class="col-md-2"><?php echo numFormat($totalIncome[0]['amount']);?></div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Expense Box starts -->
				<div class="row">
					<div class="col">
						<div class="row">
							<div class="col secheader">
								EXPENSE
							</div>
						</div>
						<div class="row">
							<div class="col">
								<?php
									foreach ($expenseTypeArr as $expenseType) {
								?>
								<div class="row">
									<div class="col-md-10"><?php echo $expenseType['typeName'];?></div>
									<div class="col-md-2"><?php echo numFormat($expenseType['totalExpense']);?></div>
								</div>
								<?php
									}
								?>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">Total Expenses</div>
									<div class="col-md-2"><?php echo numFormat($totalExpense[0]['amount']);?></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">Net Monthly Cash Flow</div>
									<div class="col-md-2"><?php echo numFormat($totalIncome[0]['amount'] - $totalExpense[0]['amount']);?></div>
								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Right box -->
			<div class="col">			
				<!-- Income Box Starts -->
				<div class="row">
					<div class="col">
						<div class="row">
							<div class="col secheader">
								ANALYSIS
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">How Much Do You Keep</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Cash Flow/Total Income</div>
									<div class="col-md-2">123123</div>
								</div>
								<div class="row">
									<div class="col-md-10">***should be increasing</div>
									<div class="col-md-2"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">How Much Do You Keep</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Cash Flow/Total Income</div>
									<div class="col-md-2">123123</div>
								</div>
								<div class="row">
									<div class="col-md-10">***should be increasing</div>
									<div class="col-md-2"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">How Much Do You Keep</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Cash Flow/Total Income</div>
									<div class="col-md-2">123123</div>
								</div>
								<div class="row">
									<div class="col-md-10">***should be increasing</div>
									<div class="col-md-2"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">How Much Do You Keep</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Cash Flow/Total Income</div>
									<div class="col-md-2">123123</div>
								</div>
								<div class="row">
									<div class="col-md-10">***should be increasing</div>
									<div class="col-md-2"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Left and right Container -->
		<!-- second row -->
		<div class="row">			
			<!-- left box -->
			<div class="col">			
				<!-- Assets Box Starts -->
				<div class="row">
					<div class="col">
						<div class="row">
							<div class="col secheader">
								ASSETS
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">Assets</div>
									<div class="col-md-2"></div>
								</div>
								<?php if (isset($bankTotal)) {
								?>
								<div class="row">
									<div class="col-md-10">Bank Accounts</div>
									<div class="col-md-2"><?php echo numFormat($bankTotal);?></div>
								</div>
								<?php
									}
								?>
								<?php if (isset($stockTotal)) {
								?>
								<div class="row">
									<div class="col-md-10">Stocks</div>
									<div class="col-md-2"><?php echo numFormat($stockTotal);?></div>
								</div>
								<?php
									}
								?>
								<?php if (isset($mutualFundTotal)) {
								?>
								<div class="row">
									<div class="col-md-10">Mutual Fund</div>
									<div class="col-md-2"><?php echo numFormat($mutualFundTotal);?></div>
								</div>
								<?php
									}
								?>
								<?php if (isset($providentFundTotal)) {
								?>
								<div class="row">
									<div class="col-md-10">Provident Fund</div>
									<div class="col-md-2"><?php echo numFormat($providentFundTotal);?></div>
								</div>
								<?php
									}
								?>
								<?php if (isset($receivablesTotal)) {
								?>
								<div class="row">
									<div class="col-md-10">Receivables</div>
									<div class="col-md-2"><?php echo numFormat($receivablesTotal);?></div>
								</div>
								<?php
									}
								?>
								<?php if (isset($realEstateTotalArr)) {
								?>
								<div class="row">
									<div class="col-md-10">Real Estate</div>
									<div class="col-md-2">##INSERT_VALUE##</div>
								</div>
								<?php
									}
								?>
								<?php if (isset($totalAssetArr)) {
								?>
								<div class="row total">
									<div class="col-md-10">Total Assets</div>
									<div class="col-md-2"><?php echo numFormat($totalAssetArr);?></div>
								</div>
								<?php
									}
								?>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">Doodads</div>
									<div class="col-md-2"></div>
								</div>
								<?php if (!empty($doodadsInfoArr)) {
									foreach ($doodadsInfoArr as $doodadsInfo) {
								?>
								<div class="row">
									<div class="col-md-10"><?php echo $doodadsInfo['name'];?></div>
									<div class="col-md-2"><?php echo numFormat($doodadsInfo['amount']);?></div>
								</div>
								<?php
									}
								}
								?>
								<?php if (!empty($doodadsTotalArr)) {
								?>
								<div class="row total">
									<div class="col-md-10">Total Doodads</div>
									<div class="col-md-2"><?php echo numFormat($doodadsTotalArr[0]['amount']);?></div>
								</div>
								<?php
								}
								?>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">Total Assets Per Banker</div>
									<div class="col-md-2">123123</div>
								</div>
								<div class="row">
									<div class="col-md-10">(Assets Total + Doodads)</div>
									<div class="col-md-2"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">TOTAL ASSETS per Rich Dad</div>
									<div class="col-md-2">123123</div>
								</div>
								<div class="row">
									<div class="col-md-10">(Assets Total Only, No Doodads)</div>
									<div class="col-md-2"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Right box -->
			<div class="col">			
				<!-- Income Box Starts -->
				<div class="row">
					<div class="col">
						<div class="row">
							<div class="col secheader">
								LIABILITIES
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">Liabilities</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Credit Cards</div>
									<div class="col-md-2">123123</div>
								</div>
								<?php
									if (isset($notesPayableTotal) && !empty($notesPayableTotal)) {
								?>
								<div class="row">
									<div class="col-md-10">Notes Payable</div>
									<div class="col-md-2"><?php echo numFormat($notesPayableTotal[0]['remaining_amount']);?></div>
								</div>
								<?php
									}
								?>
								<div class="row total">
									<div class="col-md-10">TOTAL LIABILITIES</div>
									<div class="col-md-2">123123</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">NET WORTH per Banker</div>
									<div class="col-md-2">123123</div>
								</div>
								<div class="row">
									<div class="col-md-10">(Total Assets per Banker minus Total Liabilities)</div>
									<div class="col-md-2"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">NET WORTH per Rich Dad</div>
									<div class="col-md-2">123123</div>
								</div>
								<div class="row">
									<div class="col-md-10">(Total Assets per Rich Dad minus Total Liabilities)</div>
									<div class="col-md-2"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>