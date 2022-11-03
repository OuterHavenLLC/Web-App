<?php
 Class Authentication extends GW {
  function __construct() {
   parent::__construct();
   $this->you = $this->system->Member($this->system->Username());
  }
  function ArticleChangeMemberRole(array $a) {
   $data = $a["Data"] ?? [];
   $id = $data["ID"] ?? "";
   $mbr = $data["Member"] ?? "";
   $r = $this->system->Dialog([
    "Body" => $this->system->Element([
     "p", "The Article Identifier is missing."
    ]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($y["Login"]["Username"] == $this->system->ID) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($id)) {
    $id = base64_decode($id);
    $Page = $this->system->Data("Get", ["pg", $id]) ?? [];
    $r = $this->system->Change([[
     "[Roles.ID]" => $Page["ID"],
     "[Roles.Member]" => base64_decode($mbr),
     "[Roles.Title]" => $Page["Title"],
     "[Roles.Processor]" => base64_encode("v=".base64_encode("Page:ChangeMemberRole")),
     "[Roles.Role]" => $this->system->Select("Role", "req v2 v2w"),
     "[X.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0")
    ], $this->system->Page("270d16c83b59b067231b0c6124a4038d")]);
   }
   return $r;
  }
  function AuthorizeChange(array $a) {
   $data = $a["Data"] ?? [];
   $form = $data["Form"] ?? "";
   $id = $data["ID"] ?? "";
   $processor = $data["Processor"] ?? "";
   $text = $data["Text"] ?? base64_encode("Do you authorize this Change?");
   $r = $this->system->Dialog([
    "Body" => $this->system->Element([
     "p", "The Form, Identifier or Processor are missing."
    ]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($form) && !empty($id) && !empty($processor)) {
    $r = $this->system->Change([[
     "[Authorize.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "[Authorize.Form]" => base64_decode($form),
     "[Authorize.ID]" => $id,
     "[Authorize.Text]" => base64_decode($text),
     "[Authorize.Processor]" => $processor
    ], $this->system->Page("7f6ec4e23b8b7c616bb7d79b2d1d3157")]);
   }
   return $r;
  }
  function BlogChangeMemberRole(array $a) {
   $data = $a["Data"] ?? [];
   $id = $data["ID"] ?? "";
   $mbr = $data["Member"] ?? "";
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "The Forum Identifier is missing."]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($id)) {
    $id = base64_decode($id);
    $blog = $this->system->Data("Get", ["blg", $id]) ?? [];
    $r = $this->system->Change([[
     "[Roles.ID]" => $id,
     "[Roles.Member]" => base64_decode($mbr),
     "[Roles.Title]" => $blog["Title"],
     "[Roles.Processor]" => base64_encode("v=".base64_encode("Blog:ChangeMemberRole")),
     "[Roles.Role]" => $this->system->Select("Role", "req v2 v2w"),
     "[X.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0")
    ], $this->system->Page("270d16c83b59b067231b0c6124a4038d")]);
   }
   return $r;
  }
  function DeleteAlbum(array $a) {
   $data = $a["Data"] ?? [];
   $aid = $data["AID"] ?? md5("unsorted");
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "The Album Identifier is missing."]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($aid)) {
    $alb = $this->system->Data("Get", [
     "fs",
     md5($y["Login"]["Username"])
    ]) ?? [];
    $alb = $alb["Albums"][$aid] ?? [];
    $r = $this->system->Change([[
     "[Delete.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "[Delete.ID]" => $alb["ID"],
     "[Delete.Processor]" => base64_encode("v=".base64_encode("Album:SaveDelete")),
     "[Delete.Title]" => $alb["Title"]
    ], $this->system->Page("fca4a243a55cc333f5fa35c8e32dd2a0")]);
   }
   return $r;
  }
  function DeleteBlog(array $a) {
   $delete = base64_encode("Blog:SaveDelete");
   $data = $a["Data"] ?? [];
   $id = $data["ID"] ?? "";
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "The Blog Identifier is missing."]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($id)) {
    $id = base64_decode($id);
    $blog = $this->system->Data("Get", ["blg", $id]) ?? [];
    $r = $this->system->Change([[
     "[Delete.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "[Delete.ID]" => $blog["ID"],
     "[Delete.Processor]" => base64_encode("v=".base64_encode("Blog:SaveDelete")),
     "[Delete.Title]" => $blog["Title"]
    ], $this->system->Page("fca4a243a55cc333f5fa35c8e32dd2a0")]);
   }
   return $r;
  }
  function DeleteBlogPost(array $a) {
   $data = $a["Data"] ?? [];
   $id = $data["ID"] ?? "";
   $r = $this->system->Dialog([
    "Body" => $this->system->Element([
     "p", "The Blog-Post Identifier is missing."
    ]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($id)) {
    $post = explode("-", base64_decode($id));
    $post = $this->system->Data("Get", ["bp", $post[1]]) ?? [];
    $r = $this->system->Change([[
     "[Delete.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "[Delete.ID]" => base64_decode($id),
     "[Delete.Processor]" => base64_encode("v=".base64_encode("BlogPost:SaveDelete")),
     "[Delete.Title]" => $post["Title"]
    ], $this->system->Page("fca4a243a55cc333f5fa35c8e32dd2a0")]);
   }
   return $r;
  }
  function DeleteDiscountCode(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["ID"]);
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "The Code Identifier is missing."]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($data["ID"])) {
    $r = $this->system->Change([[
     "[Delete.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "[Delete.ID]" => $data["ID"],
     "[Delete.Processor]" => base64_encode("v=".base64_encode("DiscountCode:SaveDelete")),
     "[Delete.Title]" => "this Discount Code"
    ], $this->system->Page("fca4a243a55cc333f5fa35c8e32dd2a0")]);
   }
   return $r;
  }
  function DeleteFAB(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["ID", "UN"]);
   $r = $this->system->Dialog([
    "Body" => $this->system->Element([
     "p", "The Broadcaster Identifier is missing."
    ]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($data["ID"])) {
    $id = base64_decode($data["ID"]);
    $fab = $this->system->Data("Get", [
     "x",
     md5("FreeAmericaBroadcasting")
    ]) ?? [];
    $fab = $fab[$id]["Title"] ?? "Broadcaster";
    $r = $this->system->Change([[
     "[Delete.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "[Delete.ID]" => $data["ID"],
     "[Delete.Processor]" => base64_encode("v=".base64_encode("FAB:SaveDelete")),
     "[Delete.Title]" => $fab
    ], $this->system->Page("fca4a243a55cc333f5fa35c8e32dd2a0")]);
   }
   return $r;
  }
  function DeleteFile(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["AID", "ID", "UN"]);
   $aid = $data["AID"] ?? md5("unsorted");
   $id = $data["ID"];
   $un = $data["UN"];
   $y = $this->you;
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "The File Identifier is missing."]),
    "Header" => "Error"
   ]);
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif((!empty($id) && !empty($un))) {
    $un = base64_decode($un);
    $efs = $this->system->Data("Get", [
     "fs",
     md5($y["Login"]["Username"])
    ]) ?? [];
    $efs = ($un == $this->system->ID) ? $this->system->Data("Get", [
     "x",
     "fs"
    ]) : $efs;
    $dlc = $efs[$id];
    $r = $this->system->Change([[
     "[Delete.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "[Delete.ID]" => base64_encode("$un-$aid-$id"),
     "[Delete.Processor]" => base64_encode("v=".base64_encode("File:SaveDelete")),
     "[Delete.Title]" => $dlc["Title"]
    ], $this->system->Page("fca4a243a55cc333f5fa35c8e32dd2a0")]);
   }
   return $r;
  }
  function DeleteForum(array $a) {
   $data = $a["Data"] ?? [];
   $id = $data["ID"] ?? "";
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "The Forum Identifier is missing."]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif($id == "cb3e432f76b38eaa66c7269d658bd7ea") {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You cannot delete this forum."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($id)) {
    $id = base64_decode($id);
    $forum = $this->system->Data("Get", ["pf", $id]) ?? [];
    $title = $forum["Title"] ?? "all forums";
    $r = $this->system->Change([[
     "[Delete.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "[Delete.ID]" => $id,
     "[Delete.Processor]" => base64_encode("v=".base64_encode("Forum:SaveDelete")),
     "[Delete.Title]" => $title
    ], $this->system->Page("fca4a243a55cc333f5fa35c8e32dd2a0")]);
   }
   return $r;
  }
  function DeleteForumPost(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["FID", "ID"]);
   $all = $data["all"] ?? 0;
   $fid = $data["FID"];
   $id = $data["ID"];
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "The Forum Identifier is missing."]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif((!empty($fid) && !empty($id))) {
    $r = $this->system->Change([[
     "[Delete.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "[Delete.ID]" => base64_encode("$fid-$id"),
     "[Delete.Processor]" => base64_encode("v=".base64_encode("ForumPost:SaveDelete")),
     "[Delete.Title]" => $post["Title"]
    ], $this->system->Page("fca4a243a55cc333f5fa35c8e32dd2a0")]);
   }
   return $r;
  }
  function DeletePage(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["ID"]);
   $r = $this->system->Dialog([
    "Body" => $this->system->Element([
     "p", "The Article Identifier is missing."
    ]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($data["ID"])) {
    $pg = $this->system->Data("Get", ["pg", $data["ID"]]) ?? [];
    $r = $this->system->Change([[
     "[Delete.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "[Delete.ID]" => $pg["ID"],
     "[Delete.Title]" => $pg["Title"],
     "[Delete.Processor]" => base64_encode("v=".base64_encode("Page:SaveDelete"))
    ], $this->system->Page("fca4a243a55cc333f5fa35c8e32dd2a0")]);
   }
   return $r;
  }
  function DeleteProduct(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["ID"]);
   $all = $data["all"] ?? 0;
   $pd = base64_encode("Product:SaveDelete");
   $y = $this->you;
   $r = $this->system->Dialog([
    "Body" => $this->system->Element([
     "p", "The Product Identifier is missing."
    ]),
    "Header" => "Error"
   ]);
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($data["ID"])) {
    $product = $this->system->Data("Get", ["miny", $data["ID"]]) ?? [];
    $r = $this->system->Change([[
     "[Delete.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "[Delete.ID]" => $product["ID"],
     "[Delete.Processor]" => base64_encode("v=".base64_encode("Product:SaveDelete")),
     "[Delete.Title]" => $product["Title"]
    ], $this->system->Page("fca4a243a55cc333f5fa35c8e32dd2a0")]);
   }
   return $r;
  }
  function DeleteStatusUpdate(array $a) {
   $data = $a["Data"] ?? [];
   $id = $data["ID"] ?? "";
   $r = $this->system->Dialog([
    "Body" => $this->system->Element([
     "p", "The Update Identifier is missing."
    ]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($id)) {
    $id = base64_decode($id);
    $r = $this->system->Change([[
     "[Delete.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "[Delete.ID]" => $id,
     "[Delete.Processor]" => base64_encode("v=".base64_encode("StatusUpdate:SaveDelete")),
     "[Delete.Title]" => "this post"
    ], $this->system->Page("fca4a243a55cc333f5fa35c8e32dd2a0")]);
   }
   return $r;
  }
  function NewPassword(array $a) {
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "Unknown error."]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } else {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "You must enter your current PIN to update your Password."
     ]).$this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "Header" => "Update Password",
     "Option" => $this->system->Element([
      "button", "Cancel", ["class" => "dBC v2 v2w"]
     ]),
     "Option2" => $this->system->Element([
      "button", "Update Password", [
       "class" => "SendData v2 v2w",
       "data-form" => ".NewPassword",
       "data-processor" => base64_encode("v=".base64_encode("Profile:SavePassword"))
      ]
     ])
    ]);
   }
   return $r;
  }
  function NewPIN(array $a) {
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "Unknown error."]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($y["Login"]["Username"] == $this->system->ID) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } else {
    $save = base64_encode("Profile:SavePIN");
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "Are you sure you want to update your PIN?"
     ]),
     "Header" => "Update PIN",
     "Option" => $this->system->Element([
      "button", "Cancel", ["class" => "dBC v2 v2w"]
     ]),
     "Option2" => $this->system->Element([
      "button", "Update PIN", [
       "class" => "SendData dBC v2 v2w",
       "data-form" => ".NewPIN",
       "data-processor" => base64_encode("v=$save")
      ]
     ])
    ]);
   }
   return $r;
  }
  function PFChangeMemberRole(array $a) {
   $data = $a["Data"] ?? [];
   $id = $data["ID"] ?? "";
   $mbr = $data["Member"] ?? "";
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "The Forum Identifier is missing."]),
    "Header" => "Error"
   ]);
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($id)) {
    $id = base64_decode($id);
    $forum = $this->system->Data("Get", ["pf", $id]) ?? [];
    $r = $this->system->Change([[
     "[Roles.ID]" => $id,
     "[Roles.Member]" => base64_decode($mbr),
     "[Roles.Title]" => $forum["Title"],
     "[Roles.Processor]" => base64_encode("v=".base64_encode("Forum:ChangeMemberRole")),
     "[Roles.Role]" => $this->system->Select("Role", "req v2 v2w"),
     "[X.Auth]" => $this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0")
    ], $this->system->Page("270d16c83b59b067231b0c6124a4038d")]);
   }
   return $r;
  }
  function Preferences(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["UN"]);
   $r = $this->system->Dialog([
    "Body" => $this->system->Element(["p", "The Username is missing!"]),
    "Header" => "Error"
   ]);
   $sp = base64_encode("Profile:SavePreferences");
   $y = $this->you;
   if($this->system->ID == $y["Login"]["Username"]) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element(["p", "You must sign in to continue."]),
     "Header" => "Forbidden"
    ]);
   } elseif(!empty($data["UN"])) {
    $r = $this->system->Dialog([
     "Body" => $this->system->Element([
      "p", "You must enter your current PIN to save your Preferences."
     ]).$this->system->Page("92c7c84d33f9c3d8ccd6cc04dc228bf0"),
     "Header" => "Update Preferences",
     "Option" => $this->system->Element([
      "button", "Cancel", ["class" => "dBC v2 v2w"]
     ]),
     "Option2" => $this->system->Element([
      "button", "Save", [
       "class" => "Xpref v2 v2w",
       "data-f" => ".Preferences",
       "data-u" => base64_encode("v=$sp&UN=".$data["UN"]),
       "id" => "fSub"
      ]
     ])
    ]);
   }
   return $r;
  }
  function __destruct() {
   // DESTROYS THIS CLASS
  }
 }
?>