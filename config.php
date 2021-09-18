<?php
  define("BASEURL",$_SERVER["DOCUMENT_ROOT"]."/ecommerceProject/");
	define("CART_COOKIE","SBwi72UCKlwiqzz2");
	define("CART_COOKIE_EXPIRE", time() +(86400 *30));
	define("TAXRATE", 0.087); // sales tax rate set to 0 when you not going to use tax....

	define("CURRENCY", "USD");
	define("CHECKOUTMODE", "TEST"); // change test to live when you are ready to go to live....

	if(CHECKOUTMODE == "TEST"){
		define("STRIPE_PRIVATE","sk_test_d29joF5sNdrknFpc3iNoOACS");
		define("STRIPE_PUBLIC","pk_test_hFN2eH0QEHK8saSmXi4EKFug");
	}

	if(CHECKOUTMODE == "LIVE"){
		define("STRIPE_PRIVATE","");
		define("STRIPE_PUBLIC","");
	}
?>