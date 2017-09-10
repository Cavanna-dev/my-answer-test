
var fs = require('fs');
var path = process.argv[2];
var validWords = splitWords(fs.readFileSync(path, "utf-8"));

readStdin(function (input) {
  var inputWords = splitWords(input);
  inputWords.forEach(function (inputWord) {
    if (validWords.indexOf(inputWord) > -1) {
      console.log(inputWord);
    } else {
      console.log("<"+inputWord+">");
    }
  });
});

function splitWords(str) {
  return str.split(/\s+/).filter(function (w) {
    return w.length > 0;
  });
}

function readStdin(cb) {
  var data = "";
  process.stdin.setEncoding('utf8');
  process.stdin.on('readable', function() {
    var chunk = process.stdin.read();
    if (chunk !== null) {
      data += chunk;
    }
  });
  process.stdin.on('end', function() {
    cb(data);
  });
}
