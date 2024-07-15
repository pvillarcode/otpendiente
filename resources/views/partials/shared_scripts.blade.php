function updateAllCheckboxState(checked, codigo, checkboxes) {
        const updates = Array.from(checkboxes).map(checkbox => {
            return {
                columna: checkbox.getAttribute('data-columna'),
                valor: checked ? 1 : 0
            };
        });

        fetch('/update-all-checkboxes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                codigo: codigo,
                updates: updates
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


function attachRowSelectors() {
        const rowSelectors = document.querySelectorAll('.select-all-row');
        rowSelectors.forEach(selector => {
            const row = selector.closest('tr');
            const checkboxes = row.querySelectorAll('.row-checkbox');
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            selector.checked = allChecked;
            selector.addEventListener('change', function() {
                const checked = selector.checked;
                checkboxes.forEach(checkbox => {
                    checkbox.checked = checked;
                });
                updateAllCheckboxState(checked, selector.getAttribute('data-codigo'), checkboxes);
            });
        });
    }

    attachRowSelectors();




 

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


    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });

    const channel = pusher.subscribe('checkbox-channel');
    channel.bind('checkbox-updated', function(data) {
        updateUIFromPusherEvent(data.checkboxState);
    });

    function updateUIFromPusherEvent(checkboxState) {
    const hiddenInput = document.querySelector(`tr input[type="hidden"][value="${checkboxState.codigo}"]`);
    if (hiddenInput) {
        const row = hiddenInput.closest('tr');
        if (row) {
            const isIndexView = document.body.classList.contains('index-view');
            
            // Actualizar checkboxes
            ['matriz', 'corte', 'pulido', 'perforado', 'pintado', 'empavonado', 'curvado', 'laminado'].forEach(field => {
                const checkbox = row.querySelector(`input[type="checkbox"][onchange*="${field}"]`);
                if (checkbox) {                    
                    if (isIndexView) {
                        checkbox.checked = checkboxState[field];
                        const parentLabel = checkbox.parentElement;
                        if (parentLabel) {
                            parentLabel.style.display = checkboxState[`disabled_${field}`] ? 'none' : '';
                        }
                    }
                }
            });

            // Actualizar estado
            const estadoInput = row.querySelector('input[name="estado"]');
            if (estadoInput) {
                estadoInput.value = checkboxState.estado || '';
            }
        }
    } else {
        console.error(`No se encontró el elemento oculto con el valor ${checkboxState.codigo}`);
    }
}
