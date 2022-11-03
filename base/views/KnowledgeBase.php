<?php
 Class KnowledgeBase extends GW {
  function __construct() {
   parent::__construct();
   $this->you = $this->system->Member($this->system->Username());
  }
  function Administration(array $a) {
   $kb = $this->system->DatabaseSet("KB");
   // BEGIN TPL
   return "
<div class=\"Desktop75 MobileFull\">
 <h1 class=\"UpperCase\">Administration Dashboard</h1>
 <p>Manager for support inquiries and company feedback.</p>
 <input name=\"date\" min=\"1776-07-04\" max=\"2018-04-24\" type=\"date\" value=\"2021-04-23\">
 <p>Test Date Input</p>
 [Knowledge.List]
</div>
[footer]
   ".$this->system->Element(["div", $this->system->Element([
   	"p", json_encode($kb, true)
   ]), ["class" => "Desktop75 MobileFull"]]);//TEMP
   // END TPL
   return $this->system->Change([[
   	"[Knowledge.List]" => $this->view($sc, ["Data" => [
   	 "st" => "ADM-Knowledge"
   	]]),
   	"[footer]" => $this->system->Page("a095e689f81ac28068b4bf426b871f71")
   ], $this->system->Change("#")]);
  }
  function Home(array $a) {
   $sc = base64_encode("Search:Containers");
   // BEGIN TPL
   $r = "
<div class=\"Desktop75 MobileFull\">
 [Knowledge.List]
</div>
[footer]
   ";
   // END TPL
   return $this->system->Change([[
    "[Knowledge.List]" => $this->view($sc, ["Data" => [
     "st" => "Knowledge"
    ]]),
    "[footer]" => $this->system->Page("a095e689f81ac28068b4bf426b871f71")
   ], $r]);
   #], $this->system->Change("#")]);
  }
  function __destruct() {
   // DESTROYS THIS CLASS
  }
 }
?>