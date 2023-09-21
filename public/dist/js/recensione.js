(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
		/*
		var cf=$("#codfisc").val()
		var valida=validaCodiceFiscale(cf);
		if (valida==false) {
		  $("#codfisc").removeClass('is-valid').addClass('is-invalid');
          event.preventDefault()
          event.stopPropagation()
		};
		*/
		
        form.classList.add('was-validated')
      }, false)
    })
})()
$(document).ready( function () {
	$("#btn_save_recensione").click(function(){
		 $('#cliente').attr('required', false); 
		 $('#codice_sl').attr('required', false); 
	});
	$("#btn_sign_recensione").click(function(){
		 $('#cliente').attr('required', true); 
		 $('#codice_sl').attr('required', true); 
		 sign_recensione()
	});

	$("#btn_sign_qa").click(function(){
		 $('#cliente').attr('required', false); 
		 $('#codice_sl').attr('required', false); 
		 sign_qa()
	});
	
} );


function sign_qa() {
	if (!confirm("Attenzione!\nOperazione Irreversibile. Sicuri di apporre la firma QA?")) event.preventDefault()
}

function sign_recensione() {
	if (!confirm("Attenzione!\nOperazione Irreversibile. Sicuri di firmare la sezione?")) event.preventDefault()
}