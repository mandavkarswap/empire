<?php
/**
 * TODO :
 * Need expense sefgregation into 
 * 	NEcessary
 *  Lifestyle
 *  Houseing etc etc
 *
 * Month filter
 */
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

// Housing
$housingExpenses = 0.00;
$lifeStyleExpenses = 0.00;
$expenseArrKey = array(
			'home_rent',
			'home_expenses',
			'maintenance',
		);

// Life style
$expenseArrKeyLifeStyle = array(
			'food',
			'smoke',
			'phone_bill',
			'concerts',
			'subscriptions',
		);

foreach ($expenseTypeArr as $expenseType) {

	$expenseTypeName = $expenseType['typeName'];

	if (in_array($expenseTypeName, $expenseArrKey)) {
		$housingExpenses += $expenseType['totalExpense'];
	}

	if (in_array($expenseTypeName, $expenseArrKeyLifeStyle)) {
		$lifeStyleExpenses += $expenseType['totalExpense'];
	}
}

$totalIncome = $totalIncome[0]['amount'];
$totalExpense = $totalExpense[0]['amount'];
$netMonthlyCashFlow = $totalIncome - $totalExpense;

$totalAssetArr = $bankTotal + $stockTotal + $mutualFundTotal + $receivablesTotal + $providentFundTotal;

$doodadsTotal = $doodadsTotalArr[0]['amount'];

$bankerAsset = $totalAssetArr + $doodadsTotal;
$richDadAsset = $totalAssetArr;

$totalLiabilities = $notesPayableTotal[0]['remaining_amount'];
$netWorthBanker = $bankerAsset - $totalLiabilities;
$netWorthRichDad = $richDadAsset - $totalLiabilities;


// Analysis
$doYouKeep = $netMonthlyCashFlow / $totalIncome;
$howMuchHousing = $housingExpenses / $totalIncome;
$howMuchLifeStyle = $lifeStyleExpenses / $totalIncome;
$howMuchWealthy = $richDadAsset / $totalExpense;
$howMuchDoodadSpent = $doodadsTotal / $bankerAsset;

function numFormat($num, $isDecimal = false) {
	if ($isDecimal) {
		return number_format(floatval($num), 2);
	} else {
		return number_format(floatval($num));
	}
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
									<div class="col-md-2"><?php echo numFormat($totalIncome);?></div>
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
									<div class="col-md-2"><?php echo numFormat($totalExpense);?></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">Net Monthly Cash Flow</div>
									<div class="col-md-2"><?php echo numFormat($netMonthlyCashFlow);?></div>
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
									<div class="col-md-2"><?php echo numFormat($doYouKeep, true);?></div>
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
									<div class="col-md-10">Does Your Money Work For You?</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Passive+Portfolio/Total Inc</div>
									<div class="col-md-2">0.00</div>
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
									<div class="col-md-10">How Much Do You Pay In Taxes?</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Income Taxes/Total Income</div>
									<div class="col-md-2">0.00</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">How Much Goes to Housing?</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Housing Expenses/Income</div>
									<div class="col-md-2"><?php echo numFormat($howMuchHousing, true);?></div>
								</div>
								<div class="row">
									<div class="col-md-10">***keep under 33 percent (Life Style + Housing)</div>
									<div class="col-md-2"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">How Much Goes in to Life Style?</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Life Style Expenses/Income</div>
									<div class="col-md-2"><?php echo numFormat($howMuchLifeStyle, true);?></div>
								</div>
								<div class="row">
									<div class="col-md-10">***keep under 33 percent  (Life Style + Housing)</div>
									<div class="col-md-2"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">How Much Do You Spend on Doodads?</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Doodad Total/Banker Assets</div>
									<div class="col-md-2"><?php echo numFormat($howMuchDoodadSpent, true);?></div>
								</div>
								<div class="row">
									<div class="col-md-10">***keep under 33 percent</div>
									<div class="col-md-2"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">What Is Your Annual Return On Assets?</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Pass+Port/Rich Dad Assets</div>
									<div class="col-md-2">0.00</div>
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
									<div class="col-md-10">How Wealthy Are You?</div>
									<div class="col-md-2"></div>
								</div>
								<div class="row">
									<div class="col-md-10">Rich Dad Assets/Expenses</div>
									<div class="col-md-2"><?php echo numFormat($howMuchWealthy, true);?></div>
								</div>
								<div class="row">
									<div class="col-md-10">***measured in months</div>
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
									<div class="col-md-2"><?php echo numFormat($doodadsTotal);?></div>
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
									<div class="col-md-2"><?php echo numFormat($bankerAsset);?></div>
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
									<div class="col-md-2"><?php echo numFormat($richDadAsset);?></div>
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
								<?php
									if (isset($creditCard) && !empty($creditCard)) {
								?>
								<div class="row">
									<div class="col-md-10">Credit Cards</div>
									<div class="col-md-2">123123</div>
								</div>
								<?php
									}
								?>
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
									<div class="col-md-2"><?php echo numFormat($totalLiabilities);?></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="row subheader">
									<div class="col-md-10">NET WORTH per Banker</div>
									<div class="col-md-2"><?php echo numFormat($netWorthBanker);?></div>
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
									<div class="col-md-2"><?php echo numFormat($netWorthRichDad);?></div>
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