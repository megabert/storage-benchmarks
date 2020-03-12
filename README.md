# Storage Benchmarks

## WARNING! WARNING! WARNING!

All is work in progress. Consider this scripts as **very dangerously** because filesystems are 
created, destroyed, wiped and overwritten a lot. No one would run them on a computer, with data on it, which are important for you.

## General

These are some scripts to automate storage benchmarks with the intention to get performance data
for storage to be used for full virtualization with kvm.

## Scripts function

### stbench
 
The main script for storage configurations(filesystems, raid arrays, ... ).

### report.php 

A script for sorting the results and extracting data out of the json data files from fio.

### all_fio_tests

A script for running a defined set of fio tests on a mounted path.

### mk_dev_names

A script creating unique device names for testing based on the serial numbers of the devices, so that the same storage device has the same name at every system boot.

