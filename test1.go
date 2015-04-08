package main

import (
	"fmt"
	"io/ioutil"
	"os"
	"regexp"
	"strings"
)

type whitelist []string

var wordRegexp = regexp.MustCompile("([^ \n]+)")

func readWords(path string) whitelist {
	file, err := os.Open(path)
	if err != nil {
		panic(err)
	}

	buf, err := ioutil.ReadAll(file)
	if err != nil {
		panic(err)
	}

	return strings.Split(string(buf), "\n")
}

func (w whitelist) contains(needle string) bool {
	for _, v := range w {
		if v == needle {
			return true
		}
	}
	return false
}

func main() {

	path := "/usr/share/dict/words"
	if len(os.Args) > 1 {
		path = os.Args[1]
	}

	words := readWords(path)

	input, err := ioutil.ReadAll(os.Stdin)
	if err != nil {
		panic(err)
	}

	inputWords := wordRegexp.FindAllString(string(input), -1)

	for _, w := range inputWords {
		if words.contains(w) {
			fmt.Fprintf(os.Stdout, "%s\n", w)
		} else {
			fmt.Fprintf(os.Stdout, "<%s>\n", w)
		}
	}
}
