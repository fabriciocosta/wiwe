<?php 

function validateEmail ($email) { 
    global $SERVER_NAME; 
    $return = array (false, ""); 
    list ($user, $domain)  = split ("@", $email, 2); 
    $arr = explode (".", $domain); 
    $count = count ($arr); 
    ## Here starts the modification (E.Soysal) 
    if (($count>  2) and ($arr[$count - 2]=='com' or $arr[$count - 2]=='org' or 
        $arr[$count - 2]=='net' or $arr[$count - 2]=='edu' or 
        $arr[$count - 2]=='mil' or $arr[$count - 2]=='k12')) { 
            $tld = $arr[$count - 3].".".$arr[$count - 2] . "." . $arr[$count - 1]; 
    } else { 
    ## End of modification 
        $tld = $arr[$count - 2] . "." . $arr[$count - 1]; 
    } 
    if (checkdnsrr ($tld, "MX")) { 
        if (getmxrr ($tld, $mxhosts, $weight)) { 
            for ($i = 0; $i < count ($mxhosts); $i++) { 
                $fp = fsockopen ($mxhosts[$i], 25); 
                if ($fp) { 
                    $s = 0; 
                    $c = 0; 
                    $out = ""; 
                    set_socket_blocking ($fp, false); 
                    do { 
                        $out = fgets ($fp, 2500); 
                        if (ereg ("^220", $out)) { 
                            $s = 0; 
                            $out = ""; 
                            $c++; 
                        } else if (($c > 0) && ($out == "")) { 
                            break; 
                        } else { 
                            $s++; 
                        } 
                        if ($s == 9999) { 
                            break; 
                        } 
                    } while ($out == ""); 
                    set_socket_blocking ($fp, true); 

                    fputs ($fp, "HELO $SERVER_NAME\n"); 
                    $output = fgets ($fp, 2000); 
                    fputs ($fp, "MAIL FROM: <info@" . $tld . ">\n"); 
                    $output = fgets ($fp, 2000); 
                    fputs ($fp, "RCPT TO: <$email>\n"); 
                    $output = fgets ($fp, 2000); 
                    if (ereg ("^250", $output)) { 
                        $return[0] = true; 
                    } else { 
                        $return[0] = false; 
                        $return[1] = $output; 
                    } 
                    fputs ($fp, "QUIT\n"); 
                    fclose($fp); 

                    if ($return[0] == true) { 
                        break; 
                    } 
                } 
            } 
        } 
    } 
    return $return; 
} 


function checkEmail($email) 
{
   if(eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $email)) 
   {
      return FALSE;
   }

   list($Username, $Domain) = split("@",$email);

   if ($Domain=='') return FALSE;   
   
   if(getmxrr($Domain, $MXHost)) 
   {
      return TRUE;
   }
   else 
   {/*
      if(fsockopen($Domain, 25, $errno, $errstr, 30)) 
      {
         return TRUE; 
      }
      else 
      {
         return FALSE; 
      }
      */
   	  return FALSE;
   }
}

?>