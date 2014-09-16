#!/usr/bin/perl -w
use strict;

my %allwords;
my %newwords;
my $file = "kw_tmp.txt";
my $newadd = "add_$ARGV[0].txt";
my $stat_all = './stat_all.txt';	#存储总的词表

#遍历词表保存到哈希变量中
open STAT_ALL, "< $stat_all" or die "Can not open $stat_all: $!";
while (defined (my $line = <STAT_ALL>))
{
	chomp($line);
	$allwords{$line}++;
}
close STAT_ALL;

#读入数据列表
#open DATA_LIST, "< ./alldata.txt" or die "Can not open datalist: $!";
#while (defined (my $line = <DATA_LIST>)) {
#	chomp($line);
#	my @fields = split /\t/, $line;
#	&combine($fields[0]);
#}
#close DATA_LIST;

#子程序
&combine;


sub combine{
	#my $file = "$_[0].txt";	#传入的文件名
	#my $newadd = "add_$file";

	#读入当前词表文件
	#my $file = './20090806.txt';
	open LOG, "< ./$file" or die "Can not open $file: $!";
	my $i = 0;
	while (defined (my $line = <LOG>)) {
		chomp($line);
		#my @fields = split /,/, $line;
		if( !defined($allwords{$line}) )
		{
			#chomp($fields[0]);
			$allwords{$line}++;
			$newwords{$i} = $line;
			$i++;
		}
		#@fields = ();
	}
	close LOG;


	#保存新的总哈希词表到文件


	#保存新增词到文件
	#open NEWADD, "> ./newadd.txt" or die "Can not open output file: $!";
	open NEWADD, "> ./newadd/$newadd" or die "Can not open output file: $!";
	while (my ($k, $v) = each %newwords) {
		chomp($v);
		printf NEWADD "%s\n", $v;
		#print "$_\n";
	}
	close NEWADD;
	%newwords = ();

	print "$newadd Success! \n";
}

#保存新的总哈希词表到文件
open STAT_ALL, "> ./stat_all.txt" or die "Can not open output file: $!";
while (my ($word, $val) = each %allwords) {
	printf STAT_ALL "%s\n", $word;
	delete $allwords{$word};
}
close STAT_ALL;
%allwords = ();


