#!/usr/bin/env php
<?PHP

function get_element($text) {

	if(preg_match("/^(raid|singledisk)/",$text))
		return ["disktype", $text];

	if(preg_match("/(write|read)/",$text)) 
	       	return ["testtype", $text]; 

	if(preg_match("/^lvm/",$text)) 
	       	return ["lvm", $text]; 

	if(preg_match("/(ext[234]|btrfs|zfs|xfs|reiserfs)/",$text,$matches))
		return ["fs", $matches[1]]; 

	if(preg_match("/^bs_[0-9]+/",$text))
	       	return ["blocksize", $text]; 

	if(preg_match("/^[0-9]+/",$text))
	       	return ["run", $text]; 

}

function split_filename($filename) {

	#example
	# singledisk.ext3.bs_64k.seq_read.run_2.json
	#       
	preg_match("/([^\.]+)\.([^\.]+).([^\.]+)\.([^\.]+)\.(lvm)?\.?run_([^\.]+)/",$filename,$matches);
	#            comment    filesys blocksize testtype  lvm     run#
	$i=0;
	$record=[];
	$record["filename"]=$filename;
	foreach($matches as $element) {
		$i++;
		if($i==1) continue;
		$res=get_element($element);
		$record[$res[0]]=$res[1];
	}
	return $record;
}


function array_key_sort($a,$b,$key="mykey") {
	if($a[$key] == $b[$key]) {
		$res=0;
	} else {
		$res=($a[$key] < $b[$key]) ? -1 : 1;
	}
	return $res;
}

function sort_by() {

	$args=func_get_args();

	# the first argument is the array to be sorted
	$new_array=array_shift($args);

	# sort array in reverse order (the highest prio sort last)
	$args=array_reverse($args);

	# process sort keys from last to first
	foreach($args as $arg) {

		# create an anonymous function which takes the sort key and arg[0] and arg[1] from usort
		$sorter=function() use ($arg) { 
			$args=func_get_args(); 
			return array_key_sort($args[0],$args[1],$arg);};

		usort($new_array,$sorter);
	}

	return $new_array;
}

$records=[];
foreach (glob("*run_1.json") as $filename) {
        #list($nothing, $comment, $diskname, $filesystem, $blocksize,$testname,$run)=split_filename($filename);
	array_push($records,split_filename($filename));
	#printf("%-60s %-13s %-10s %-20s %-10s %-15s %d\n",$filename,$comment,$diskname,$filesystem,$blocksize,$testname,$run);
}
$new_array=sort_by($records,"testtype","blocksize");
foreach($new_array as $r) {
	printf("%-60s %-13s %-20s %-15s %-10s \n",$r["filename"],$r["disktype"],$r["fs"],$r["testtype"],$r["blocksize"]);
}
?>
