<?php
/**
 * Shopping Cart - Manipulate the item of a shopping cart using AJAX
 *
 * @category    E-Commerce
 * @package     AJAX
 * @author      Ashraf Gheith <nurazije@gmail.com>
 * @license     http://www.php.net/license/2_02.txt   The PHP License
 * @link        http://www.phpclasses.org/browse/package/3188.html
 * @todo        Make a better interface
 * @todo        Add some DB manipulation functions
 */
class cart{
	/**
     * Set a function to handle the cart operations
     *
     * @param   mixed   $product_id    The product id
     * @param   mixed   $price		   The price of the product (single item) without VAT
     * @param   mixed   $session_id    The session id for example from session_id();
     * @param   mixed   $product_name  The product name
     * @param   mixed   $vat    	   The VAT value
     * @param   mixed   $user_id       The user id in the crm system
     * @param   mixed   $case    	   The operation wanted to be done, Remove an item or Add one
     * @param   int     $id    		   The id of the product in the shoping cart
     * @param   mixed   $type    	   The type of the product if there is any
     * @return  void
     */
	function cart_container($product_id,$price,$session_id,$product_name,$vat,$user_id,$case,$id = NULL,$type = NULL)
	{
		$container = array(); // create temp array
		session_register($container); // register a variable in Session
		switch($case){	// Two cases - Add or Remove item
			case "Add": // Add an item in the cart
				// asign values to the array
				$container["p{$product_id}"]["name"] = $product_name; 
				$container["p{$product_id}"]["id_product"] = $product_id;
				$container["p{$product_id}"]["count"] = 1;
				$container["p{$product_id}"]["session"] = $session_id;
				$container["p{$product_id}"]["price"] = $price;
				$container["p{$product_id}"]["vat"] = $vat;
				$container["p{$product_id}"]["user"] = $user_id;
				$container["p{$product_id}"]["type"] = $type;
				$duplicate = "false"; // we expect there is no duplicates by defaults
				for(@reset($_SESSION["container"]); list($i) = @each($_SESSION["container"]);) // walk through the array
				{
					if($_SESSION["container"][$i]["id_product"]==$container["p{$product_id}"]["id_product"]){ // if we find a duplicate add 1 to counter
						$duplicate = "true"; // we found a duplicate
						$_SESSION["container"][$i]["count"]++;
					}
				}
				if($duplicate == "true"){ 
					$duplicate = "false"; // duplicate found, keep looking
				}else{
					$_SESSION["container"][]=$container["p{$product_id}"]; // no duplicates asign the whole array as a new row
				}
				$this->tracking($product_id,$price,$session_id,$product_name,$vat,$user_id,$case,$type); // add to DB tracking
			break;
			case "Remove": // Remove an item from the cart
				// Null the data of a product
				$_SESSION["container"][$id]="";
				unset($_SESSION["container"][$id]); // unset the product totaly
				$this->tracking($product_id,$price,$session_id,$product_name,$vat,$user_id,$case,$type); // add to DB tracking
			break;
		}
	} 
	/**
     * Track what happens in the cart and log it in DB - this function can be canceled
     *
     * @param   mixed   $product_id    The product id
     * @param   mixed   $price		   The price of the product (single item) without VAT
     * @param   mixed   $session_id    The session id for example from session_id();
     * @param   mixed   $product_name  The product name
     * @param   mixed   $vat    	   The VAT value
     * @param   mixed   $user_id       The user id in the crm system
     * @param   mixed   $case    	   The operation wanted to be done, Remove an item or Add one
     * @param   mixed   $type    	   The type of the product if there is any
     * @return  void
     */
	function tracking($product_id,$price,$session_id,$product_name,$vat,$user_id,$case,$type = NULL)
	{
		$query = "INSERT INTO `track_cart` ( `id` , `date` , `product_id` , `price` , `session_id` , `product_name` , `vat` , `user_id` , `case` )VALUES (NULL, NOW(), '$product_id', '$price', '$session_id', '$product_name', '$vat', '$user_id', '$case')";
		// we call a class which connects to the DB and makes the insert, here you add your class or function
		// you can use my class in http://www.phpclasses.org/browse/package/3046.html
	}
	/**
     * Show the cart in HTML table
     *
     * @return  void
     */
	function show_cart(){ 
		for (@reset($_SESSION["container"]); list($i) = @each($_SESSION["container"]);){ // walk through the cart
			echo "<table width=\"97%\" border=\"0\" cellspacing=\"0\" align=\"center\" cellpadding=\"10\">
                      <tr>
                        <td width=\"80%\" height=\"14\"><table width=\"100%\" border=\"0\">
                            <tr>
                              <td width=\"71%\" height=\"14\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><div id='change$i'>".$_SESSION["container"][$i]["count"]." Items of</div> <b>".$_SESSION["container"][$i]["name"]." (".$_SESSION["container"][$i]["price"]."&euro;)</b> <br>"; 
								if(isset($_SESSION["container"][$i]["type"])&&$_SESSION["container"][$i]["type"]!=NULL){
                              echo "Version : ".$_SESSION["container"][$i]["type"];
								}
                              echo "</font></td>
                              <td width=\"29%\" height=\"14\">&nbsp;</td>
                            </tr>
                        </table></td>
                        <td width=\"20%\" height=\"14\"><div align=\"center\"><font face=\"Arial, Helvetica, sans-serif\"><font face=\"Arial, Helvetica, sans-serif\"><font size=\"2\"><b>".$_SESSION["container"][$i]["price"]*$_SESSION["container"][$i]["count"]." EUR</b></font></font></font></div></td>
                      </tr>
                      <tr style=\"border-bottom : solid 1px #666666\">
                        <td style=\"border-bottom : solid 1px #666666\" width=\"80%\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\">VAT 
                          (".$_SESSION["container"][$i]["vat"].") </font></td>
                        <td style=\"border-bottom : solid 1px #666666\" width=\"20%\"><div align=\"center\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\">".$this->sub_total($i,$_SESSION["container"][$i]["vat"])." 
                          EUR </font></div></td>
                      </tr><tr align=center><td colspan=2 align=center></td></tr>";
			}
		echo "<script language=JavaScript>\n
				function createRequestObject(){
					var request_;
					var browser = navigator.appName;
					if(browser == \"Microsoft Internet Explorer\"){
		 				request_ = new ActiveXObject(\"Microsoft.XMLHTTP\");
					}else{
		 				request_ = new XMLHttpRequest();
			     	}
					return request_;
				}
				var http = createRequestObject();
				function Call(url){
					//alert(url);	
					http.open('get',url);    
					http.onreadystatechange = handle_call;
					http.send(null);	
				}
				function handle_call(){
					if(http.readyState == 4)
					{
		 				var response = http.responseText;
		 				document.getElementById('display_cart').innerHTML = response;
					}
				}
			</script>";
			if(count($_SESSION["container"])>0){
                      echo "<tr>
                        <td style=\"border-bottom : solid 1px #666666\" width=\"80%\" height=\"34\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b>Total 
                          TTC</b></font></td>
                        <td style=\"border-bottom : solid 1px #666666\" width=\"20%\" height=\"34\"><div align=\"center\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b> ".$this->total()."
                          EUR</b></font></div></td>
                      </tr>";}else{
                      echo "<tr><td colspan=2> Sorry there is no products in cart ...</td></tr>";
                      }
                      
                  echo "</table>";
	}
	/**
     * Return the VAT value of a product multiplied with the amount of that item in the cart
     *
     * @param   int     $id       The product number in the cart
     * @param   mixed   $vat      The VAT value
     * @return  float
     */
	function sub_total($id,$vat){
		$times = $_SESSION["container"][$id]["count"];
		$sub = $_SESSION["container"][$id]["price"]*$times;
		$vat = substr($vat, 0, -1);
		$total = $sub * $vat / 100;
		return $total;
	}
	/**
     * Return the total amount must be payed including VAT
     *
     * @return  float
     */
	function total(){
		$sum = 0;
		for(@reset($_SESSION["container"]); list($i) = @each($_SESSION["container"]);){	// walk through the cart
			$sum = $sum + ($this->sub_total($i,$_SESSION["container"][$i]["vat"])+($_SESSION["container"][$i]["count"]*$_SESSION["container"][$i]["price"]));
		}
		return $sum;
	}
	/**
     * Show the edit case of the cart
     *
     * @return  void
     */
	function show_cart_edit(){
		for (@reset($_SESSION["container"]); list($i) = @each($_SESSION["container"]);){ // walk through the cart
			echo "<form action=\"cart.php\" method=post><table width=\"97%\" border=\"0\" cellspacing=\"0\" align=\"center\" cellpadding=\"10\">
                      <tr>
                        <td width=\"80%\" height=\"14\"><table width=\"100%\" border=\"0\">
                            <tr>
                              <td width=\"71%\" height=\"14\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><input type=text size=2 name=edit$i value='".$_SESSION["container"][$i]["count"]."'> Items of <b>".htmlentities(stripslashes($_SESSION["container"][$i]["name"]))." (".$_SESSION["container"][$i]["price"]."&euro;)</b> <br>";
                              if(isset($type)&&$type!=NULL){
                              		echo "Version : ".$_SESSION["container"][$i]["type"];
								}
								echo "</font></td>
                              <td width=\"29%\" height=\"14\">&nbsp;</td>
                            </tr>
                        </table></td>
                        <td width=\"20%\" height=\"14\"><div align=\"center\"><font face=\"Arial, Helvetica, sans-serif\"><font face=\"Arial, Helvetica, sans-serif\"><font size=\"2\"><b>".$_SESSION["container"][$i]["price"]*$_SESSION["container"][$i]["count"]." EUR</b></font></font></font></div></td>
                      </tr>
                      <tr style=\"border-bottom : solid 1px #666666\">
                        <td style=\"border-bottom : solid 1px #666666\" width=\"80%\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\">VAT 
                          (".$_SESSION["container"][$i]["vat"].") </font></td>
                        <td style=\"border-bottom : solid 1px #666666\" width=\"20%\"><div align=\"center\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\">".$this->sub_total($i,$_SESSION["container"][$i]["vat"])." 
                          EUR </font></div></td>
                      </tr><tr align=center><td colspan=2 align=center><div><input type=checkbox name=remove$i> Remove this item..</div></td></tr>";
		}
		echo "<script language=JavaScript>\n
				function createRequestObject(){
					var request_;
					var browser = navigator.appName;
					if(browser == \"Microsoft Internet Explorer\"){
		 				request_ = new ActiveXObject(\"Microsoft.XMLHTTP\");
					}else{
		 				request_ = new XMLHttpRequest();
			     	}
					return request_;
				}
				var http = createRequestObject();
				function Call(url){
					//alert(url);	
					http.open('get',url);    
					http.onreadystatechange = handle_call;
					http.send(null);	
				}
				function handle_call(){
					if(http.readyState == 4)
					{
		 				var response = http.responseText;
		 				document.getElementById('display_cart').innerHTML = response;
					}
				}
			</script>";
			if(count($_SESSION["container"])>0){
					echo "<tr>
                        <td style=\"border-bottom : solid 1px #666666\" width=\"80%\" height=\"34\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b>Total 
                          TTC</b></font></td>
                        <td style=\"border-bottom : solid 1px #666666\" width=\"20%\" height=\"34\"><div align=\"center\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b> ".$this->total()."
                          EUR</b></font></div></td>
                      </tr><tr><td colspan=2 align=center><input type=hidden name=save value=true><input type=submit value='Save Changes'>";
                      echo "</td></tr>";}else{
                      echo "<tr><td colspan=2> Sorry there is no products in cart ...</td></tr>";
                      }
                      
                  echo "</table></form>";
	}

}

// This section is operation section, it is not example, it must be here to interact with the example file..
if(isset($_GET["operation"])&&$_GET["operation"]=="true")
{
	$cart = new cart();
	$cart->cart_container($product_id,$price,$session_id,$product_name,$vat,$user_id,$case,$id,$type);
}
if(isset($_GET["show"])&&$_GET["show"]=="true"){
	$cart_show = new cart();
	$cart_show->show_cart();
}

if(isset($_GET["load"])&&$_GET["load"]=="true"){
	ob_start();
	header("location: index.php");
}

if(isset($_GET["edit"])&&$_GET["edit"]=="true"){
	$cart = new cart();
	$cart->show_cart_edit();
}

if(isset($_POST["save"])&&$_POST["save"]=="true"){
	$cart = new cart();
	//print "<pre>".print_r($_POST,true)."</pre>";
	for (@reset($_SESSION["container"]); list($i) = @each($_SESSION["container"]);){
		if(isset($_POST["edit$i"])&&($_POST["edit$i"]>0))
		{
			if(!preg_match("/[^0-9]/",$_POST["edit$i"])){
				$_SESSION["container"][$i]["count"]=$_POST["edit$i"];
			}
		}
		if(isset($_POST["remove$i"])&&$_POST["remove$i"]=="on"){
			$cart->cart_container($_SESSION["container"][$i]["product_id"],$_SESSION["container"][$i]["price"],$_SESSION["container"][$i]["session"],$_SESSION["container"][$i]["name"],$_SESSION["container"][$i]["vat"],$_SESSION["email"],"Remove",$i,$_SESSION["container"][$i]["type"]);
		}
		//print "<pre>".print_r($_SESSION,true)."</pre>";
	}
	ob_start();
	header("location: index.php");
}
//print "<pre>".print_r($_SESSION,true)."</pre>";
?>