<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>

<head>
  <title>The Federal Government of Germany</title>

  <style type="text/css">
  <!--
  
    a, a:link, a:visited, a:active, a:focus
    {
        color : #6E749F;
        background : transparent;
        text-decoration : none;
        font-weight : bold;
    }
    
    a:hover
    {
        color : #6E749F;
        background : transparent;
        text-decoration : none;
        font-weight : bold;
    }
    
    body
    {
        margin : 0px 0px 0px 0px;
        background : #FFFFFF;
        font : 10pt Verdana, Geneva, Arial, Helvetica, sans-serif;
    }
    
    hr
    {
        color : #000000;
        background : transparent;
        width : 100%;
        height : 1px;
    }
    
    span.description
    {
        background : transparent;
        color : #000000;
        background-attachment : scroll;
        line-height : 150%;
    }
    
    span.header
    {
        background : transparent;
        color : #6E749F;
        font : bold 13pt Tahoma, Verdana, Geneva, Arial, Helvetica, sans-serif;
        letter-spacing : 1px;
    }
    
    span.small
    {
        background : transparent;
        color : #000000;
        font : 8pt Tahoma, Verdana, Geneva, Arial, Helvetica, sans-serif;
    }
    
    td
    {
        background : transparent;
        font : 10pt Verdana, Geneva, Arial, Helvetica, sans-serif;
    }
    
    td.content
    {
        padding : 20px 20px 20px 20px;
        background : #FFFFFF;
        color : #000000;
        vertical-align : top;
    }
    
  -->
  </style>
</head>

<body>

<form action="<?php echo $PHP_SELF; ?>" method="post">

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="content">
      <span class="header">
        The Federal Government of Germany
      </span>
      
      <hr><br>
      
      <span class="description">
        In this list you can get more information about the Federal Government
        of Germany. Currently the government is stored in an XML file.
      </span>
      
      <br><br>

      <table border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td><b>Search:</b></td>
          <td><input type="text" name="term" value="<?php error_reporting(E_ERROR | E_WARNING | E_PARSE); echo $term; ?>" size="30" maxlength="30"></td>
          <td><input type="submit" value="Search"></td>
        </tr>
      </table>      
      
      <br><br>
      
      <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
        <tr>
          <td><span class="small"><b>Surname</b></span></td>
          <td><span class="small"><b>Prename</b></span></td>
          <td><span class="small"><b>Party</b></span></td>
          <td><span class="small"><b>Position</b></span></td>
        </tr>
        
        <?php
        

            // Include the <phpXML/> class.
            include("XMLBuilder.class.php");
            
            // Create an XML object for the XML file.
            $xml = new XMLBuilder("government.xml");
            
            // Check whether a search term was given.
            if ( !empty($term) )
            {
                // Only select those persons, in whose name or position
                // the search string is present.
                $government = $xml->evaluate(
                    "//person/*[contains(., $term)]/..");
            }
            else
            {
                // Select all members of the government.
                $government = $xml->evaluate("//person");
            }
            
            // Run through all members of the government.
            foreach ( $government as $person )
            {
                // Retrieve information about the person.
                $surname  = $xml->get_content($person."/surname[1]");
                $prename  = $xml->get_content($person."/prename[1]");
                $party    = $xml->get_content($person."/party[1]");
                $position = $xml->get_content($person."/position[1]");
                
                // Display the information.
                ?>
                
                <tr>
                  <td><?php echo $surname; ?></td>
                  <td><?php echo $prename; ?></td>
                  <td><?php echo $party; ?></td>
                  <td><?php echo $position; ?></td>
                </tr>
                
                <?php
            }
        
        ?>
        
      </table>
    </td>
  </tr>
  <tr>
    <td class="content">
      <hr>
      
      <span class="small">
        Copyright © 2001 <a href="mailto:mpm@phpxml.org">Michael P. Mehl</a>.
        All rights reserved.
      </span>      
    </td>
  </tr>
</table>

</form>
</body>
</html>
