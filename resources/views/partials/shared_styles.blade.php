/* Estilos para los checkboxes */
       [type="checkbox"]+span:not(.lever):before, 
[type="checkbox"]:not(.filled-in)+span:not(.lever):after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 30px;
    height: 30px;
    z-index: 0;
    border: 2px solid #5a5a5a;
    border-radius: 1px;
    margin-top: 3px;
    transition: .2s;
}

/* Ajusta el tamaño del check dentro del checkbox */
[type="checkbox"]:checked+span:not(.lever):before {
    top: -4px;
    left: -5px;
    width: 12px;
    height: 22px;
    border-top: 2px solid transparent;
    border-left: 2px solid transparent;
    border-right: 2px solid #26a69a;
    border-bottom: 2px solid #26a69a;
    transform: rotate(40deg);
    backface-visibility: hidden;
    transform-origin: 100% 100%;
}

/* Ajusta el espaciado del texto junto al checkbox */
[type="checkbox"]+span:not(.lever) {
    padding-left: 35px;
    height: 30px;
    line-height: 30px;
}
        .table-state-column {
            width: 20%;
        }
        .table-client-column {
            width: 15%;
        }
        .checkbox-column {
            text-align: center;
        }
        .btn.active {
            background-color: #26a69a;
            color: white;
        }

        .checkbox-column input[type="checkbox"] {
            margin: 0 auto;
        }
        nav {
  background-color: #616161; /* Gris medio para el fondo */
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.tabs {
  background-color: #616161; /* Mismo color que nav para consistencia */
}

.tabs .tab a {
  color: #e0e0e0; /* Gris muy claro para el texto */
  font-weight: 500;
}

.tabs .tab a:hover {
  background-color: #757575; /* Gris ligeramente más claro al pasar el mouse */
  color: #ffffff; /* Blanco para máximo contraste al hover */
}

.tabs .tab a.active {
    background-color: #9e9e9e;  /* Gris más claro para la pestaña activa */
  color: #212121; /* Gris muy oscuro para el texto de la pestaña activa */
  font-weight: 600;
}

.tabs .indicator {
  background-color: #2196F3; /* Azul para el indicador */
  height: 3px;
}


.header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        margin-top: 20px;
    }

    .header-container h1 {
        margin: 0;
        font-size: 2.5rem; /* Ajusta según sea necesario */
    }

    .logo {
        max-height: 80px; /* Ajusta según sea necesario */
        width: auto;
    }

    thead {
        position: sticky;
        top: 0;
        background-color: #fff; /* O el color que prefieras */
        z-index: 10;
    }

    th {
        background-color: #fff; /* O el color que prefieras */
    }

    /* Asegura que el contenido no se oculte detrás del header fijo */
    table {
        border-collapse: separate;
        border-spacing: 0;
    }

    /* Si tienes una barra de navegación fija, ajusta este valor */
    thead {
        top: 3px; /* Ajusta este valor a la altura de tu barra de navegación */
    }

    .short-code {
    font-size: 19px; /* o el tamaño que prefieras */
    }

    .text-desc {
    font-size: 13px; /* o el tamaño que prefieras */
    }