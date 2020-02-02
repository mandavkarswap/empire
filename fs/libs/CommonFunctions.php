<?php
function getTableHTML($headerArr, $tableData) {
  $html = '';


  $html = '<table border=2>' . 
            generateTableHeader($headerArr) .
            generateTableBody($headerArr, $tableData) .
          '</table>';

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