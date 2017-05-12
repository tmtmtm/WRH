<?php
/* 
 * Weighted Rendezvous Hash
 * based on http://www.snia.org/sites/default/files/SDC15_presentations/dist_sys/Jason_Resch_New_Consistent_Hashings_Rev.pdf
 * adapted for PHP by tadas@esu.lt
 * 
 * This is free and unencumbered software released into the public domain.
 * For more information, please refer to <http://unlicense.org/>
 */



/*
 *

// id : [ seed, weight ]
$nodes = [
    'a8'    => [ 8,  8	],
    'a1'    => [ 1,  1	],
    'a2'    => [ 2,  32 ],
    'a3'    => [ 3,  25	],
    'a4'    => [ 4,  25	],
    'a9'    => [ 81, 10	]
];

// - or -

// id : weight
$nodes = [
    'a8'    => 8,
    'a1'    => 1,
    'a2'    => 32,
    'a3'    => 25,
    'a4'    => 25,
    'a9'    => 10
];


*/




Class Wrh {
    private $nodes, $algo, $seed2;

    public function __construct( $nodes=[], $algo='joaat', $seed2='x0g' ) {
	$this->algo  = $algo;
	$this->seed2 = $seed2;
	$this->nodes = $nodes;

	// use node id as seed, if not specified
	foreach( $this->nodes as $id => $data ){
	    if( !is_array($data) ){
		$this->nodes[$id] = [ $id, $data ];
	    }
	}
    }


    public function pick ( $key ) {
	$hiscore = -1;
	$winner = null;

	foreach( $this->nodes as $id => list($seed, $weight) ){
	    
	    $hash_i = hexdec( hash($this->algo, $key.$seed.$this->seed2 ));
	    $hash_f = ($hash_i & 0xFFFFFFFF) / 0x100000000;	    // int to float 
	    $score = $weight / -log( $hash_f );

	    if( $score > $hiscore ) {
		$winner = $id;
		$hiscore = $score;
	    }
	}
	return $winner;
    }


    public function pickMore ( $key, $ammount ) {
	$winners = [];

	foreach( $this->nodes as $id => list($seed, $weight) ){
	    
	    $hash_i = hexdec( hash($this->algo, $key.$seed.$this->seed2 ));
	    $hash_f = ($hash_i & 0xFFFFFFFF) / 0x100000000;	    // int to float 
	    $winners[$id] = $weight / -log( $hash_f );

	}

	arsort( $winners );

	return array_slice( array_keys($winners), 0, $ammount );
    }


}


