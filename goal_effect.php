<?php
   if(($goal_minutes ==0) || ((0<=$sum_minutes) && ($sum_minutes < $goal_minutes / 2))){
      echo "少しずつ頑張っていきましょう<br>";
      echo "<img class='img'  src='https://2.bp.blogspot.com/-JGH_cz6Tzwo/WKbKxiP9ByI/AAAAAAABB3I/a5XZSzqRWCgWMBLRDg6xTUY2zspSHrMZACLcB/s180-c/pose_hashiru_guruguru_man.png'>";
   }
   else if(($sum_minutes >=  $goal_minutes/2) && ($sum_minutes < $goal_minutes * 0.8)){
      echo "半分達成です！この調子です!";
      echo "<span class='material-icons'>navigate_next</span>";
      echo "<img class='img' src='https://3.bp.blogspot.com/-3enEXKDTuPA/Vffs6YrKjnI/AAAAAAAAx-0/zfIJZVrA6LM/s180-c/otaku_otagei.png'>";
   }
   else if(($goal_minutes * 0.8 <=$sum_minutes)  && ($sum_minutes < $goal_minutes)){
      echo "もう少しで達成です!頑張るあなたは最高です!";
      echo "<img class='img' src='https://1.bp.blogspot.com/-TpLtuUgW-iw/XexqS_FgY_I/AAAAAAABWes/fm5Bmpa7yMcozBMVX7gzoNf1TW5YTQ9EACNcBGAsYHQ/s200/kagenagara_ouuen_idle_man01.png'>";
   }
   else if(($goal_minutes <= $sum_minutes) && ($sum_minutes < $goal_minutes*2)){
      echo "よく頑張りましたね!できるところまで頑張っていきましょう!<br>";
      echo "<img class='img' src='https://4.bp.blogspot.com/-14ly7lSqWfc/Wq9-uBU5nAI/AAAAAAABK-4/z3m0vZaMeqUO65jhtFlsaTCK3jv09KoPwCLcBGAs/s180-c/trophy_man.png'>";
   }
   else if($goal_minutes*2 <= $sum_minutes){
      echo "素晴らしすぎる... あなたは努力の神ですか?<br>";
      echo "<img class='img' src='https://2.bp.blogspot.com/-y2bo0WxJCiU/V4-O0nuVH0I/AAAAAAAA8Zc/lxv7lgGGBY49qw7jBMhMkQpH-sC1qfn3ACLcB/s180-c/internet_god.png'>";
   }

?>