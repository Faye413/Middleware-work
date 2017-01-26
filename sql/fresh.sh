#!/bin/bash

dropdb -U postgres training
createdb -U postgres training
psql -U postgres -1 -f create.sql training
psql -U postgres -1 -f functions.sql training
psql -U postgres -1 -f populate.sql training