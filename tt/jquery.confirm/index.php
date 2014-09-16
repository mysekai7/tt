<!DOCTYPE html>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>A jQuery Confirm Dialog Replacement with CSS3 | Tutorialzine Demo</title>

<link href='http://fonts.googleapis.com/css?family=Cuprum&amp;subset=latin' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="jquery.confirm/jquery.confirm.css" />

</head>
<body>

<div id="page">

	<?php 
    
    $tuts = array(
        array('id' => 1274, 'title' => 'Coding a Rotating Image Slideshow w/ CSS3 and jQuery'),
        array('id' => 1266, 'title' => 'Making an Apple-style Splash Screen'),
        array('id' => 1260, 'title' => 'jQuery\'s Data Method – How and Why to Use It'),
        array('id' => 1248, 'title' => 'Making Better Select Elements with jQuery and CSS3'),
        array('id' => 1240, 'title' => 'Creating a Stylish Coming Soon Page with jQuery'),
        array('id' => 1209, 'title' => 'Making an AJAX Web Chat'),
        array('id' => 1185, 'title' => 'Quick Feedback Form w/ PHP and jQuery')
    );
    
    foreach($tuts as $t){
        ?>
        
        <div class="item">
	        <a href="http://tutorialzine.com/?p=<?php echo $t['id']?>">
            	<img src="http://cdn.tutorialzine.com/img/featured/<?php echo $t['id']?>.jpg" title="<?php echo $t['title']?>" alt="<?php echo $t['title']?>" width="620" height="340" />
            </a>
            <div class="delete"></div>
        </div>
        <?php
    }
    
    ?>
</div>

<div id="footer">
	<div class="tri"></div>
	<h1>A jQuery Confirm Dialog Replacement</h1>
	<a class="tzine" href="http://tutorialzine.com/2010/12/better-confirm-box-jquery-css3/">Read &amp; Download on</a>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script src="jquery.confirm/jquery.confirm.js"></script>
<script src="js/script.js"></script>

</body>
</html>
