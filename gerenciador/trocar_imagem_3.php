		<script language="Javascript">


			// Remember to invoke within jQuery(window).load(...)
			// If you don't, Jcrop may not initialize properly
			$(document).ready(function () { 

				jQuery('#cropbox').Jcrop({
					onChange: showCoords,
					onSelect: showCoords,
					aspectRatio: 1
				});

			});

			// Our simple event handler, called from onChange and onSelect
			// event handlers, as per the Jcrop invocation above
			function showPreview(coords)
			{
				if (parseInt(coords.w) > 0)
				{
					var rx = 100 / coords.w;
					var ry = 100 / coords.h;

					jQuery('#preview').css({
						width: Math.round(rx * 500) + 'px',
						height: Math.round(ry * 370) + 'px',
						marginLeft: '-' + Math.round(rx * coords.x) + 'px',
						marginTop: '-' + Math.round(ry * coords.y) + 'px'
					});
				}
			}
			
			// Our simple event handler, called from onChange and onSelect
			// event handlers, as per the Jcrop invocation above
			function showCoords(c)
			{
				jQuery('#x').val(c.x);
				jQuery('#y').val(c.y);
				jQuery('#x2').val(c.x2);
				jQuery('#y2').val(c.y2);
				jQuery('#w').val(c.w);
				jQuery('#h').val(c.h);
			}
			

		</script>
		<style>
		img{
				border:none; padding:0;
		}
		</style>
	<div id="outer">
	<div class="jcExample">
	<div class="article">

		<h1>Jcrop - Aspect ratio lock w/ preview pane</h1>

		<!-- This is the image we're attaching Jcrop to -->
		<table>
		<tr>
		<td>
		<img src="jcrop/demos/demo_files/flowers.jpg" id="cropbox" />
		</td>
		<td>
		<!--<div style="width:100px;height:100px;overflow:hidden;">
			<img src="jcrop/demos/demo_files/flowers.jpg" id="preview" />
		</div>-->
				<form onsubmit="return false;">
			<label>X1 <input type="text" size="4" id="x" name="x" /></label><br />
			<label>Y1 <input type="text" size="4" id="y" name="y" /></label><br />
			<label>X2 <input type="text" size="4" id="x2" name="x2" /></label><br />
			<label>Y2 <input type="text" size="4" id="y2" name="y2" /></label><br />
			<label>W <input type="text" size="4" id="w" name="w" /></label><br />
			<label>H <input type="text" size="4" id="h" name="h" /></label>
		</form>

		
		</td>
		</tr>
		</table>


	</div>
	</div>
	</div>