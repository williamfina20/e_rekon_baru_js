const ExcelDateToJSDate = (date) => {
    let converted_date = new Date(Math.round((date - 25569) * 864e5));
    converted_date = String(converted_date).slice(4, 15)
    let dates = converted_date.split(" ")
    let day = dates[1];
    let month = dates[0];
    month = "JanFebMarAprMayJunJulAugSepOctNovDec".indexOf(month) / 3 + 1
    if (month.toString().length <= 1)
        month = '0' + month
    let year = dates[2];
    // return String(day + '/' + month + '/' + year.slice(2, 4))
    return String(day + '/' + month + '/' + year);

}

export default ExcelDateToJSDate;