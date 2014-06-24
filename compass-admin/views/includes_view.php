<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
switch ($screen):
	case 'tinymce':
	?>
		<script>
			tinymce.init({
			    selector: "textarea.tinymce",
			    theme: "modern",
			    language : "<?php echo get_setting('general_language'); ?>",
			    menubar : false,
			    relative_urls: false,
			    convert_urls: false,
			    toolbar1: "undo redo | styleselect  forecolor | bold italic underline strikethrough | blockquote numlist bullist | alignleft aligncenter alignright alignjustify | removeformat charmap link unlink media image preview fullscreen code",
			    plugins: [
			         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
			         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			         "save table contextmenu directionality emoticons template paste textcolor"
			   	],
			   	image_advtab: true,
			   	content_css: "<?php echo base_url(); ?>/compass-content/includes/css/foundation.min.css",
				style_formats: [
			        {title: "Parágrafo", inline: "p"},
			        {title: "Título H1", block: "h1"},
			        {title: "Título H2", block: "h2"},
			        {title: "Título H3", block: "h3"},
			        {title: "Título H4", block: "h4"},
			        {title: "Destaque", inline: "span", styles: {color: "#ff0000"}},
			    ],
			});
		</script>
	<?php
		break;
	case 'tinymcesmall':
	?>
		<script>
			tinymce.init({
			    selector: "textarea.tinymce",
			    theme: "modern",
			    language : "<?php echo get_setting('general_language'); ?>",
			    menubar : false,
			    relative_urls: false,
			    convert_urls: false,
			    toolbar1: "bold italic underline | numlist bullist | link unlink | image | fullscreen | code",
			    plugins: [
			         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
			         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			         "save table contextmenu directionality emoticons template paste textcolor"
			   	],
			   	image_advtab: true,
			   	content_css: "<?php echo base_url(); ?>/compass-content/includes/css/foundation.min.css",
			});
		</script>
	<?php
		break;
	case 'codemirror':
	?>
		<script>
		    var editor = CodeMirror.fromTextArea(document.getElementById("codemirror"), {
		        lineNumbers: true,
		        mode: "text/html",
		        matchBrackets: true,
		        lineWrapping: true
		    });
		</script>
	<?php
		break;
	case 'datatable':
	?>
		<script type="text/javascript">
		    $(document).ready(function(){
				$(".data-table").dataTable({
					"oLanguage": {
						"sSearch": "<?php echo lang('core_search'); ?>",
						"sInfo": "<?php echo lang('core_showing'); ?> _START_  - _END_ , _TOTAL_ <?php echo lang('core_registers'); ?>"
					},
					"sScrollY": "auto",
					"sScrollX": "100%",
					"sScrollXInner": "100%",
					"bPaginate": false,
					"aaSorting": [[1, "asc"]]
				});
				$(".dataTables_filter").addClass('small-3 small-offset-9');
				$(".dataTables_filter label").first().focus().addClass('');
			});
		</script>
	<?php
		break;
	case 'datetimepicker':
	?>
		<script type="text/javascript">
		    $(".datetimepicker").datetimepicker({
			 lang:"de",
			 i18n:{
			  de:{
			   months:[
			    "<?php echo lang('core_month_january'); ?>","<?php echo lang('core_month_february'); ?>","<?php echo lang('core_month_march'); ?>",
			    "<?php echo lang('core_month_april'); ?>", "<?php echo lang('core_month_may'); ?>","<?php echo lang('core_month_june'); ?>",
			    "<?php echo lang('core_month_july'); ?>","<?php echo lang('core_month_august'); ?>","<?php echo lang('core_month_september'); ?>",
			    "<?php echo lang('core_month_october'); ?>","<?php echo lang('core_month_november'); ?>","<?php echo lang('core_month_december'); ?>",
			   ],
			   dayOfWeek:[
			    "<?php echo lang('core_day_monday'); ?>", "<?php echo lang('core_day_tuesday'); ?>", "<?php echo lang('core_day_wednesday'); ?>",
			     "<?php echo lang('core_day_thursday'); ?>", "<?php echo lang('core_day_friday'); ?>",
			     "<?php echo lang('core_day_saturday'); ?>", "<?php echo lang('core_day_sunday'); ?>",
			   ]
			  }
			 },
			 timepicker:true,
			 format:"Y-m-d H:i:s",
			 allowBlank:true
			});
		</script>
	<?php
		break;
	case 'tagsinputmaster':
	?>
