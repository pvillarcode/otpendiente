function updateCheckboxState(checkbox, codigo, columna) {
            const valor = checkbox.checked ? 1 : 0;

            fetch('/update-checkbox', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    codigo: codigo,
                    columna: columna,
                    valor: valor
                })
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            }).then(data => {
                console.log(data);
            }).catch(error => {
                console.error('Error:', error);
            });
        }
        let timeout;

function updateEstado(event, input, codigo) {
    // Función para realizar la actualización
    const doUpdate = () => {
        fetch('/update-estado', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                codigo: codigo,
                estado: input.value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Optionally, provide some visual feedback
                input.style.backgroundColor = '#e8f5e9';
                setTimeout(() => {
                    input.style.backgroundColor = '';
                }, 2000);
            } else {
                console.error('Error updating estado:', data.error);
                // Optionally, provide some error feedback
                input.style.backgroundColor = '#ffebee';
                setTimeout(() => {
                    input.style.backgroundColor = '';
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };

    // Limpiar el timeout existente
    clearTimeout(timeout);

    if (event.type === 'keypress' && event.key === 'Enter') {
        event.preventDefault(); // Prevent form submission if it's within a form
        doUpdate();
    } else if (event.type === 'input') {
        // Establecer un nuevo timeout
        timeout = setTimeout(doUpdate, 3000);
    } else if (event.type === 'blur') {
        doUpdate();
    }
}