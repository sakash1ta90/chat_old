const fs = require('fs');
const readline = require('readline');
const stream = fs.createReadStream('./chat.log', 'utf-8');


const reader = readline.createInterface({
    input: stream
});

reader.on('line', (line) => {
    let data = JSON.parse(line);
    console.table(data);
});