<script type="text/javascript">
	$(function() {
		$("#tags").tagsInput({
			width:"auto",
			"defaultText":""
		});
	});
</script>
	<?php
		break;
	case 'colorpicker':
	?>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			$("#layout_background").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_text_color").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_link_color").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_link_visited").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_link_hover").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_header_background").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_title_color").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_description_color").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_sidebar_color").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_sidebar_background").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_sidebar_tabs_color").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_sidebar_tabs_background").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_pages_title_color").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_pages_title_background").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_pages_background").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_images_background").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_images_border_color").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_footer_color").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
			$("#layout_footer_background").colorpicker({strings: "<?php echo lang('core_colors_pallet'); ?>"});
		});
		</script>
	<?php
		break;
	case 'slug':
	?>
		<script type="text/javascript">
		jQuery.fn.slug = function(options) {	
			var settings = {
				slug: "slug"		
			};
			if(options) {
				jQuery.extend(settings, options);
			}
			$this = jQuery(this);
			var slugfy = function() {
				str = $this.val();
				str = str.replace(/^\s+|\s+$/g, ""); 
				str = str.toLowerCase();	  
				var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
				var to   = "aaaaaeeeeeiiiiooooouuuunc------";
				for (var i=0, l=from.length ; i<l ; i++) {
				  str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
				}
				str = str.replace(/[^a-z0-9 -]/g, "").replace(/\s+/g, "-").replace(/-+/g, "-");
				jQuery("input." + settings.slug).val(str);
			};
			jQuery(this).keyup(slugfy);
			return $this;
		};
		</script>
		<script type="text/javascript"> 
			$(document).ready(function(){ 
				$("#title").slug(); 
			});
		</script>
	<?php
		break;
	case 'deletereg':
	?>
		<script type="text/javascript">
		    $(function(){
		        $(".deletereg").click(function(){
		            if (confirm("<?php echo lang('core_confirm_delete'); ?>")) return true; else return false;
		        });
		    });
		</script>
	<?php
		break;
	case 'selectinput':
	?>
		<script type="text/javascript">
		    $(function(){
		        $("input.select").click(function(){
		            (this).select();
		        });
		    });
		</script>
	<?php
		break;
	case 'insertmediasmodal':
	?>
		<script type="text/javascript">
			$(function(){
				$('.insertIMG').click(function(e){
					e.preventDefault();
					$('#modalimg').reveal({
						animation : 'none'
					});
				});
				$('.searchIMG').click(function(){
					var destino = "<?php echo base_url('cms/medias/insertmediasmodal') ?>";
					var dados = $(".searchTXT").serialize();
					$.ajax({
						type: "POST",
						url: destino,
						data: dados,
						success: function(ret){
							$(".return").html(ret);
						}
					});
				});
				$(".limparimg").click(function(){
					$(".searchTXT").val('');
					$(".return").html('');
				});
				$('.uploadIMG').click(function(){
					var destino = "<?php echo base_url('cms/uploadmediasmodal') ?>";
					var dados = $(".fileIMG").serialize();
					$.ajax({
						type: "POST",
						url: destino,
						data: dados,
						success: function(ret){
							$(".return").html(ret);
						}
					});
				});
			});
		</script>
	<?php
		break;
	case 'elementshiden':
	?>
		<script type='text/javascript'>
			$(document).ready(function(){
				$('.elements > div').hide();
				$('select[name="termmeta_type"]').change(function(){
					$('.elements > div').hide();
					$('.'+$( this ).val()).show('fast');
				});
			});
		</script>
	<?php
		break;
	case 'scrollbar':
	?>
		<script type="text/javascript">
		    $(function(){
		      $(".nano").nanoScroller({
		        preventPageScrolling: true
		      });
		    });
		</script>
	<?php
		break;
endswitch;