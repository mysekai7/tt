#!/usr/bin/perl -w
use strict;

my %analysis;
my $file = './kw_tmp.txt';
open LOG, "< $file" or die "Can not open $file: $!";
while (defined (my $line = <LOG>)) {
    chomp($line);
    $analysis{$line}++;
}
close LOG;

open STAT_ALL, "> ./distinct_kw.txt" or die "Can not open output file: $!";
while (my ($url, $val) = each %analysis) {
    printf STAT_ALL "%s,%d\n", $url, $val;
    delete $analysis{$url};
    #print STAT_ALL "$url";
}
close STAT_ALL;
%analysis = ();
print "Success\n";
