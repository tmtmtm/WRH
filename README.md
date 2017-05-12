# WRH
Weighted Rendezvous Hash for PHP

based on http://www.snia.org/sites/default/files/SDC15_presentations/dist_sys/Jason_Resch_New_Consistent_Hashings_Rev.pdf

## Usage:

    // id : weight or id : [ seed, weight ]
    $nodes = [
        'a8'    => 8,
        'a1'    => 1,
        'a2'    => 32,
        'a3'    => 25,
        'a4'    => 25,
        'a9'    => 10
    ];
    
    $wrh = new Wrh( $nodes );
    $node = $wrh->pick( $key );
    
