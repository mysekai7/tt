<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>

<head>
  <title>XPath Testing Environment</title>

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
  
  <script language="JavaScript1.2" type="text/javascript">
  <!--
  
    // Function to display the selected XML file.
    function displayXML ( )
    {
        // Open a new window containing the XML file.
        window.open(document.forms.testsuite.file.value);
    }
    
    // Function to evaluate an XPath expression.
    function evaluateXPath ( )
    {
        // Check whether a expression was given.
        if ( document.forms.testsuite.expression.value == "" )
        {
            // Display an error message.
            alert("Please insert an XPath expression to be evaluated.");
            
            // Select the field.
            document.forms.testsuite.expression.focus();
            
            // Stop the execution of this function.
            return;
        }
        
        // Submit the form.
        document.forms.testsuite.submit();
    }

  //-->
  </script>
</head>

<body>

<form name="testsuite" action="<?php echo $PHP_SELF; ?>" method="post">

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td class="content">
      <span class="header">
        XPath Testing Environment
      </span>
      
      <hr><br>
      
      <span class="description">
        This example is written to show you how you can use the
        <a href="http://www.w3.org/TR/xpath" target="_blank">XPath</a>
        language to access and even modify XML documents. Please take a look
        at the official <a href="http://www.w3.org/TR/xpath" target="_blank">
        XPath Recommendation</a> or - which may be better - at the
        <a href="http://www.zvon.org/xxl/XPathTutorial/General/examples.html"
        target="_blank">ZVON XPath Tutorial</a>. Without knowing the XPath
        syntax this example may be very boring... ;-)
      </span>
      
      <br><br>
      
      <span class="description">
        To test &lt;phpXML/&gt; simply first select the XML file you want to
        use for your testing. You can view the content of an XML file by
        clicking the <i>Display</i> link. After that insert your XPath
        expression and click on the <i>Evaluate</i> link. For enhanced testing,
        you can also insert an XPath context. This is the full document path to
        a document node, from which the evaluation shall start. You can also
        select what to do with the nodes matching your XPath expression. If you
        don't known what to do with this enhancing testing, simply leave these
        fields blank.
      </span>
      
      <br><br>
      
      <hr><br>
      
      <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
        <tr>
          <td nowrap>
            <b>XML file:</b>
          </td>
          <td width="100%">
            <select name="file" style="width:100%">
            
            <?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);

            
                // Create an object to access the current directory.
                $directory = dir(getcwd());
                
                // Run through all files of the current directory.
                while ( $entry = $directory->read() )
                {
                    // Check whether it's an XML file.
                    if ( eregi("\.xml$", $entry) )
                    {
                        // Add an entry for this file.
                        echo "<option value=\"$entry\"";
                        
                        // Check whether this file is selected.
                        if ( $entry == $file )
                        {
                            // Select the entry.
                            echo " selected";
                        }
                        
                        // Close the option tag.
                        echo ">$entry</option>\n";
                    }
                }
                
                // Close the directory.
                $directory->close();
            
            ?>
            
            </select>
          </td>
          <td nowrap>
            [ <a href="javascript:displayXML()">Display</a> ]
          </td>
        </tr>
        <tr>
          <td nowrap>
            <b>XPath expression:</b>
          </td>
          <td width="100%">
            <input type="text" name="expression" value="<?php echo ( empty($expression) ? "//*" : $expression ); ?>" style="width:100%">
          </td>
          <td nowrap>
            [ <a href="javascript:evaluateXPath()">Evaluate</a> ]
          </td>
        </tr>
        <tr>
          <td nowrap>
            <b>XPath context:</b>
          </td>
          <td width="100%">
            <input type="text" name="context" value="<?php echo $context; ?>" style="width:100%">
          </td>
          <td nowrap>
            &nbsp;
          </td>
        </tr>
        <tr>
          <td nowrap>
            <b>XPath action:</b>
          </td>
          <td width="100%">
            <select name="action" style="width:100%">
            
            <?php
            
                // Define the available actions.
                $actions = array(
                    "highlight" => "Simply highlight all nodes matching the ".
                                   "XPath expression.",
                    "remove"    => "Remove all nodes matching the XPath ".
                                   "expression."
                );
                
                // Now run through the array of actions.
                foreach ( $actions as $name => $description )
                {
                    // Create the option tag.
                    echo "<option value=\"$name\"";
                    
                    // Check whether the option is selected.
                    if ( $name == $action )
                    {
                        // Select the action.
                        echo " selected";
                    }
                    
                    // Close the option tag.
                    echo ">$description</option>\n";
                }
                
            ?>
            
            </select>
          </td>
          <td nowrap>
            &nbsp;
          </td>
        </tr>
      </table>
      
      <?php
      
        // Check whether a file and a expression was given.
        if ( !empty($file) && !empty($expression) )
        {
            // Display a separator.
            echo "<br><hr><br>";
            
            // Include the <phpXML/> class.
            include("XMLBuilder.class.php");
            
            // Now create a new object for accessing the XML file.
            $xml = new XMLBuilder($file);
            
            // Evaluate the XPath expression.
            $results = $xml->evaluate($expression, $context);
            
            // Now check what action to perform.
            if ( $action == "remove" )
            {
                // Run through all nodes that were found.
                foreach ( $results as $result )
                {
                    // Remove the node.
                    $xml->remove_node($result);
                }
                
                // Clear the results.
                $results = array();
            }
            
            // Now display the content of the XML file.
            echo "<pre>";
            echo $xml->get_file($results);
            echo "</pre>";
        }
      
      ?>
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
