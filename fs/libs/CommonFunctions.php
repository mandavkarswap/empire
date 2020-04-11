<?php
function getSelectOptions($optArr) {
  $optStr = '';
  $tmpArr = array();

  foreach ($optArr as $optKey => $optVal) {
    $tmpArr[] = '<option value="' . $optKey . '">' . $optVal . '</option>';
  }

  $optStr = implode('', $tmpArr);

  return $optStr;
}

function getPageCss() {

  $css = <<<PAGE_CSS
<!-- Font Awesome -->
<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"> -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<!-- Material Design Bootstrap -->
<link href="css/mdb.min.css" rel="stylesheet">
<!-- Your custom styles (optional) -->
<link href="css/style.css" rel="stylesheet">  

<link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">
PAGE_CSS;

  return $css; 
}

function getPageMeta() {

  $meta = <<<PAGE_META
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
PAGE_META;

  return $meta;
}

function getPageJS() {

  $js = <<<PAGE_JS
    <!-- jQuery -->
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
PAGE_JS;

  return $js; 
}

function getTableHTML($headerArr, $tableData, $tableId='') {
  $html = '';

  if (!empty($tableId)) {

    $html = '<table id="' . $tableId . '" border=2>' . 
              generateTableHeader($headerArr) .
              generateTableBody($headerArr, $tableData) .
            '</table>';
  } else {

    $html = '<table border=2>' . 
              generateTableHeader($headerArr) .
              generateTableBody($headerArr, $tableData) .
            '</table>';
  }


  return $html;
}

function generateTableHeader($headerArr) {
  $html = '';
  $htmlArr = array();
  $htmlArr[] = '<thead>';
  foreach ($headerArr as $tableHeader) {
      $htmlArr[] = '<th>' . $tableHeader['viewcol'] . '</th>';
  }

  $htmlArr[] = '</thead>';

  $html = implode('', $htmlArr);

  return $html;
}

function generateTableBody($headerArr, $tableData) {
  $html = '';
  $htmlArr = array();

  $htmlArr[] = '<tbody>';

  foreach ($tableData as $dbData) {
    $htmlArr[] = '<tr>';

    foreach ($headerArr as $dbColumn => $tableHeader) {
      $htmlArr[] = '<td align="' . $tableHeader['align'] . '">' . $dbData[$dbColumn] . '</td>';
    }

    $htmlArr[] = '</tr>';
  }

  $htmlArr[] = '</tbody>';

  $html = implode('', $htmlArr);

  return $html;
}

/**
 * Function to get results from SP
 */
function getResultsFromSP($host='localhost', $user, $password, $db, $query) {
    $mysqli = new mysqli($host, $user, $password, $db);

    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    /* execute multi query */
    $resultSetArr = array();
    $index = 0;
    if ($mysqli->multi_query($query)) {
        do {
            /* store first result set */
            if ($result = $mysqli->use_result()) {
                while ($row = $result->fetch_assoc()) {
                    $resultSetArr[$index][] = $row;
                }

                $result->close();
            }
            /* print divider */
            if ($mysqli->more_results()) {
                $index++;
            }
        } while ($mysqli->next_result());
    }

    /* close connection */
    $mysqli->close();
    return $resultSetArr;
}