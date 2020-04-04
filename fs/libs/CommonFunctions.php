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