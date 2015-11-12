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
	
	$('#media').on('click','.remove_image',function() {
		image = $(this).prev();
		if(confirm("Deseja realmente excluir?"))
		{
			 $(this).parent().parent().remove();
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

var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles',
	container: document.getElementById('container'),
	url : url_upload,
	flash_swf_url : flash_swf_url,
	silverlight_xap_url : silverlight_xap_url,
	
	filters : {
		max_file_size : '10mb',
		mime_types: [
			{title : "Imagens", extensions : "jpg,gif,png,jpeg"}
		]
	},

	init: {
		PostInit: function() {
			document.getElementById('filelist').innerHTML = '';

			document.getElementById('uploadfiles').onclick = function() {
				uploader.start();
				return false;
			};
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
			});
		},

		FileUploaded: function(up, file, info) {
			response = JSON.parse(info.response);
			image = response.image;
			prototype = $('#media').find('ul').attr('data-prototype');
			
			html = '<img src="'+image+'"><a class="remove_image">x</a>';
			html += prototype.replace(/__name__/g, media_index);
			$('#media').children().first().append('<li><div class="body-image">'+html+'</div></li>');
			$("#galeria_type_images_"+media_index).find('input[type="text"]').val(response.title);
			$("#galeria_type_images_"+media_index+"_id").val(response.id);
			$("#galeria_type_images_"+media_index).hide();
			
			media_index++;
         },

		UploadProgress: function(up, file) {
			document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
		},

		Error: function(up, err) {
			document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
		},

		UploadComplete: function(up, files) {
			
		},
	}
});

uploader.init();
