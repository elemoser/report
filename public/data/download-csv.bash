#!/usr/bin/env bash

url="https://docs.google.com/spreadsheets/d/1iNe0jUhg4FoOCejpKxICD-c73C0CrvS-MNe5sR2tgzM/gviz/tq?tqx=out:csv&sheet"

for target in Items Room; do
    printf "%s\\n" "$target"
    curl --silent "$url=$target" > "./$target.csv"
done

ls -l -- *.csv
file -- *.csv
