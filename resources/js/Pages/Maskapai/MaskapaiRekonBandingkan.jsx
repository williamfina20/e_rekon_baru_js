import axios from 'axios';
import React, { useEffect, useState } from 'react'
import { createRoot } from 'react-dom/client';
import * as XLSX from 'xlsx';
import toast, { Toaster } from 'react-hot-toast';
import urlWeb from '../../Hosting/urlWeb';


const MaskapaiRekonBandingkan = () => {
    const [bulan, setBulan] = useState(data_rekon.bulan);
    const [data_admin, setData_admin] = useState([]);
    const [data_maskapai, setData_maskapai] = useState([]);
    const [data_maskapai_2, setData_maskapai_2] = useState([]);
    const [errors, setErrors] = useState([]);
    let data4 = [];
    let status = '';
    let ubah_angka = '';
    const [riwayat, setRiwayat] = useState([]);

    useEffect(() => {
        if (data_rekon.rekon_admin_text !== null) {
            setData_admin(JSON.parse(data_rekon.rekon_admin_text));
        }
        if (data_rekon.rekon_maskapai_text !== null) {
            setData_maskapai(JSON.parse(data_rekon.rekon_maskapai_text));
            setData_maskapai_2(JSON.parse(data_rekon.rekon_maskapai_text));
        }
        return () => {
            setBulan('');
            setData_admin([]);
            setData_maskapai([]);
            setErrors([]);
        }
    }, []);

    const simpanData = (e) => {
        e.preventDefault()

        axios.put(`${urlWeb}/api/maskapai/datarekon/${data_rekon.id}/update`, {
            bulan: bulan,
            data_rekon: data_maskapai_2,
            riwayat: riwayat
        })
            .then((res) => {
                console.log(res.data);
                if (res.data.pesan === 'gagal') {
                    setErrors(res.data.aksi);
                    toast.error('validasi gagal');
                } else if (res.data.pesan === 'berhasil') {
                    toast.success('Data Berhasil Disimpan');
                    setTimeout(() => {
                        window.location.replace(`/maskapai/datarekon`);
                    }, 600);
                }
            })
            .catch((err) => console.log(err.data));
    }

    function isNumber(n) { return /^-?[\d.]+(?:e-?\d+)?$/.test(n); }

    const gantiData = (data_index, data_i, data_e) => {
        data_maskapai_2.map((row, index) => (
            data4[index] = {},
            Object.values(row).map((value, i) => {
                if (data_index === index && i === data_i) {
                    if (isNumber(data_e) && data_e !== '') {
                        console.log('harus ubah ke angka');
                        ubah_angka = parseInt(data_e);
                        data4[index][Object.keys(row)[i]] = ubah_angka;
                    } else {
                        data4[index][Object.keys(row)[i]] = data_e;
                    }
                    // =========================== 
                    // Mengubah Riwayat
                    let riwayat1 = {
                        id: data_index,
                        id_kolom: Object.keys(row)[i],
                        isi: `${data_e}`,
                    }
                    if (riwayat.length > 0) {
                        let cari_riwayat = riwayat.find(rf => (rf.id === riwayat1.id && rf.id_kolom === riwayat1.id_kolom));
                        if (cari_riwayat) {
                            let new_a = riwayat.map((rw, i_rw) => {
                                if (rw.id === riwayat1.id && rw.id_kolom === riwayat1.id_kolom) {
                                    return { ...rw, isi: riwayat1.isi }
                                } else {
                                    return rw;
                                }
                            });
                            setRiwayat(new_a);
                        } else {
                            setRiwayat(rw =>
                                [
                                    ...rw,
                                    riwayat1
                                ])
                        }
                    } else {
                        setRiwayat([riwayat1]);
                    }
                    // =========================== 
                } else {
                    data4[index][Object.keys(row)[i]] = value;
                }
            })
        ));

        console.log('data4 = ', data4);
        console.log('riwayat = ', riwayat);
        setData_maskapai_2(data4);
    }

    return (
        <div>
            <Toaster />
            {data_rekon.rekon_admin_text === data_rekon.rekon_maskapai_text ? (
                ''
            ) : (
                <form onSubmit={simpanData}>
                    {data_admin.length > 0 && (
                        <div className='d-flex'>
                            <div className="table-responsive">
                                <table className='table' id='example'>
                                    <thead>
                                        <tr>
                                            <th style={{
                                                position: 'sticky',
                                                left: 0,
                                                backgroundColor: '#eeeee4'
                                            }}>Cek
                                            </th>
                                            {Object.keys(data_admin[0]).map((key) => (
                                                <th key={key}>{key}</th>
                                            ))}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data_admin.map((row, index) => (
                                            (JSON.stringify(row) !== JSON.stringify(data_maskapai[index]) && (
                                                < tr key={index} >
                                                    < td style={{
                                                        position: 'sticky',
                                                        left: 0,
                                                        backgroundColor: '#eeeee4'
                                                    }}>
                                                        <i className="mdi mdi-close-circle text-danger"></i>
                                                    </td>
                                                    {
                                                        Object.values(row).map((value, i) => {
                                                            if (value === (Object.values(data_maskapai[index])[i])) {
                                                                return (
                                                                    <td key={i}>{value}</td>
                                                                );
                                                            } else {
                                                                return (
                                                                    <td style={{ color: 'red', flexDirection: 'column', flex: 1, }} key={i}>
                                                                        <div className="d-flex">
                                                                            <div className='text-primary'>{value}</div> <div className='text-muted'>&nbsp;-&nbsp;</div> <div className='text-danger'>{Object.values(data_maskapai[index])[i]}</div>
                                                                        </div>
                                                                        <input type="text" value={Object.values(data_maskapai_2[index])[i]} onChange={(e) => gantiData(index, i, e.target.value)} size={8} required />
                                                                    </td>
                                                                );
                                                            }
                                                        })
                                                    }
                                                </tr>
                                            ))
                                        ))}
                                    </tbody>
                                </table >
                            </div>
                        </div >
                    )
                    }
                    <div className="mb-3">
                        <button type="submit" className="btn btn-primary btn-sm">Simpan</button>
                    </div>
                </form>
            )}
        </div >
    )
}

export default MaskapaiRekonBandingkan

if (document.getElementById('maskapai_rekon_bandingkan')) {
    const domNode = document.getElementById('maskapai_rekon_bandingkan');
    const root = createRoot(domNode);
    root.render(<MaskapaiRekonBandingkan data_rekon={window.data_rekon} data_maskapai={window.data_maskapai} />);
}