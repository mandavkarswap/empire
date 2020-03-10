<?php
$pageName = "Index";

$moduels = array(
			'Expense' => array(
							'Insert Expense' => '/expense.php',
							'Expense Stats' => '/expense-stats.php',
							'Insert Expense Type' => '/expense-type.php',
						),
			'Stocks' => array(
							'Stock Order' => '/stock.php',
							'Insert New Stock' => '/insert-stock.php',
							'View Portfolio' => '/portfolio-list.php',
						),

			'Receivables' => array(
							'Insert Receivables' => '/receivables.php',
							'Receivables List' => '/receivables-list.php',
							'Received Receivables' => '/received-receivables.php',
							'Insert Borrower' => '/insert-borrower.php',
						),
			'Notes Payable' => array(
							'Insert Notes Payable' => '/notes-payable.php',
							'Notes Payable List' => '/notes-payable-list.php',
							'Payable Notes Paid' => '/notes-payable-paid.php',
						),
			'Income' => array(
							'Insert Income' => '/income.php',
							'List Income' => '/income-list.php',
							'Insert Loaner' => '/insert-loaner.php',
							'Insert Income Type' => '/insert-income-type.php',
						),
		);
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageName; ?> | FS</title>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<center>
<h1><?php echo $pageName;?></h2>
<?php
foreach ($moduels as $moduleName => $pageArr) {
	?>
		<table border=1>
			<thead>
				<th><?php echo $moduleName;?></th>
			</thead>
			<tbody>
	<?php
	foreach ($pageArr as $pageNameText => $pageLink) {
		?>
				<tr>
					<td><a href="<?php echo $pageLink;?>"><?php echo $pageNameText;?></a></td>
				</tr>
		<?php

	}
	?>
			</tbody>
		</table>
		<br>
		<br>
	<?php
}
?>
</center>
</body>
</html>