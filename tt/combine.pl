#!/usr/bin/perl -w
use strict;

my %allwords;
my %newwords;
my $stat_all = './stat_all.txt';

open STAT_ALL, "< $stat_all" or die "Can not open $stat_all: $!";
while (defined (my $line = <STAT_ALL>))
{
	chomp($line);
	$allwords{$line}++;
}
close STAT_ALL;


my $file = './20090806.txt';
open LOG, "< $file" or die "Can not open $file: $!";
my $i = 0;	
while (defined (my $line = <LOG>)) {
	chomp($line);
	my @fields = split /,/, $line;
	if( !defined($allwords{$fields[0]}) )
	{
		chomp($fields[0]);
		$allwords{$fields[0]}++;
		$newwords{$i} = $fields[0];
		$i++;
		#push @new_words, $fields[0];
		#@newwords[] = $fields[0];
	}
	@fields = ();
}
close LOG;


open STAT_ALL, "> ./stat_all.txt" or die "Can not open output file: $!";
while (my ($word, $val) = each %allwords) {
	printf STAT_ALL "%s\n", $word;
	delete $allwords{$word};
}
close STAT_ALL;
%allwords = ();

open NEWADD, "> ./newadd.txt" or die "Can not open output file: $!";
while (my ($k, $v) = each %newwords) {
	chomp($v);
	printf NEWADD "%s\n", $v;
	#print "$_\n";
}
close NEWADD;
%newwords = ();


