<?php
 Class Pay extends GW {
  function __construct() {
   parent::__construct();
   $this->root = $_SERVER["DOCUMENT_ROOT"]."/base/pay/Braintree.php";
   $this->you = $this->system->Member($this->system->Username());
  }
  function Checkout(array $a) {
   $data = $a["Data"] ?? [];
   $r = "";
   $y = $this->you;
   $you = $y["Login"]["Username"];
   $username = $data["UN"] ?? base64_encode($you);
   if(!empty($username)) {
    require_once($this->root);
    $username = base64_decode($username);
    $shop = $this->system->Data("Get", ["shop", md5($username)]) ?? [];
    $t = ($username == $you) ? $y : $this->system->Member($username);
    $braintree = $shop["Processing"] ?? [];
    $envrionment = ($shop["Live"] == 1) ? "production" : "sandbox";
    $token = base64_decode($braintree["BraintreeToken"]);
    $btmid = base64_decode($braintree["BraintreeMerchantID"]);
    $braintree = new Braintree_Gateway([
     "environment" => $envrionment,
     "merchantId" => $btmid,
     "privateKey" => base64_decode($braintree["BraintreePrivateKey"]),
     "publicKey" => base64_decode($braintree["BraintreePublicKey"])
    ]);
    $token = $braintree->clientToken()->generate([
     "merchantAccountId" => $btmid
    ]) ?? $token;
    $cart = $y["Shopping"]["Cart"][md5($username)]["Products"] ?? [];
    $cartCount = count($cart);
    $credits = $y["Shopping"]["Cart"][md5($username)]["Credits"] ?? 0;
    $credits = number_format($credits, 2);
    $discountCode = $y["Shopping"]["Cart"][md5($username)]["DiscountCode"] ?? 0;
    $now = $this->system->timestamp;
    $subtotal = 0;
    $total = 0;
    foreach($cart as $key => $value) {
     $product = $this->system->Data("Get", ["miny", $key]) ?? [];
     $ck = (strtotime($now) < $product["Expires"]) ? 1 : 0;
     if($ck == 1) {
      $price = str_replace(",", "", $product["Cost"]);
      $price = $price + str_replace(",", "", $product["Profit"]);
      $subtotal = $subtotal + $price;
     }
    } if($discountCode != 0) {
     $discountCode = $discountCode ?? [];
     $dollarAmount = $discountCode["DollarAmount"] ?? 0.00;
     $dollarAmount = number_format($dollarAmount, 2);
     $percentile = $discountCode["Percentile"] ?? 0;
     $percentile = $subtotal * ($percentile / 100);
     $check = ($dollarAmount > $percentile) ? "Dollars" : "Percentile";
     $discountCode = [
      "Amount" => $check,
      "Dollars" => $dollarAmount,
      "Percentile" => $percentile
     ];
     if($discountCode["Amount"] == "Dollars") {
      $discountCode = $discountCode["Dollars"];
     } else {
      $discountCode = number_format($discountCode["Percentile"], 2);
     }
    }
    $total = $subtotal - $credits - $discountCode;
    $tax = $shop["Tax"] ?? 10.00;
    $tax = number_format($total * ($tax / 100), 2);
    $r = $this->system->Change([[
     "[Checkout.FSTID]" => md5("Checkout_$btmid"),
     "[Checkout.ID]" => md5($btmid),
     "[Checkout.Processor]" => "v=".base64_encode("Pay:SaveCheckout")."&ID=".md5($username)."&UN=".$data["UN"]."&payment_method_nonce=",
     "[Checkout.Region]" => $this->system->region,
     "[Checkout.Title]" => $shop["Title"],
     "[Checkout.Token]" => $token,
     "[Checkout.Total]" => number_format($tax + $total, 2)
    ], $this->system->Page("a32d886447733485978116cc52d4f7aa")]);
   }
   return $r;
  }
  function Commission(array $a) {
   $data = $a["Data"] ?? [];
   $amount = $data["amount"] ?? base64_encode(0);
   $amount = base64_decode($amount);
   $amount = ($amount < 5) ? 5 : $amount;
   $username = $this->system->ShopID;
   $t = $this->system->Member($username);
   $shop = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];
   $braintree = $shop["Processing"] ?? [];
   $env = ($shop["Live"] == 1) ? "production" : "sandbox";
   require_once($this->root);
   $sc = base64_encode("Pay:SaveCommissionOrDonation");
   $token = base64_decode($braintree["BraintreeToken"]);
   $btmid = base64_decode($braintree["BraintreeMerchantID"]);
   $braintree = new Braintree_Gateway([
    "environment" => $env,
    "merchantId" => $btmid,
    "privateKey" => base64_decode($braintree["BraintreePrivateKey"]),
    "publicKey" => base64_decode($braintree["BraintreePublicKey"])
   ]);
   $token = $braintree->clientToken()->generate([
    "merchantAccountId" => $btmid
   ]) ?? $token;
   $te = base64_encode($amount);
   return $this->system->Change([[
    "[Commission.Action]" => "pay your $$amount commission",
    "[Commission.FSTID]" => md5("Commission_$btmid"),
    "[Commission.ID]" => md5($btmid),
    "[Commission.Processor]" => "v=$sc&amount=$te&ID=".md5($username)."&st=".base64_encode("Commission")."&payment_method_nonce=",
    "[Commission.Title]" => $shop["Title"],
    "[Commission.Region]" => $this->system->region,
    "[Commission.Token]" => $token,
    "[Commission.Total]" => number_format($amount, 2)
   ], $this->system->Page("d84203cf19a999c65a50ee01bbd984dc")]);
  }
  function Donation(array $a) {
   $data = $a["Data"] ?? [];
   $amount = $data["amount"] ?? base64_encode(0);
   $amount = base64_decode($amount);
   $amount = ($amount < 5) ? 5 : $amount;
   $username = $this->system->ShopID;
   $t = $this->system->Member($username);
   $shop = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];
   $braintree = $shop["Processing"] ?? [];
   $env = ($shop["Live"] == 1) ? "production" : "sandbox";
   require_once($this->root);
   $sc = base64_encode("Pay:SaveCommissionOrDonation");
   $token = base64_decode($braintree["BraintreeToken"]);
   $btmid = base64_decode($braintree["BraintreeMerchantID"]);
   $braintree = new Braintree_Gateway([
    "environment" => $env,
    "merchantId" => $btmid,
    "privateKey" => base64_decode($braintree["BraintreePrivateKey"]),
    "publicKey" => base64_decode($braintree["BraintreePublicKey"])
   ]);
   $token = $braintree->clientToken()->generate([
    "merchantAccountId" => $btmid
   ]) ?? $token;
   $te = base64_encode($amount);
   return $this->system->Change([[
    "[Commission.Action]" => "donate $$amount",
    "[Commission.FSTID]" => md5("Donation_$btmid"),
    "[Commission.ID]" => md5($btmid),
    "[Commission.Processor]" => "v=$sc&amount=$te&ID=".md5($username)."&st=".base64_encode("Donation")."&payment_method_nonce=",
    "[Commission.Title]" => $shop["Title"],
    "[Commission.Region]" => $this->system->region,
    "[Commission.Token]" => $token,
    "[Commission.Total]" => number_format($amount, 2)
   ], $this->system->Page("d84203cf19a999c65a50ee01bbd984dc")]);
  }
  function Partner(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["Month", "UN", "Year"]);
   $sp = base64_encode("Pay:PartnerComplete");
   $username = base64_decode($data["UN"]);
   $t = $this->system->Member($username);
   $shop = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];
   $braintree = $shop["Processing"] ?? [];
   $ck = $this->system->CheckBraintreeKeys($bt);
   $r = $this->system->Change([[
    "[Error.Back]" => "",
    "[Error.Header]" => "Error",
    "[Error.Message]" => "The Member, Month, or Year Identifiers are missing, or the keys are missing."
   ], $this->system->Page("f7d85d236cc3718d50c9ccdd067ae713")]);
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if(!empty($data["Month"]) && !empty($data["UN"]) && !empty($data["Year"]) && $ck == 4) {
    $id = $this->system->Data("Get", ["id", md5($you)]) ?? [];
    $id = $id[$data["Year"]][$data["Month"]] ?? [];
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
     $id[$data["Year"]][$data["Month"]][$username]["Paid"] = 1;
     $this->system->Data("Save", ["id", md5($y["Login"]["Username"]), $id]);
    } else {
     require_once($this->root);
     $env = ($shop["Live"] == 1) ? "production" : "sandbox";
     $btmid = base64_decode($braintree["BraintreeMerchantID"]);
     $btpubk = base64_decode($braintree["BraintreePublicKey"]);
     $braintree = new Braintree_Gateway([
      "environment" => $env,
      "merchantId" => $btmid,
      "privateKey" => base64_decode($braintree["BraintreePrivateKey"]),
      "publicKey" => $btpubk
     ]);
     $token = $braintree->clientToken()->generate([
      "merchantAccountId" => $btmid
     ]) ?? $btpubk;
     $total = $sales / $partners;
     $r = $this->system->Change([[
      "[Partner.Checkout]" => "v=$sp&Month=".$data["Month"]."&UN=".$data["UN"]."&Year=".$data["Year"]."&amount=".base64_encode($total)."&payment_method_nonce=",
      "[Partner.LastMonth]" => $this->system->ConvertCalendarMonths($data["Month"]),
      "[Partner.Pay.Amount]" => number_format($total, 2),
      "[Partner.Pay.FSTID]" => md5("PaymentComplete$username"),
      "[Partner.Pay.ID]" => md5($btmid),
      "[Partner.Pay.Region]" => $this->system->region,
      "[Partner.Pay.Token]" => $token,
      "[Partner.ProfilePicture]" => $this->system->ProfilePicture($t, "margin:12.5% 25%;width:50%"),
      "[Partner.Username]" => $username
     ], $this->system->Page("6ed9bbbc61563b846b512acf94550806")]);
    }
   }
   return $r;
  }
  function PartnerComplete(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, [
    "UN",
    "amount",
    "payment_method_nonce"
   ]);
   $y = $this->you;
   $paymentNonce = $data["payment_method_nonce"];
   $r = $this->system->Change([[
    "[Error.Back]" => "",
    "[Error.Header]" => "Error",
    "[Error.Message]" => "The Member Identifier or Payment Type are missing."
   ], $this->system->Page("f7d85d236cc3718d50c9ccdd067ae713")]);
   $username = $data["UN"];
   if(!empty($data["Month"]) && !empty($data["Year"]) && !empty($paymentNonce) && !empty($username)) {
    $username = base64_decode($username);
    $t = $this->system->Member($username);
    $shop = $this->system->Data("Get", [
     "shop",
     md5($t["Login"]["Username"])
    ]) ?? [];
    $braintree = $shop["Processing"] ?? [];
    $ck = $this->system->CheckBraintreeKeys($bt);
    if($ck == 4) {
     $env = ($shop["Live"] == 1) ? "production" : "sandbox";
     require_once($this->root);
     $braintree = new Braintree_Gateway([
      "environment" => $env,
      "merchantId" => base64_decode($braintree["BraintreeMerchantID"]),
      "privateKey" => base64_decode($braintree["BraintreePrivateKey"]),
      "publicKey" => base64_decode($braintree["BraintreePublicKey"])
     ]);
     $amount = $data["amount"] ?? base64_encode(0);
     $amount = base64_decode($amount);
     $amount = ($amount < 5) ? 5 : $amount;
     $amount = number_format($amount, 2);
     $order = $braintree->transaction()->sale([
      "amount" => str_replace(",", "", $amount),
       "customer" => [
       "firstName" => $y["firstName"]
      ],
      "options" => [
       "submitForSettlement" => true
      ],
      "paymentMethodNonce" => $paymentNonce
     ]);
     if($order->success) {
      $id = $this->system->Data("Get", [
       "id",
       md5($y["Login"]["Username"])
      ]) ?? [];
      $pc = number_format(0, 2);
      $pid = "DISBURSEMENT*$username";
      $this->system->Revenue([$username, [
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
      $id[$data["Year"]][$data["Month"]]["Partners"][$username]["Paid"] = 1;
      $this->system->Data("Save", ["id", md5($y["Login"]["Username"]), $id]);
      $r = $this->system->Change([[
       "[Partner.Amount]" => $amount,
       "[Partner.ProfilePicture]" => $this->system->ProfilePicture($t, "margin:12.5% 25%;width:50%"),
       "[Partner.Username]" => $username
      ], $this->system->Page("70881ae11e9353107ded2bed93620ef4")]);
     } else {
      $r = $this->system->Change([[
       "[Checkout.Order.Message]" => $order->message,
       "[Checkout.Order.Products]" => 1,
       "[Checkout.Order.Success]" => json_encode($order->success)
      ], $this->system->Page("229e494ec0f0f43824913a622a46dfca")]);
     }
    }
   }
   return $r;
  }
  function ProcessCartOrder(array $a) {
   $bundle = $a["Bundled"] ?? 0;
   $po = $a["PhysicalOrders"] ?? [];
   $username = $a["UN"] ?? $a["Member"]["UN"];
   $usernamee = md5($username);
   $r = "";
   $y = $a["Member"] ?? $this->you;
   $you = $y["Login"]["Username"];
   if(!empty($a["Member"]) && is_array($a["Product"])) {
    $h2 = $y["Shopping"]["History"][$usernamee] ?? [];
    $id = $a["Product"]["ID"] ?? "";
    $t = ($username == $you) ? $y : $this->system->Member($username);
    $shop = $this->system->Data("Get", ["shop", md5($username)]) ?? [];
    $product = $this->system->Data("Get", ["miny", $id]) ?? [];
    if(!empty($product["Title"])) {
     $bundledProducts = $product["Bundled"] ?? [];
     $contributors = $shop["Contributors"] ?? [];
     $now = $this->system->timestamp;
     $opt = "";
     if(!empty($product) && $now < $product["Expires"]) {
      $base = $this->system->efs;
      $cat = $product["Category"];
      $coverPhoto = $product["ICO"] ?? $this->system->PlainText([
       "Data" => "[sIMG:MiNY]",
       "Display" => 1
      ]);
      $coverPhoto = base64_encode($coverPhoto);
      $pts = $this->system->core["PTS"]["Products"];
      $quantity = $product["Quantity"] ?? 0;
      $st = $product["SubscriptionTerm"] ?? "month";
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
      $product["Quantity"] = ($quantity > 0) ? $quantity - $a["Product"]["Quantity"] : $quantity;
      $r .= $this->system->Change([[
       "[Product.Added]" => $this->system->TimeAgo($this->system->timestamp),
       "[Product.ICO]" => $coverPhoto,
       "[Product.Description]" => $this->system->PlainText([
        "Data" => $product["Description"],
        "Display" => 1
       ]),
       "[Product.Options]" => $opt,
       "[Product.Quantity]" => $a["Product"]["Quantity"],
       "[Product.Title]" => $product["Title"]
      ], $this->system->Page("4c304af9fcf2153e354e147e4744eab6")]);
      $y["Shopping"]["History"][$usernamee] = $h2;
      $y["Points"] = $y["Points"] + $pts[$cat];
      if($bundle == 0) {
       /*$this->system->Revenue([$you, [
        "Cost" => $product["Cost"],
        "ID" => $id,
        "Partners" => $contributors,
        "Profit" => $product["Profit"],
        "Quantity" => $a["Product"]["Quantity"],
        "Title" => $product["Title"]
       ]]);*/
      } if($product["Quantity"] > 0) {
       #$this->system->Data("Save", ["miny", $id, $p]);
      }
     }
     foreach($bundledProducts as $bundled) {
      $bundled = explode("-", base64_decode($bundled));
      $cartOrder = $this->ProcessCartOrder([
       "Member" => $y,
       "PhysicalOrders" => $physicalOrders,
       "Product" => [
        "DiscountCode" => 0,
        "DiscountCredit" => 0,
        "ID" => $bundled[1],
        "Instructions" => "",
        "Quantity" => 1
       ],
       "UN" => $bundled[0]
      ]);
      $physicalOrders = ($cartOrder["ERR"] == 0) ? $cartOrder["PhysicalOrders"] : $physicalOrders;
      $r .= $cartOrder["Response"];
      $y = $cartOrder["Member"];
     }
    }
    $r = [
     "ERR" => 0,
     "Member" => $y,
     "PhysicalOrders" => $physicalOrders,
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
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, ["UN", "payment_method_nonce"]);
   $username = $data["UN"];
   $paymentNonce = $data["payment_method_nonce"];
   $r = $this->system->Change([[
    "[Checkout.Data]" => json_encode($data)
   ], $this->system->Page("f9ee8c43d9a4710ca1cfc435037e9abd")]);
   $y = $this->you;
   $you = $y["Login"]["Username"];
   if(!empty($paymentNonce)) {
    require_once($this->root);
    $username = (!empty($username)) ? base64_decode($username) : $you;
    $t = ($username == $you) ? $y : $this->system->Member($username);
    $shop = $this->system->Data("Get", [
     "shop",
     md5($t["Login"]["Username"])
    ]) ?? [];
    $braintree = $shop["Processing"] ?? [];
    $live = $shop["Live"] ?? 0;
    $environment = ($live == 1) ? "production" : "sandbox";
    $braintree = new Braintree_Gateway([
     "environment" => $environment,
     "merchantId" => base64_decode($braintree["BraintreeMerchantID"]),
     "privateKey" => base64_decode($braintree["BraintreePrivateKey"]),
     "publicKey" => base64_decode($braintree["BraintreePublicKey"])
    ]);
    $cart = $y["Shopping"]["Cart"][md5($username)]["Products"] ?? [];
    $cartCount = count($cart);
    $credits = $y["Shopping"]["Cart"][md5($username)]["Credits"] ?? 0;
    $credits = number_format($credits, 2);
    $discountCode = $y["Shopping"]["Cart"][md5($username)]["DiscountCode"] ?? 0;
    $now = $this->system->timestamp;
    $subtotal = 0;
    $total = 0;
    foreach($cart as $key => $value) {
     $product = $this->system->Data("Get", ["miny", $key]) ?? [];
     $ck = (strtotime($now) < $product["Expires"]) ? 1 : 0;
     if($ck == 1) {
      $price = str_replace(",", "", $product["Cost"]);
      $price = $price + str_replace(",", "", $product["Profit"]);
      $subtotal = $subtotal + $price;
     }
    } if($discountCode != 0) {
     $discountCode = $discountCode ?? [];
     $dollarAmount = $discountCode["DollarAmount"] ?? 0.00;
     $dollarAmount = number_format($dollarAmount, 2);
     $percentile = $discountCode["Percentile"] ?? 0;
     $percentile = $subtotal * ($percentile / 100);
     $check = ($dollarAmount > $percentile) ? "Dollars" : "Percentile";
     $discountCode = [
      "Amount" => $check,
      "Dollars" => $dollarAmount,
      "Percentile" => $percentile
     ];
     if($discountCode["Amount"] == "Dollars") {
      $discountCode = $discountCode["Dollars"];
     } else {
      $discountCode = number_format($discountCode["Percentile"], 2);
     }
    }
    $total = $subtotal - $credits - $discountCode;
    $tax = $shop["Tax"] ?? 10.00;
    $tax = number_format($total * ($tax / 100), 2);
    $total = number_format($tax + $total, 2);
    $order = $braintree->transaction()->sale([
     "amount" => str_replace(",", "", $total),
     "customer" => [
      "firstName" => $y["Personal"]["FirstName"]
     ],
     "options" => [
      "submitForSettlement" => true
     ],
     "paymentMethodNonce" => $paymentNonce
    ]);
    if($order->success) {
     $y["Shopping"]["Cart"][md5($username)]["DiscountCode"] = 0;
     $r = "";
     $physicalOrders = $this->system->Data("Get", [
      "po",
      md5($username)
     ]) ?? [];
     foreach($cart as $key => $value) {
      $value["ID"] = $value["ID"] ?? $key;
      $bundle = $value["Bundled"] ?? [];
      $bundle = (!empty($bundle)) ? 1 : 0;
      $cartOrder = $this->ProcessCartOrder([
       "Bundled" => $bundle,
       "Member" => $y,
       "PhysicalOrders" => $physicalOrders,
       "Product" => $value,
       "UN" => $username
      ]);
      $physicalOrders = ($cartOrder["ERR"] == 0) ? $cartOrder["PhysicalOrders"] : $physicalOrders;
      $r .= $cartOrder["Response"];
      $y = $cartOrder["Member"];
     }
     $y["Shopping"]["Cart"][md5($username)]["Credits"] = 0;
     $y["Shopping"]["Cart"][md5($username)]["Products"] = [];
     $r = $this->system->Change([[
      "[Checkout.Order]" => $r."<br/>".json_encode($y["Shopping"]["Cart"][md5($username)], true),
      "[Checkout.Title]" => $shop["Title"],
      "[Checkout.Total]" => $t
     ], $this->system->Page("83d6fedaa3fa042d53722ec0a757e910")]);
     #$this->system->Data("Save", ["mbr", md5($you), $y]);
     #$this->system->Data("Save", ["po", md5($username), $physicalOrders]);
    } else {
     $r = $this->system->Change([[
      "[Checkout.Order.Message]" => $order->message,
      "[Checkout.Order.Products]" => count($y["Shopping"]["Cart"][md5($username)]["Products"]),
      "[Checkout.Order.Success]" => json_encode($order->success)
     ], $this->system->Page("229e494ec0f0f43824913a622a46dfca")]);
    }
   }
   return $r;
  }
  function SaveCommissionOrDonation(array $a) {
   $data = $a["Data"] ?? [];
   $data = $this->system->FixMissing($data, [
    "amount", "payment_method_nonce", "st"
   ]);
   $y = $this->you;
   $amount = $data["amount"] ?? base64_encode(0);
   $amount = base64_decode($amount);
   $amount = ($amount < 5) ? 5 : $amount;
   $amount = number_format($amount, 2);
   $pts = $this->system->core["PTS"]["Donations"] ?? 0;
   $st = (!empty($data["st"])) ? base64_decode($data["st"]) : "";
   $t = $this->system->Member($this->system->ShopID);
   $shop = $this->system->Data("Get", [
    "shop",
    md5($t["Login"]["Username"])
   ]) ?? [];
   $braintree = $shop["Processing"] ?? [];
   $live = $shop["Live"] ?? 0;
   $env = ($live == 1) ? "production" : "sandbox";
   $r = $this->system->Change([[
    "[Checkout.Data]" => json_encode($data)
   ], $this->system->Page("f9ee8c43d9a4710ca1cfc435037e9abd")]);
   if(!empty($data["payment_method_nonce"])) {
    require_once($this->root);
    $braintree = new Braintree_Gateway([
     "environment" => $env,
     "merchantId" => base64_decode($braintree["BraintreeMerchantID"]),
     "privateKey" => base64_decode($braintree["BraintreePrivateKey"]),
     "publicKey" => base64_decode($braintree["BraintreePublicKey"])
    ]);
    $order = $braintree->transaction()->sale([
     "amount" => str_replace(",", "", $amount),
      "customer" => [
      "firstName" => $y["Personal"]["FirstName"]
     ],
     "options" => [
      "submitForSettlement" => true
     ],
     "paymentMethodNonce" => $data["payment_method_nonce"]
    ]);
    if($order->success) {
     if($st == "Commission") {
      $y["Commission"] = 0;
     }
     $st2 = ($st == "Commission") ? "$$amount commission" : base64_decode($data["amount"])."$$amount donation";
     $st3 = ($st == "Commission") ? "You may now access your Artist dashboard." : "$pts points have been added";
     $y["Points"] = $y["Points"] + $pts;
     $r = $this->system->Change([[
      "[Commission.Message]" => $st3,
      "[Commission.Type]" => $st2
     ], $this->system->Page("f2bea3c1ebf2913437fcfdc0c1601ce0")]);
     $this->system->Data("Save", ["mbr", md5($y["Login"]["Username"]), $y]);
    } else {
     $r = $this->system->Change([[
      "[Checkout.Order.Message]" => $order->message,
      "[Checkout.Order.Products]" => 1,
      "[Checkout.Order.Success]" => json_encode($order->success)
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