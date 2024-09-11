<?php
class paginator {
 
     private $_conn;
        private $_limit;
        private $_page;
        private $_query;
        private $_params;
        private $_total;
        private $_other;
 


public function __construct($query,$params=NULL, $other=NULL ) {
    $this->_conn = db::getInstance();
    $this->_query = $query;
    $this->_other   =  $other;
    $this->_params = $params;
    $rs= $this->_conn->query( $this->_query,$this->_params);
    $this->_total = $rs->count();
}

public function getData( $limit = 10, $page = 1 ) {
     
    $this->_limit   = $limit;
    $this->_page    = $page;
 
    if ( $this->_limit == 'all' ) {
        $query      = $this->_query;
    } else {
        $query      = $this->_query . " LIMIT " . ( ( $this->_page - 1 ) * $this->_limit ) . ", $this->_limit";
    }
    $rs             = $this->_conn->query( $query,$this->_params );
    return $rs->result();
}

public function createLinks( $links, $list_class ) {
    if ( $this->_limit == 'all' ) {
        return '';
    }
 
    $last       = ceil( $this->_total / $this->_limit );
   // echo $last;
    $start      = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
    $end        = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;
 
    $html       = '<ul class="' . $list_class . '">';
 
    $class      = ( $this->_page == 1 ) ? "disabled" : "";
    $li         = ($this->_page != 1)?'?'.$this->_other.'&limit=' . $this->_limit . '&page=' . ( $this->_page - 1 ) .'':'#';
    $html       .= '<li class="' . $class . '"><a href="'.$li.'">&laquo;</a></li>';
 
    if ( $start > 1 ) {
        $html   .= '<li><a href="?'.$this->_other.'&limit=' . $this->_limit . '&page=1">1</a></li>';
        $html   .= '<li class="disabled"> <a href="#"><span>...</span></a></li>';
    }
 
    for ( $i = $start ; $i <= $end; $i++ ) {
        $class  = ( $this->_page == $i ) ? "active" : "";
        $html   .= '<li class="' . $class . '"><a href="?'.$this->_other.'&limit=' . $this->_limit . '&page=' . $i.'">' . $i . '</a></li>';
    }
 
    if ( $end < $last ) {
        $html   .= '<li class="disabled"><a href="#"><span>...</span></a></li>';
        $html   .= '<li><a href="?'.$this->_other.'&limit=' . $this->_limit . '&page=' . $last .'">' . $last . '</a></li>';
    }
 
    $class      = ( $this->_page == $last || $last==0  ) ? "disabled" : "";
    $li         = ($this->_page == $last || $last==0)?'#':'?'.$this->_other.'&limit=' . $this->_limit . '&page=' . ( $this->_page + 1 ) .'';
    $html       .= '<li class="' . $class . '"><a href="'.$li.'">&raquo;</a></li>';
 
    $html       .= '</ul>';
 
    return $html;
}

public function getSummery(){
    return ( (( $this->_page - 1 ) * $this->_limit )+1). ' to '.$this->_limit*$this->_page.' from '.$this->_total.' Records';
}

public function getStart(){
    return (( $this->_page - 1 ) * $this->_limit );
}

}
