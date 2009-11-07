<?php
# Copyright (c) 2009 John (Jack) Angers, jacktasia@gmail.com
# Licensed under the terms of the MIT License (see LICENSE.txt)
# 
# 
# see findwords.org for demo and sql dump of word list.

class FindWords {
  protected $have_schars;
  protected $to_regex;
  protected $no_chars;

  function __construct($st) {
    $st = str_replace(' ','',$st);
    $dash_check = strpos($st,'-');
    $star_check = strpos($st,'*');
    $hat_check = strpos($st,'^');
    $this->have_schars = !($dash_check === false && 
			   $star_check === false && 
			   $hat_check === false);

    if ( $hat_check !== false ) {
      $st_raw = explode('^',$st);
      $this->to_regex = $st_raw[0];
      $this->no_chars = $st_raw[1];
    } else {
      $this->to_regex = $st;
      $this->no_chars = '~'; # likely not used
    }
  }
  
  function getRegex() {
    $no_chars = $this->no_chars;
    $regex = str_replace('-',"[^$no_chars]{1}",$this->to_regex);
    $regex = str_replace('*',"[^$no_chars]+",$regex);
    return "^$regex$";
  }

  function needRegex() {
    return $this->have_schars;
  }
}

/*

Example Usage: see findwords.org for demo and sql dump of word list.
for anagrams, based of off: http://codeidol.com/sql/sql-hack/Text-Handling/Solve-Anagrams/ 

$find_words = $_GET['find_words'];
$fw = new FindWords($find_words);
if ( $fw->needRegex() ) {
  $regex = $fw->getRegex();
  $sql = "SELECT * FROM words WHERE word REGEXP '$regex' ORDER BY word ASC LIMIT 200";
  $words = $db->GetAll($sql);
} else {

  $word_len = strlen($find_words);
  $sql = "SELECT * FROM `words` WHERE h = (SELECT SUM(1<<(ORD(SUBSTRING('$find_words',i,1))-97)*2)
            FROM integers
            WHERE i<=LENGTH('$find_words')) and LENGTH(word)=$word_len";
  $words = $db->GetAll($sql);
}
$smarty->assign('words_count',sizeof($words));
$smarty->assign('words',$words);
$smarty->assign('find_words',$find_words);

*/

?>
