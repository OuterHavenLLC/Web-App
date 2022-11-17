<?php
 Class Album extends GW {
  function __construct() {
   parent::__construct();
   $this->you = $this->system->Member($this->system->Username());
  }
  function Edit(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["AID", "new"]);
   $frbtn = "";
   $id = $data["AID"];
   $new = $data["new"] ?? 0;
   $r = $this->system->Change([[
    "[Error.Header]" => "Error",
    "[Error.Message]" => "The Album Identifier is missing."
   ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if($this->system->ID == $you) {
    $fr = $this->system->Change([[
     "[Error.Header]" => "Forbidden",
     "[Error.Message]" => "You must sign in to continue."
    ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
   } elseif(!empty($id) || $new == 1) {
    $action = ($new == 1) ? "Post" : "Update";
    $t = $data["UN"] ?? base64_encode($you);
    $t = base64_decode($t);
    $t = ($t == $you) ? $y : $this->system->Member($t);
    $fs = $this->system->Data("Get", ["fs", md5($t["Login"]["Username"])]);
    $id = ($new == 1) ? md5($t["Login"]["Username"].$this->system->timestamp) : $id;
    $alb = $fs["Albums"][$id] ?? [];
    $description = $alb["Description"] ?? "";
    $nsfw = $alb["NSFW"] ?? $y["Privacy"]["NSFW"];
    $privacy = $alb["Privacy"] ?? $y["Privacy"]["Albums"];
    $title = $alb["Title"] ?? "";
    $header = ($new == 1) ? "Create New Album" : "Edit $title";
    $bck = $this->view(base64_encode("Language:Edit"), ["Data" => [
     "ID" => base64_encode($id)
    ]]);
    $fr = $this->system->Change([[
     "[Album.Header]" => $header,
     "[Album.Description]" => $description,
     "[Album.ID]" => $id,
     "[Album.New]" => $new,
     "[Album.Options.NSFW]" => $this->system->Select("nsfw", "req v2w", $nsfw),
     "[Album.Options.Privacy]" => $this->system->Select("Privacy", "req v2w", $privacy),
     "[Album.Title]" => $title
    ], $this->system->Page("760cd577207eb0d2121509d7212038d4")]);
    $frbtn = $this->system->Element(["button", $action, [
     "class" => "CardButton SendData dB2C",
     "data-form" => ".ALBE_$id",
     "data-processor" => base64_encode("v=".base64_encode("Album:Save"))
    ]]);
   }
   return $this->system->Card([
    "Front" => $fr,
    "FrontButton" => $frbtn
   ]);
  }
  function Home(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, [
    "AID",
    "UN",
    "b2",
    "back",
    "lPG",
    "lPP"
   ]);
   $y = $this->you;
   $id = $data["AID"];
   $b2 = $data["b2"] ?? "Albums";
   $bck = $data["back"] ?? 0;
   $lpg = $data["lPG"];
   $lpp = $data["lPP"] ?? "OHCC";
   $un = $data["UN"] ?? $y["Login"]["Username"];
   $bck = ($bck == 1) ? $this->system->Element(["button", "Back to $b2", [
    "class" => "GoToParent LI head",
    "data-type" => ".$lpp;$lpg"
   ]]) : "";
   $r = $this->system->Change([[
    "[Error.Back]" => $bck,
    "[Error.Header]" => "Not Found",
    "[Error.Message]" => "The requested Album could not be found."
   ], $this->system->Page("f7d85d236cc3718d50c9ccdd067ae713")]);
   if(!empty($id)) {
    $r = $bck.$this->view(base64_encode("Search:Containers"), ["Data" => [
     "AID" => $id,
     "UN" => $un,
     "st" => "MBR-XFS"
    ]]);
   }
   return $r;
  }
  function Home(array $a) {
   $ad = base64_encode("Authentication:DeleteAlbum");
   $ae = base64_encode("Album:Edit");
   $ah = base64_encode("Album:Home");
   $as = base64_encode("Album:Share");
   $cr = base64_encode("Common:Reactions");
   $data = $a["Data"] ?? [];
   $fu = base64_encode("File:Upload");
   $lpg = $data["lPG"] ?? "";
   $lpp = $data["lPP"] ?? "OHCC";
   $id = $data["AID"] ?? "";
   $b2 = $data["b2"] ?? "Albums";
   $b2 = urlencode($b2);
   $bck = $data["back"] ?? 0;
   $r = $this->system->Change([[
    "[Error.Header]" => "Not Found",
    "[Error.Message]" => "The Album Identifier is missing."
   ], $this->system->Page("f7d85d236cc3718d50c9ccdd067ae713")]);
   $xfsLimit = $this->system->core["XFS"]["limits"]["Total"] ?? 0;
   $xfsLimit = str_replace(",", "", $xfsLimit)."MB";
   $xfsUsage = 0;
   $y = $this->you;
   $fs = $this->system->Data("Get", ["fs", md5($y["Login"]["Username"])]) ?? [];
   foreach($fs["Files"] as $k => $v) {
    $xfsUsage = $xfsUsage + $v["Size"];
   }
   $xfsUsage = number_format(round($xfsUsage / 1000));
   $xfsUsage = str_replace(",", "", $xfsUsage);
   if(!empty($id) || $new == 1) {
    $t = ($data["UN"] == $y["Login"]["Username"]) ? $y : $this->system->Member($data["UN"]);
    $fs = $this->system->Data("Get", [
     "fs",
     md5($t["Login"]["Username"])
    ]) ?? [];
    $tun = base64_encode($t["Login"]["Username"]);
    $abl = base64_encode($t["Login"]["Username"]."-$id");
    $alb = $fs["Albums"][$id] ?? [];
    $bl = $this->system->CheckBlocked([$y, "Albums", $abl]);
    $blc = ($bl == 0) ? "B" : "U";
    $blt = ($bl == 0) ? "Block" : "Unblock";
    $blt .= " <em>".$alb["Title"]."</em>";
    $blu = base64_encode("Common:SaveBlacklist");
    $ck = ($t["Login"]["Username"] == $y["Login"]["Username"]) ? 1 : 0;
    $ck2 = $y["subscr"]["XFS"]["A"] ?? 0;
    $ck2 = ($ck2 == 1 || $xfsUsage < $xfsLimit) ? 1 : 0;
    $ico = $alb["ICO"] ?? "";
    $ico = $this->system->GetSourceFromExtension([$t["Login"]["Username"], $ico]);
    $nsfw = ($alb["NSFW"] == 1) ? "No" : "Yes";
    $opt = ($ck == 0) ? $this->system->Element([
     "button", $blt, [
      "class" => "BLK LI v2 v2w",
      "data-cmd" => base64_encode($blc),
      "data-u" => base64_encode("v=$blu&BU=".base64_encode("<em>".$alb["Title"]."</em>")."&content=".base64_encode($abl)."&list=".base64_encode("Albums")."&BC=")
     ]
    ]) : "";
    if($ck == 1) {
     $opt .= ($ck2 == 1) ? $this->system->Element(["button", "Add Files", [
      "class" => "LI dB2O v2 v2w",
      "data-type" => base64_encode("v=$fu&AID=$id&UN=".$t["Login"]["Username"])
     ]]) : "";
     $opt .= ($id != md5("unsorted")) ? $this->system->Element([
      "button", "Delete Album", [
       "class" => "CFST LI dBO v2 v2w",
       "data-type" => "v=$ad&AID=$id&UN=$tun"
      ]
     ]) : "";
     $opt .= $this->system->Element(["button", "Edit Album", [
      "class" => "LI dB2O v2 v2w",
      "data-type" => base64_encode("v=$ae&AID=$id&UN=$tun")
     ]]);
    }
    $opt = ($y["Login"]["Username"] != $this->system->ID) ? $opt : "";
    $r = $this->system->Change([[
     "[Album.CoverPhoto]" => $ico,
     "[Album.Created]" => $this->system->TimeAgo($alb["Created"]),
     "[Album.Description]" => $alb["Description"],
     "[Album.Modified]" => $this->system->TimeAgo($alb["Modified"]),
     "[Album.Illegal]" => base64_encode("v=".base64_encode("Common:Illegal")."&ID=".base64_encode("Album;".$t["Login"]["Username"].";$id")),
     "[Album.NSFW]" => $nsfw,
     "[Album.Options]" => $opt,
     "[Album.Owner]" => $t["Personal"]["DisplayName"],
     "[Album.Reactions]" => $this->view($cr, ["Data" => [
      "CRID" => $id, "T" => $t["Login"]["Username"], "Type" => 2
     ]]),
     "[Album.Share]" => base64_encode("v=$as&ID=$id&UN=$tun"),
     "[Album.Title]" => $alb["Title"],
     "[Album.View]" => base64_encode("v=$ah&AID=$id&UN=$tun")
    ], $this->system->Page("91c56e0ee2a632b493451aa044c32515")]);
   }
   return $this->system->Card(["Front" => $r]);
  }
  function Save(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->DecodeBridgeData($data);
   $data = $this->system->FixMissing($data, [
    "AID", "Title", "new", "nsfw", "pri"
   ]);
   $ec = "Denied";
   $id = $data["AID"];
   $new = $data["new"] ?? 0;
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "The Album Identifier is missing."]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($y["Login"]["Username"] == $this->system->ID) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "You must be signed in to continue."
     ]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($id)) {
    $ec = "Accepted";
    $actionTaken = ($new == 1) ? "saved" : "updated";
    $fs = $this->system->Data("Get", [
     "fs",
     md5($y["Login"]["Username"])
    ]) ?? [];
    $efs = $fs["Albums"] ?? [];
    $cr = $efs[$id]["Created"] ?? $this->system->timestamp;
    $ico = $efs[$id]["ICO"] ?? "";
    $illegal = $efs[$id]["Illegal"] ?? 0;
    $nsfw = $data["nsfw"] ?? $y["privacy_opt"]["NSFW"];
    $pri = $data["pri"] ?? $y["privacy_opt"]["Albums"];
    $efs[$id] = [
     "Created" => $cr,
     "Description" => $data["Description"],
     "ICO" => $ico,
     "ID" => $id,
     "Illegal" => $illegal,
     "Modified" => $this->system->timestamp,
     "NSFW" => $nsfw,
     "Privacy" => $pri,
     "Title" => $data["Title"]
    ];
    $fs["Albums"] = $efs;
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "The Album was $actionTaken."]),
     "Header" => "Done"
    ]);
    #$this->system->Data("Save", ["fs", md5($y["Login"]["Username"]), $fs]);
   }
   return $this->system->JSONResponse([$ec, $r]);
  }
  function SaveDelete(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->DecodeBridgeData($data);
   $ec = "Denied";
   $id = $data["AID"] ?? "";
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "The Album Identifier is missing."]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "You must be signed in to continue."
     ]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($id)) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "The default Album cannot be deleted."
     ]),
     "Header" => "Error"
    ]);
    if($id != md5("unsorted")) {
     $ec = "Accepted";
     $a2 = [];
     $f2 = [];
     $fs = $this->system->Data("Get", [
      "fs",
      md5($y["Login"]["Username"])
     ]) ?? [];
     $id = base64_decode($id);
     $efs = $fs["Albums"] ?? [];
     $efs2 = $fs["Files"] ?? [];
     $ttl = $efs[$id]["Title"];
     foreach($efs as $k => $v) {
      if($k != $id && $v["ID"] != $id) {
       $a2[$k] = $v;
      }
     } foreach($efs2 as $k => $v) {
      if($v["AID"] == $id) {
       $v["AID"] = md5("unsorted");
       $f2[$k] = $v;
      }
     }
     #$this->view(base64_encode("Conversation:SaveDelete"), [
     # "Data" => ["ID" => $id]
     #]);
     #$this->system->Data("Purge", ["local", $id]);
     #$this->system->Data("Purge", ["react", $id]);
     $fs["Albums"] = $a2;
     $fs["Files"] = $f2;
     #$this->system->Data("Save", ["fs", md5($y["Login"]["Username"]), $fs]);
     $r = $this->system->Dialog([
      "Body" => $this->system->Element([
       "p", "The Album <em>$ttl</em> was successfully deleted."
      ]),
      "Header" => "Done",
      "Option2" => $this->system->Element(["button", "Okay", [
       "class" => "CFST2 dBC2"
      ]])
     ]);
    }
   }
   return $this->system->JSONResponse([$ec, $r]);
  }
  function Share(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["ID", "UN"]);
   $id = $data["ID"];
   $un = $data["UN"];
   $r = $this->system->Change([[
    "[Error.Header]" => "Error",
    "[Error.Message]" => "The Share Sheet Identifier is missing."
   ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
   $y = $this->you;
   if(!empty($id) && !empty($un)) {
    $un = base64_decode($un);
    $code = base64_encode("$un;$id");
    $t = ($un == $y["Login"]["Username"]) ? $y : $this->system->Member($un);
    $body = $this->system->PlainText([
     "Data" => $this->system->Element([
      "p", "Check out ".$t["Personal"]["DisplayName"]."'s media album!"
     ]).$this->system->Element([
      "div", "[Album:$code]", ["class" => "NONAME"]
     ]),
     "HTMLEncode" => 1
    ]);
    $body = base64_encode($body);
    $fs = $this->system->Data("Get", ["fs", md5($un)]) ?? [];
    $fs = $fs["Albums"][$id] ?? [];
    $r = $this->system->Change([[
     "[Share.Code]" => "v=".base64_encode("LiveView:GetCode")."&Code=$code&Type=Album",
     "[Share.ContentID]" => "Album",
     "[Share.GroupMessage]" => base64_encode("v=".base64_encode("Chat:ShareGroup")."&ID=$body"),
     "[Share.ID]" => $id,
     "[Share.Link]" => "",
     "[Share.Message]" => base64_encode("v=".base64_encode("Chat:Share")."&ID=$body"),
     "[Share.StatusUpdate]" => base64_encode("v=".base64_encode("StatusUpdate:Edit")."&body=$body&new=1&UN=".base64_encode($y["Login"]["Username"])),
     "[Share.Title]" => $fs["Title"]
    ], $this->system->Page("de66bd3907c83f8c350a74d9bbfb96f6")]);
   }
   return $this->system->Card(["Front" => $r]);
  }
  function __destruct() {
   // DESTROYS THIS CLASS
  }
 }
?>