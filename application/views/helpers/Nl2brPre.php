<?php

class Zend_View_Helper_Nl2brPre
{
	public function nl2brPre($string)
	{
		// do nl2br except when in a pre tag
	    // First, check for <pre> tag
	    if(!strpos($string, "<pre>"))
	    {
	        echo nl2br($string);
	    } else {
		
		    // If there is a <pre>, we have to split by line
		    // and manually replace the linebreaks with <br />
		    $strArr=explode("\n", $string);
		    $output="";
		    $preFound=false;
		
		    // Loop over each line
		    foreach($strArr as $line)
		    {    // See if the line has a <pre>. If it does, set $preFound to true
		        if(strpos($line, "<pre>"))
		        {
		            $preFound=true;
		        }
		        elseif(strpos($line, "</pre>"))
		        {
		            $preFound=false;
		        }
		       
		        // If we are in a pre tag, just give a \n, else a <br />
		        if($preFound)
		        {
		            $output .= $line . "\n";
		        }
		        else
		        {
		            $output .= $line . "<br />";
		        }
		    }
		
		    echo $output;
		}
	}	
}
