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
	buf, err := ioutil.ReadFile(path)
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

	var path string
	if len(os.Args) > 1 {
		path = os.Args[1]
	} else {
		panic("missing argument")
	}

	validWords := readWords(path)

	input, err := ioutil.ReadAll(os.Stdin)
	if err != nil {
		panic(err)
	}

	inputWords := wordRegexp.FindAllString(string(input), -1)

	for _, w := range inputWords {
		if validWords.contains(w) {
			fmt.Fprintf(os.Stdout, "%s\n", w)
		} else {
			fmt.Fprintf(os.Stdout, "<%s>\n", w)
		}
	}
}
