<?php 
	echo "<pre>";
	$path_root = $_SERVER['DOCUMENT_ROOT']."/rank/";
	if ( $_SERVER['REQUEST_METHOD'] === 'POST') {
	    echo "<pre>";
		$files = $_FILES['files']['name'];
		if ( !empty( $files)) {
			$target_dir = 'D:\Sync G\MMO\API\vd.txt';
			$r = array();
			foreach ( $files as $key => $file ) {
				if($_FILES['files']['error'][$key] == 0){
					$info = pathinfo( $file);
					if ( $info['extension'] !== 'txt') continue;	
					//bat dau copy file	
					$valid_file = true;
					//now is the time to modify the future file name and validate the file
					if($_FILES['files']['size'][$key] > (1024000*2)) //can't be larger than 2 MB
					{
						$valid_file = false;
						$message = 'Oops!  Your file\'s size is to large.';
					}
					
					//if the file has passed the test
					if($valid_file)
					{
						//move it to where we want it to be
						move_uploaded_file($_FILES['files']['tmp_name'][$key], $target_dir);
						$message = 'Congratulations!  Your file was accepted.';
						shell_exec($path_root.'tool/rank.bat');
					}
					echo $message;	
				}
			}
			$r = actionRank();
		}
		
		echo "</pre>";
	}else{
		header("Location: index.html");
	}
	// $r = actionRank();
	// print_r($_SERVER);die();
	function actionRank(){
		$path = "D:/Sync G/MMO/API/test/result/";
		$files = scandir($path);
		$b = array();
		for ($i=0; $i < sizeof($files); $i++) { 
			if($files[$i] == '.' || $files[$i] == '..') continue;
			if(is_file($path.$files[$i])) continue;

			// echo $files[$i];echo "<br>";
			$newpath = $path.$files[$i]."/";
			$keyword = trim(explode("- Google", $files[$i])[0]);
			$b[$keyword] = getRankFolder($newpath);
			// echo "-------------------------\n<br>";
		}
		// shell_exec('rmdir /s /q "D:/Sync G/MMO/API/test/result/"');
		// shell_exec('mkdir "D:/Sync G/MMO/API/test/result"');
		return $b;
		
	}

	// die();
	

	function getRankFolder($path){
		// echo 'getRankFolder '.$path."<br>";
		$scans = scandir($path);
		$a = array();
		for ($i=0; $i < sizeof($scans); $i++) { 
			if($scans[$i] == '.' || $scans[$i] == '..') continue;
			if(!is_file($path.$scans[$i])) continue;

			$info = pathinfo($path.$scans[$i]);
			if(!isset($info['extension'])) continue;
			$extension = $info['extension'];
			if($extension != 'html') continue;
			$content_file = getContentFile($path.$scans[$i]);
			if(!empty($content_file))
				$a[] = $content_file; 
		}
		return $a;
		
	}

	function getContentFile($path_files){
		// echo $path_files."<br>";
		$myFile = $path_files;
		$content = file($myFile);
		// how many lines in this file
		$numLines = count($content);
		$group = array();
		$vpos = $vda = $vpa = $vlink = '';
		// process each line
		for ($i = 0; $i < $numLines; $i++) {
			$line = trim($content[$i]);
		   	// do something with $line here ...
			$string = $line;

			$position = '<div class="position">';
			$pa = '<div class="title">PA';
			$da = '<div class="title">DA';
			$link = '<div class="links">';
			$link2 = '<a href="https://moz.com/researchtools/ose/links?site=http';

			//co ky tu trong chuoi
			if ( strpos( $string, $position ) !== false ) {
				$string = str_replace('<div class="position">', '', $string);
				$string = str_replace(')</div>', '', $string);
				$vpos = trim($string); 
			}

			if ( strpos( $string, $da ) !== false ) {
				$string = str_replace('<div class="title">DA:', '', $string);
				$string = str_replace('</div>', '', $string);
				$vda = trim($string);
			} 

			if ( strpos ( $string, $pa ) !== false ) {
				$string = str_replace('<div class="title">PA:', '', $string);
				$string = str_replace('</div>', '', $string);
				$vpa = trim($string);
			} 

			if ( strpos( $string, $link2 ) !== false ) {
				$string = str_replace('<a href="https://moz.com/researchtools/ose/links?site=http', 'http', $string);
				$vlink = trim(explode('&', $string)[0]);
			}
			
		}
		$a = array('pos'=>$vpos,'da'=>$vda,'pa'=>$vpa,'link'=>$vlink);
		if(!($vpos == '' && $vda == '' && $vpa == '' && $vlink == '')){
			$group = $a;
		}
		return $group;
	}
	

	function sortRank($mang) {
    // Đếm tổng số phần tử của mảng
		$sophantu = count($mang);

    // Lặp để sắp xếp
		for ($i = 0; $i < $sophantu ; $i++)
		{
        // Tìm vị trí phần tử nhỏ nhất
			$min = $i;
			for ($j = $i + 1; $j < $sophantu; $j++){
				if ($mang[$j]['pos'] < $mang[$min]['pos']){
					$min = $j;
				}
			}

        // Sau khi có vị trí nhỏ nhất thì hoán vị
        // với vị trí thứ $i
			$temp = $mang[$i];
			$mang[$i] = $mang[$min];
			$mang[$min] = $temp;
		}

    // Trả về mảng đã sắp xếp
		return $mang;
	}
	echo "</pre>";
	?>



	<!DOCTYPE html>
	<html>
	<head>
		<title>Tôi là bố Minh Đăng. Chào mừng đến với công cụ đếm Rank của Moz Bar</title>
		<style type="text/css">
			.tg  {border-collapse:collapse;border-spacing:0;border-color:#aaa;}
			.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#aaa;color:#333;background-color:#fff;}
			.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#aaa;color:#fff;background-color:#f38630;}
			.tg .tg-j2zy{background-color:#FCFBE3;vertical-align:top;}
			.tg .tg-yw4l{vertical-align:top}
			.tg-link{width: 900px; max-width: 900px;}
			.center {text-align: center;}
			.progress {
			  border: 0;
			  height: 11px;
			  /* Turns off styling - not usually needed, but good to know. */
			    appearance: none;
			    -moz-appearance: none;
			    -webkit-appearance: none;
			}
			.progress-easy, .easy { background-color: #6B8E23 !important;}
			.progress-normal, .normal { background-color: #FFA500 !important;}
			.progress-hard, .hard { background-color: #FF4500 !important;}
		</style>
		<script src="js/Chart.min.js"></script>
	</head>
	<body>
		<form style="text-align:left;" id="paypalform" action="cal.php" method="POST" enctype="multipart/form-data">
			<input type="file" id="files" name="files[]" multiple="" />
			<output id="list"></output>
			<input type="submit" name="submit" value="Submit">
		</form>
		<script>
			function handleFileSelect(evt) {
      var files = evt.target.files; // FileList object

      // files is a FileList of File objects. List some properties.
      var output = [];
      for (var i = 0, f; f = files[i]; i++) {
      	output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
      		f.size, ' bytes, last modified: ',
      		f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
      		'</li>');
      }
      document.getElementById('list').innerHTML = '<ul>' + output.join('') + '</ul>';
  }

  document.getElementById('files').addEventListener('change', handleFileSelect, false);
</script>
<br>
<?php 
echo "<pre>";

$easy = 35; $hard = 45;
$array = array();
foreach ($r as $keyword => $value) {
	$sum_da = 0;$row = 0; $de = $tb = $kho = 0;
	$value = sortRank($value);
	foreach ($value as $key => $v) {
		($v['da'] != '')? $da = $v['da'] : $da = 0;
		$sum_da += $da;
		$row++;

		if($da <= $easy) {
			$de++;
		} else if ($da > $easy && $da <= $hard) {
			$tb++;	
		}else if($da > $hard) {
			$kho++;
		}
	}
	$array[$keyword]['row'] = $row;
	$array[$keyword]['avg_da'] = $sum_da/$row;
	

	$array[$keyword]['de'] = $de;
	$array[$keyword]['trungbinh'] = $tb;
	$array[$keyword]['kho'] = $kho;

	$array[$keyword]['link'] = $value;
}
// print_r($array);
// print_r($r);
echo "</pre>";
?>
<div class="show-result">
	<?php 
		$i=0; 
		foreach ($array as $key_word => $lists) { ?>
		<?php $i++; ?>
		<table class="tg">
			<tr>
				<th class="tg-yw4l" colspan="4">Overview</th>
				<th class="tg-yw4l" colspan="7">
					<a href="https://www.google.com.vn/search?q=<?php echo str_replace(' ', '+', trim($key_word)); ?>" target="_blank">
						<?php echo $key_word; ?>
					</a>
				</th>
			</tr>
			<tr>
				<td class="tg-j2zy" colspan="4" rowspan="3">
					
					<?php 
						$da_class="progress-easy";
						if($lists['avg_da'] > $easy && $lists['avg_da'] <= $hard) $da_class = 'progress-normal';
						if($lists['avg_da'] > $hard) $da_class = 'progress-hard';
					?>
					Rank : <span class="<?php echo $da_class; ?>"> <?php echo (int)($lists['avg_da'])." / 100"; ?> </span> <br>
					
					<progress max="100" value="<?php echo $lists['avg_da']; ?>" class="progress progress-hard <?php //echo $da_class; ?>"></progress>
				</td>
				<td class="tg-j2zy" colspan="7" rowspan="3">
					<span class="easy">Site dễ : <?php echo $lists['de']; ?> </span>-
					<span class="normal">Site trung bình : <?php echo $lists['trungbinh']; ?> </span>- 
					<span class="hard">Site khó : <?php echo $lists['kho']; ?></span> 
					<br>
					<canvas id="countries-<?php echo $i; ?>" width="100" height="80"></canvas>
					<script type="text/javascript">
						// pie chart data
					    var pieData = [
					        {
					        	//dễ
					            value : <?php echo $lists['de']*10; ?>,
					            color : "#4ACAB4"
					        },
					        {
					        	//trung binh
					            value : <?php echo $lists['trungbinh']*10; ?>,
					            color : "#FFEA88"
					        },
					        {
					        	//kho
					            value : <?= $lists['kho']*10; ?>,
					            color : "#FF8153"
					        }
					    ];
					 
					    // pie chart options
					    var pieOptions = {
					        segmentShowStroke : true,
					        animateScale : true,
					        cutoutPercentage : 0
					    }
					 
					    // get pie chart canvas
					    var countries= document.getElementById("countries-<?php echo $i; ?>").getContext("2d");
					    // draw pie chart
					    new Chart(countries).Pie(pieData, pieOptions);
					</script>
				</td>
			</tr>
			<tr>
			</tr>
			<tr>
			</tr>
			<tr>
				<td class="tg-yw4l">#</td>
				<td class="tg-yw4l">Google SERP</td>
				<td class="tg-yw4l center">DA</td>
				<td class="tg-yw4l center">PA</td>
				<td class="tg-yw4l center">MR</td>
				<td class="tg-yw4l center">MT</td>
				<td class="tg-yw4l center">Links</td>
				<td class="tg-yw4l center">FB</td>
				<td class="tg-yw4l center">G+</td>
				<td class="tg-yw4l center">Rank</td>
				<td class="tg-yw4l center">Est. visits</td>
			</tr>
			<?php foreach ($lists['link'] as $k => $value) { ?>
				<tr>
					<?php 
						$da_cls="easy";
						if($value['da'] > $easy && $value['da'] <= $hard) $da_cls = 'normal';
						if($value['da'] > $hard) $da_cls = 'hard';
					?>
					<td class="tg-j2zy"><?php echo $value['pos']; ?></td>
					<td class="tg-j2zy tg-link">
						<a href="<?php echo $value['link']; ?>" target="_blank"><?php echo $value['link']; ?></a>
					</td>
					<td class="tg-j2zy center <?php echo $da_cls; ?>"><?php echo $value['da']; ?></td>
					<td class="tg-j2zy center"><?php echo $value['pa']; ?></td>
					<td class="tg-j2zy center"></td>
					<td class="tg-j2zy center"></td>
					<td class="tg-j2zy center"></td>
					<td class="tg-j2zy center"></td>
					<td class="tg-j2zy center"></td>
					<td class="tg-j2zy center"></td>
					<td class="tg-j2zy center"></td>
				</tr>
			<?php } ?>
		</table>
		<br>
	<?php } ?>
</div>  

</body>
</html>

<?php 
	// echo "<pre>";
	// print_r($array);
	// echo "</pre>";
?>

