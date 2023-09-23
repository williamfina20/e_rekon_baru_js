import axios from 'axios';
import React, { useEffect, useState } from 'react'
import { createRoot } from 'react-dom/client';
import urlWeb from '../../Hosting/urlWeb';
//jQuery libraries
import 'jquery/dist/jquery.min.js';
//Datatable Modules
import "datatables.net-dt/js/dataTables.dataTables"
import "datatables.net-dt/css/jquery.dataTables.min.css"
import $ from 'jquery';
import TableData from '../../Fungsi/TableData';
import toast, { Toaster } from 'react-hot-toast';

const BandaraBandingkanJs = () => {
  const [data_rekon, setData_rekon] = useState([]);
  const [records, setRecords] = useState([]);
  const [column, setColumn] = useState([]);
  const [error_bandara, setError_bandara] = useState('');
  const [error_maskapai, setError_maskapai] = useState('');
  const [riwayat, setRiwayat] = useState([]);
  const [loading, setLoading] = useState(false);
  const [data_edit, setData_edit] = useState({});
  const [form_edit, setForm_edit] = useState({});
  // untuk Modal Edit
  const [show, setShow] = useState(false);

  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);

  useEffect(() => {
    PanggilBandingkanBandara();
  }, []);

  const PanggilBandingkanBandara = async () => {
    setLoading(true);
    $('#table_rekon').DataTable().clear().destroy();
    try {
      let res = await axios.get(`${urlWeb}/api/bandara/datarekon/${rekon_id}/api_bandingkan_bandara`);
      console.log(res.data);
      HitungDataRekon(await res.data);
      setTimeout(() => {
        TableData();
        setLoading(false);
      }, 1000);

    } catch (err) {
      console.log(err);
      setTimeout(() => {
        setLoading(false);
      }, 1000);
    }
  }

  async function HitungDataRekon(data) {
    let data_a = await JSON.parse(data.data_rekon.rekon_admin_text);
    let data_b = await JSON.parse(data.data_rekon.rekon_maskapai_text);
    // let data_a = await data.data_rekon_admin;
    // let data_b = await data.data_rekon_maskapai;

    let tampung_kunci = [];
    let a_validasi_awb_tidak_ada = [];
    let a_validasi_awb_sama = [];

    let jumlah_error_bandara = 0;

    data_a.map((a_item, a_items) => {
      // cek data awb yang tidak ada
      data_b.map((b_item, b_items) => {
        if (a_item.AWB === b_item.AWB) {
          tampung_kunci.push(a_item.AWB)
          return
        }
      })
      if (tampung_kunci.includes(a_item.AWB)) {
        a_validasi_awb_tidak_ada[a_items] = 'ada';
      } else {
        a_validasi_awb_tidak_ada[a_items] = 'tidak';
      }

      // cek data awb yang sama
      let i = 0;
      data_a.map((a2_item, a2_items) => {
        if (a_item.AWB === a2_item.AWB) {
          i++;
        }
      })
      a_validasi_awb_sama[a_items] = i;

      // ====================================================

      if (a_validasi_awb_tidak_ada[a_items] === 'tidak') {
        data_a[a_items]['status_rekon'] = 'hapus';
        data_a[a_items]['baris_id'] = a_items;
        jumlah_error_bandara++;
      } else {
        if (a_validasi_awb_sama[a_items] > 1) {
          data_a[a_items]['status_rekon'] = 'sama';
          data_a[a_items]['baris_id'] = a_items;
          jumlah_error_bandara++;
        } else {
          data_b.map((b_item, b_items) => {
            if ((Object.values(b_item)).includes(a_item.AWB)) {
              let jumlah_kolom_error = 0;
              (Object.values(a_item)).map((a_isi, x) => {
                if (String(a_isi) !== String(b_item[Object.keys(a_item)[x]])) {
                  data_a[a_items][Object.keys(a_item)[x]] = `${a_isi} => ${b_item[Object.keys(a_item)[x]]}`;
                  jumlah_kolom_error++;
                }
              })
              if (jumlah_kolom_error > 0) {
                // if (data_a[a_items].hasOwnProperty('status_rekon') && data_a[a_items]['status_rekon'] === 'sama') {
                // }
                jumlah_error_bandara++;
                data_a[a_items]['status_rekon'] = 'edit';
                data_a[a_items]['baris_id'] = a_items;
              } else {
                data_a[a_items]['status_rekon'] = '';
                data_a[a_items]['baris_id'] = a_items;
              }
            }
          })
        }
      }
    })
    data_b.map((b_item, b_items) => {
      if (tampung_kunci.includes(String(b_item.AWB))) {
      } else {
        data_b[b_items]['status_rekon'] = 'tambah';
        data_b[b_items]['baris_id'] = b_items;
        jumlah_error_bandara++
        data_a.push(data_b[b_items]);
      }
    });

    setRecords(data_a);
    setColumn(Object.keys(data_a[0]));
    setError_bandara(jumlah_error_bandara);
    PanggilErrorMaskapai();
    PanggilRiwayatRekon();
  }

  const PanggilRiwayatRekon = async () => {
    await axios.get(`${urlWeb}/api/bandara/datarekon/${rekon_id}/api_riwayat_rekon`)
      .then((res) => {
        setRiwayat(res.data.riwayat_rekon);
      })
      .catch((err) => { console.log(err) });
  }

  const PanggilErrorMaskapai = async () => {
    await axios.get(`${urlWeb}/api/bandara/datarekon/${rekon_id}/api_error_maskapai`)
      .then((res) => {
        console.log(res.data.jumlah_error_maskapai);
        setError_maskapai(res.data.jumlah_error_maskapai);
      })
      .catch((err) => { console.log(err) });
  }

  const PanggilBandingkanBandaraTambah = async (baris_id, rekon_id) => {
    await axios.post(`${urlWeb}/api/bandara/datarekon/${rekon_id}/api_bandingkan_bandara_tambah`, {
      baris_id: baris_id,
      user_id: user_id,
      user_tipe: user_tipe,
    })
      .then((res) => {
        PanggilBandingkanBandara();
        // PanggilErrorMaskapai();
        // PanggilRiwayatRekon();
      })
      .catch((err) => {
        console.log(err);
      });
  }

  const PanggilBandingkanBandaraHapus = async (baris_id, rekon_id, i) => {
    await axios.post(`${urlWeb}/api/bandara/datarekon/${rekon_id}/api_bandingkan_bandara_hapus`, {
      baris_id: baris_id,
      user_id: user_id,
      user_tipe: user_tipe,
    })
      .then((res) => {
        PanggilBandingkanBandara();
        // let data_a_new = records;
        // delete data_a_new[i];
        // setRecords(data_a_new);
        // setColumn(Object.keys(data_a_new[0]));

        // PanggilErrorMaskapai();
        // PanggilRiwayatRekon();
      })
      .catch((err) => {
        console.log(err);
        setLoading(false);
      });
  }

  const PanggilBandingkanBandaraEdit = async (baris_id, rekon_id) => {
    await axios.post(`${urlWeb}/api/bandara/datarekon/${rekon_id}/api_bandingkan_bandara_edit`, {
      baris_id: baris_id,
      user_id: user_id,
      user_tipe: user_tipe,
      form_edit: form_edit,
    })
      .then((res) => {
        PanggilBandingkanBandara();
        // PanggilErrorMaskapai();
        // PanggilRiwayatRekon();
      })
      .catch((err) => {
        console.log(err);
      });
  }

  const KirimRekon = async () => {
    await axios.post(`${urlWeb}/api/bandara/datarekon/${rekon_id}/api_kirim_rekon`)
      .then((res) => {
        toast.success('Data berhasil dikirim ke pusat');
        setTimeout(() => {
          if (user_tipe === 'bandara') {
            window.location.replace(`/bandara/datarekon/${data_rekon.maskapai_id}/show`);
          }
        }, 1500);
      })
      .catch((err) => {
        console.log(err);
      });
  }

  const PersetujuanPusat = async () => {
    await axios.post(`${urlWeb}/api/bandara/datarekon/${rekon_id}/api_persetujuan_pusat`)
      .then((res) => {
        toast.success('Data disetujui pusat dan dikirim kembali ke daerah');
        setTimeout(() => {
          if (user_tipe === 'admin_pusat') {
            window.location.replace(`/admin/datarekon/${data_rekon.maskapai_id}/show`);
          }
        }, 1500);
      })
      .catch((err) => {
        console.log(err);
      });
  }

  const PersetujuanDaerah = async () => {
    await axios.post(`${urlWeb}/api/bandara/datarekon/${rekon_id}/api_persetujuan_daerah`)
      .then((res) => {
        toast.success('Data telah disetujui');
        setTimeout(() => {
          if (user_tipe === 'bandara') {
            window.location.replace(`/bandara/datarekon/${data_rekon.maskapai_id}/show`);
          }
        }, 1500);
      })
      .catch((err) => {
        console.log(err);
      });
  }

  const TombolAksi = (record) => {
    if (Object.values(record)[10] === 'tambah') {
      return (
        <button className='btn btn-primary btn-sm' onClick={() => PanggilBandingkanBandaraTambah(Object.values(record)[11], rekon_id)}>Tambah</button>
      );
    } else if (Object.values(record)[10] === 'hapus') {
      return (
        <button className='btn btn-danger btn-sm' onClick={() => PanggilBandingkanBandaraHapus(Object.values(record)[11], rekon_id, i)}>Hapus</button>
      );
    } else if (Object.values(record)[10] === 'edit') {
      return (
        <button type="button" className="btn btn-warning btn-sm" onClick={() => {
          setForm_edit(record);
          setData_edit(record);
        }} data-bs-toggle="modal" data-bs-target="#exampleModal">
          Edit
        </button>
      );
    } else if (Object.values(record)[10] === 'sama') {
      return (
        <>
          <button type="button" className="btn btn-warning btn-sm me-1" onClick={() => {
            setForm_edit(record);
            setData_edit(record);
          }} data-bs-toggle="modal" data-bs-target="#exampleModal">
            Edit
          </button>
          <button className='btn btn-danger btn-sm' onClick={() => PanggilBandingkanBandaraHapus(Object.values(record)[11], rekon_id)}>hapus</button>
        </>
      );
    } else {
      return ('');
    }
  }

  return (
    <div>
      <Toaster />
      <>
        {user_tipe === 'bandara' && (
          <div className="my-2">
            {data_rekon.admin_status === undefined && (
              <>
                {error_bandara === 0 && error_maskapai === 0 && (
                  <button className='btn btn-primary text-white' onClick={() => KirimRekon()}>Kirim Rekon Ke pusat</button>
                )}
              </>
            )}
            {data_rekon.admin_status === '2' && (
              <>
                {data_rekon.admin_acc === null && (
                  <button className='btn btn-success text-white' onClick={() => PersetujuanDaerah()}>Persetujuan Daerah</button>
                )}
              </>
            )}
          </div>
        )}
        {user_tipe === 'admin_pusat' && (
          <div className="my-2">
            {data_rekon.admin_status === '1' && (
              <button className='btn btn-success text-white' onClick={() => PersetujuanPusat()}>Persetujuan Pusat</button>
            )}
          </div>
        )}
      </>
      {/* ===================== */}
      <div className="modal fade" id="exampleModal" tabIndex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div className="modal-dialog">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title" id="exampleModalLabel">Edit Data</h5>
              <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div className="modal-body">
              <>
                {Object.keys(form_edit).map((f_e, i) => {
                  let pisah = [];
                  if (Object.keys(form_edit)[i] !== 'status_rekon' && Object.keys(form_edit)[i] !== 'baris_id') {
                    return (
                      <div className="mb-3" key={i}>
                        <div className="d-flex">
                          <label>{f_e}</label>
                          {(Object.values(data_edit)[i].toString()).includes(' => ') === true && (
                            <span className='ms-auto text-muted'>
                              {(Object.values(data_edit)[i])}
                            </span>
                          )}
                        </div>
                        {
                          (Object.values(form_edit)[i].toString()).includes(' => ') === true && (
                            <>
                              {pisah = (Object.values(form_edit)[i]).toString().split(' => ')}
                              {setForm_edit({ ...form_edit, [f_e]: pisah[0] })}
                            </>
                          )
                        }
                        <input type="text" name={f_e} className='form-control' value={Object.values(form_edit)[i]} onChange={(e) => setForm_edit({ ...form_edit, [e.target.name]: e.target.value })} />
                      </div>
                    );
                  }
                }
                )}
              </>
            </div>
            <div className="modal-footer">
              <button type="button" className="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" className="btn btn-primary" data-bs-dismiss="modal" onClick={() => {
                PanggilBandingkanBandaraEdit(form_edit.baris_id, rekon_id);
              }}>Simpan</button>
            </div>
          </div>
        </div>
      </div>
      <>
        {loading === true ? (
          <div className="loading_rekon fixed-top d-flex justify-content-center align-items-center">
            <div className='text-center'>
              <h4>Memproses Data</h4>
              <div className="spinner-border" role="status">
                <span className="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
        ) : (
          <>
            {records.length > 0 && (
              <div className='table-responsive'>
                <table id='table_rekon'>
                  <thead>
                    <tr>
                      <th>Aksi</th>
                      {column.map((c, i) => {
                        if (c !== 'status_rekon' && c !== 'id_rekon_lawan' && c !== 'baris_id') {
                          return (
                            <th key={i}>{c}</th>
                          );
                        }
                      }
                      )}
                    </tr>
                  </thead>
                  <tbody>
                    {records.map((record, i) => (
                      <tr key={i}>
                        {user_tipe !== 'admin_pusat' ? (
                          <td>
                            {TombolAksi(record)}
                          </td>
                        ) : (
                          <td>
                            {Object.values(record)[10] === 'tambah' && (
                              <button className='btn btn-primary btn-sm' disabled>Tambah</button>
                            )}
                            {Object.values(record)[10] === 'hapus' && (
                              <button className='btn btn-danger btn-sm' disabled>Hapus</button>
                            )}
                            {Object.values(record)[10] === 'edit' && (
                              <button type="button" className="btn btn-warning btn-sm" disabled>
                                Edit
                              </button>
                            )}
                            {Object.values(record)[10] === 'sama' && (
                              <>
                                <button type="button" className="btn btn-warning btn-sm" disabled>
                                  Edit
                                </button>
                                <button className='btn btn-danger btn-sm m-1' disabled>hapus</button>
                              </>
                            )}
                          </td>
                        )}
                        {
                          Object.values(record).map((r, j) => {
                            if (Object.keys(record)[j] !== 'status_rekon' && Object.keys(record)[j] !== 'id_rekon_lawan' && Object.keys(record)[j] !== 'baris_id') {
                              return (
                                <td key={j}>{r}</td>
                              );
                            }
                          }
                          )
                        }
                        {Object.keys(record).includes('status_rekon') ? '' : <td>ss</td>}

                      </tr>
                    ))}
                  </tbody>
                </table>
                <span className='text-black fw-bold'>Jumlah Error : {error_bandara}</span>
                <br />
                {error_maskapai > 0 && (
                  <span className='text-danger'>* Masih Terdapat error pada rekon maskapai</span>
                )}
                <br />
              </div>
            )}
            <div>
              <h4 className='mt-3'>Keterangan</h4>
              <table>
                <tbody>
                  <tr>
                    <td className='btn btn-primary btn-sm my-1'>Tambah</td>
                    <td className='ps-2'>:</td>
                    <td>Data AWB Maskapai yang tidak ada di Bandara</td>
                  </tr>
                  <tr>
                    <td className='btn btn-danger btn-sm my-1'>Hapus</td>
                    <td className='ps-2'>:</td>
                    <td>Data AWB tidak ditemukan di Maskapai</td>
                  </tr>
                  <tr>
                    <td className='btn btn-warning btn-sm my-1'>Edit</td>
                    <td className='ps-2'>:</td>
                    <td>Terdapat Data AWB yang sama </td>
                  </tr>
                  <tr>
                    <td className='px-2'>{`=>`}</td>
                    <td className='ps-2'>:</td>
                    <td>Data Yang berbeda</td>
                  </tr>
                  <tr>
                  </tr>
                </tbody>
              </table>
            </div>
          </>
        )}
        {riwayat.length > 0 && (
          <div className='table-responsive'>
            <h4 className='mt-4'>Riwayat Rekon</h4>
            <table className='table'>
              <thead>
                <tr>
                  <th className='fw-bold'>No</th>
                  <th className='fw-bold'>Akun</th>
                  <th className='fw-bold'>Proses</th>
                  <th className='fw-bold'>Riwayat Ubah</th>
                  <th className='fw-bold'>Waktu</th>
                </tr>
              </thead>
              <tbody>
                {riwayat.map((r, i) => (
                  <tr key={i}>
                    <td>{i + 1}</td>
                    <td>{r.user}</td>
                    <td>{r.proses}</td>
                    <td>{r.riwayat_ubah}</td>
                    <td>{r.created_at}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </>
    </div >
  );
}

export default BandaraBandingkanJs

if (document.getElementById('bandara_bandingkan_js')) {
  const domNode = document.getElementById('bandara_bandingkan_js');
  const root = createRoot(domNode);
  root.render(
    <BandaraBandingkanJs
      rekon_id={window.rekon_id}
      user_id={window.user_id}
      user_tipe={window.user_tipe}
    />
  );
}