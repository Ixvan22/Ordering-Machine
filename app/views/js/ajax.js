const formularios_ajax = document.querySelectorAll('.FormularioAjax');

formularios_ajax.forEach(formulario => {
    formulario.addEventListener('submit', e => {
        e.preventDefault();

        let data = new FormData(formulario);
        let method = formulario.getAttribute("method");
        let action = formulario.getAttribute("action");

        let encabezados = new Headers();

        let config = {
            method: method,
            headers: encabezados,
            mode: 'cors',
            cache: 'no-cache',
            body: data
        };

        fetch(action, config)
        .then(respuesta => respuesta.json())
        .then(respuesta => {
            return alertas_ajax(respuesta);
        });
        
    });
});

function alertas_ajax(alerta) {

    if (alerta.tipo == "recargar") {
        Swal.fire({
            position: "center",
            icon: alerta.icono,
            title: alerta.titulo,
            showConfirmButton: true,
            confirmButtonText: "Ok",
          }).then((result) => {
            if (result.isConfirmed) {
              location.href = alerta.url;
            }
          });
    }

    else if (alerta.tipo == "simple") {
        Swal.fire({
            position: "top-end",
            icon: alerta.icono,
            title: alerta.titulo,
            showConfirmButton: false,
            timer: 1500
          });
    }

    else if (alerta.tipo == "confirmar") {
        Swal.fire({
            title: alerta.titulo,
            icon: alerta.icono,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirmar",
            cancelButtonText: "Cancelar"
          }).then((result) => {
            if (result.isConfirmed) {
              location.href = alerta.url;
            }
          });
    }
    else if (alerta.tipo == 'limpiar') {
      Swal.fire({
          position: "top-end",
          icon: alerta.icono,
          title: alerta.titulo,
          confirmButtonText: 'Ok'
      }).then((result) => {
          if(result.isConfirmed){
              document.querySelector('.FormularioAjax').reset();
          }
      });
    }


}