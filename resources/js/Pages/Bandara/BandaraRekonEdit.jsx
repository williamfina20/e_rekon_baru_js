import axios from 'axios';
import React, { useEffect, useState } from 'react'
import { createRoot } from 'react-dom/client';
import * as XLSX from 'xlsx';
import toast, { Toaster } from 'react-hot-toast';
import urlWeb from '../../Hosting/urlWeb';
import ExcelDateToJSDate from '../../Fungsi/ExcelDateToJSDate';


const BandaraRekonEdit = () => {
    const [bulan, setBulan] = useState(data_rekon.bulan);
    const [data_admin, setData_admin] = useState([]);
    const [data_maskapai, setData_maskapai] = useState([]);
    const [admin_baris, setAdmin_baris] = useState(0);
    const [admin_kolom, setAdmin_kolom] = useState(0);
    const [maskapai_baris, setMaskapai_baris] = useState(0);
    const [maskapai_kolom, setMaskapai_kolom] = useState(0);
    const [errors, setErrors] = useState([]);

    useEffect(() => {
        if (data_rekon.rekon_admin_text !== null) {
            setData_admin(JSON.parse(data_rekon.rekon_admin_text));
        }
        if (data_rekon.rekon_maskapai_text !== null) {
            setData_maskapai(JSON.parse(data_rekon.rekon_maskapai_text));
        }
        return () => {
            setBulan('');
            setData_admin([]);
            setData_maskapai([]);
            setErrors([]);
        }
    }, []);

    const CekAdmin = () => {
        if (data_rekon.rekon_maskapai_text !== null) {
            setTimeout(() => {
                setAdmin_baris(data_admin.length);
                let nilai_kolom_admin = 0;
                data_admin.map((da, i) => {
                    if (Object.keys(da).length > nilai_kolom_admin) {
                        nilai_kolom_admin = Object.keys(da).length;
                    }
                })
                setAdmin_kolom(nilai_kolom_admin);
            }, 50);
        }
    }

    const CekMaskapai = () => {
        setTimeout(() => {
            setMaskapai_baris(data_maskapai.length);
            let nilai_kolom_maskapai = 0;
            data_maskapai.map((da, i) => {
                if (Object.keys(da).length > nilai_kolom_maskapai) {
                    nilai_kolom_maskapai = Object.keys(da).length;
                }
            })
            setMaskapai_kolom(nilai_kolom_maskapai);
        }, 50);
    }

    const handleFileUpload = async (e) => {
        const reader = new FileReader();
        reader.readAsBinaryString(e.target.files[0]);
        reader.onload = (e) => {
            const data_admin = e.target.result;
            const workbook = XLSX.read(data_admin, { type: "binary" });
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
            setData_admin(parseData2);
        };
    }

    const simpanData = (e) => {
        e.preventDefault()

        axios.put(`${urlWeb}/api/bandara/datarekon/${data_rekon.id}/update`, {
            bulan: bulan,
            data_rekon: data_admin
        })
            .then((res) => {
                console.log(res.data);
                if (res.data.pesan === 'gagal') {
                    setErrors(res.data.aksi);
                    toast.error('validasi gagal');
                } else if (res.data.pesan === 'berhasil') {
                    toast.success('Data Berhasil Disimpan');
                    setTimeout(() => {
                        window.location.replace(`/bandara/datarekon/${data_rekon.maskapai_id}/show`);
                    }, 600);
                }
            })
            .catch((err) => console.log(err.data));
    }

    return (
        <div>
            <Toaster />
            <CekAdmin />
            <CekMaskapai />
            <form onSubmit={simpanData}>
                <div className="mb-3">
                    <label>Bulan</label>
                    <input type="month" name="bulan" className="form-control" value={bulan} onChange={(e) => setBulan(e.target.value)} required={true} readOnly />
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

                {data_rekon.rekon_maskapai_text && (
                    <table className='my-2'>
                        <tbody>
                            <tr>
                                <td>
                                    Admin baris : {admin_baris}
                                    {admin_baris !== maskapai_baris ?
                                        <i className="mdi mdi-close-circle text-danger"></i>
                                        :
                                        <i className="mdi mdi-check-circle text-success"></i>
                                    }
                                </td>
                                <td className='ps-3'>
                                    Maskapai baris : {maskapai_baris}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Admin kolom : {admin_kolom}
                                    {admin_kolom !== maskapai_kolom ?
                                        <i className="mdi mdi-close-circle text-danger"></i>
                                        :
                                        <i className="mdi mdi-check-circle text-success"></i>
                                    }
                                </td>
                                <td className='ps-3'>
                                    Maskapai kolom : {maskapai_kolom}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                )}
                <div className="mb-3">
                    <a href={`/bandara/datarekon/${data_rekon.maskapai_id}/show`}
                        className="btn btn-secondary btn-sm">Kembali</a>
                    {(admin_kolom === maskapai_kolom) && (
                        <button type="submit" className="btn btn-primary btn-sm">Simpan</button>
                    )}
                </div>
            </form>
            {data_admin.length > 0 && (
                <div className="table-responsive">
                    {/* <table className='table table-striped'>
                        <thead>
                            <tr>
                                {Object.keys(data_admin[0]).map((key) => (
                                    <th key={key}>{key}</th>
                                ))}
                            </tr>
                        </thead>
                        <tbody>
                            {data_admin.map((row, index) => (
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

export default BandaraRekonEdit

if (document.getElementById('bandara_rekon_edit')) {
    const domNode = document.getElementById('bandara_rekon_edit');
    const root = createRoot(domNode);
    root.render(<BandaraRekonEdit data_rekon={window.data_rekon} data_maskapai={window.data_maskapai} />);
}