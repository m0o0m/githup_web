<?include_once("main_header.php");?>
<input type="hidden" id="touzhutype" value="0">
<script language="javascript" src="../style/js/jquery.js"></script>
<script language="javascript" src="../style/js/jquery_dialog.js"></script>
<script language="javascript" src="../style/js/common.js"></script>
<script language="javascript" src="ft_ruqiushu.js" ></script>
<script language="javascript" src="../style/js/bet_match.js"></script>
<script language="javascript" src="../style/js/mouse.js"></script>

<script  language="JavaScript">


function chg_league() {
	var legview = document.getElementById('legView');

	try {

			legFrame.location.href = "chuangkous.php?lsm=ftz_ruqiushu";
	} catch(e) {
            legFrame.src = "chuangkous.php?lsm=ftz_ruqiushu";
    }
	legview.style.display = '';
	legview.style.top = document.body.scrollTop + 82; 
	
	
	legview.style.left = document.getElementById('myTable').scrollLeft + 10;

	
}
function setleghi(leghight) {
	var legview = document.getElementById('legFrame');

	if ((leghight * 1) > 95) {
		legview.height = leghight;
	} else {

		legview.height = 95;
	}
	
	
	
}
function LegBack() {
	var legview = document.getElementById('legView');
	legview.style.display = 'none';
	reload_var("");
}

</script>


  
   
          <table  id="game_table"  cellspacing="0"  cellpadding="0"  class="game">
           <tr>
              <th  class="time">时间</th>
			  <th  class="time">主客队伍</th>
			 <!-- <th  class="h_1x2">1x2</th>-->
			  <th class="h_1x2">0~1</th>
              <th class="h_1x2">2~3</th>
              <th class="h_1x2">4~6</th>
              <th class="h_1x2">7或以上</th>
		
            </tr>
         <tbody  id="datashow">	

		  
				
				
		  <tr><td  colspan="18" align="center" style="background:#fff;">正在加载数据...</td></tr>
	
	</tbody></table>
  
	
	</td>
      </tr>
<Tr><td id="foot"><b>&nbsp;</b></td></Tr>
    </tbody></table>


<div  id="refresh_down"  class="refresh_M_btn" ><span onclick="javascript:shuaxin(document.getElementById('league').value);" >刷新</span></div>


</td>
		</tr>
	</tbody>
</table>

	



<div  id="legView"  style="display:none;"  class="legView">
    <div  class="leg_head" ></div>
	<div><iframe  id="legFrame"  scrolling="no"  frameborder="no"  border="0"  allowtransparency="true"></iframe></div>
    <div  class="leg_foot"></div>
</div>

</body></html>


