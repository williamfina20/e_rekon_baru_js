import axios from 'axios';
import React, { useEffect, useState } from 'react'
import { createRoot } from 'react-dom/client';
import * as XLSX from 'xlsx';
import toast, { Toaster } from 'react-hot-toast';
import ExcelDateToJSDate from '../../Fungsi/ExcelDateToJSDate';
import urlWeb from '../../Hosting/urlWeb';


const BandaraStafRekonTambah = () => {
    const [bulan, setBulan] = useState('');
    const [data, setData] = useState([]);
    const [errors, setErrors] = useState([]);
    const [admin_kolom, setAdmin_kolom] = useState(0);

    const CekAdmin = (parsedata2) => {
        if (parsedata2 !== null) {
            setTimeout(() => {
                let nilai_kolom_admin = 0;
                parsedata2.map((da, i) => {
                    if (Object.keys(da).length > nilai_kolom_admin) {
                        nilai_kolom_admin = Object.keys(da).length;
                    }
                })
                setAdmin_kolom(nilai_kolom_admin);
                if (nilai_kolom_admin !== 12) {
                    toast.error(`Jumlah Kolom data harus 12 sesuai format`);
                }
            }, 50);
        }
    }


    const handleFileUpload = (e) => {
        const reader = new FileReader();
        reader.readAsBinaryString(e.target.files[0]);
        reader.onload = (e) => {
            const data = e.target.result;
            const workbook = XLSX.read(data, { type: "binary" });
            const sheetName = workbook.SheetNames[0];
            const sheet = workbook.Sheets[sheetName];
            let parseData = XLSX.utils.sheet_to_json(sheet);
            let parseData2 = [];
            parseData.map((ps, i_ps) => {
                parseData2[i_ps] = {};
                Object.values(ps).map((ops, i_ops) => {
                    let converted_date = new Date(Math.round((ops - 25569) * 864e5));
                    converted_date = String(converted_date).slice(4, 15);
                    let dates = converted_date.slice(-4);
                    if (dates > 2000) {
                        parseData2[i_ps][Object.keys(ps)[i_ops]] = ExcelDateToJSDate(ops);
                    } else {
                        parseData2[i_ps][Object.keys(ps)[i_ops]] = ops;
                    }

                })
            })
            setData(parseData2);
            CekAdmin(parseData2);
        };
    }

    const simpanData = (e) => {
        e.preventDefault()

        axios.post(`${urlWeb}/api/bandara/datarekon/${data_maskapai.id}/store`, {
            bulan: bulan,
            data_rekon: data
        })
            .then((res) => {
                console.log(res.data);
                if (res.data.pesan === 'gagal') {
                    setErrors(res.data.aksi);
                    toast.error('validasi gagal');
                } else if (res.data.pesan === 'berhasil') {
                    toast.success('Data Berhasil Disimpan');
                    setTimeout(() => {
                        window.location.replace(`/bandara_staf/datarekon/${data_maskapai.id}/show`);
                    }, 600);
                }
            })
            .catch((err) => console.log(err.data));
    }


    return (
        <div>
            <Toaster />
            <form onSubmit={simpanData}>
                <div className="mb-3">
                    <label>Bulan</label>
                    <input type="month" name="bulan" className="form-control" value={bulan} onChange={(e) => setBulan(e.target.value)} required={true} />
                    {errors && (
                        <h5 className='text-danger'>{errors.bulan}</h5>
                    )}
                </div>
                <div className="mb-3">
                    <label>Data Rekon</label>
                    <input type="file" onChange={handleFileUpload} className='form-control' />
                    {errors && (
                        <h5 className='text-danger'>{errors.data_rekon}</h5>
                    )}
                </div>
                <div className="mb-3">
                    <a href={`/bandara_staf/datarekon/${data_maskapai.id}/show`}
                        className="btn btn-secondary btn-sm">Kembali</a>
                    {admin_kolom === 12 &&
                        (
                            <button type="submit" className="btn btn-primary btn-sm">Simpan</button>
                        )}
                </div>
            </form>
            {data.length > 0 && (
                <div className="table-responsive">
                    Kolom Data : {admin_kolom}
                    {admin_kolom !== 12 ?
                        <i className="mdi mdi-close-circle text-danger"></i>
                        :
                        <i className="mdi mdi-check-circle text-success"></i>
                    }
                    {/* <table className='table table-striped'>
                        <thead>
                            <tr>
                                {Object.keys(data[0]).map((key) => (
                                    <th key={key}>{key}</th>
                                ))}
                            </tr>
                        </thead>
                        <tbody>
                            {data.map((row, index) => (
                                <tr key={index}>
                                    {Object.values(row).map((value, i) => (
                                        <td key={i}>
                                            {value}
                                        </td>
                                    ))}
                                </tr>

                            ))}
                        </tbody>
                    </table > */}
                </div>
            )}
        </div>
    )
}

export default BandaraStafRekonTambah

if (document.getElementById('bandara_staf_rekon_tambah')) {
    const domNode = document.getElementById('bandara_staf_rekon_tambah');
    const root = createRoot(domNode);
    root.render(<BandaraStafRekonTambah data_maskapai={window.data_maskapai} />);
}