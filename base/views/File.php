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
   $username = $data["UN"];
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
    $album = $this->system->Element(["p", "System Library`"]);
    if($this->system->ID != $username) {
     $album = $file["AID"] ?? md5("unsorted");
     $album = $this->system->Select("Album", "req v2w", $album);
    }
    $nsfw = $file["NSFW"] ?? $y["Privacy"]["NSFW"];
    $privacy = $file["Privacy"];
    $r = $this->system->Change([[
     "[File.Album]" => $album,
     "[File.Description]" => $file["Description"],
     "[File.ID]" => $id,
     "[File.NSFW]" => $this->system->Select("nsfw", "req v2w", $nsfw),
     "[File.Privacy]" => $this->system->Select("Privacy", "req v2w", $privacy),
     "[File.Title]" => $file["Title"],
     "[File.Username]" => $username
    ], $this->system->Page("7c85540db53add027bddeb42221dd104")]);
    $frbtn = $this->system->Element(["button", "Update", [
     "class" => "CardButton SendData",
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
    "lPG"
   ]);
   $back = ($data["back"] == 1) ? $this->system->Element([
    "button", "Back to Files", [
     "class" => "GoToParent LI",
     "data-type" => $data["lPG"]
    ]
   ]) : "";
   $id = $data["ID"] ?? "";
   $pub = $data["pub"] ?? 0;
   $r = $this->system->Change([[
    "[Error.Header]" => "Not Found",
    "[Error.Message]" => "The File Identifier or Username are missing."
   ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
   $username = $data["UN"] ?? "";
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if(!empty($id) && !empty($username)) {
    $t = ($username == $you) ? $y : $this->system->Member($username);
    $attachmentID = base64_encode($t["Login"]["Username"]."-".$id);
    $bl = $this->system->CheckBlocked([$y, "Files", $id]);
    $dm = base64_encode(json_encode([
     "t" => $username,
     "y" => $you
    ]));
    $files = $this->system->Data("Get", [
     "fs",
     md5($t["Login"]["Username"])
    ]) ?? [];
    $files = ($this->system->ID == $username) ? $this->system->Data("Get", [
     "x",
     "fs"
    ]) : $files["Files"];
    $file = $files[$id] ?? [];
    $r = $this->system->Change([[
     "[Error.Header]" => "Not Found",
     "[Error.Message]" => "The File <em>$id</em> could not be found."
    ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
    if(!empty($file) && $bl == 0) {
     $actions = ($username != $you) ? $this->view(base64_encode("Common:Reactions"), ["Data" => [
      "CRID" => $id,
      "T" => $t["Login"]["Username"],
      "Type" => 4
     ]]).$this->system->Element([
      "button", "Block", [
       "class" => "BLK Small v2",
       "data-cmd" => base64_encode("B"),
       "data-u" => base64_encode("v=".base64_encode("Common:SaveBlacklist")."&BU=".base64_encode("this File")."&content=".base64_encode($id)."&list=".base64_encode("Files")."&BC=")
      ]
     ]) : "";
     $addTo = $data["AddTo"] ?? "";
     $addTo = (!empty($addTo)) ? explode(":", base64_decode($addTo)) : [];
     $addTo = (!empty($addTo[1])) ? $this->system->Element([
      "button", $addTo[0], [
       "class" => "AddTo v2",
       "data-a" => $attachmentID,
       "data-c" => $data["Added"],
       "data-f" => base64_encode($addTo[1]),
       "data-m" => $dm
      ]
     ]) : "";
     $ck = ($this->system->ID == $username && $y["Rank"] == md5("High Command")) ? 1 : 0;
     $actions .= ($ck == 1 || $username == $you) ? $this->system->Element([
      "button", "Delete", [
       "class" => "Small dBO v2",
       "data-type" => "v=".base64_encode("Authentication:DeleteFile")."&AID=".$file["AID"]."&ID=$id&ParentView=".$this->system->PlainText([
        "Data" => $data["lPG"],
        "Encode" => 1
       ])."&UN=".base64_encode($username)
      ]
     ]) : "";
     $actions .= $this->system->Element([
      "button", "Download", [
       "class" => "Small v2",
       "onclick" => "W('".$this->system->base."/?_API=Web&v=".base64_encode("File:Download")."&FilePath=".base64_encode($t["Login"]["Username"]."/".$file["Name"])."', '_top');"
      ]
     ]);
     $actions .= ($ck == 1 || $username == $you) ? $this->system->Element([
      "button", "Edit", [
       "class" => "Small dB2O v2",
       "data-type" => base64_encode("v=".base64_encode("File:Edit")."&ID=".base64_encode($id)."&UN=".base64_encode($username))
      ]
     ]) : "";
     $fileCheck = $this->system->CheckFileType([$file["EXT"], "Photo"]);
     $nsfw = $file["NSFW"] ?? $y["Privacy"]["NSFW"];
     $setAsProfileImage = "";
     if($nsfw == 0 && $fileCheck == 1) {
      $_Source = $this->system->GetSourceFromExtension([
       $t["Login"]["Username"],
       $file
      ]);
      $readEFS = curl_init($_Source);
      curl_setopt($readEFS, CURLOPT_NOBODY, true);
      curl_exec($readEFS);
      $efsResponse = curl_getinfo($readEFS, CURLINFO_HTTP_CODE);
      curl_close($readEFS);
      if($efsResponse != 200) {
       $_Source = $this->system->efs."D.jpg";
      }
      list($height, $width) = getimagesize($_Source);
      $_Size = ($height <= ($width / 1.5) || $height == $width) ? 1 : 0;
      $cp = ($height <= ($width / 1.5)) ? "Cover Photo" : "Profile Picture";
      $type = ($height <= ($width / 1.5)) ? "CoverPhoto" : "ProfilePicture";
      $type = base64_encode($type);
      $setAsProfileImage = ($_Size == 1) ? $this->system->Element([
       "button", "Set as Your $cp", [
        "class" => "Disable dBO v2",
        "data-type" => "v=".base64_encode("File:SaveProfileImage")."&DLC=$attachmentID&FT=$type"
       ]
      ]) : "";
     }
     $nsfw = ($nsfw == 1) ? "Adults Only" : "Kid-Friendly";
     $r = $this->system->Change([[
      "[File.Actions]" => $actions,
      "[File.AddTo]" => $addTo,
      "[File.Back]" => $back,
      "[File.Conversation]" => $this->system->Change([[
       "[Conversation.CRID]" => $id,
       "[Conversation.CRIDE]" => base64_encode($id),
       "[Conversation.Level]" => base64_encode(1),
       "[Conversation.URL]" => base64_encode("v=".base64_encode("Conversation:Home")."&CRID=[CRID]&LVL=[LVL]")
      ], $this->system->Page("d6414ead3bbd9c36b1c028cf1bb1eb4a")]),
      "[File.Description]" => $file["Description"],
      "[File.Extension]" => $file["EXT"],
      "[File.ID]" => $id,
      "[File.Illegal]" => base64_encode("v=".base64_encode("Common:Illegal")."&ID=".base64_encode("File;".$t["Login"]["Username"].";$id")),
      "[File.Modified]" => $this->system->TimeAgo($file["Modified"]),
      "[File.Name]" => $file["Name"],
      "[File.NSFW]" => $nsfw,
      "[File.Preview]" => $this->system->GetAttachmentPreview([
       "DLL" => $file,
       "T" => $username,
       "Y" => $you
      ]).$this->system->Element(["div", NULL, [
       "class" => "NONAME",
       "style" => "height:0.5em"
      ]]),
      "[File.SetAsProfileImage]" => $setAsProfileImage,
      "[File.Share]" => base64_encode("v=".base64_encode("File:Share")."&ID=".base64_encode($id)."&UN=".base64_encode($t["Login"]["Username"])),
      "[File.Title]" => $file["Title"],
      "[File.Type]" => $file["Type"],
      "[File.Uploaded]" => $this->system->TimeAgo($file["Timestamp"])
     ], $this->system->Page("c31701a05a48069702cd7590d31ebd63")]);
    }
   }
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
    $album = $data["Album"] ?? md5("unsorted");
    $username = $data["UN"] ?? $you;
    $fileSystem = $this->system->Data("Get", ["fs", md5($username)]) ?? [];
    $files = ($this->system->ID == $username) ? $this->system->Data("Get", [
     "x",
     "fs"
    ]) : $fileSystem["Files"];
    $now = $this->system->timestamp;
    $file = $files[$id] ?? [];
    $file["AID"] = $album ?? $files[$id]["Created"];
    $file["Created"] = $files[$id]["Created"] ?? $now;
    $file["Description"] = $data["Description"];
    $file["Illegal"] = $files[$id]["Illegal"] ?? 0;
    $file["Modified"] = $now;
    $file["NSFW"] = $data["nsfw"];
    $file["Privacy"] = $data["Privacy"];
    $file["Title"] = $data["Title"];
    $files[$id] = $file;
    if($this->system->ID == $username) {
     $this->system->Data("Save", ["x", "fs", $files]);
    } else {
     $fileSystem["Files"] = $files;
     $this->system->Data("Save", ["fs", md5($you), $fileSystem]);
    }
    $this->system->Statistic("ULu");
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "The file <em>".$file["Title"]."</em> was updated.<br/>"
     ]),
     "Header" => "Done"
    ]);
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
   $accessCode = "Denied";
   $acknowledge = $this->Element(["button", "Okay", [
    "class" => "dBC v2 v2w"
   ]]);
   $data = $a["Data"] ?? [];
   $data = $this->system->DecodeBridgeData($data);
   $id = $data["ID"] ?? "";
   $parentView = $data["ParentView"] ?? "";
   $r = "The File Identifier is missing.";
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if(md5($data["PIN"]) != $y["Login"]["PIN"]) {
    $r = "The PINs do not match.";
   } elseif($this->system->ID == $you) {
    $r = "You must be signed in to continue.";
   } elseif(!empty($id) && !empty($parentView)) {
    $_ID = explode("-", $id);
    $accessCode = "Accepted";
    $files = $_FileSystem["Files"] ?? [];
    $id = $_ID[1];
    $username = $_ID[0];
    $fileSystem = $this->system->Data("Get", ["fs", md5($username)]) ?? [];
    $files = $fileSystem["Files"] ?? [];
    $files = ($this->system->ID == $username) ? $this->system->Data("Get", [
     "x",
     "fs"
    ]) : $files;
    $file = $files[$id] ?? [];
    $newFiles = [];
    $points = $this->system->core["PTS"]["DeleteFile"];
    $r = "The File <strong>#$id</strong> could not be found.";
    if(!empty($file["AID"])) {
     $albumID = $file["AID"];
     $albums = $fileSystem["Albums"] ?? [];
     foreach($files as $key => $value) {
      if($id != $value["ID"]) {
       $newFiles[$key] = $value;
      } else {
       $p2p = $this->system->core["EFS"] ?? [];
       $p2p_domain = ftp_connect($this->system->p2p);
       $p2p_password = base64_decode($p2p["Password"]);
       $p2p_username = base64_decode($p2p["Username"]);
       if(!ftp_login($p2p_domain, $p2p_username, $p2p_password)) {
        $accessCode = "Denied";
        $r = "Failed to connect to the Extended File System.";
       } else {
        ftp_pasv($p2p_domain, true);
        ftp_chmod($p2p_domain, 0777, "html");
        ftp_chdir($p2p_domain, "html");
        if(!in_array($username, ftp_nlist($p2p_domain, "."))) {
         ftp_mkdir($p2p_domain, $username);
        }
        ftp_chmod($p2p_domain, 0777, $username);
        ftp_chdir($p2p_domain, $username);
        $list = ftp_nlist($p2p_domain, ".");
        if(in_array($value["Name"], $list)) {
         $baseName = explode(".", $value["Name"])[0];
         if($albums[$albumID]["ICO"] == $value["Name"] && $username == $you) {
          $albums[$albumID]["ICO"] = "";
         }
         $this->view(base64_encode("Conversation:SaveDelete"), [
          "Data" => ["ID" => $key]
         ]);
         $this->system->Data("Purge", ["react", $key]);
         ftp_delete($p2p_domain, "thumbnail.$baseName.png");
         ftp_delete($p2p_domain, $value["Name"]);
        }
       }
       ftp_close($p2p_domain);
      }
     } if($this->system->ID == $username) {
      $this->system->Data("Save", ["x", "fs", $newFiles]);
     } else {
      $fileSystem["Albums"] = $albums;
      $fileSystem["Files"] = $newFiles;
      $y["Points"] = $y["Points"] + $points;
      $this->system->Data("Save", ["fs", md5($you), $fileSystem]);
      $this->system->Data("Save", ["mbr", md5($you), $y]);
     }
     $acknowledge = $this->system->Element(["button", "Okay", [
      "class" => "GoToParent dBC v2 v2w",
      "data-type" => $parentView
     ]]);
     $r = ($accessCode == "Accepted") ? "The File was deleted." : $r;
    }
   }
   $header = ($accessCode == "Denied") ? "Error" : "Done";
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", $r]),
    "Header" => $header,
    "Option2" => $acknowledge
   ]);
   return $this->system->JSONResponse([
    "AccessCode" => $accessCode,
    "Response" => [
     "JSON" => "",
     "Web" => $r
    ],
    "ResponseType" => "Dialog",
    "Success" => "CloseDialog"
   ]);
  }
  function SaveProfileImage(array $a) {
   $data = $a["Data"];
   $file = $data["DLC"] ?? "";
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
   } elseif(!empty($file) && !empty($type)) {
    $type = base64_decode($type);
    $cp = ($type == "CoverPhoto") ? "Cover Photo" : "Profile Picture";
    $dbi = explode("-", base64_decode($file));
    if(!empty($dbi[0]) && !empty($dbi[1])) {
     $t = $this->system->Member($dbi[0]);
     $fs = $this->system->Data("Get", [
      "fs",
      md5($t["Login"]["Username"])
     ]) ?? [];
     $image = $dbi[0]."/".$fs["Files"][$dbi[1]]["Name"];
     $y["Personal"][$type] = base64_encode($image);
    }
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "The Photo was set as your $cp.<br/>".json_encode($y["Personal"], true)
     ]),
     "Header" => "Done"
    ]);
    #$this->system->Data("Save", ["mbr", md5($you), $y]);
   }
   return $r;
  }
  function Share(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["ID", "UN"]);
   $id = $data["ID"];
   $username = $data["UN"];
   $r = $this->system->Change([[
    "[Error.Header]" => "Error",
    "[Error.Message]" => "The Share Sheet Identifier is missing."
   ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if(!empty($id) && !empty($username)) {
    $id = base64_decode($id);
    $username = base64_decode($username);
    $code = base64_encode("$username;$id");
    $t = ($username == $y["Login"]["Username"]) ? $y : $this->system->Member($username);
    $fileSystem = $this->system->Data("Get", ["fs", md5($username)]) ?? [];
    $file = $fileSystem["Files"][$id] ?? [];
    $body = $this->system->PlainText([
     "Data" => $this->system->Element([
      "p", "Check out the ".$file["Type"]." ".$t["Personal"]["DisplayName"]." uploaded!"
     ]).$this->system->Element([
      "div", "[ATT:$code]", ["class" => "NONAME"]
     ]),
     "HTMLEncode" => 1
    ]);
    $body = base64_encode($body);
    $r = $this->system->Change([[
     "[Share.Code]" => "v=".base64_encode("LiveView:GetCode")."&Code=$code&Type=ATT",
     "[Share.ContentID]" => $file["Type"],
     "[Share.GroupMessage]" => base64_encode("v=".base64_encode("Chat:ShareGroup")."&ID=$body"),
     "[Share.ID]" => $id,
     "[Share.Link]" => "",
     "[Share.Message]" => base64_encode("v=".base64_encode("Chat:Share")."&ID=$body"),
     "[Share.StatusUpdate]" => base64_encode("v=".base64_encode("StatusUpdate:Edit")."&body=$body&new=1&UN=".base64_encode($you)),
     "[Share.Title]" => $file["Title"]
    ], $this->system->Page("de66bd3907c83f8c350a74d9bbfb96f6")]);
   }
   return $this->system->Card(["Front" => $r]);
  }
  function SaveUpload(array $a) {
   $_Failed = [];
   $_Passed = [];
   $accessCode = "Denied";
   $data = $a["Data"] ?? [];
   $err = "Internal Error";
   $id = $data["AID"] ?? md5("unsorted");
   $username = $data["UN"] ?? "";
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if(empty($id) || empty($username)) {
    $r = [
     "Failed" => $_Failed,
     "MSG" => "You don't have permission to access this view.",
     "Passed" => $_Passed
    ];
   } else {
    header("Content-Type: application/json");
    $_FileSystem = $this->system->Data("Get", ["fs", md5($you)]) ?? [];
    $_DLC = $this->system->core["XFS"]["FT"] ?? [];
    $_HC = ($y["Rank"] == md5("High Command")) ? 1 : 0;
    $albums = $_FileSystem["Albums"] ?? [];
    $admin = ($username == $this->system->ID) ? 1 : 0;
    $admin = ($_HC == 1 && $admin == 1) ? 1 : 0;
    $admin = ($admin == 1 || $username != $this->system->ID) ? 1 : 0;
    $albumID = $data["AID"] ?? base64_encode(md5("unsorted"));
    $albumID = base64_decode($albumID);
    $files = $_FileSystem["Files"] ?? [];
    $files = $this->system->Data("Get", ["x", "fs"]) ?? $files;
    $now = $this->system->timestamp;
    $nsfw = $data["nsfw"] ?? base64_encode($y["Privacy"]["NSFW"]);
    $nsfw = base64_decode($nsfw);
    $privacy = $data["Privacy"] ?? base64_encode($y["Privacy"]["DLL"]);
    $privacy = base64_decode($privacy);
    $root = $_SERVER["DOCUMENT_ROOT"]."/transit/";
    $uploads = $a["Files"] ?? [];
    $username = base64_decode($username);
    $xfsLimits = $this->system->core["XFS"]["limits"] ?? [];
    $xfsLimit = str_replace(",", "", $xfsLimits["Total"]);
    $xfsUsage = 0;
    foreach($files as $key => $info) {
     $size = $info["Size"] ?? 0;
     $xfsUsage = $xfsUsage + $size;
    }
    $xfsUsage = str_replace(",", "", $this->system->ByteNotation($xfsUsage));
    $ck = $y["Subscriptions"]["XFS"]["A"] ?? 0;
    $ck = ($_HC == 1 || $ck == 1 || $xfsUsage < $xfsLimit) ? 1 : 0;
    $allowed = array_merge($_DLC["A"], $_DLC["D"], $_DLC["P"], $_DLC["V"]);
    foreach($uploads["name"] as $key => $value) {
     $n = $uploads["name"][$key];
     $ext = explode(".", $n);
     $ext = strtolower(end($ext));
     $ck = ($admin == 1 || $ck == 1) ? 1 : 0;
     $ck2 = (in_array($ext, $allowed) && $uploads["error"][$key] == 0) ? 1 : 0;
     $id = md5("$you-$n-$now");
     $mime = $uploads["type"][$key];
     $name = "$id.$ext";
     $size = $this->system->ByteNotation($uploads["size"][$key]);
     $size2 = str_replace(",", "", $size);
     $tmp = $uploads["tmp_name"][$key];
     if(in_array($ext, $_DLC["A"])) {
      $ck3 = ($size2 < $xfsLimits["Audio"]) ? 1 : 0;
      $type = $this->system->core["XFS"]["FT"]["_FT"][0];
     } elseif(in_array($ext, $_DLC["P"])) {
      $ck3 = ($size2 < $xfsLimits["Images"]) ? 1 : 0;
      $type = $this->system->core["XFS"]["FT"]["_FT"][2];
     } elseif(in_array($ext, $_DLC["D"])) {
      $ck3 = ($size2 < $xfsLimits["Documents"]) ? 1 : 0;
      $type = $this->system->core["XFS"]["FT"]["_FT"][1];
     } elseif(in_array($ext, $_DLC["V"])) {
      $ck3 = ($size2 < $xfsLimits["Videos"]) ? 1 : 0;
      $type = $this->system->core["XFS"]["FT"]["_FT"][3];
     } else {
      $ck3 = ($size2 < $xfsLimits["Documents"]) ? 1 : 0;
      $type = $this->system->core["XFS"]["FT"]["_FT"][1];
     }
     $fileCheck = [
      "Checks" => [
       "AdministratorClearance" => $admin,
       "Album" => $id,
       "File" => [
        "Clearance" => $ck2,
        "Data" => $uploads["name"],
        "Name" => $name,
        "Limits" => [
         "Categories" => [
          "Audio" => $xfsLimits["Audio"],
          "Documents" => $xfsLimits["Documents"],
          "Images" => $xfsLimits["Images"],
          "Videos" => $xfsLimits["Videos"]
         ],
         "Clearance" => $ck3,
         "Size" => $size2,
         "Totals" => [$xfsUsage, $xfsLimit]
        ],
        "Size" => $size,
        "Type" => $type
       ],
       "MemberClearance" => $ck,
       "Subscription" => $y["Subscriptions"]["XFS"]["A"]
      ],
      "UploadErrorStatus" => $uploads["error"][$key],
      "TemporaryName" => $uploads["tmp_name"][$key]
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
      array_push($_Failed, [$uploads["name"][$key], $err, $fileCheck]);
     } else {
      if(!move_uploaded_file($tmp, $root.basename($name))) {
       array_push($fileCheck, "Failed to move $name to the transit camp.");
       array_push($_Failed, [$uploads["name"][$key], $err, $fileCheck]);
      } else {
       $file = [
        "AID" => $albumID,
        "Description" => "",
        "EXT" => $ext,
        "ID" => $id,
        "MIME" => $mime,
        "Modified" => $now,
        "Name" => $name,
        "NSFW" => $nsfw,
        "Privacy" => $privacy,
        "Size" => $size,
        "Title" => $name,
        "Timestamp" => $now,
        "Type" => $type
       ];
       $files[$id] = $file;
       $p2p = $this->system->core["EFS"] ?? [];
       $p2p_domain = ftp_connect($this->system->p2p);
       $p2p_password = base64_decode($p2p["Password"]);
       $p2p_username = base64_decode($p2p["Username"]);
       if(!ftp_login($p2p_domain, $p2p_username, $p2p_password)) {
        array_push($fileCheck, "Failed to connect to the Extended File System.");
        array_push($_Failed, [$uploads["name"][$key], $err, $fileCheck]);
       } else {
        ftp_pasv($p2p_domain, true);
        ftp_chmod($p2p_domain, 0777, "html");
        ftp_chdir($p2p_domain, "html");
        if(!in_array($username, ftp_nlist($p2p_domain, "."))) {
         ftp_mkdir($p2p_domain, $username);
        }
        ftp_chmod($p2p_domain, 0777, $username);
        ftp_chdir($p2p_domain, $username);
        $local = $root.basename($name);
        if(in_array($name, ftp_nlist($p2p_domain, "."))) {
         array_push($fileCheck, "Duplicate file.");
         array_push($_Failed, [$uploads["name"][$key], $err, $fileCheck]);
        } elseif(!ftp_put($p2p_domain, basename($name), $local, FTP_BINARY)) {
         array_push($fileCheck, "Failed to push $name to the Extended File System.");
         array_push($_Failed, [$uploads["name"][$key], $err, $fileCheck]);
        } else {
         $accessCode = "Accepted";
         if($admin == 1 && $this->system->ID == $username) {
          $files[$id]["UN"] = $you;
          $this->system->Data("Save", ["x", "fs", $files]);
         } else {
          $_FileSystem = $_FileSystem ?? [];
          $_FileSystem["Albums"] = $albums;
          $_FileSystem["Files"] = $files;
          if(in_array($ext, $this->system->core["XFS"]["FT"]["P"])) {
           $thumbnail = $this->system->Thumbnail([
            "File" => $name,
            "Username" => $you
           ])["AlbumCover"] ?? $name;
           $_FileSystem["Albums"][$albumID]["ICO"] = $thumbnail;
          }
          $_FileSystem["Albums"][$albumID]["Modified"] = $now;
          $y["Points"] = $y["Points"] + $this->system->core["PTS"]["NewContent"];
          $this->system->Data("Save", ["fs", md5($you), $_FileSystem]);
          $this->system->Data("Save", ["mbr", md5($you), $y]);
         }
         array_push($_Passed, [
          "HTML" => $this->system->Element([
           "div", $this->system->GetAttachmentPreview([
            "DLL" => $file,
            "T" => $username,
            "Y" => $you
           ]), [
            "class" => "InnerMargin Medium"
           ]
          ]),
          "Raw" => $file
         ]);
        }
        ftp_close($p2p_domain);
       }
       unlink($root.basename($name));
      }
     }
    }
    $r = [
     "Data" => $data,
     "Failed" => $_Failed,
     "Passed" => $_Passed
    ];
    $this->system->Statistic("UL");
   }
   return $this->system->JSONResponse([
    "AccessCode" => $accessCode,
    "Response" => [
     "JSON" => $r
    ],
    "ResponseType" => "N/A"
   ]);
  }
  function Upload(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, [
    "AID",
    "UN",
    "lPG"
   ]);
   $albumID = $data["AID"];
   $back = ($data["back"] == 1) ? $this->system->Element([
    "button", "Back to Files", [
     "class" => "GoToParent LI header",
     "data-type" => $data["lPG"]
    ]
   ]) : "";
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if($this->system->ID == $you) {
    $r = $this->system->Change([[
     "[Error.Header]" => "Forbidden",
     "[Error.Message]" => "You must sign in to continue."
    ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
   } elseif(!empty($albumID)) {
    $_HC = ($y["Rank"] == md5("High Command")) ? 1 : 0;
    $username = $data["UN"] ?? $you;
    $fileSystem = $this->system->Data("Get", ["fs", md5($username)]) ?? [];
    $files = $fileSystem["Files"] ?? [];
    $xfsLimit = $this->system->core["XFS"]["limits"]["Total"] ?? 0;
    $xfsLimit = $xfsLimit."MB";
    $xfsUsage = 0;
    foreach($files as $key => $value) {
     $xfsUsage = $xfsUsage + $value["Size"];
    }
    $xfsUsage = $this->system->ByteNotation($xfsUsage)."MB";
    $limit = $this->system->Change([["MB" => "", "," => ""], $xfsLimit]);
    $usage = $this->system->Change([["MB" => "", "," => ""], $xfsUsage]);
    $r = $back.$this->system->Change([[
     "[Error.Header]" => "Forbidden",
     "[Error.Message]" => "You may have reached your upload limit. You have used $xfsUsage and exceeded the limit of $xfsLimit."
    ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
    $uploadsAllowed = ($usage < $limit) ? 1 : 0;
    $uploadsAllowed = $y["Subscriptions"]["XFS"]["A"] ?? $uploadsAllowed;
    if(!empty($username) && $uploadsAllowed == 1) {
     $ck = ($_HC == 1 && $this->system->ID == $username) ? 1 : 0;
     $ck2 = ($username == $you) ? 1 : 0;
     $files = ($this->system->ID == $username) ? $this->system->Data("Get", [
      "x",
      "fs"
     ]) : $files;
     $r = $this->system->Change([[
      "[Error.Header]" => "Forbidden",
      "[Error.Message]" => "You do not have permission to upload files to $username's Library."
     ], $this->system->Page("eac72ccb1b600e0ccd3dc62d26fa5464")]);
     if($ck == 1 || $ck2 == 1) {
      $limit = ($ck == 1 || $y["Subscriptions"]["Artist"]["A"] == 1) ? "You do not have a cumulative upload limit" : "Your cumulative file upload limit is $xfsLimit";
      $options = "<input name=\"UN\" type=\"hidden\" value=\"$username\"/>\r\n";
      if($ck == 1) {
       $options .= "<input id=\"AID\" name=\"AID\" type=\"hidden\" value=\"".md5("unsorted")."\"/>\r\n";
       $options .= "<input id=\"Privacy\" name=\"pri\" type=\"hidden\" value=\"".md5("public")."\"/>\r\n";
       $options .= "<input id=\"nsfw\" name=\"nsfw\" type=\"hidden\" value=\"0\"/>\r\n";
       $title = "System Library";
      } elseif($ck2 == 1) {
       $options .= "<input name=\"AID\" type=\"hidden\" value=\"$albumID\"/>\r\n";
       $options .= $this->system->Element([
        "div", $this->system->Select("Privacy", "req v2w", $y["Privacy"]["Posts"]),
        ["class" => "Desktop50"]
       ]).$this->system->Element([
        "div", $this->system->Select("nsfw", "req v2w", $y["Privacy"]["NSFW"]),
        ["class" => "Desktop50"]
       ]);
       $title = $fileSystem["Albums"][$albumID]["Title"] ?? "Unsorted";
      }
      $r = $this->system->Change([[
       "[Upload.Back]" => $back,
       "[Upload.Limit]" => $limit,
       "[Upload.Options]" => $options,
       "[Upload.Processor]" => base64_encode("v=".base64_encode("File:SaveUpload")),
       "[Upload.Title]" => $title
      ], $this->system->Page("bf6bb3ddf61497a81485d5eded18e5f8")]);
     }
    }
   }
   return $r;
  }
  function __destruct() {
   // DESTROYS THIS CLASS
  }
 }
?>