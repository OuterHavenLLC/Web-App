<?php
 Class System {
  protected function __construct() {
   try {
    $this->cypher = New Cypher;
    $this->ID = "ohc";
    $this->PayPalMID = base64_decode("Qk5aVjk0TkxYTDJESg==");
    $this->PayPalURL = "https://www.sandbox.paypal.com/cgi-bin/webscr";
    $this->ShopID = "Mike";
    $this->base = $this->ConfigureBaseURL();
    $this->core = $this->Core();
    $this->efs = $this->ConfigureBaseURL("efs");
    $this->p2p = "efs.outerhaven.nyc";
    $this->timestamp = date("Y-m-d h:i:sA");
    $this->region = $_COOKIE["region"] ?? "en_US";
    $this->sk = $_COOKIE["SK"] ?? "";
    $this->you = $this->Member($this->Username());
   } catch(PDOException $e) {
    return $this->Element([
     "p", "Failed to initialize GW... ".$e->getMessage()
    ]);
   }
  }
  function AdminMenu() {
   $album = base64_encode("Album:Home");
   $renewSubscriptions = base64_encode("Subscription:RenewAll");
   $search = base64_encode("Search:Containers");
   return $this->Change([[
    "[Admin.Databases]" => "W('https://www.outerhaven.nyc/dba', '_blank');",
    "[Admin.Domain]" => "W('https://www.godaddy.com/', '_blank');",
    "[Admin.Files]" => "v=$album&AID=".md5("unsorted")."&UN=".base64_encode($this->ID),
    "[Admin.Pages]" => "v=$search&st=ADM-LLP",
    "[Admin.RenewSubscriptions]" => "v=$renewSubscriptions",
    "[Admin.Server]" => "W('https://www.digitalocean.com/', '_blank');",
   ], $this->Page("5c1ce5c08e2add4d1487bcd2193315a7")]);
  }
  function AttachmentPreview(array $a) {
   $s = $this->efs."/".$a["T"]."/".$a["DLL"]["Name"];
   $t = $a["DLL"]["Type"] ?? "";
   $r = "";
   if($t == "Audio") {
    $cover = $this->efs."/A.jpg";
    $r = $this->Element([
     "source", NULL, ["src" => $s, "type" => $a["DLL"]["MIME"]]
    ]);
    $r = "<audio class=\"PreviewAudio\" controls>$r</audio>\r\n";
    // F.A.B. Source: $this->Element(["source", NULL, ["src" => "[base]:8000/listen.pls?sid=1", "type" => "audio/aac"]])
   } elseif($t == "Document") {
    $cover = $this->efs."/D.jpg";
    $r = $this->Element([
     "h3", $a["DLL"]["Title"], ["class" => "CenterText PreviewDocument"]
    ]);
   } elseif($t == "Photo") {
    $s = $this->GetSourceFromExtension([$a["T"], $a["DLL"]]);
    $r = "<img src=\"$s\" style=\"width:100%\"/>\r\n";
   } elseif($t == "Video") {
    $r = $this->eElement(["video", $this->Element([
     "source", NULL, ["src" => $s, "type" => $a["DLL"]["MIME"]]
    ])]);
   }
   return $r;
  }
  function ByteNotation(int $a, $b = "MB") {
   $units = [
    "GB" => number_format($a / 1073741824, 2),
    "KB" => number_format($a / 1024, 2),
    "MB" => number_format($a / 1048576, 2)
   ];
   $r = $units[$b] ?? $units["MB"];
   return $r ?? 0;
  }
  function CallSign($a) {
   return $this->Change([[
    " " => "",
    "'" => "",
    "\"" => "",
    "<" => "",
    ">" => "",
    ":" => "",
    "," => ""
   ], htmlentities($a)]);
  }
  function Card(array $a) {
   $ack = ["Back", "Front", "FrontButton"];
   for($i = 0; $i < count($ack); $i++) {
    $a[$ack[$i]] = $a[$ack[$i]] ?? "";
   } if(!empty($a["Front"])) {
    return $this->Change([[
     "[Card.Back]" => $a["Back"],
     "[Card.Front]" => $a["Front"],
     "[Card.Front.Button]" => $a["FrontButton"]
    ], $this->Page("50adcf59f82d808c78d94f5aa640b69d")]);
   }
  }
  function Change(array $a) {
   $r = $a[1] ?? "";
   $ls = $a[0] ?? "";
   foreach($ls as $k => $v) {
    if(!is_array($k) && !is_array($v)) {
     $r = str_replace($k, $v, $r);
    }
   }
   return $r;
  }
  function CheckBlocked(array $a) {
   $r = 0;
   if(!empty($a[1]) && !empty($a[2])) {
    $x = $a[0]["Blocked"][$a[1]] ?? [];
    foreach($x as $k => $v) {
     if($v == $a[2]) {
      $r++;
     }
    }
   }
   return $r;
  }
  function CheckBraintreeKeys(array $a) {
   $r = 0;
   foreach($a as $k => $v) {
    if(strpos($k, "Braintree") !== false && !empty($v)) {
     if(!empty(base64_decode($v))) {
      $r++;
     }
    }
   }
   return $r;
  }
  function CheckFileType(array $a) {
   $efs = $this->core["XFS"]["FT"];
   if(isset($a[1]) && in_array($a[1], $efs["_FT"])) {
    if($a[1] == $efs["_FT"][0]) {
     $all = $efs["A"];
    } elseif($a[1] == $efs["_FT"][1]) {
     $all = $efs["D"];
    } elseif($a[1] == $efs["_FT"][2]) {
     $all = $efs["P"];
    } elseif($a[1] == $efs["_FT"][3]) {
     $all = $efs["V"];
    }
   } else {
    $all = array_merge($efs["A"], $efs["D"], $efs["P"], $efs["V"]);
   }
   $r = (in_array($a[0], $all)) ? 1 : 0;
   return $r;
  }
  function CheckPrivacy(array $a) {
   $ck = (!empty($a["Contacts"])) ? 1 : 0;
   $ck2 = (!empty($a["Privacy"])) ? 1 : 0;
   $ck3 = (!empty($a["Y"])) ? 1 : 0;
   $r = 0;
   if($ck == 1 || ($ck2 == 1 && $ck3 == 1)) {
    $pri = $a["Privacy"] ?? md5("Private");
    $pri2 = md5("Public");
    $aci = 0;
    $cfi = 0;
    $fi = 0;
    $fl = [md5("Acquaintances"), md5("Close Contacts"), md5("Contacts")];
    $x = $a["Contacts"] ?? [];
    foreach($x as $k => $v) {
     $ls = $v["List"] ?? md5("Public");
     $fl2 = ($k == $a["Y"] && $ls == $fl[0]) ? 1 : 0;
     $fl3 = ($k == $a["Y"] && $ls == $fl[1]) ? 1 : 0;
     $fl4 = ($k == $a["Y"] && $ls == $fl[2]) ? 1 : 0;
     $aci = ($fl2 == 1) ? $aci++ : $aci;
     $cfi = ($fl2 == 1 || $fl3 == 1) ? $cfi++ : $cfi;
     $fi = ($fl2 == 1 || $fl3 == 1 || $fl4 == 1) ? $fi++ : $fi;
    }
    $f = ($pri == $pri2) ? 1 : 0;
    $f2 = ($pri == $fl[0] && $aci > 0) ? 1 : 0;
    $f3 = ($pri == $fl[1] && $cfi > 0) ? 1 : 0;
    $f4 = ($pri == $fl[2] && $fi > 0) ? 1 : 0;
    $r = ($f == 1 || $f2 == 1 || $f3 == 1 || $f4 == 1) ? 1 : 0;
    $r = ($a["UN"] == $a["Y"] || $r == 1) ? 1 : 0;
   }
   return $r;
  }
  function ConfigureBaseURL($a = NULL) {
   $base = $_SERVER["HTTP_HOST"] ?? "outerhaven.nyc";
   if($a == "efs") {
    $r = "efs.$base/";
   } else {
    $r = $base;
   }
   return $this->ConfigureSecureHTTP().$r;
  }
  function ConfigureSecureHTTP() {
   $r = $_SERVER["HTTPS"] ?? "on";
   $r = (!empty($r) && $r == "on") ? "https" : "http";
   return "$r://";
  }
  function ConvertCalendarMonths(int $a) {
   $r = ($a == "01") ? "January" : $a;
   $r = ($a == "02") ? "February" : $r;
   $r = ($a == "03") ? "March" : $r;
   $r = ($a == "04") ? "April" : $r;
   $r = ($a == "05") ? "May" : $r;
   $r = ($a == "06") ? "June" : $r;
   $r = ($a == "07") ? "July" : $r;
   $r = ($a == "08") ? "August" : $r;
   $r = ($a == "09") ? "September" : $r;
   $r = ($a == 10) ? "October" : $r;
   $r = ($a == 11) ? "November" : $r;
   $r = ($a == 12) ? "December" : $r;
   return $r;
  }
  function Core() {
   # CORE PREFERENCES (save to db and remove list)
   $r = [
     "EFS" => [
      "Password" => base64_encode("SendMeFiles^2020"),
      "Username" => base64_encode("extendedfilesystem")
     ],
     "IMG" => [
      "CODE" => "78569ee93f82cf2cd9415e7c4ca5e65b.png",
      "CP" => "738389d58a41d5f26908a79964532b0f.jpg",
      "CPW" => "7d4928e003e769f78cf40068cfdd2bc9.jpg",
      "DOC" => "0dc477cf7c6d1210b9312c1f579f8a1d.png",
      "FAB" => "8806a28fa51a9cf0ecbec012f1e4fd66.png",
      "LOGO" => "04ca5194af02b1f4e50ed4135fe6c39a.png",
      "LOGO-D" => "11b1bd06816a4cc429d25626731f6458.png",
      "MSG" => "b3a19746167389a973c51f5ffced827b.png",
      "MiNY" => "444936471454a369761338d1896f5091.png",
      "PLUS" => "701d70ba025a96a6af69528d89ac6ef3.png",
      "REGSEL" => "7fd8af13e99bdb762e2c68acd11c0a71.png",
      "VVA" => "b3f36e7638e6961eda52b62016aa1b50.png",
      "VVA-CP" => ""
     ],
     "PTS" => [
      "Default" => 10,
      "Donations" => 100,
      "DeleteFile" => 100,
      "NewContent" => 1,
      "Products" => [
       "ARCH" => 1000,
       "DLC" => 200,
       "DONATE" => 500,
       "PHYS" => 50,
       "SUB" => 400
      ]
     ],
     "SQL" => [
      "Key" => base64_encode("ReSearch^2022@OH.nyc"),
      "Password" => "V2VCZVNlYXJjaGluQE9ILm55Y14yMDIy",
      "Username" => "research"
     ],
     "STAT" => [
      "FS" => "Feedback Submissions",
      "LI" => "Logins",
      "MBR" => "New Members",
      "Visits" => "Visitors",
      "PGu" => "Article Updates",
      "PROD" => "New Products",
      "PRODu" => "Product Updates",
      "SU" => "Status Updates",
      "SUu" => "Edits to Status Updates",
      "UL" => "File Uploads",
      "ULu" => "File Updates"
     ],
     "SUB" => [
      "Artist" => [
       "Description" => "Create and manage a Shop, accept donations, and more, for a 5% commission.",
       "Title" => "Artist",
       "Term" => [1, "month"]
      ],
      "Blogger" => [
       "Description" => "Create and manage blogs.",
       "Title" => "Blogger",
       "Term" => [1, "month"]
      ],
      "Developer" => [
       "Description" => "Contribute to the platform.",
       "Title" => "Developer",
       "Term" => [1, "year"]
      ],
      "VIP" => [
       "Description" => "Enjoy access to all subscriptions, and exclusive content.",
       "Title" => "V.I.P. Access",
       "Term" => [1, "month"]
      ],
      "XFS" => [
       "Description" => "Upload files to your heart's delight.",
       "Title" => "Unlimited File Storage",
       "Term" => [1, "month"]
      ]
     ],
     "SYS" => [
      "Description" => "The Wild-West of the Internet.",
      "FAB" => [md5("FreeAmericaRadio") => [
       "Description" => "Bringing you the latest developments at Outer Haven, as well as a variety of other topics.",
       "ICO" => "",
       "ICO-SRC" => "",
       "Listen" => $this->base,
       "NSFW" => 0,
       "Role" => 0,
       "Title" => "Free America Radio",
       "UN" => $this->ShopID,
       "URL" => $this->base.":8000/listen.pls?sid=1"
      ]],
      "Illegal" => 777,
      "Keywords" => "Outer Haven, social entertainment, artist tools, social media, social discovery, creative community, hidden, deep web, private network, empowering expression, connect to the world, videos, music, share, playlists, mixes, profiles, discovery, discover, join the world with privatized social networking, NSA-free",
      "ProductCategories" => [
       "ARCH" => "Architecture",
       "DLC" => "Downloadable Content",
       "DONATE" => "Donation",
       "PHYS" => "Physical Service",
       "SUB" => "Subscription"
      ],
      "SearchIDs" => [
       "ADM-LLP",
       "BGP",
       "BL",
       "BLG",
       "Bulletins",
       "CA",
       "CART",
       "Contacts",
       "ContactsChatList",
       "ContactsProfileList",
       "ContactsRequests",
       "Contributors",
       "DC",
       "FAB",
       "Forums",
       "Forums-Admin",
       "Forums-Posts",
       "Knowledge",
       "Mainstream",
       "MBR",
       "MBR-ALB",
       "MBR-BLG",
       "MBR-CA",
       "MBR-Forums",
       "MBR-JE",
       "MBR-LLP",
       "MBR-SU",
       "MBR-XFS",
       "MiNY",
       "PR",
       "S-Blogger",
       "SHOP",
       "SHOP-Orders",
       "SHOP-Products",
       "XFS"
      ],
      "Title" => "Outer Haven"
     ],
     "XFS" => [
      "FT" => [
       "_FT" => ["Audio", "Document", "Photo", "Video"],
       "A" => ["aac", "flac", "m4a", "mp3", "wma"],
       "D" => ["3dm", "bimx", "cr", "cr2", "cr3", "doc", "docx", "dwg", "dwf", "pcx", "pdf", "rar", "tar.gz", "ttf", "txt", "usdz", "vbs"],
       "P" => ["bmp", "gif", "jpg", "jpeg", "png", "tiff"],
       "V" => ["avi", "flv", "mov", "mp4", "ogg"]
      ],
      "limits" => [
       "Audio" => 50,
       "Documents" => 250,
       "Images" => 10,
       "Videos" => 200,
       "Total" => 500
      ]
     ],
    "Xmaintanance" => 0,
    "minAge" => 18,
    "minRegAge" => 13
   ];
   $this->Data("Save", ["x", md5("core"), $r]);
   return $r;
  }
  function CoverPhoto(string $a) {
   $efs = $this->efs;
   $r = $this->PlainText([
    "Data" => "[sIMG:CP]",
    "Display" => 1
   ]);
   if(!empty($a)) {
    $r = $efs.base64_decode($a);
   }
   return $r;
  }
  function Credentials($a, $b) {
   $s = (!empty($b)) ? explode(":", $this->Decrypt($b)) : "";
   $sk = $_COOKIE["SK"] ?? "";
   if($a == "UN") {
    $s = (!empty($sk)) ? $s[0] : $this->ID;
   } elseif($a == "PW") {
    $s = (!empty($sk)) ? $s[1] : "P@ssw0rd!";
   }
   return $s;
  }
  function Data(string $action, array $data) {
   if(!empty($data)) {
    $r = "/var/www/html/ext/c.oh.".$data[0];
    $r .= (!empty($data[1])) ? ".".$data[1] : "";
    if($action == "Get") {
     if(!file_exists($r)) {
      $r = json_encode([]);
     } else {
      $r = file_get_contents($r);
      $r = $this->Decrypt($r) ?? json_encode([]);
     }
     return json_decode($r, true);
    } elseif($action == "Purge") {
     if(file_exists($r)) {
      unlink($r);
     }
    } elseif($action == "Save") {
     $data[2] = $data[2] ?? [];
     $r = fopen($r, "w+");
     if(!empty($data[2])) {
      fwrite($r, $this->Encrypt(json_encode($data[2], true)));
      fclose($r);
     }
    }
   }
  }
  function DatabaseSet($a = NULL) {
   $r = array_diff(scandir("/var/www/html/ext/"), [
    ".", "..", "index.php"
   ]);
   foreach($r as $k => $v) {
    if(!empty($a)) {
     if($a == "BLG") {
      $a = "c.oh.blg.";
     } elseif($a == "BlogPosts") {
      $a = "c.oh.bp.";
     } elseif($a == "KB") {
      $a = "c.oh.kb.";
     } elseif($a == "MBR") {
      $a = "c.oh.mbr.";
     } elseif($a == "PF") {
      $a = "c.oh.pf.";
     } elseif($a == "PG") {
      $a = "c.oh.pg.";
     } elseif($a == "SU") {
      $a = "c.oh.su.";
     } if(strpos($v, $a) !== false) { 
      $r[$k] = $v;
     } else {
      unset($r[$k]);
     }
    } else {
     $r[$k] = $v;
    }
   }
   return $r;
  }
  function DecodeBridgeData(array $a) {
   foreach($a as $k => $v)  {
    $a[$k] = urldecode(base64_decode($v));
   }
   return $a;
  }
  function Decrypt($data) {
   return $this->cypher->Decrypt($data);
  }
  function Dialog(array $a) {
   $b = $a["Body"] ?? $this->Element(["p", "Unknown Error"]);
   $h = $a["Header"] ?? "Error";
   $o = $a["Option"] ?? "&nbsp;";
   $o2 = $a["Option2"] ?? $this->Element(["button", "Okay", [
    "class" => "dBC v2 v2w"
   ]]);
   return $this->Change([[
    "[Dialog.Body]" => $b,
    "[Dialog.Header]" => $h,
    "[Dialog.Options.A]" => $o,
    "[Dialog.Options.B]" => $o2
   ], $this->Page("082ee385e1ac8bfd783a038340a85bff")]);
  }
  function Encrypt($data) {
   return $this->cypher->Encrypt($data);
  }
  function Element(array $a) {
   $a[2] = $a[2] ?? [];
   $r = "";
   if(!empty($a[0])) {
    $a["DLL"] = $a["DLL"] ?? 0;
    $d = "";
    if(!empty($a[2])) {
     foreach($a[2] as $k => $v) {
      if(empty($v)) {
       $d .= " $k";
      } else {
       $d .= " $k=\"$v\"";
      }
     }
    }
    $r = "<".$a[0]."$d>".$a[1]."</".$a[0].">";
   } else {
    $r = $this->Element(["p", "An Element type must be defined."]);
   }
   return "$r\r\n";
  }
  function Excerpt($a, $b = 180) {
   $a = substr($a, 0, $b);
   $a = substr($a, 0, strrpos($a, " "));
   $a = (strlen($a) > $b) ? strip_tags($a)."..." : strip_tags($a);
   return htmlentities($a);
  }
  function FixMissing(array $a, array $b) {
   foreach($b as $b) {
    $a[$b] = $a[$b] ?? "";
   }
   return $a;
  }
  function Gender(string $a) {
   if($a == "Female") {
    $r = "she;her;her";
   } else {
    $r = "he;him;his";
   }
   return explode(";", $r);
  }
  function GetCopyrightInformation() {
   $ttl = $this->core["SYS"]["Title"];
   return $this->Element([
    "p", "Copyright &copy; 2017-".date("Y")." <em>$ttl</em>.",
    ["class" => "CenterText"]
   ]).$this->Element([
    "p", "All rights reserved.",
    ["class" => "CenterText"]
   ]).$this->Element([
    "p", "<em>We the People power this Bastion of Freedom.</em>",
    ["class" => "CenterText"]
   ]);
  }
  function GetSourceFromExtension(array $a) {
   $_ALL = $this->core["XFS"]["FT"] ?? [];
   $a[1] = $a[1] ?? "";
   $src = "D.jpg";
   if(!empty($a[1])) {
    if(!is_array($a[1])) {
     $ex = explode(".", $a[1])[1] ?? "";
     $nm = $a[1];
     $src = "$ex/$nm";
    } else {
     $ex = $a[1]["EXT"];
     $nm = $a[1]["Name"];
     $src = "$ex/$nm";
    } if(in_array($ex, $_ALL["A"])) {
     $src = "A.jpg";
    } elseif(in_array($ex, $_ALL["D"])) {
     $src = "D.jpg";
    } elseif(in_array($ex, $_ALL["P"])) {
     $src = $a[0]."/$nm";
    } elseif(in_array($ex, $_ALL["V"])) {
     $src = "V.jpg";
    } else {
     $src = "D.jpg";
    }
   }
   return $this->efs."/$src";
  }
  function JSONResponse(array $a) {
   return json_encode($a, true);
  }
  function Languages() {
   return [
    "en_US" => "English",
    "de_DU" => "German",
    "ja_JP" => "Japanese",
    "es_SP" => "Spanish"
   ];
  }
  function LastMonth() {
   $r = date_create(date("Y-m")." first day of last month");
   return ["LastMonth" => $r->format("Y-m"), "Now" => date("Y-m")];
  }
  function Member(string $username) {
   if($username == $this->ID) {
    $r = $this->NewMember(["Username" => $this->ID]);
   } else {
    $r = $this->Data("Get", ["mbr", md5($username)]) ?? [];
   }
   $r["Activity"]["LastActive"] = $this->timestamp;
   return $r;
  }
  function NewMember(array $a) {
   $a = $this->FixMissing($a, [
    "CoverPhoto",
    "Donations_Patreon",
    "Donations_PayPal",
    "Donations_SubscribeStar",
    "Email",
    "Gender",
    "Password",
    "Patreon",
    "PayPal",
    "PIN",
    "ProfilePicture",
    "Rank",
    "SubscribeStar",
    "Username"
   ]);
   $age = $a["Age"] ?? $this->core["minRegAge"];
   $birthMonth = $a["BirthMonth"] ?? 10;
   $birthYear = $a["BirthYear"] ?? 1995;
   $blogs = $a["Blogs"] ?? [];
   $cart = $a["Cart"] ?? [];
   $displayName = $a["DisplayName"] ?? $this->ID;
   $forums = $a["Forums"] ?? [];
   $email = $a["Email"] ?? "jappleseed@apple.com";
   $firstName = $a["FirstName"] ?? "John";
   $gender = $a["Gender"] ?? "Male";
   $history = $a["History"] ?? [];
   $now = $this->timestamp;
   $lastActive = $a["LastActive"] ?? $now;
   $onlineStatus = $a["OnlineStatus"] ?? 1;
   $pages = $a["Pages"] ?? [];
   $password = $a["Password"] ?? md5("P@ssw0rd!");
   $pin = $a["PIN"] ?? md5(0000000);
   $rank = $a["Rank"] ?? md5("Member");
   $registered = $a["Registered"] ?? $this->timestamp;
   $relationshipStatus = $a["RelationshipStatus"] ?? md5("Single");
   $username = $a["Username"] ?? $this->ID;
   return [
    "Activity" => [
     "LastActive" => $lastActive,
     "OnlineStatus" => $onlineStatus,
     "Registered" => $registered
    ],
    "Blocked" => [
     "Albums" => [],
     "Blogs" => [],
     "Blog Posts" => [],
     "Comments" => [],
     "Files" => [],
     "Forums" => [],
     "Forum Posts" => [],
     "Links" => [],
     "Members" => [],
     "Pages" => [],
     "Products" => [],
     "Shops" => [],
     "Status Updates" => []
    ],
    "Blogs" => $blogs,
    "Donations" => [
     "Patreon" => $a["Patreon"],
     "PayPal" => $a["PayPal"],
     "SubscribeStar" => $a["SubscribeStar"]
    ],
    "Forums" => $forums,
    "Login" => [
     "Password" => $password,
     "PIN" => $pin,
     "Username" => $username
    ],
    "Pages" => $pages,
    "Personal" => [
     "AboutPage" => "<p>About Page Template</p>",
     "Age" => $age,
     "Bio" => "",
     "Birthday" => [
      "Month" => $birthMonth,
      "Year" => $birthYear
     ],
     "CoverPhoto" => $a["CoverPhoto"],
     "DisplayName" => $displayName,
     "Description" => "",
     "Email" => $email,
     "FirstName" => $firstName,
     "Gender" => $gender,
     "ProfilePicture" => $a["ProfilePicture"],
     "RelationshipStatus" => $relationshipStatus
    ],
    "Points" => 1000,
    "Privacy" => [
     "Albums" => md5("Public"),
     "Archive" => md5("Public"),
     "Articles" => md5("Public"),
     "Comments" => md5("Public"),
     "ContactInfo" => md5("Private"),
     "ContactInfoEmails" => md5("Private"),
     "ContactInfoDonate" => md5("Public"),
     "ContactRequests" => md5("Public"),
     "Contacts" => md5("Contacts"),
     "Contributions" => md5("Public"),
     "Forums" => md5("Close Contacts"),
     "ForumsType" => "Private",
     "Gender" => md5("Public"),
     "DLL" => md5("Public"),
     "Journal" => md5("Contacts"),
     "LastActivity" => md5("Close Contacts"),
     "LookMeUp" => 1,
     "OnlineStatus" => md5("Contacts"),
     "MSG" => md5("Close Contacts"),
     "NSFW" => 0,
     "Products" => md5("Public"),
     "Profile" => md5("Public"),
     "Posts" => md5("Acquaintances"),
     "RelationshipStatus" => md5("Contacts"),
     "RelationshipWith" => md5("Contacts"),
     "Registered" => md5("Close Contacts"),
     "Shop" => md5("Public")
    ],
    "Rank" => $rank,
    "Shopping" => [
     "Cart" => $cart,
     "History" => $history
    ],
    "Subscriptions" => [
     "Artist" => [
      "A" => 0,
      "B" => $now,
      "E" => $now
     ],
     "Blogger" => [
      "A" => 1,
      "B" => $now,
      "E" => $this->TimePlus($now, 1, "month")
     ],
     "Developer" => [
      "A" => 0,
      "B" => $now,
      "E" => $now
     ],
     "VIP" => [
      "A" => 0,
      "B" => $now,
      "E" => $this->TimePlus($now, 1, "month")
     ],
     "XFS" => [
      "A" => 0,
      "B" => $now,
      "E" => $now
     ]
    ]
   ];
  }
  function Page(string $a) {
   $x = $this->Data("Get", ["pg", $a]) ?? [];
   if(empty($x)) {
    $r = $this->Change([[
     "[Error.Back]" => "",
     "[Error.Header]" => "Not Found",
     "[Error.Message]" => "The Extension <em>$a</em> could not be found."
    ], $this->Page("f7d85d236cc3718d50c9ccdd067ae713")]);
   } else {
    $r = $this->PlainText([
     "Data" => $x["Body"],
     "Decode" => 1,
     "Display" => 1,
     "HTMLDecode" => 1
    ]);
   }
   return $r;
  }
  function PlainText(array $a) {
   $ck = [
    "BBCodes",
    "Decode",
    "Display",
    "Encode",
    "HTMLDecode",
    "HTMLEncode",
    "Processor"
   ];
   $r = $a["Data"] ?? "";
   for($i = 0; $i < count($ck); $i++) {
    $a[$ck[$i]] = $a[$ck[$i]] ?? 0;
   } if($a["Decode"] == 1) {
    $r = urldecode(urldecode(base64_decode($r)));
   } if($a["HTMLDecode"] == 1) {
    $r = html_entity_decode($r);
   } if($a["Display"] == 1) {
    $pc = base64_encode("Page:Card");
    $r = preg_replace_callback("/\[LLP:(.*?)\]/i", array(&$this, "Extension"), $r);
    $r = preg_replace_callback("/\[Languages:(.*?)\]/i", array(&$this, "LanguagesTranslation"), $r);
    $r = preg_replace_callback("/\[sIMG:(.*?)\]/i", array(&$this, "SystemImage"), $r);
    $r = $this->Change([[
     "[X.contact]" => base64_encode("v=".base64_encode("Company:Feedback")),
     "[X.terms]" => base64_encode("v=$pc&ID=".base64_encode("b490a7c4490eddea6cc886b4d82dbb78")),
     "[X.year]" => date("Y"),
     "[base]" => $this->base,
     "[copyrightInfo]" => $this->GetCopyrightInformation(),
     "[efs]" => $this->efs,
     "[plus]" => "+",
     "[space]" => "&nbsp;",
     "[percent]" => "%"
    ], $r]);
   } if($a["Display"] == 1 && $a["BBCodes"] == 1) {
    $r = $this->RecursiveChange([[
     "/\[b\](.*?)\[\/b\]/is" => "<strong>$1</strong>",
     "/\[d:.(.*?)\](.*?)\[\/d\]/is" => "<div class=\"$1\">$2</div>\r\n",
     "/\[d:#(.*?)\](.*?)\[\/d\]/is" => "<div id=\"$1\">$2</div>\r\n",
     "/\[i\](.*?)\[\/i\]/is" => "<em>$1</em>",
     "/\[u\](.*?)\[\/u\]/is" => "<u>$1</u>",
     "/\[(.*?)\[(.*?)\]:(.*?)\]/is" => "<$1 $2>$3</$1>",
     "/\[IMG:s=(.*?)&w=(.*?)\]/is" => "<img src=\"$1\" style=\"width:$2\"/>",
     "/\[P:(.*?)\]/is" => "<p>$1</p>",
     "/@+([A-Za-z0-9_]+)/" => $this->Element(["button", "@$1", [
      "onclick" => "W('".$this->base."/mbr/$1', '_blank');"
     ]]),
     "/#+([A-Za-z0-9_]+)/" => $this->Element(["button", "#$1", [
      "onclick" => "W('".$this->base."/topics/$1', '_blank');"
     ]])
    ], $r, 0]);
   } if($a["HTMLEncode"] == 1) {
    $r = htmlentities($r);
   } if($a["Encode"] == 1) {
    $r = base64_encode(urlencode(urlencode($r)));
   } if($a["Processor"] == 1) {
    $r = base64_encode(urlencode($r));
   }
   return $r;
  }
  function ProductCategory($a) {
   $i = 0;
   foreach($this->core["SYS"]["ProductCategories"] as $k => $v) {
    if($i == 0 && $a == $k) {
     $r = $v;
    }
   }
   return $r;
  }
  function ProfilePicture(array $a, $b = NULL) {
   $b = (!empty($b)) ? " style=\"$b\"" : "";
   $base = $this->efs;
   $pp = $a["Personal"] ?? [];
   $pp = $a["Personal"]["ProfilePicture"] ?? "";
   $r = "[sIMG:LOGO]";
   if(!empty($pp) && @fopen($base.base64_decode($pp), "r")) {
    $r = $base.base64_decode($pp);
   }
   return $this->PlainText([
    "Data" => "<img class=\"c2\" src=\"$r\"$b/>", "Display" => 1
   ]);
  }
  function RecursiveChange(array $a) {
   $_HTML = $a[2] ?? 0;
   $r = $a[1];
   foreach($a[0] as $k => $v) {
    $v = ($_HTML == 0) ? $v : htmlentities($v);
    $r = preg_replace($k, $v, $r);
   }
   return $r;
  }
  function Revenue(array $a) {
   if(!empty($a[0]) && is_array($a[1])) {
    $id = $a[1]["ID"] ?? md5($this->timestamp.rand(0, 9999));
    $revenue = $this->Data("Get", ["id", md5($a[0])]) ?? [];
    $month = date("m");
    $year = date("Y");
    $newRevenue = [];
    $newRevenue["UN"] = $a[0];
    $newRevenue[$year] = $revenue[$year] ?? [];
    $newRevenue[$year][$month] = $revenue[$year][$month] ?? [];
    $newRevenue[$year][$month]["Partners"] = $a[1]["Partners"] ?? [];
    $newRevenue[$year][$month]["Sales"][$day] = $revenue[$year][$month]["Sales"][$day] ?? [];
    if(!empty($a[1]["Cost"]) && !empty($a[1]["Profit"])) {
     array_push($newRevenue[$year][$month]["Sales"][$day], [$id => [
      "Cost" => $a[1]["Cost"],
      "Profit" => $a[1]["Profit"],
      "Quantity" => $a[1]["Quantity"],
      "Title" => $a[1]["Title"]
     ]]);
    }
    $revenue = $newRevenue;
    #$this->Data("Save", ["id", md5($a[0]), $revenue]);
   }
  }
  function SendBulletin(array $a) {
   $data = $a["Data"] ?? "";
   $to = $a["To"] ?? "";
   $type = $a["Type"] ?? "";
   if(!empty($data) && !empty($to) && !empty($type)) {
    $y = $this->you;
    $bulletins = $this->Data("Get", ["bulletins", md5($to)]) ?? [];
    $bulletins[md5($y["Login"]["Username"].$this->timestamp)] = [
     "Data" => $data,
     "From" => $y["Login"]["Username"],
     "Read" => 0,
     "Seen" => 0,
     "Sent" => $this->timestamp,
     "Type" => $type
    ];
    $this->Data("Save", ["bulletins", md5($to), $bulletins]);
   }
  }
  function Select($a, $b = NULL, $c = NULL) {
   $cl = $b ?? "v2 v2w";
   $i = 0;
   $r = "";
   $y = $this->Member($this->Username());
   $_HC = ($y["Rank"] == md5("High Command")) ? 1 : 0;
   if(strpos($a, "Privacy") !== false) {
    $hli = ["Acquaintances", "Close Contacts", "Contacts", "Private", "Public"];
    $opt = ["Acquaintances", "Close Contacts", "Contacts", "Private", "Public"];
    foreach($opt as $opt) {
     $s = ($c == md5($opt)) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"".md5($opt)."\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Privacy"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "BirthMonth") {
    for($i = 1; $i < 12; $i++) {
     $s = ((empty($c) && $i == date("m")) || $i == $c) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$i\"$s>$i</option>\r\n";
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Month"]
     ]), ["class" => "v2", "name" => $a]
    ]);
   } elseif($a == "BirthYear") {
    for($i = 1776; $i < date("Y"); $i++) {
     $s = ((empty($c) && $i == date("Y")) || $i == $c) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$i\"$s>$i</option>\r\n";
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Year"]
     ]), ["class" => "v2", "name" => $a]
    ]);
   } elseif($a == "CE") {
    $i = 1000;
    $r = $this->Element([
     "p", "Credit Exchange requires a minimum of 1,000 points to be converted."
    ]);
    if($y["Points"] >= $i) {
     $r = $this->Change([[
      "[CreditExchange]" => $a,
      "[CreditExchange.Data]" => base64_encode("v=".base64_encode("Shop:SaveCreditExchange")."&ID=$c&P="),
      "[CreditExchange.ID]" => md5($this->timestamp.rand(0, 9999)),
      "[CreditExchange.Points]" => $y["Points"],
      "[CreditExchange.Points.Minimum]" => $i
     ], $this->Page("b9c61e4806cf07c0068f1721678bef1e")]);
    }
   } elseif($a == "ContactList") {
    $hli = ["Acquaintances", "Close Contacts", "Contacts"];
    $opt = ["Acquaintances", "Close Contacts", "Contacts"];
    foreach($opt as $opt) {
     $o = md5($opt);
     $s = ($c == $o) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$o\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Choose a Contact List..."]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "DiscountCodeQTY") {
    $c = $c ?? 100;
    for($i = 1; $i < 100; $i++) {
     $s = ($c == $i) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$i\"$s>$i</option>\r\n";
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Quantity"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "ListArticles") {
    foreach($y["Pages"] as $key => $value) {
     $page = $this->Data("Get", ["pg", $value]) ?? [];
     $opt = $page["ID"];
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$page["Title"]."</option>\r\n";
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Choose an Article..."]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "ListBlogs") {
    foreach($y["Blogs"] as $key => $value) {
     $blog = $this->Data("Get", ["blg", $value]) ?? [];
     $opt = $blog["ID"];
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$blog["Title"]."</option>\r\n";
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Choose a Blog..."]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "ListForums") {
    foreach($y["Forums"] as $key => $value) {
     $forum = $this->Data("Get", ["pf", $value]) ?? [];
     $opt = $forum["ID"];
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$forum["Title"]."</option>\r\n";
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Choose a Forum..."]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "Live") {
    $hli = ["Sandbox", "Production"];
    $opt = [0, 1];
    foreach($opt as $opt) {
     $ck = ($opt == 0 || $opt == $c) ? 1 : 0;
     $s = ($ck == 1) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Payment Environment"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "Index") {
    $hli = ["No", "Yes"];
    $opt = [0, 1];
    foreach($opt as $opt) {
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Allow to be Indexed?"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "Open") {
    $hli = ["Closed", "Open"];
    $opt = [0, 1];
    foreach($opt as $opt) {
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Open or Closed?"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "Percentile") {
    $c = $c ?? 100;
    for($i = 1; $i < 100; $i++) {
     $s = ($c == $i) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$i\"$s>$i%</option>\r\n";
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Percent Off"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "PFType") {
    $hli = ["Private", "Public"];
    $opt = ["Private", "Public"];
    foreach($opt as $opt) {
     $o = md5($opt);
     $s = ($c == $o) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$o\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Choose a Forum Type..."]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "Priority") {
    $hli = ["High", "Normal", "Low"];
    $opt = [1, 2, 3];
    foreach($opt as $opt) {
     $s = ($opt == 2) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"".md5($opt)."\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Priority"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "Rank") {
    if($_HC == 1) {
     $hli = ["High Command", "Member", "Support"];
     $opt = ["High Command", "Member", "Support"];
    } else {
     $hli = ["Member", "Support"];
     $opt = ["Member", "Support"];
    }
    foreach($opt as $opt) {
     $r .= "<option value=\"$opt\">".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Select a Rank"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "Role") {
    $hli = ["Administrator", "Contributor"];
    $opt = [0, 1];
    foreach($opt as $opt) {
     $ck = ($opt == 1 || $opt == $c) ? 1 : 0;
     $s = ($ck == 1) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Who is allowed to make changes?"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "SOE") {
    $hli = [
     "I do not want to recieve occasional E-Mails or Promotional offers.",
     "I want occasional E-Mails and Promotional offers!"
    ];
    $opt = [0, 1];
    foreach($opt as $opt) {
     $s = ($opt == 1) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Send Occasional E-Mails?"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "TPL-BLG") {
    $hli = [];
    $opt = [];
    $tpl = $this->DatabaseSet("PG");
    foreach($tpl as $k => $v) {
     $v = str_replace("c.oh.pg.", "", $v);
     $t = $this->Data("Get", ["pg", $v]) ?? [];
     if($t["Category"] == "TPL-BLG") {
      array_push($hli, $t["Title"]);
      array_push($opt, str_replace("c.oh.pg.", "", $v));
     }
    }
    foreach($opt as $opt) {
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Choose a Template..."]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "TPL-CA") {
    $hli = [];
    $opt = [];
    $tpl = $this->DatabaseSet("PG");
    foreach($tpl as $k => $v) {
     $v = str_replace("c.oh.pg.", "", $v);
     $t = $this->Data("Get", ["pg", $v]) ?? [];
     if($t["Category"] == "TPL-CA") {
      array_push($hli, $t["Title"]);
      array_push($opt, str_replace("c.oh.pg.", "", $v));
     }
    }
    foreach($opt as $opt) {
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Choose an Article Template..."]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "gender") {
    $hli = ["Male", "Female"];
    $opt = ["Male", "Female"];
    $r = "";
    foreach($opt as $opt) {
     $s = ($c == $opt) ? " checked" : "";
     $r .= $this->Element([
      "div", $this->Element([
       "div",
       "<input class=\"req\" name=\"gender\" type=\"radio\" value=\"$opt\"$s/>",
       ["class" => "Desktop25"]
      ]).$this->Element([
       "div", "<p>".$hli[$i]."</p>", ["class" => "d75s"]
      ]), ["class" => "Desktop50"]
     ]);
     $i++;
    }
   } elseif($a == "nsfw") {
    $hli = ["Adults Only", "Kid-Friendly"];
    $opt = [1, 0];
    foreach($opt as $opt) {
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Content Status"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "oStatus") {
    $hli = ["Online", "Offline"];
    $opt = [1, 0];
    foreach($opt as $opt) {
     $s = ($opt == $y["Activity"]["OnlineStatus"]) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Online Status"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "PageCategory") {
    if($_HC == 1) {
     $hli = [
      "Article",
      "Extension",
      "Journal Entry",
      "Press Release",
      "Blog Template",
      "Community Archive Template"
     ];
     $opt = [
      "CA",
      "EXT",
      "JE",
      "PR",
      "TPL-BLG",
      "TPL-CA"
     ];
    } else {
     $hli = [
      "Article",
      "Journal Entry",
      "Blog Template",
      "Community Archive Template"
     ];
     $opt = [
      "CA",
      "JE",
      "TPL-BLG",
      "TPL-CA"
     ];
    }
    foreach($opt as $opt) {
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"".base64_encode($opt)."\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Choose a Category..."]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "ProductCategory") {
    if($_HC == 1) {
     $hli = ["Architecture", "Download", "Donation", "Physical", "Subscription"];
     $opt = ["ARCH", "DLC", "DONATE", "PHYS", "SUB"];
    } else {
     $hli = ["Download", "Donation", "Physical"];
     $opt = ["DLC", "DONATE", "PHYS"];
    }
    foreach($opt as $opt) {
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"".base64_encode($opt)."\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Choose a Product Category..."]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "ProductCost") {
    $i = "RI".md5($this->timestamp.rand(0, 9999));
    $m = 1;
    $v = $c ?? 0;
    $r = "<input class=\"$i\" max=\"1999\" min=\"$m\" name=\"$a\" type=\"range\" value=\"$v\"/>\r\n";
    $r .= $this->Element([
     "p", $m, ["class" => "GetRangeValue"]
    ]).$this->Element([
     "script", "getRangeValue(\".$i\");"
    ]);
   } elseif($a == "ProductExpiresQuantity") {
    for($i = 1; $i <= 100; $i++) {
     $s = ($c == $i) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$i\"$s>$i</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Choose a Quantity..."]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "ProductExpiresTimeSpan") {
    $hli = ["Month", "Year"];
    $opt = ["month", "year"];
    foreach($opt as $opt) {
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Choose a Time Span..."]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "ProductInstructions") {
    $hli = ["No", "Yes"];
    $opt = [0, 1];
    foreach($opt as $opt) {
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\">".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Allow Instructions?"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "ProductProfit") {
    $i = "RI".md5($this->timestamp.rand(0, 9999));
    $m = 0;
    $v = $c ?? 5;
    $r = "<input class=\"$i\" max=\"1999\" min=\"$m\" name=\"$a\" type=\"range\" value=\"$v\"/>\r\n";
    $r .= $this->Element([
     "p", $m, ["class" => "GetRangeValue"]
    ]).$this->Element([
     "script", "getRangeValue(\".$i\");"
    ]);
   } elseif($a == "ProductSubscriptionTerm") {
    $hli = ["Month", "Year"];
    $opt = ["month", "year"];
    foreach($opt as $opt) {
     $s = ($c == $opt) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"$opt\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Product Permissions"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "rAlb") {
    $fs = $this->Data("Get", [
     "fs",
     md5($y["Login"]["Username"])
    ]) ?? [];
    foreach($fs["Albums"] as $k => $v) {
     $r .= "<option value=\"$k\">".$v["Title"]."</option>\r\n";
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Album"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   } elseif($a == "rStatus") {
    $hli = ["Single", "In a Relationship", "Engaged", "Married", "Divorced", "Widowed"];
    $opt = ["Single", "In a Relationship", "Engaged", "Married", "Divorced", "Widowed"];
    foreach($opt as &$opt) {
     $s = ($c == md5($opt)) ? " selected=\"selected\"" : "";
     $r .= "<option value=\"".md5($opt)."\"$s>".$hli[$i]."</option>\r\n";
     $i++;
    }
    $r = $this->Element([
     "select", $this->Element([
      "optgroup", $r, ["label" => "Relationship Status"]
     ]), ["class" => $cl, "name" => $a]
    ]);
   }
   return $r;
  }
  function Setup(string $a) {
   $documentRoot = "/var/www/html";
   if(!empty($a)) {
    if($a == "FAR") {
     $a = "$documentRoot/far/FAR.conf";
     $template = "";
    } elseif($a == "SYS") {
     $a = "$documentRoot/.htaccess";
     $template = "97291f4b155f663aa79cc8b624323c5b";
    }
    $d = fopen($a, "w+");
    fwrite($d, $this->Page($template));
    fclose($d);
    chmod($a, 0755);
   }
  }
  function ShortNumber($a) {
   $r = str_replace(",", "", $a);
   if($r > 1000000000000) {
    $r = round(($r / 1000000000000), 1)."T";
   } elseif($r > 1500000000000) {
    $r = round(($r / 1500000000000), 1.5)."T";
   } elseif($r > 1000000000) {
    $r = round(($r / 1000000000), 1)."B";
   } elseif($r > 1500000000) {
    $r = round(($r / 1500000000), 1.5)."B";
   } elseif($r > 1000000) {
    $r = round(($r / 1000000), 1)."M";
   } elseif($r > 1500000) {
    $r = round(($r / 1500000), 1.5)."M";
   } elseif($r > 1000) {
    $r = round(($r / 1000), 1)."K";
   } elseif($r > 1500) {
    $r = round(($r / 1500), 1.5)."K";
   } else {
    $r = number_format($r);
   }
   return $r;
  }
  function ShuffleList($list) { 
   if(!is_array($list)) return $list; 
   $keys = array_keys($list); 
   shuffle($keys); 
   $random = array(); 
   foreach($keys as $key) { 
    $random[$key] = $list[$key]; 
   }
   return $random; 
  }
  function SQL(string $query, array $values) {
   try {
    $core = $this->core["SQL"] ?? [];
    $sql = "mysql:host=localhost;dbname=ReSearch";
    $sql = new PDO($sql, $core["Username"], base64_decode($core["Password"]), [
     PDO::ATTR_PERSISTENT => true,
     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $r = $sql->prepare($query);
    if(!empty($values)) {
     foreach($values as $key => $value) {
      switch(true) {
       case is_int($value):
        $type = PDO::PARAM_INT;
        break;
       case is_bool($value):
        $type = PDO::PARAM_BOOL;
        break;
       case is_null($value):
        $type = PDO::PARAM_NULL;
        break;
       default:
        $type = PDO::PARAM_STR;
      }
      $r->bindValue($key, $value, $type);
     }
    }
    $r->execute();
   } catch(PDOException $error) {
    $r = $error->getMessage();
   }
   return $r;
  }
  function Statistic($a) {
   $m = date("m");
   $x = $this->Data("Get", ["x", "stats"]) ?? [];
   $y = date("Y");
   $x[$y] = $x[$y] ?? [];
   $x[$y][$m] = $x[$y][$m] ?? [];
   $x[$y][$m][$a] = $x[$y][$m][$a] ?? 0;
   $x[$y][$m][$a]++;
   $this->Data("Save", ["x", "stats", $x]);
  }
  function TimeAgo($t) {
   $p = [
    "second", "minute", "hour", "day", "week", "month", "year", "decade", "century", "millennium"
   ];
   $l = [
    60,
    60,
    24,
    7,
    4.35,
    12,
    10,
    100,
    1000
   ];
   $n = time();
   $ud = strtotime($t);
   if(empty($ud)) {
    return "<strong>Invalid!</strong>";
   } if($n > $ud) {
    $d = $n - $ud;
    $t = "ago";
   } else {
    $d = $ud - $n;
    $t = "just now";
   } for($j = 0; $d >= $l[$j] && $j < count($l) - 1; $j++) {
    $d /= $l[$j];
   }
   $d = round($d);
   if($d != 1) {
    $p[$j] .= "s";
    return "$d $p[$j] {$t}";
   } else {
    return "Just now";
   }
  }
  function TimePlus($a, $b, $c) {
   return strtotime("+$b $c", strtotime($a));
  }
  function Username() {
   $sk = $_COOKIE["SK"] ?? "";
   $r = (!empty($sk)) ? $this->Credentials("UN", $sk) : $this->ID;
   return $r;
  }
  function WYSIWYG($a) {
   $r = "";
   $ta = "";
   foreach($a["opt"] as $k => $v) {
    $ta .= " $k=\"$v\"";
   }
   $tan = "textarea#".$a["opt"]["id"];
   // BEGIN TPL (WYSIWYG)
   $r .= $this->Element(["button", "Basic Text Formatting", [
    "class" => "PGS NNW", "data-type" => ".B"
   ]]).$this->Element([
    "div", $this->Element(["button", $this->Element(["strong", "B"]), [
     "class" => "iTXT v2",
     "data-type" => base64_encode("B;$tan"),
     "data-value" => "[B:Bold Text]",
     "id" => "XbuildUI"
    ]]).$this->Element(["button", "<i>I</i>", [
     "class" => "iTXT v2",
     "data-type" => base64_encode("I;$tan"),
     "data-value" => "[B:Italic Text]",
     "id" => "XbuildUI"
    ]]).$this->Element(["button", "<u>U</u>", [
     "class" => "iTXT v2",
     "data-type" => base64_encode("U;$tan"),
     "data-value" => "[B:Underlined Text]",
     "id" => "XbuildUI"
    ]]).$this->Element(["button", "Break", [
     "class" => "iTXT v2", "data-type" => base64_encode("BR;$tan")
    ]]).$this->Element(["button", "Div", [
     "class" => "iTXT v2", "data-type" => base64_encode("DIV;$tan")
    ]]), ["class" => "B PGSCC h"]
   ]).$this->Element(["button", "Attachments", [
    "class" => "PGS NNW", "data-type" => ".E"
   ]]).$this->Element([
    "div", $this->Element(["button", "File", [
     "class" => "iTXT v2", "data-type" => base64_encode("ATT;$tan")
    ]]).$this->Element(["button", "Link", [
     "class" => "iTXT v2", "data-type" => base64_encode("URL;$tan")
    ]]).$this->Element(["button", "Page", [
     "class" => "iTXT v2", "data-type" => base64_encode("LLP;$tan")
    ]]), ["class" => "E PGSCC h"]
   ]);
   $r .= "<textarea$ta>".base64_encode($a["Body"])."</textarea>\r\n";
   return $this->Element(["div", $r, ["class" => "PGSC WYSIWYG"]]);
   // END TPL (WYSIWYG)
  }
  public static function Extension($a = NULL) {
   $x = New System;
   if(!empty($a)) {
    $r = $x->Page($a[1]);
    $x->__destruct();
    return $r;
   }
  }
  public static function LanguagesTranslation($a = NULL) {
   $x = New System;
   if(!empty($a[1])) {
    $l = explode("-", $a[1]);
    $lt = $x->Data("Get", ["local", $l[0]]) ?? [];
    $r = $lt[$l[1]]["en_US"] ?? "";
    $r = $lt[$l[1]][$x->region] ?? $r;
    return (!empty($r)) ? $x-PlainText([
     "BBCodes" => 1,
     "Data" => $r,
     "Decode" => 1,
     "Display" => 1,
     "HTMLDecode" => 1
    ]) : $x->Element(["p", "No Translations Found"]);
   }
  }
  public static function SystemImage($a = NULL) {
   $x = New System;
   if(!empty($a)) {
    $r = $x->efs."ohc/".$x->core["IMG"][$a[1]];
    $x->__destruct();
    return $r;
   }
  }
  function __destruct() {
   // DESTROYS THIS CLASS
  }
 }
?>