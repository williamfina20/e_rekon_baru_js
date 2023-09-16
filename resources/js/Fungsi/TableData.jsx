import React from 'react'

const TableData = () => {
  $(document).ready(function () {
    setTimeout(function () {
      $('#table_rekon').DataTable();
    }, 800);
  });
  // $(document).ready(function () {
  //   setTimeout(function () {
  //     $('#table_riwayat').DataTable();
  //   }, 800);
  // });
}

export default TableData