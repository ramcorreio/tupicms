$(function() {
	$(".datepicker").datepicker({
		dateFormat: 'dd/mm/yy',
	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	    nextText: '>',
	    prevText: '<',
	});
	
	$('#imovel_address_state').change(function(){
	       var val = $(this).val();
	       $.ajax({
	            type: "POST",
	            url: cidade_ajax_call + "?state_id=" + val,
	            success: function(data) {
	                $('#imovel_address_city').html('');
	                $.each(data, function(key, value) {
	                    $('#imovel_address_city').append('<option value="' + key + '">' + value + '</option>');
	                });
	            }
	        });
	        return false;
	    });

	$('li#gmap_click a').on('click', function(){
		loadMap();
	});
	
	$('#galeria').on('click','.remove_image',function() {
        if(confirm("Deseja realmente excluir?"))
        {
                 $(this).parent().remove();
        }
        refreshSort('#galeria');
	});	
	
	$('#plantas').on('click','.remove_image',function() {
        if(confirm("Deseja realmente excluir?"))
        {
                 $(this).parent().remove();
        }
        refreshSort('#plantas');
	});	
	
	 $(".sort_image").sortable({
	      stop: function(event, ui) {
	        refreshSort("#"+ui.item.parent().parent().attr("id"));	        		 
	     }
	 });
	
});

function semAcento(palavra) {
	if(palavra === 'undefined')
		return false;
    com_acento = 'áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇ ';
    sem_acento = 'aaaaaeeeeiiiiooooouuuucaaaaaeeeeiiiiooooouuuuc_';
    nova='';
    for(var i=0;i<palavra.length;i++) {
      if (com_acento.search(palavra.substr(i,1))>=0) {
      nova+=sem_acento.substr(com_acento.search(palavra.substr(i,1)),1);
      }
      else {
       nova+=palavra.substr(i,1);
      }
    }
    return nova;
}

function refreshSort($area)
{
	var position = 1;
	$($area + " input[id*='_position']").each(function(){
	    $(this).val(position++);
    });
}


/** CARREGA MAPA / GOOGLE MAPS **/
function loadMap()
{
	var street = semAcento($('#imovel_address_street').val());
	if(street === ''){
		alert('Campo rua vazio.');
		return false;
	}
	var number = $('#imovel_address_number').val();
	var city = semAcento($('.city .styleCombobox span').text());
	//var uf = $('').val();
	var address = street+'_'+number+', '+city+', '+'rj';
	if(address === 'undefined'){
		alert('error');
		return false;
	}
   	var map = new google.maps.Map(document.getElementById('map'), { 
       mapTypeId: google.maps.MapTypeId.ROADMAP,       
       zoom: 15,
       scrollwheel: false,
   	});

   	var geocoder = new google.maps.Geocoder();
   	geocoder.geocode({
      'address': address
   	}, 
   	function(results, status) {
        if(status == google.maps.GeocoderStatus.OK) {
      	  var marker = new google.maps.Marker({
                	position: results[0].geometry.location, //posição daonde o marker ficará
      		    map: map,
      		    animation: google.maps.Animation.DROP, //animacao do marker OUTRAS ANIMAÇÕES: BOUNCE,
      		});
           map.setCenter(results[0].geometry.location);
        }
     });
}


var uploader_galeria = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles_galeria',
	container: document.getElementById('container_galeria'),
	url : url_upload,
	flash_swf_url : flash_swf_url,
	silverlight_xap_url : silverlight_xap_url,
	multipart_params : {
        "area" : "galeria",
    },
	filters : {
		max_file_size : '10mb',
		mime_types: [
			{title : "Imagens", extensions : "jpg,gif,png,jpeg"}
		]
	},

	init: {
		PostInit: function() {
			document.getElementById('filelist_galeria').innerHTML = '';
			return false;
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('filelist_galeria').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
			});
			up.start();
		},

		FileUploaded: function(up, file, info) {
			response = JSON.parse(info.response);
			image = response.image;
			prototype = $('#image_prototype').attr('data-prototype');
			html = '<img src="'+image+'"><a class="remove_image">x</a>';
			html += prototype.replace(/__name__/g, media_index);
			$('#galeria').children().first().append('<li id="image_'+response.id+'">'+html+'</li>');
			$("#imovel_images_"+media_index+"_id").val(response.id);
			
			media_index++;
			refreshSort('#galeria');
         },

		UploadProgress: function(up, file) {
			document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
		}
	}
});

uploader_galeria.init();

var uploader_plantas = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles_plantas',
	container: document.getElementById('container_plantas'),
	url : url_upload,
	flash_swf_url : flash_swf_url,
	silverlight_xap_url : silverlight_xap_url,
	multipart_params : {
        "area" : "plantas",
    },
	filters : {
		max_file_size : '10mb',
		mime_types: [
			{title : "Imagens", extensions : "jpg,gif,png,jpeg"}
		]
	},

	init: {
		PostInit: function() {
			document.getElementById('filelist_plantas').innerHTML = '';
			return false;
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('filelist_plantas').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
			});
			up.start();
		},

		FileUploaded: function(up, file, info) {
			response = JSON.parse(info.response);
			image = response.image;
			prototype = $('#image_prototype').attr('data-prototype');
			html = '<img src="'+image+'"><a class="remove_image">x</a>';
			html += prototype.replace(/__name__/g, media_index);
			$('#plantas').children().first().append('<li id="image_'+response.id+'">'+html+'</li>');
			$("#imovel_images_"+media_index+"_id").val(response.id);
			
			media_index++;
			refreshSort('#plantas');
         },

		UploadProgress: function(up, file) {
			document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
		}
	}
});

uploader_plantas.init();

	



