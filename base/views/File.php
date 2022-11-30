<?php
 Class File extends GW {
  function __construct() {
   parent::__construct();
   $this->you = $this->system->Member($this->system->Username());
  }
  function Download(array $a) {
   $data = $a["Data"] ?? [];
   $filePath = $data["FilePath"] ?? "";
   if(empty($filePath)) {
    return "Not Found";
   } else {
    $filePath = $this->system->efs.base64_decode($filePath);
    header("Content-type: application/x-file-to-save");
    header("Content-Disposition: attachment; filename=". basename($filePath));
    ob_end_clean();
    readfile($filePath);
    exit;
   }
  }
  function Edit(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["ID", "UN"]);
   $id = $data["ID"];
   $r = $this->system->Change([[
    "[Error.Header]" => "Forbidden",
    "[Error.Message]" => "The File Identifier is missing."
   ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
   $un = $data["UN"];
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if($this->system->ID == $you) {
    $r = $this->system->Change([[
     "[Error.Header]" => "Forbidden",
     "[Error.Message]" => "You must sign in to continue."
    ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
   } elseif(!empty($id)) {
    $id = base64_decode($id);
    $username = $data["UN"] ?? base64_encode($you);
    $username = base64_decode($username);
    $fileSystem = $this->system->Data("Get", ["fs", md5($username)]) ?? [];
    $files = ($this->system->ID == $username) ? $this->system->Data("Get", [
     "x",
     "fs"
    ]) : $fileSystem["Files"];
    $file = $files[$id] ?? [];
    $nsfw = $file["NSFW"] ?? $y["Privacy"]["NSFW"];
    $privacy = $file["Privacy"];
    $r = $this->system->Change([[
     "[File.Description]" => $file["Description"],
     "[File.ID]" => $id,
     "[File.NSFW]" => $this->system->Select("nsfw", "req v2w", $nsfw),
     "[File.Privacy]" => $this->system->Select("Privacy", "req v2w", $privacy),
     "[File.Title]" => $file["Title"],
     "[File.Username]" => $username
    ], $this->system->Page("7c85540db53add027bddeb42221dd104")]);
    $frbtn = $this->system->Element(["button", "Update", [
     "class" => "SendData",
     "data-form" => ".EditFile$id",
     "data-processor" => base64_encode("v=".base64_encode("File:Save"))
    ]]);
   }
   return $this->system->Card([
    "Front" => $r,
    "FrontButton" => $frbtn
   ]);
  }
  function Home(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, [
    "AddTo",
    "Added",
    "CARD",
    "ID",
    "UN",
    "back",
    "lPG",
    "lPP"
   ]);
   $back = ($data["back"] == 1) ? $this->system->Element([
    "button", "Back to Files", [
     "class" => "GoToParent LI",
     "data-type" => ".".$data["lPP"].";".$data["lPG"]
    ]
   ]) : "";
   $id = $data["ID"] ?? "";
   $pub = $data["pub"] ?? 0;
   $r = $this->system->Change([[
    "[Error.Header]" => "Not Found",
    "[Error.Message]" => "The File Identifier or Username are missing."
   ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
   $un = $data["UN"] ?? "";
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if(!empty($id) && !empty($un)) {
    $t = ($un == $you) ? $y : $this->system->Member($un);
    $fs = $this->system->Data("Get", [
     "fs",
     md5($t["Login"]["Username"])
    ]) ?? [];
    $atf = base64_encode($t["Login"]["Username"]."-".$id);
    $dm = base64_encode(json_encode([
     "t" => $un,
     "y" => $you
    ]));
    $efs = ($this->system->ID == $un) ? $this->system->Data("Get", [
     "x",
     "fs"
    ]) : $fs["Files"];
    $r = $this->system->Change([[
     "[Error.Header]" => "Not Found",
     "[Error.Message]" => "The File <em>$id</em> could not be found."
    ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
    if(!empty($efs[$id])) {
     $at = $data["AddTo"] ?? "";
     $at = (!empty($at)) ? explode(":", base64_decode($at)) : [];
     $at = (!empty($at[1])) ? $this->system->Element(["button", $at[0], [
      "class" => "AddTo LI",
      "data-a" => $atf,
      "data-c" => $data["Added"],
      "data-f" => base64_encode($at[1]),
      "data-m" => $dm
     ]]) : "";
     $bl = $this->system->CheckBlocked([$y, "Files", $id]);
     $ck = ($un == $this->system->ID && $y["Rank"] == md5("High Command")) ? 1 : 0;
     $dlc = $efs[$id] ?? [];
     $fck = $this->system->CheckFileType([$dlc["EXT"], "Photo"]);
     $fd = ($ck == 1 || $un == $you) ? $this->system->Element([
      "button", "Delete", [
       "class" => "LI dBO",
       "data-type" => "v=".base64_encode("Authentication:DeleteFile")."&ID=$id&UN=".base64_encode($un)
      ]
     ]) : "";
     $fe = ($ck == 1 || $un == $you) ? $this->system->Element([
      "button", "Edit", [
       "class" => "LI dB2O",
       "data-type" => base64_encode("v=".base64_encode("File:Edit")."&ID=".base64_encode($id)."&UN=".base64_encode($un))
      ]
     ]) : "";
     $nsfw = $dlc["NSFW"] ?? $y["Privacy"]["NSFW"];
     $opt = "";
     if($nsfw == 0 && $fck == 1) {
      $nsfw = ($nsfw == 1) ? "Adults Only" : "Kid-Friendly";
      $isrc = $this->system->GetSourceFromExtension([
       $t["Login"]["Username"],
       $dlc
      ]);
      list($isw, $ish) = getimagesize($isrc);
      $is = ($ish <= ($isw / 1.5) || $ish == $isw) ? 1 : 0;
      $cp = ($ish <= ($isw / 1.5)) ? "Cover Photo" : "Profile Picture";
      $type = ($ish <= ($isw / 1.5)) ? "CoverPhoto" : "ProfilePicture";
      $type = base64_encode($type);
      $opt .= ($is == 1) ? $this->system->Element([
       "button", "Set as Your $cp", [
        "class" => "Disable LI dBO",
        "data-type" => "v=".base64_encode("File:SaveProfileImage")."&DLC=$atf&FT=$type"
       ]
      ]) : "";
     }
     $bl = ($un != $you) ? $this->system->Element([
      "button", "Block this File", [
       "class" => "BLK LI",
       "data-cmd" => base64_encode("B"),
       "data-u" => base64_encode("v=".base64_encode("Common:SaveBlacklist")."&BU=".base64_encode("this File")."&content=".base64_encode($id)."&list=".base64_encode("Files")."&BC=")
      ]
     ]) : "";
     $bl = ($this->system->ID != $you) ? $bl : "";
     $reactions = ($un != $you) ? $this->view(base64_encode("Common:Reactions"), ["Data" => [
      "CRID" => $id,
      "T" => $t["Login"]["Username"],
      "Type" => 2
     ]]) : "";
     $r = $this->system->Change([[
      "[File.AddTo]" => $at,
      "[File.Back]" => $back,
      "[File.Block]" => $bl,
      "[File.Conversation]" => $this->system->Change([[
       "[Conversation.CRID]" => $id,
       "[Conversation.CRIDE]" => base64_encode($id),
       "[Conversation.Level]" => base64_encode(1),
       "[Conversation.URL]" => base64_encode("v=".base64_encode("Conversation:Home")."&CRID=[CRID]&LVL=[LVL]")
      ], $this->system->Page("d6414ead3bbd9c36b1c028cf1bb1eb4a")]),
      "[File.Delete]" => $fd,
      "[File.Description]" => $dlc["Description"],
      "[File.Download]" => $this->system->base."/?_API=Web&v=".base64_encode("File:Download")."&FilePath=".base64_encode($t["Login"]["Username"]."/".$dlc["Name"]),
      "[File.Edit]" => $fe,
      "[File.Extension]" => $dlc["EXT"],
      "[File.ID]" => $dlc["ID"],
      "[File.Illegal]" => base64_encode("v=".base64_encode("Common:Illegal")."&ID=".base64_encode("File;".$t["Login"]["Username"].";$id")),
      "[File.Modified]" => $this->system->TimeAgo($dlc["Modified"]),
      "[File.Name]" => $dlc["Name"],
      "[File.NSFW]" => $nsfw,
      "[File.Options]" => $opt,
      "[File.Preview]" => $this->system->AttachmentPreview([
       "DLL" => $dlc,
       "T" => $un,
       "Y" => $you
      ]).$this->system->Element(["div", NULL, [
       "class" => "NONAME",
       "style" => "height:0.5em"
      ]]),
      "[File.Reactions]" => $reactions,
      "[File.Share]" => base64_encode("v=".base64_encode("File:Share")."&ID=".base64_encode($id)."&UN=".base64_encode($t["Login"]["Username"])),
      "[File.Title]" => $dlc["Title"],
      "[File.Type]" => $dlc["Type"],
      "[File.Uploaded]" => $this->system->TimeAgo($dlc["Timestamp"])
     ], $this->system->Page("c31701a05a48069702cd7590d31ebd63")]);
    }
   }
   $r = ($data["back"] == 1) ? $back.$r : $r;
   $r = ($data["CARD"] == 1) ? $this->system->Card(["Front" => $r]) : $r;
   $r = ($pub == 1) ? $this->view(base64_encode("WebUI:Containers"), [
    "Data" => ["Content" => $r]
   ]) : $r;
   return $r;
  }
  function Save(array $a) {
   $accessCode = "Denied";
   $data = $a["Data"] ?? [];
   $data = $this->system->DecodeBridgeData($data);
   $data = $this->system->FixMissing($data, [
    "Description",
    "ID",
    "Title",
    "UN",
    "nsfw",
    "Privacy"
   ]);
   $id = $data["ID"];
   $r = $this->system->Dialog([
    "Body" => $this->system->Element([
     "p", "The File Identifier is missing."
    ]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if($this->system->ID == $you) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "You must be signed in to continue."
     ]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($id)) {
    $accessCode = "Accepted";
    $username = $data["UN"] ?? $you;
    $fileSystem = $this->system->Data("Get", ["fs", md5($username)]) ?? [];
    $files = ($this->system->ID == $username) ? $this->system->Data("Get", [
     "x",
     "fs"
    ]) : $fileSystem["Files"];
    $now = $this->system->timestamp;
    $file = $files[$id] ?? [];
    $file["Created"] = $files[$id]["Created"] ?? $now;
    $file["Description"] = $data["Description"];
    $file["Illegal"] = $files[$id]["Illegal"] ?? 0;
    $file["Modified"] = $now;
    $file["NSFW"] = $data["nsfw"];
    $file["Privacy"] = $data["pri"];
    $file["Title"] = $data["Title"];
    $files[$id] = $file;
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "The file <em>".$file["Title"]."</em> was updated.<br/>".json_encode($files, true)
     ]),
     "Header" => "Done"
    ]);
    if($this->system->ID == $username) {
     #$this->system->Data("Save", ["x", "fs", $files]);
    } else {
     $fileSystem["Files"] = $files;
     #$this->system->Data("Save", ["fs", md5($you), $fileSystem]);
    }
    $this->system->Statistic("ULu");
   }
   return $this->system->JSONResponse([
    "AccessCode" => $accessCode,
    "Response" => [
     "JSON" => "",
     "Web" => $r
    ],
    "ResponseType" => "Dialog",
    "Success" => "CloseCard"
   ]);
  }
  function SaveDelete(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->DecodeBridgeData($data);
   $ec = "Denied";
   $y = $this->you;
   $you = $y["Login"]["Username"];
   $id = $data["AID"] ?? md5("unsorted");
   $un = $data["UN"] ?? $you;
   $fs = $this->system->Data("Get", ["fs", md5($un)]) ?? [];
   $efs = $fs["Files"] ?? [];
   $efs = ($un == $this->system->ID) ? $this->system->Data("Get", [
    "x",
    "fs"
   ]) : $efs;
   $id = $data["ID"] ?? "";
   $r = $this->system->Dialog([
    "Body" => $this->system->Element([
     "p", "The File Identifier is missing."
    ]),
    "Header" => "Error"
   ]);
   if(md5($data["PIN"]) != $y["Login"]["PIN"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "The PINs do not match."]),
     "Header" => "Error"
    ]);
   } elseif($this->system->ID == $you) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "You must be signed in to continue."
     ]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($id) && !empty($un)) {
    $ec = "Accepted";
    $fs = $this->system->Data("Get", ["fs", md5($you)]) ?? [];
    $efs2 = [];
    $efsa = $fs["Albums"] ?? [];
    $pts = $this->system->core["PTS"]["DeleteFile"];
    foreach($efs as $k => $v) {
     if($k != $id && $v["AID"] != $id) {
      $efs2[$k] = $v;
     } else {
      $p2p = $this->system->core["EFS"] ?? [];
      $p2p_domain = ftp_connect($this->system->p2p);
      $p2p_password = base64_decode($p2p["Password"]);
      $p2p_username = base64_decode($p2p["Username"]);
      if(!ftp_login($p2p_domain, $p2p_username, $p2p_password)) {
       array_push($fck, "Failed to connect to the Extended File System.");
       array_push($_F, [$f["name"][$k], "$err.", $fck]);
      } else {
       ftp_pasv($p2p_domain, true);
       ftp_chmod($p2p_domain, 0777, "html");
       ftp_chdir($p2p_domain, "html");
       if(!in_array($mbr, ftp_nlist($p2p_domain, "."))) {
        ftp_mkdir($p2p_domain, "$mbr");
       }
       ftp_chmod($p2p_domain, 0777, $mbr);
       ftp_chdir($p2p_domain, $mbr);
       $list = ftp_nlist($p2p_domain, ".");
       if(in_array($v["Name"], $list)) {
        if($efsa[$id]["ICO"] == $v["Name"] && $un == $you) {
         $efsa[$id]["ICO"] = "";
        }
        $this->view(base64_encode("Conversation:SaveDelete"), [
         "Data" => ["ID" => $k]
        ]);
        $this->system->Data("Purge", ["react", $k]);
        ftp_delete($p2p_domain, $v["Name"]);
       }
      }
      ftp_close($p2p_domain);
     }
    } if($un == $this->system->ID) {
     $this->system->Data("Save", ["x", "fs", $efs2]);
    } else {
     $y["Points"] = $y["Points"] + $pts;
     $fs["Albums"] = $efsa;
     $fs["Files"] = $efs2;
     $this->system->Data("Save", ["fs", md5($you), $fs]);
     $this->system->Data("Save", ["mbr", md5($you), $y]);
    }
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "The File was deleted."]),
     "Header" => "Done"
    ]);
   }
   return $this->system->JSONResponse([$ec, $r]);
  }
  function SaveProfileImage(array $a) {
   $data = $a["Data"];
   $dlc = $data["DLC"] ?? "";
   $type = $data["FT"] ?? "";
   $r = $this->system->Dialog([
    "Body" => $this->system->Element([
     "p", "The Photo type is missing."
    ]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if($this->system->ID == $you) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "You must be signed in to continue."
     ]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($dlc) && !empty($type)) {
    $type = base64_decode($type);
    $cp = ($type == "CoverPhoto") ? "Cover Photo" : "Profile Picture";
    $dbi = explode("-", base64_decode($dlc));
    if(!empty($dbi[0]) && !empty($dbi[1])) {
     $t = $this->system->Member($dbi[0]);
     $fs = $this->system->Data("Get", [
      "fs",
      md5($t["Login"]["Username"])
     ]) ?? [];
     $ico = $dbi[0]."/".$fs["Files"][$dbi[1]]["Name"];
     $y["Personalization"][$type] = base64_encode($ico);
    }
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "The Photo was set as your $cp."
     ]),
     "Header" => "$cp Set!"
    ]);
    $this->system->Data("Save", ["mbr", md5($y["Login"]["Username"]), $y]);
   }
   return $r;
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
    $id = base64_decode($id);
    $un = base64_decode($un);
    $code = base64_encode("$un;$id");
    $t = ($un == $y["Login"]["Username"]) ? $y : $this->system->Member($un);
    $fs = $this->system->Data("Get", ["fs", md5($un)]) ?? [];
    $fs = $fs["Files"][$id] ?? [];
    $body = $this->system->PlainText([
     "Data" => $this->system->Element([
      "p", "Check out the ".$fs["Type"]." ".$t["Personal"]["DisplayName"]." uploaded!"
     ]).$this->system->Element([
      "div", "[ATT:$code]", ["class" => "NONAME"]
     ]),
     "HTMLEncode" => 1
    ]);
    $body = base64_encode($body);
    $r = $this->system->Change([[
     "[Share.Code]" => "v=".base64_encode("LiveView:GetCode")."&Code=$code&Type=ATT",
     "[Share.ContentID]" => $fs["Type"],
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
  function SaveUpload(array $a) {
   $_F = [];
   $_PS = [];
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["AID", "UN", "ss"]);
   $err = "Internal Error";
   $id = $data["AID"];
   $un = $data["UN"];
   $y = $this->you;
   $fs = $this->system->Data("Get", ["fs", md5($y["Login"]["Username"])]) ?? [];
   $fa = $fs["Albums"] ?? [];
   $fs = $fs["Files"] ?? [];
   if(empty($id) || empty($un)) {
    $r = [
     "F" => "Denied",
     "MSG" => "You don't have permission to access this view.",
     "PS" => $_PS
    ];
   } else {
    header("Content-Type: application/json");
    $un = base64_decode($un);
    $admin = ($un == $this->system->ID) ? 1 : 0;
    $admin = ($admin == 1 && $y["Rank"] == md5("High Command")) ? 1 : 0;
    $admin = ($admin == 1 || $un != $this->system->ID) ? 1 : 0;
    $f = $a["Files"] ?? [];
    $fpri = $data["pri"] ?? base64_encode($y["Privacy"]["DLL"]);
    $fpri = base64_decode($fpri);
    $id = base64_decode($id);
    $id = $id ?? md5("unsorted");
    $nsfw = $data["nsfw"] ?? base64_encode($y["Privacy"]["NSFW"]);
    $nsfw = base64_decode($nsfw);
    $root = $_SERVER["DOCUMENT_ROOT"]."/transit/";
    $xfsLimits = $this->system->core["XFS"]["limits"] ?? [];
    $xfsLimit = str_replace(",", "", $xfsLimits["Total"]);
    $xfsUsage = 0;
    foreach($fs as $key => $file) {
     $size = $file["Size"] ?? 0;
     $xfsUsage = $xfsUsage + $size;
    }
    $xfsUsage = str_replace(",", "", $this->system->ByteNotation($xfsUsage));
    if($admin == 1 && $un == $this->system->ID) {
     $efs = $this->system->Data("Get", ["x", "fs"]) ?? [];
     $ck = 1;
    } else {
     $efs = $fs ?? [];
     $ck = $y["Subscriptions"]["XFS"]["A"] ?? 0;
     $ck = ($ck == 1 || $xfsUsage < $xfsLimit) ? 1 : 0;
    }
    $_DLC = $this->system->core["XFS"]["FT"] ?? [];
    $allowed = array_merge($_DLC["A"], $_DLC["D"], $_DLC["P"], $_DLC["V"]);
    $src = $this->system->efs;
    foreach($f["name"] as $k => $v) {
     $n = $f["name"][$k];
     $ext = explode(".", $n);
     $ext = strtolower(end($ext));
     $ck = ($admin == 1 || $ck == 1) ? 1 : 0;
     $ck2 = (in_array($ext, $allowed) && $f["error"][$k] == 0) ? 1 : 0;
     $mime = $f["type"][$k];
     $fn = md5($y["Login"]["Username"]."-".$n."-".$this->system->timestamp);
     $name = "$fn.$ext";
     $s = $this->system->ByteNotation($f["size"][$k]);
     $s2 = str_replace(",", "", $s);
     $tmp = $f["tmp_name"][$k];
     if(in_array($ext, $_DLC["A"])) {
      $src = $src."A.jpg";
      $ck3 = ($s2 < $xfsLimits["Audio"]) ? 1 : 0;
      $type = $this->system->core["XFS"]["FT"]["_FT"][0];
     } elseif(in_array($ext, $_DLC["P"])) {
      $src = $src."$un/$name";
      $ck3 = ($s2 < $xfsLimits["Images"]) ? 1 : 0;
      $type = $this->system->core["XFS"]["FT"]["_FT"][2];
     } elseif(in_array($ext, $_DLC["D"])) {
      $src = $src."D.jpg";
      $ck3 = ($s2 < $xfsLimits["Documents"]) ? 1 : 0;
      $type = $this->system->core["XFS"]["FT"]["_FT"][1];
     } elseif(in_array($ext, $_DLC["V"])) {
      $src = $src."V.jpg";
      $ck3 = ($s2 < $xfsLimits["Videos"]) ? 1 : 0;
      $type = $this->system->core["XFS"]["FT"]["_FT"][3];
     } else {
      $src = $src."D.jpg";
      $ck3 = ($s2 < $xfsLimits["Documents"]) ? 1 : 0;
      $type = $this->system->core["XFS"]["FT"]["_FT"][1];
     }
     $fck = [
      "Checks" => [
       "AdministratorClearance" => $admin,
       "Album" => $id,
       "File" => [
        "Clearance" => $ck2,
        "Data" => $f,
        "Name" => $name,
        "Limits" => [
         "Categories" => [
          "Audio" => $xfsLimits["Audio"],
          "Documents" => $xfsLimits["Documents"],
          "Images" => $xfsLimits["Images"],
          "Videos" => $xfsLimits["Videos"]
         ],
         "Clearance" => $ck3,
         "Size" => $s2,
         "Totals" => [$xfsUsage, $xfsLimit]
        ],
        "Size" => $s,
        "Type" => $type
       ],
       "MemberClearance" => $ck,
       "Subscription" => $y["Subscriptions"]["XFS"]["A"]
      ],
      "UploadErrorStatus" => $f["error"][$k],
      "TemporaryName" => $f["tmp_name"][$k]
     ];
     if($ck == 0 || $ck2 == 0 || $ck3 == 0) {
      if(!in_array($ext, $allowed)) {
       $err = "Invalid file type";
      } elseif($ck == 0) {
       $err = "Forbidden";
      } elseif($ck2 == 0) {
       $err = "File Clearance failed";
      } elseif($ck3 == 0) {
       $err = "File storage limit exceeded";
      } elseif($xfsUsage > $xfsLimit) {
       $err = "Total storage limit exceeded";
      }
      array_push($_F, [$f["name"][$k], $err, $fck]);
     } else {
      if(!move_uploaded_file($tmp, $root.basename($name))) {
       array_push($fck, "Failed to move $name to the transit camp.");
       array_push($_F, [$f["name"][$k], $err, $fck]);
      } else {
       $dlc = [
        "AID" => $id,
        "Description" => "",
        "EXT" => $ext,
        "ID" => $fn,
        "MIME" => $mime,
        "Modified" => $this->system->timestamp,
        "Name" => $name,
        "NSFW" => $nsfw,
        "Privacy" => $fpri,
        "Size" => $s,
        "Title" => $n,
        "Timestamp" => $this->system->timestamp,
        "Type" => $type
       ];
       $efs[$fn] = $dlc;
       $p2p = $this->system->core["EFS"] ?? [];
       $p2p_domain = ftp_connect($this->system->p2p);
       $p2p_password = base64_decode($p2p["Password"]);
       $p2p_username = base64_decode($p2p["Username"]);
       if(!ftp_login($p2p_domain, $p2p_username, $p2p_password)) {
        array_push($fck, "Failed to connect to the Extended File System.");
        array_push($_F, [$f["name"][$k], $err, $fck]);
       } else {
        ftp_pasv($p2p_domain, true);
        ftp_chmod($p2p_domain, 0777, "html");
        ftp_chdir($p2p_domain, "html");
        if(!in_array($un, ftp_nlist($p2p_domain, "."))) {
         ftp_mkdir($p2p_domain, $un);
        }
        ftp_chmod($p2p_domain, 0777, $un);
        ftp_chdir($p2p_domain, $un);
        $local = $root.basename($name);
        if(in_array($name, ftp_nlist($p2p_domain, "."))) {
         array_push($fck, "Duplicate file.");
         array_push($_F, [$f["name"][$k], $err, $fck]);
        } elseif(!ftp_put($p2p_domain, basename($name), $local, FTP_BINARY)) {
         array_push($fck, "Failed to push $name to the Extended File System.");
         array_push($_F, [$f["name"][$k], $err, $fck]);
        } else {
         if($admin == 1 && $un == $this->system->ID) {
          $dlc["UN"] = $y["Login"]["Username"];
          $this->system->Data("Save", ["x", "fs", $efs]);
         } else {
          $fs = [];
          $fs["Albums"] = $fa;
          $fs["Files"] = $efs;
          if(in_array($ext, $this->system->core["XFS"]["FT"]["P"])) {
           $fs["Albums"][$id]["ICO"] = $dlc["Name"];
          }
          $fs["Albums"][$id]["Modified"] = $this->system->timestamp;
          $y["Points"] = $y["Points"] + $this->system->core["PTS"]["NewContent"];
          $this->system->Data("Save", [
           "fs",
           md5($y["Login"]["Username"]), $fs
          ]);
          $this->system->Data("Save", [
           "mbr",
           md5($y["Login"]["Username"]), $y
          ]);
         }
         array_push($_PS, [$dlc, $fn, $fck]);
        }
        ftp_close($p2p_domain);
       }
       unlink($root.basename($name));
      }
     }
    }
    $r = ["D" => $data, "F" => $_F, "PS" => $_PS];
    $this->system->Statistic("UL");
   }
   return $this->system->JSONResponse($r);
  }
  function Upload(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, [
    "AID",
    "UN",
    "lPG",
    "lPP"
   ]);
   $back = ($data["back"] == 1) ? $this->system->Element([
    "button", "Back to Files", [
     "class" => "GoToParent LI",
     "data-type" => ".".$data["lPP"].";".$data["lPG"]
    ]
   ]) : "";
   $id = $data["AID"];
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if($this->system->ID == $you) {
    $fr = $this->system->Change([[
     "[Error.Header]" => "Forbidden",
     "[Error.Message]" => "You must sign in to continue."
    ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
   } elseif(!empty($id)) {
    $_HC = ($y["Rank"] == md5("High Command")) ? 1 : 0;
    $fs = $this->system->Data("Get", ["fs", md5($you)]) ?? [];
    $id = $id ?? md5("unsorted");
    $efs = $fs["Files"] ?? [];
    $xfsLimit = $this->system->core["XFS"]["limits"]["Total"] ?? 0;
    $xfsLimit = $xfsLimit."MB";
    $xfsUsage = 0;
    foreach($fs["Files"] as $key => $value) {
     $xfsUsage = $xfsUsage + $value["Size"];
    }
    $xfsUsage = $this->system->ByteNotation($xfsUsage)."MB";
    $limit = $this->system->Change([["MB" => "", "," => ""], $xfsLimit]);
    $usage = $this->system->Change([["MB" => "", "," => ""], $xfsUsage]);
    $r = $this->system->Change([[
     "[Error.Header]" => "Forbidden",
     "[Error.Message]" => "You may have reached your upload limit. You have used $xfsUsage, and exceeded the limit of $xfsLimit."
    ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
    $un = $data["UN"] ?? $you;
    $uploadsAllowed = ($usage < $limit) ? 1 : 0;
    $uploadsAllowed = $y["Subscriptions"]["XFS"]["A"] ?? $uploadsAllowed;
    #$uploadsAllowed = ($_HC == 1) ? 1 : $uploadsAllowed;
    if(!empty($id) && !empty($un) && $uploadsAllowed == 1) {
     $t = ($un != $you) ? $this->system->Member($un) : $y;
     $fs = $this->system->Data("Get", [
      "fs",
      md5($t["Login"]["Username"])
     ]) ?? [];
     $ck = ($_HC == 1 && $t["Login"]["Username"] == $this->system->ID) ? 1 : 0;
     $ck2 = ($t["Login"]["Username"] == $you) ? 1 : 0;
     $r = $this->system->Change([[
      "[Error.Header]" => "Forbidden",
      "[Error.Message]" => "You do not have permission to upload files to ".$t["Personal"]["DisplayName"]."'s Library."
     ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
     if($ck == 1 || $ck2 == 1) {
      $limit = ($ck == 1 || $y["Subscriptions"]["Artist"]["A"] == 1) ? "You do not have a cumulative upload limit" : "Your cumulative file upload limit is $xfsLimit";
      $opt = "<input name=\"UN\" type=\"hidden\" value=\"".$t["Login"]["Username"]."\"/>\r\n";
      if($ck == 1) {
       $opt .= "<input id=\"AID\" name=\"AID\" type=\"hidden\" value=\"".md5("unsorted")."\"/>\r\n";
       $opt .= "<input id=\"nsfw\" name=\"nsfw\" type=\"hidden\" value=\"0\"/>\r\n";
       $opt .= "<input id=\"pri\" name=\"pri\" type=\"hidden\" value=\"".md5("public")."\"/>\r\n";
       $title = "System Library";
      } elseif($ck2 == 1) {
       $opt .= "<input name=\"AID\" type=\"hidden\" value=\"$id\"/>\r\n";
       $opt .= $this->system->Element([
        "div", $this->system->Select("Privacy", "req v2w", $y["Privacy"]["Posts"]),
        ["class" => "Desktop50"]
       ]).$this->system->Element([
        "div", $this->system->Select("nsfw", "req v2w", $y["Privacy"]["NSFW"]),
        ["class" => "Desktop50"]
       ]);
       $title = $fs["Albums"][$id]["Title"] ?? "Unsorted";
      }
      $r = $this->system->Change([[
       "[Upload.Limit]" => $limit,
       "[Upload.Options]" => $opt,
       "[Upload.Title]" => $title,
       "[Upload.Upload]" => base64_encode("v=".base64_encode("File:SaveUpload"))
      ], $this->system->Page("bf6bb3ddf61497a81485d5eded18e5f8")]);
     }
    }
   }
   return $back.$r;
  }
  function __destruct() {
   // DESTROYS THIS CLASS
  }
 }
?>