import React from 'react'

const TableData = () => {
  $(document).ready(function () {
    setTimeout(function () {
      $('#table_rekon').DataTable({
        // "columnDefs": [{ targets: 'no-sort', orderable: false }],
        // info: false,
        // ordering: false,
        // paging: false
      });
    }, 500);
  });
  console.log('proses datatable');
  // $(document).ready(function () {
  //   setTimeout(function () {
  //     $('#table_riwayat').DataTable();
  //   }, 800);
  // });
}

export default TableData