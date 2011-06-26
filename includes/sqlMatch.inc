<?php

class Match 
{
	var $field;
	var $exp;
	var $comparator;
	
	function Match($f, $e, $comp = "=")
	{
		$this->field = $f;
		$this->exp = $e;
		$this->comparator = $comp;
	}
	
	function toSQL()
	{
		return "(`$this->field` $this->comparator '$this->exp')";
	}
}

class MatchCollection extends Match
{
	var $collection;
	var $connector;
	
	function MatchCollection($connector)
	{
		$this->connector = $connector;
		$this->collection = array();
	}
	
	function addMatch($match)
	{
		if(!is_a($match, 'Match'))
			die("You can only add objects of type Match to the MatchCollection.");
		
		array_push($this->collection, $match);
	}
	
	function toSQL()
	{
		if(empty($this->collection))
			return TRUE;
		
		$string = " (";
		
		foreach($this->collection as $match)
		{
			$string .= $match->toSQL() . ":";
		}
		
		$string{strlen($string) - 1} = ")";
		
		$string = str_replace(":", " ".$this->connector." ", $string) . " ";
		
		return $string;
	}
	
	function flatten()
	{
		$marray = array();
		
		foreach($this->collection as $match)
		{
			if(is_a($match, "MatchCollection"))
				array_push($marray, $match->flatten());
			else
				array_push($marray, $match);
		}
		
		return $marray;
	}
}

?>