<?php
/*PHP100精华：PHP生成柱状图*/
function createImage($data,$twidth,$tspace,$height){
            $dataName = array();
            $dataValue = array();
            $i = 0;
            $j = 0;
            $k = 0;
            $num = sizeof($data);

             foreach($data as $key => $val){
                    $dataName[] = $key;
                    $dataValue[] = $val;
                 }

            $maxnum = max($data);
            $width = ($twidth + $tspace) * $num + 4;//image's width
            $im = imagecreate($width + 40,$height+20);
            $lineColor = imagecolorallocate($im,12,12,12);
            $bgColor = imagecolorallocate($im,255,233,233);
            $tColor = imagecolorallocate($im,123,200,56);
            imagefill($im,0,0,$bgColor);
            imageline ( $im, 30, 0, 30, $height - 2, $lineColor);
            imageline ( $im, 30, $height - 2, $width + 30 -2 , $height - 2,$lineColor);
             while($i < $num){
                imagefilledrectangle ( $im, $i * ($tspace+$twidth) + 40, $height - $dataValue[$i], $i * ($tspace+$twidth) + 40 + $twidth, $height - 3, $tColor);
                imagestringup ( $im, 4, $i * ($tspace+$twidth) + $twidth/2 + 30, $height - 10, $dataName[$i]."(".$dataValue[$i].")", $lineColor);
                $i++;
             }
             while($j <= (500/10)){
                imagestringup ( $im, 4, 2, $height - $j * 10 + 10, $j * 10, $lineColor);
                $j = $j + 10;
             }
             while($k <= (500/10)){
                 if($k != 0)
                imageline ( $im, 28, $height - $k * 10, 32 , $height - $k * 10,$lineColor);
                $k = $k + 10;
             }
            imagepng($im);
         }

header("content-type:image/png");
$data = array("Yahoo" => 100, "Google" => 260,"Microsoft" => 320,"IBM" => 250,"Sun System" => 150,"Inter" => 220);
createImage($data,50,25,500);

?>