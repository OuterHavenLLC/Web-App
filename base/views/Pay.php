<?php
 Class Pay extends GW {
  function __construct() {
   parent::__construct();
   $this->root = $_SERVER["DOCUMENT_ROOT"]."/base/pay/Braintree.php";
   $this->you = $this->system->Member($this->system->Username());
  }
  function CartCheckout(array $a) {
   $d = $a["Data"] ?? [];
   $d = $this->system->FixMissing($d, ["UN"]);
   $r = "";
   $y = $this->you;
   if(!empty($d["UN"])) {
    $un = (!empty($d["UN"])) ? base64_decode($d["UN"]) : $y["Login"]["Username"];
    $t = ($un == $y["Login"]["Username"]) ? $y : $this->system->Member($un);
    $g = $this->system->Data("Get", [
     "shop",
     md5($t["Login"]["Username"])
    ]) ?? [];
    /*$shop = $this->system->Data("Get", [
     "shop",
     md5($t["Login"]["Username"])
    ]) ?? [];*/
    $bt = $g["Processing"] ?? [];
    $env = ($g["Live"] == 1) ? "production" : "sandbox";
    require_once($this->root);
    $sc = base64_encode("Pay:SaveCheckout");
    $tkn = base64_decode($bt["BraintreeToken"]);
    $btmid = base64_decode($bt["BraintreeMerchantID"]);
    $bt = new Braintree_Gateway([
     "environment" => $env,
     "merchantId" => $btmid,
     "privateKey" => base64_decode($bt["BraintreePrivateKey"]),
     "publicKey" => base64_decode($bt["BraintreePublicKey"])
    ]);
    $tkn = $bt->clientToken()->generate([
     "merchantAccountId" => $btmid
    ]) ?? $tkn;
    $di = $y["Shopping"]["Cart"][md5($un)]["Credits"] ?? 0;
    $di2 = $y["Shopping"]["Cart"][md5($un)]["DiscountCode"] ?? 0;
    $st = 0;
    $t = 0;
    $products = $y["Shopping"]["Cart"][md5($un)]["Products"] ?? [];
    foreach($products as $k => $v) {
     $p = $this->system->Data("Get", ["miny", $k]) ?? [];
     $exp = $p["Expires"] ?? [
      "Created" => $p["Created"], "Quantity" => 1, "TimeSpan" => "year"
     ];
     $ck = ($this->system->timestamp < $this->system->TimePlus($p["Created"], $exp["Quantity"], $exp["TimeSpan"])) ? 1 : 0;
     if(!empty($p) && $ck == 1) {
      $prc = str_replace(",", "", $p["Cost"]);
      $prc = $prc + str_replace(",", "", $p["Profit"]);
      $p = $prc * $v["Quantity"];
      $st = $st + $p;
     }
    }
    $di2 = ($di2 != 0) ? ($di2 / 100) * ($st - $di) : 0;
    $t = $st - $di - $di2;
    $r = $this->system->Change([[
     "[Checkout.FSTID]" => md5("Checkout_$btmid"),
     "[Checkout.ID]" => md5($btmid),
     "[Checkout.Processor]" => "v=$sc&ID=".md5($un)."&UN=".$d["UN"]."&payment_method_nonce=",
     "[Checkout.Region]" => $this->system->region,
     "[Checkout.Title]" => $g["Title"],
     "[Checkout.Token]" => $tkn,
     "[Checkout.Total]" => number_format($t, 2)
    ], $this->system->Page("a32d886447733485978116cc52d4f7aa")]);
   }
   return $r;
  }
  function Commission(array $a) {
   $d = $a["Data"] ?? [];
   $amount = $d["amount"] ?? base64_encode(0);
   $amount = base64_decode($amount);
   $amount = ($amount < 5) ? 5 : $amount;
   $un = $this->system->ShopID;
   $t = $this->system->Member($un);
   $g = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];
   /*$shop = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];*/
   $bt = $g["Processing"] ?? [];
   $env = ($g["Live"] == 1) ? "production" : "sandbox";
   require_once($this->root);
   $sc = base64_encode("Pay:SaveCommissionOrDonation");
   $tkn = base64_decode($bt["BraintreeToken"]);
   $btmid = base64_decode($bt["BraintreeMerchantID"]);
   $bt = new Braintree_Gateway([
    "environment" => $env,
    "merchantId" => $btmid,
    "privateKey" => base64_decode($bt["BraintreePrivateKey"]),
    "publicKey" => base64_decode($bt["BraintreePublicKey"])
   ]);
   $tkn = $bt->clientToken()->generate([
    "merchantAccountId" => $btmid
   ]) ?? $tkn;
   $te = base64_encode($amount);
   return $this->system->Change([[
    "[Commission.Action]" => "pay your $$amount commission",
    "[Commission.FSTID]" => md5("Commission_$btmid"),
    "[Commission.ID]" => md5($btmid),
    "[Commission.Processor]" => "v=$sc&amount=$te&ID=".md5($un)."&st=".base64_encode("Commission")."&payment_method_nonce=",
    "[Commission.Title]" => $g["Title"],
    "[Commission.Region]" => $this->system->region,
    "[Commission.Token]" => $tkn,
    "[Commission.Total]" => number_format($amount, 2)
   ], $this->system->Page("d84203cf19a999c65a50ee01bbd984dc")]);
  }
  function Donation(array $a) {
   $d = $a["Data"] ?? [];
   $amount = $d["amount"] ?? base64_encode(0);
   $amount = base64_decode($amount);
   $amount = ($amount < 5) ? 5 : $amount;
   $un = $this->system->ShopID;
   $t = $this->system->Member($un);
   $g = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];
   /*$shop = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];*/
   $bt = $g["Processing"] ?? [];
   $env = ($g["Live"] == 1) ? "production" : "sandbox";
   require_once($this->root);
   $sc = base64_encode("Pay:SaveCommissionOrDonation");
   $tkn = base64_decode($bt["BraintreeToken"]);
   $btmid = base64_decode($bt["BraintreeMerchantID"]);
   $bt = new Braintree_Gateway([
    "environment" => $env,
    "merchantId" => $btmid,
    "privateKey" => base64_decode($bt["BraintreePrivateKey"]),
    "publicKey" => base64_decode($bt["BraintreePublicKey"])
   ]);
   $tkn = $bt->clientToken()->generate([
    "merchantAccountId" => $btmid
   ]) ?? $tkn;
   $te = base64_encode($amount);
   return $this->system->Change([[
    "[Commission.Action]" => "donate $$amount",
    "[Commission.FSTID]" => md5("Donation_$btmid"),
    "[Commission.ID]" => md5($btmid),
    "[Commission.Processor]" => "v=$sc&amount=$te&ID=".md5($un)."&st=".base64_encode("Donation")."&payment_method_nonce=",
    "[Commission.Title]" => $g["Title"],
    "[Commission.Region]" => $this->system->region,
    "[Commission.Token]" => $tkn,
    "[Commission.Total]" => number_format($amount, 2)
   ], $this->system->Page("d84203cf19a999c65a50ee01bbd984dc")]);
  }
  function Partner(array $a) {
   $d = $a["Data"] ?? [];
   $d = $this->system->FixMissing($d, ["Month", "UN", "Year"]);
   $sp = base64_encode("Pay:PartnerComplete");
   $un = base64_decode($d["UN"]);
   $t = $this->system->Member($un);
   $g = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];
   /*$shop = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];*/
   $bt = $g["Processing"] ?? [];
   $ck = $this->system->CheckBraintreeKeys($bt);
   $r = $this->system->Change([[
    "[Error.Back]" => "",
    "[Error.Header]" => "Error",
    "[Error.Message]" => "The Member, Month, or Year Identifiers are missing, or the keys are missing."
   ], $this->system->Page("f7d85d236cc3718d50c9ccdd067ae713")]);
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if(!empty($d["Month"]) && !empty($d["UN"]) && !empty($d["Year"]) && $ck == 4) {
    $id = $this->system->Data("Get", ["id", md5($you)]) ?? [];
    $id = $id[$d["Year"]][$d["Month"]] ?? [];
    $partners = count($id["Partners"]);
    $sales = 0;
    foreach($id as $k => $v) {
     if($k == "Sales") {
      for($i = 0; $i < count($k); $i++) {
       foreach($v[$i] as $k2 => $v2) {
        $prc = $v2["Cost"] + $v2["Profit"];
        $prc = $prc * $v2["Quantity"];
        $sales = $sales + $prc;
       }
      }
     }
    }
    $r = $this->system->Change([
     [
      "[Error.Back]" => "",
      "[Error.Header]" => "No Payment Necessary",
      "[Error.Message]" => "You cannot pay ".$t["Personal"]["DisplayName"].", as there are no funds to disburse."
     ], $this->system->Page("f7d85d236cc3718d50c9ccdd067ae713")
    ]);
    if($s == 0) {
     $id[$d["Year"]][$d["Month"]][$un]["Paid"] = 1;
     $this->system->Data("Save", ["id", md5($y["Login"]["Username"]), $id]);
    } else {
     require_once($this->root);
     $env = ($g["Live"] == 1) ? "production" : "sandbox";
     $btmid = base64_decode($bt["BraintreeMerchantID"]);
     $btpubk = base64_decode($bt["BraintreePublicKey"]);
     $bt = new Braintree_Gateway([
      "environment" => $env,
      "merchantId" => $btmid,
      "privateKey" => base64_decode($bt["BraintreePrivateKey"]),
      "publicKey" => $btpubk
     ]);
     $tkn = $bt->clientToken()->generate([
      "merchantAccountId" => $btmid
     ]) ?? $btpubk;
     $total = $sales / $partners;
     $r = $this->system->Change([[
      "[Partner.Checkout]" => "v=$sp&Month=".$d["Month"]."&UN=".$d["UN"]."&Year=".$d["Year"]."&amount=".base64_encode($total)."&payment_method_nonce=",
      "[Partner.LastMonth]" => $this->system->ConvertCalendarMonths($d["Month"]),
      "[Partner.Pay.Amount]" => number_format($total, 2),
      "[Partner.Pay.FSTID]" => md5("PaymentComplete$un"),
      "[Partner.Pay.ID]" => md5($btmid),
      "[Partner.Pay.Region]" => $this->system->region,
      "[Partner.Pay.Token]" => $tkn,
      "[Partner.ProfilePicture]" => $this->system->ProfilePicture($t, "margin:12.5% 25%;width:50%"),
      "[Partner.Username]" => $un
     ], $this->system->Page("6ed9bbbc61563b846b512acf94550806")]);
    }
   }
   return $r;
  }
  function PartnerComplete(array $a) {
   $d = $a["Data"] ?? [];
   $d = $this->system->FixMissing($d, [
    "UN",
    "amount",
    "payment_method_nonce"
   ]);
   $y = $this->you;
   $pn = $d["payment_method_nonce"];
   $r = $this->system->Change([[
    "[Error.Back]" => "",
    "[Error.Header]" => "Error",
    "[Error.Message]" => "The Member Identifier or Payment Type are missing."
   ], $this->system->Page("f7d85d236cc3718d50c9ccdd067ae713")]);
   $un = $d["UN"];
   if(!empty($d["Month"]) && !empty($d["Year"]) && !empty($pn) && !empty($un)) {
    $un = base64_decode($un);
    $t = $this->system->Member($un);
    $shop = $this->system->Data("Get", [
     "shop",
     md5($t["Login"]["Username"])
    ]) ?? [];
    $bt = $shop["Processing"] ?? [];
    $ck = $this->system->CheckBraintreeKeys($bt);
    if($ck == 4) {
     $env = ($shop["Live"] == 1) ? "production" : "sandbox";
     require_once($this->root);
     $bt = new Braintree_Gateway([
      "environment" => $env,
      "merchantId" => base64_decode($bt["BraintreeMerchantID"]),
      "privateKey" => base64_decode($bt["BraintreePrivateKey"]),
      "publicKey" => base64_decode($bt["BraintreePublicKey"])
     ]);
     $amount = $d["amount"] ?? base64_encode(0);
     $amount = base64_decode($amount);
     $amount = ($amount < 5) ? 5 : $amount;
     $amount = number_format($amount, 2);
     $o = $bt->transaction()->sale([
      "amount" => str_replace(",", "", $amount),
       "customer" => [
       "firstName" => $y["firstName"]
      ],
      "options" => [
       "submitForSettlement" => true
      ],
      "paymentMethodNonce" => $pn
     ]);
     if($o->success) {
      $id = $this->system->Data("Get", [
       "id",
       md5($y["Login"]["Username"])
      ]) ?? [];
      $pc = number_format(0, 2);
      $pid = "DISBURSEMENT*$un";
      $this->system->Revenue([$un, [
       "Cost" => $amount,
       "ID" => $pid,
       "Partners" => $shop["Contributors"],
       "Profit" => $pc,
       "Quantity" => 1,
       "Title" => $pid
      ]]);
      $this->system->Revenue([$y["Login"]["Username"], [
       "Cost" => $amount,
       "ID" => $pid,
       "Partners" => $shop["Contributors"],
       "Profit" => $pc,
       "Quantity" => 1,
       "Title" => $pid
      ]]);
      $id[$d["Year"]][$d["Month"]]["Partners"][$un]["Paid"] = 1;
      $this->system->Data("Save", ["id", md5($y["Login"]["Username"]), $id]);
      $r = $this->system->Change([[
       "[Partner.Amount]" => $amount,
       "[Partner.ProfilePicture]" => $this->system->ProfilePicture($t, "margin:12.5% 25%;width:50%"),
       "[Partner.Username]" => $un
      ], $this->system->Page("70881ae11e9353107ded2bed93620ef4")]);
     } else {
      $r = $this->system->Change([[
       "[Checkout.Order.Message]" => $o->message,
       "[Checkout.Order.Products]" => 1,
       "[Checkout.Order.Success]" => json_encode($o->success)
      ], $this->system->Page("229e494ec0f0f43824913a622a46dfca")]);
     }
    }
   }
   return $r;
  }
  function ProcessCartOrder(array $a) {
   $bndl = $a["Bundled"] ?? 0;
   $po = $a["PhysicalOrders"] ?? [];
   $r = "";
   $un = $a["UN"] ?? $a["Member"]["UN"];
   $une = md5($un);
   $y = $a["Member"] ?? $this->you;
   if(!empty($a["Member"]) && is_array($a["Product"])) {
    $h2 = $y["Shopping"]["History"][$une] ?? [];
    $id = $a["Product"]["ID"] ?? "";
    $p = $this->system->Data("Get", ["miny", $id]) ?? [];
    $t = ($un == $y["Login"]["Username"]) ? $y : $this->system->Member($un);
    $shop = $this->system->Data("Get", ["shop", md5($t["UN"])]) ?? [];
    $contributors = $shop["Contributors"] ?? [];
    if(!empty($p["Title"])) {
     $opt = "";
     $exp = $p["Expires"];
     $ck = ($this->system->timestamp < $this->system->TimePlus($p["Created"], $exp["Quantity"], $exp["TimeSpan"])) ? 1 : 0;
     if(!empty($p) && $ck == 1) {
      $base = $this->system->efs;
      $cat = $p["Category"];
      $ico = $this->system->PlainText([
       "Data" => "[sIMG:MiNY]",
       "Display" => 1
      ]);
      $ico = (!empty($p["ICO"])) ? $base.$p["ICO"] : $ico;
      $pts = $this->system->core["PTS"]["Products"];
      $quantity = $p["Quantity"] ?? 0;
      $st = $p["SubscriptionTerm"] ?? "month";
      if($cat == "ARCH") {
       # Architecture
      } elseif($cat == "DLC") {
       # Downloadable Content
      } elseif($cat == "DONATE") {
       # Donations
       $opt = $this->system->Element(["p", "Thank You for donating!"]);
      } elseif($cat == "PHYS") {
       # Physical Products
       $opt = $this->system->Element(["button", "Contact the Seller", [
        "class" => "BB BBB v2 v2w"
       ]]);
       $po[md5($this->system->timestamp.rand(0, 9999))] = [
        "Complete" => 0,
        "Instructions" => base64_encode($a["Product"]["Instructions"]),
        "ProductID" => $id,
        "Quantity" => $a["Product"]["Quantity"],
        "UN" => $y["Login"]["Username"]
       ];
      } elseif($cat == "SUB") {
       $opt = $this->system->Element(["button", "Go to Subscription", [
        "class" => "BB BBB v2 v2w"
       ]]);
       if($id == "355fd2f096bdb49883590b8eeef72b9c") {
        foreach($y["Subscriptions"] as $sk => $sv) {
         if(!in_array($sk, ["Artist", "Developer"])) {
          $y["Subscriptions"][$sk] = [
           "A" => 1,
           "B" => $this->system->timestamp,
           "E" => $this->system->TimePlus($this->system->timestamp, 1, $st)
          ];
         }
        }
       } elseif($id == "39d05985f0667a69f3a725d5afd1265c") {
        $y["Subscriptions"]["Developer"] = [
         "A" => 1,
         "B" => $this->system->timestamp,
         "E" => $this->system->TimePlus($this->system->timestamp, 1, $st)
        ];
       } elseif($id == "5bfb3f44cdb9d3f2cd969a23f0e37093") {
        $y["Subscriptions"]["XFS"] = [
         "A" => 1,
         "B" => $this->system->timestamp,
         "E" => $this->system->TimePlus($this->system->timestamp, 1, $st)
        ];
       } elseif($id == "c7054e9c7955203b721d142dedc9e540") {
        $y["Subscriptions"]["Artist"] = [
         "A" => 1,
         "B" => $this->system->timestamp,
         "E" => $this->system->TimePlus($this->system->timestamp, 1, $st)
        ];
       } elseif($id == "cc84143175d6ae2051058ee0079bd6b8") {
        $y["Subscriptions"]["Blogger"] = [
         "A" => 1,
         "B" => $this->system->timestamp,
         "E" => $this->system->TimePlus($this->system->timestamp, 1, $st)
        ];
       }
      }
      $h2[md5($id.$this->system->timestamp.rand(0, 1776))] = [
       "ID" => $id,
       "Instructions" => $a["Product"]["Instructions"],
       "Quantity" => $a["Product"]["Quantity"],
       "Timestamp" => $this->system->timestamp
      ];
      $p["Quantity"] = ($quantity > 0) ? $quantity - $a["Product"]["Quantity"] : $quantity;
      $r .= $this->system->Change([[
       "[Product.Added]" => $this->system->TimeAgo($this->system->timestamp),
       "[Product.ICO]" => $ico,
       "[Product.Description]" => $this->system->PlainText([
        "Data" => $p["Description"],
        "Display" => 1
       ]),
       "[Product.Options]" => $opt,
       "[Product.Quantity]" => $a["Product"]["Quantity"],
       "[Product.Title]" => $p["Title"]
      ], $this->system->Page("4c304af9fcf2153e354e147e4744eab6")]);
      $y["Shopping"]["History"][$une] = $h2;
      $y["Points"] = $y["Points"] + $pts[$cat];
      if($bndl == 0) {
       $this->system->Revenue([$y["Login"]["Username"], [
        "Cost" => $p["Cost"],
        "ID" => $id,
        "Partners" => $contributors,
        "Profit" => $p["Profit"],
        "Quantity" => $a["Product"]["Quantity"],
        "Title" => $p["Title"]
       ]]);
      } if($p["Quantity"] >= 0) {
       $this->system->Data("Save", ["miny", $id, $p]);
      }
     } if(!empty($p["Bundled"]) && is_array($p["Bundled"])) {
      foreach($p["Bundled"] as $pb) {
       $pb = explode("-", base64_decode($pb));
       $co = $this->ProcessCartOrder([
        "Member" => $y,
        "PhysicalOrders" => $po,
        "Product" => [
         "DiscountCode" => 0,
         "DiscountCredit" => 0,
         "ID" => $pb[1],
         "Instructions" => "",
         "Quantity" => 1
        ],
        "UN" => $pb[0]
       ]);
       $po = ($co["ERR"] == 0) ? $co["PhysicalOrders"] : $po;
       $r .= $co["Response"];
       $y = $co["Member"];
      }
     }
    }
    $r = [
     "ERR" => 0,
     "Member" => $y,
     "PhysicalOrders" => $po,
     "Response" => $r
    ];
   } else {
    $r = [
     "ERR" => 1,
     "Parameters" => [],
     "Response" => $r
    ];
   }
   return $r;
  }
  function SaveCheckout(array $a) {
   $d = $a["Data"] ?? [];
   $d = $this->system->FixMissing($d, ["UN", "payment_method_nonce"]);
   $y = $this->you;
   $un = $d["UN"];
   $un = (!empty($un)) ? base64_decode($un) : $y["Login"]["Username"];
   $t = ($un == $y["Login"]["Username"]) ? $y : $this->system->Member($un);
   $g = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];
   /*$shop = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];*/
   $bt = $g["Processing"] ?? [];
   $live = $g["Live"] ?? 0;
   $env = ($live == 1) ? "production" : "sandbox";
   $pn = $d["payment_method_nonce"];
   $r = $this->system->Change([[
    "[Checkout.Data]" => json_encode($d)
   ], $this->system->Page("f9ee8c43d9a4710ca1cfc435037e9abd")]);
   if(!empty($pn)) {
    require_once($this->root);
    $bt = new Braintree_Gateway([
     "environment" => $env,
     "merchantId" => base64_decode($bt["BraintreeMerchantID"]),
     "privateKey" => base64_decode($bt["BraintreePrivateKey"]),
     "publicKey" => base64_decode($bt["BraintreePublicKey"])
    ]);
    $di = $y["Shopping"]["Cart"][md5($un)]["Credits"] ?? 0;
    $di2 = $y["Shopping"]["Cart"][md5($un)]["DiscountCode"] ?? 0;
    $st = 0;
    $products = $y["Shopping"]["Cart"][md5($un)]["Products"] ?? [];
    foreach($products as $k => $v) {
     $p = $this->system->Data("Get", ["miny", $k]) ?? [];
     $exp = $p["Expires"] ?? [
      "Created" => $p["Created"], "Quantity" => 1, "TimeSpan" => "year"
     ];
     $ck = ($this->system->timestamp < $this->system->TimePlus($p["Created"], $exp["Quantity"], $exp["TimeSpan"])) ? 1 : 0;
     if(!empty($p) && $ck == 1) {
      $prc = str_replace(",", "", $p["Cost"]);
      $prc = $prc + str_replace(",", "", $p["Profit"]);
      $p = $prc * $v["Quantity"];
      $st = $st + $p;
     }
    }
    $di2 = ($di2 != 0) ? ($di2 / 100) * ($st - $di) : 0;
    $t = $st - $di - $di2;
    $t = ($t < 5) ? number_format(5, 2) : $t;
    $o = $bt->transaction()->sale([
     "amount" => str_replace(",", "", $t),
     "customer" => [
      "firstName" => $y["firstName"]
     ],
     "options" => [
      "submitForSettlement" => true
     ],
     "paymentMethodNonce" => $pn
    ]);
    if($o->success) {
     $y["Shopping"]["Cart"][md5($un)]["DiscountCode"] = 0;
     $r = "";
     $po = $this->system->Data("Get", ["po", md5($un)]) ?? [];
     $t = ($un == $y["Login"]["Username"]) ? $y : $this->system->Member($un);
     foreach($products as $k => $v) {
      $v["ID"] = $v["ID"] ?? $k;
      $bndl = $v["Bundled"] ?? [];
      $bndl = (!empty($bndl)) ? 1 : 0;
      $co = $this->ProcessCartOrder([
       "Bundled" => $bndl,
       "Member" => $y,
       "PhysicalOrders" => $po,
       "Product" => $v,
       "UN" => $un
      ]);
      $po = ($co["ERR"] == 0) ? $co["PhysicalOrders"] : $po;
      $r .= $co["Response"];
      $y = $co["Member"];
     }
     $y["Shopping"]["Cart"][md5($un)]["Credits"] = 0;
     $y["Shopping"]["Cart"][md5($un)]["Products"] = [];
     $r = $this->system->Change([[
      "[Checkout.Order]" => $r,
      "[Checkout.Title]" => $g["Title"],
      "[Checkout.Total]" => $t
     ], $this->system->Page("83d6fedaa3fa042d53722ec0a757e910")]);
     $this->system->Data("Save", ["mbr", md5($y["Login"]["Username"]), $y]);
     $this->system->Data("Save", ["po", md5($un), $po]);
    } else {
     $r = $this->system->Change([[
      "[Checkout.Order.Message]" => $o->message,
      "[Checkout.Order.Products]" => count($y["Shopping"]["Cart"][md5($un)]["Products"]),
      "[Checkout.Order.Success]" => json_encode($o->success)
     ], $this->system->Page("229e494ec0f0f43824913a622a46dfca")]);
    }
   }
   return $r;
  }
  function SaveCommissionOrDonation(array $a) {
   $d = $a["Data"] ?? [];
   $d = $this->system->FixMissing($d, [
    "amount", "payment_method_nonce", "st"
   ]);
   $y = $this->you;
   $amount = $d["amount"] ?? base64_encode(0);
   $amount = base64_decode($amount);
   $amount = ($amount < 5) ? 5 : $amount;
   $amount = number_format($amount, 2);
   $pts = $this->system->core["PTS"]["Donations"] ?? 0;
   $st = (!empty($d["st"])) ? base64_decode($d["st"]) : "";
   $t = $this->system->Member($this->system->ShopID);
   $g = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];
   /*$shop = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];*/
   $bt = $g["Processing"] ?? [];
   $live = $g["Live"] ?? 0;
   $env = ($live == 1) ? "production" : "sandbox";
   $r = $this->system->Change([[
    "[Checkout.Data]" => json_encode($d)
   ], $this->system->Page("f9ee8c43d9a4710ca1cfc435037e9abd")]);
   if(!empty($d["payment_method_nonce"])) {
    require_once($this->root);
    $bt = new Braintree_Gateway([
     "environment" => $env,
     "merchantId" => base64_decode($bt["BraintreeMerchantID"]),
     "privateKey" => base64_decode($bt["BraintreePrivateKey"]),
     "publicKey" => base64_decode($bt["BraintreePublicKey"])
    ]);
    $o = $bt->transaction()->sale([
     "amount" => str_replace(",", "", $amount),
      "customer" => [
      "firstName" => $y["firstName"]
     ],
     "options" => [
      "submitForSettlement" => true
     ],
     "paymentMethodNonce" => $d["payment_method_nonce"]
    ]);
    if($o->success) {
     if($st == "Commission") {
      $y["Commission"] = 0;
     }
     $st2 = ($st == "Commission") ? "$$amount commission" : base64_decode($d["amount"])."$$amount donation";
     $st3 = ($st == "Commission") ? "You may now access your Artist dashboard." : "$pts points have been added";
     $y["Points"] = $y["Points"] + $pts;
     $r = $this->system->Change([[
      "[Commission.Message]" => $st3,
      "[Commission.Type]" => $st2
     ], $this->system->Page("f2bea3c1ebf2913437fcfdc0c1601ce0")]);
     $this->system->Data("Save", ["mbr", md5($y["Login"]["Username"]), $y]);
    } else {
     $r = $this->system->Change([[
      "[Checkout.Order.Message]" => $o->message,
      "[Checkout.Order.Products]" => 1,
      "[Checkout.Order.Success]" => json_encode($o->success)
     ], $this->system->Page("229e494ec0f0f43824913a622a46dfca")]);
    }
   }
   return $r;
  }
  function __destruct() {
   // DESTROYS THIS CLASS
  }
 }
?>