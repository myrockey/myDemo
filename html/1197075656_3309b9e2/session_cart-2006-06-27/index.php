<?php
session_start();
require_once("cartClass.php");
echo "<br><table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" width=\"100%\" height=\"202\">
  <tbody>
    <tr width=\"100%\" valign=\"top\">
      <td align=\"left\" height=\"53\" width=\"658\"><table width=\"100%\" height=\"61\" cellspacing=\"2\">
        <tr align=\"left\">
          <td height=\"8\"><table style=\"border-top: solid 1px #0c6ba1\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\">
              <tbody>
                <tr>
                  <td style=\"border-bottom: solid 1px #0c6ba1\" valign=\"middle\" height=\"13\" bgcolor=\"#ddddff\" width=\"78%\" background=\"images/nav.gif\"><font face=\"Arial, Helvetica, sans-serif\" size=\"2\"><b><font color=\"#FFFFFF\">Your Cart</font></b></font></td>
                  <td style=\"border-bottom: solid 1px #0c6ba1\" valign=\"middle\" height=\"13\" bgcolor=\"#ddddff\" width=\"22%\" background=\"images/nav.gif\"><div align=\"right\"> <font color=\"#FFFFFF\">
                      <input style=\"border : solid 1px #336699; PADDING: 0.1em; FONT-SIZE: 10px; FONT-COLOR:#FF6600; CURSOR: pointer; COLOR: white;\" type=button value=\Modify\" onclick=\"Call('cartClass.php?edit=true');\">
                  </font></div></td>
                </tr>
              </tbody>
            <tbody>
              </tbody>
          </table></td>
        </tr>
        <div id=\"div1\"><tr>
          <td height=\"51\" bgcolor=\"#ddddff\"><font size=\"1\"><b><font face=\"Arial, Helvetica, sans-serif\"> </font></b></font> <font size=\"1\"><b><font face=\"Arial, Helvetica, sans-serif\"> </font></b></font>
              <table width=\"100%\" border=\"0\" cellpadding=\"10\">
                <tr>
                  <td>";
				echo "<div id='display_cart'>";
				$cart = new cart();
				echo $cart->show_cart()."</div>";
                  echo "</td>
                </tr>
            </table></td>
        </tr></div>
      </table>";
?>