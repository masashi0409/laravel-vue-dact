/**
 * objectの配列をカンマ区切りの文字列に
 **/
const convertArrayToString = (objArr) => {
    return objArr
        .map((row) => {
            return [row['name']]
        })
        .join(', ')
}

export { convertArrayToString }
