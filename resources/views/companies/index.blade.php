@extends("layouts.app")

@section("content")
    <div class="container mx-auto h-full px-4 py-8">
        <h1 class="mb-6 text-2xl font-bold">Administración de Empresas</h1>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Company form -->
            @include("companies.partials._form")

            <!-- Companies table -->
            <div class="h-full md:col-span-2">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold">Listado de Empresas</h2>
                    <button
                        id="deleteInactiveBtn"
                        class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none"
                    >
                        Eliminar Inactivas
                    </button>
                </div>

                <div
                    class="relative max-h-[calc(100vh-200px)] max-w-full overflow-auto rounded-lg shadow-sm"
                >
                    <table
                        id="companiesTable"
                        class="min-w-full text-left text-sm text-gray-500"
                    >
                        <thead
                            class="bg-gray-50 text-xs text-gray-700 uppercase"
                        >
                            <tr>
                                <th scope="col" class="px-6 py-3">NIT</th>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Dirección</th>
                                <th scope="col" class="px-6 py-3">Teléfono</th>
                                <th scope="col" class="px-6 py-3">Estado</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- All the companies will be loaded here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("scripts")
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Referencias a elementos del DOM
            const companyForm = document.getElementById('companyForm');
            const formTitle = document.getElementById('formTitle');
            const isEditingInput = document.getElementById('isEditing');
            const editingNitInput = document.getElementById('editingNit');
            const resetFormBtn = document.getElementById('resetFormBtn');
            const deleteInactiveBtn =
                document.getElementById('deleteInactiveBtn');

            // Cargar empresas al iniciar
            loadCompanies();

            // Event Listeners
            companyForm.addEventListener('submit', handleFormSubmit);
            resetFormBtn.addEventListener('click', resetForm);
            deleteInactiveBtn.addEventListener(
                'click',
                deleteInactiveCompanies,
            );

            // Funciones
            function loadCompanies() {
                axios
                    .get('/api/v1/companies')
                    .then((response) => {
                        const companies = response.data.data;
                        renderCompaniesTable(companies);
                    })
                    .catch((error) => {
                        console.error('Error cargando empresas:', error);
                        showAlert('Error al cargar las empresas');
                    });
            }

            function renderCompaniesTable(companies) {
                const tableBody = document.querySelector(
                    '#companiesTable tbody',
                );
                tableBody.innerHTML = '';

                if (companies.length === 0) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.innerHTML = `
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay empresas registradas</td>
                    `;
                    tableBody.appendChild(emptyRow);
                    return;
                }

                companies.forEach((company) => {
                    const row = document.createElement('tr');
                    row.className = 'bg-white border-b hover:bg-gray-50';

                    const statusClass =
                        company.status === 'active'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-red-100 text-red-800';

                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">${company.nit}</td>
                        <td class="px-6 py-4">${company.name}</td>
                        <td class="px-6 py-4">${company.address || '-'}</td>
                        <td class="px-6 py-4">${company.phone || '-'}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                ${company.status === 'active' ? 'Activo' : 'Inactivo'}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button
                                onclick="editCompany('${company.nit}')"
                                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-3 py-1 text-xs font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none mr-2">
                                Editar
                            </button>
                            <button
                                onclick="deleteCompany('${company.nit}')"
                                class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-3 py-1 text-xs font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none">
                                Eliminar
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            }

            function handleFormSubmit(e) {
                e.preventDefault();

                const formData = {
                    nit: document.getElementById('nit').value,
                    name: document.getElementById('name').value,
                    address: document.getElementById('address').value,
                    phone: document.getElementById('phone').value,
                    status: document.getElementById('status').value,
                };

                const isEditing = isEditingInput.value === 'true';

                if (isEditing) {
                    const nit = editingNitInput.value;
                    updateCompany(nit, formData);
                } else {
                    createCompany(formData);
                }
            }

            function createCompany(data) {
                axios
                    .post('/api/v1/companies', data)
                    .then((response) => {
                        showAlert('Empresa creada correctamente');
                        resetForm();
                        loadCompanies();
                    })
                    .catch((error) => {
                        console.error('Error al crear empresa:', error);
                        showAlert('Error al crear la empresa');
                    });
            }

            function updateCompany(nit, data) {
                axios
                    .put(`/api/v1/companies/${nit}`, data)
                    .then((response) => {
                        showAlert('Empresa actualizada correctamente');
                        resetForm();
                        loadCompanies();
                    })
                    .catch((error) => {
                        console.error('Error al actualizar empresa:', error);
                        showAlert('Error al actualizar la empresa');
                    });
            }

            // Función para editar empresa - Expuesta globalmente
            window.editCompany = function (nit) {
                axios
                    .get(`/api/v1/companies/${nit}`)
                    .then((response) => {
                        const company = response.data.data;

                        // Rellenar el formulario
                        document.getElementById('nit').value = company.nit;
                        document.getElementById('name').value = company.name;
                        document.getElementById('address').value =
                            company.address || '';
                        document.getElementById('phone').value =
                            company.phone || '';
                        document.getElementById('status').value =
                            company.status;

                        // Cambiar el estado del formulario
                        isEditingInput.value = 'true';
                        editingNitInput.value = company.nit;
                        formTitle.textContent = 'Editar Empresa';

                        // Deshabilitar campo NIT en edición
                        document.getElementById('nit').disabled = true;
                    })
                    .catch((error) => {
                        console.error(
                            'Error al cargar datos de empresa:',
                            error,
                        );
                        showAlert('Error al cargar datos de la empresa');
                    });
            };

            // Función para eliminar empresa - Expuesta globalmente
            window.deleteCompany = function (nit) {
                if (
                    !confirm(
                        '¿Estás seguro de que deseas eliminar esta empresa?',
                    )
                )
                    return;

                axios
                    .delete(`/api/v1/companies/${nit}`)
                    .then((response) => {
                        showAlert('Empresa eliminada correctamente');
                        loadCompanies();
                    })
                    .catch((error) => {
                        if (error.response.status === 409) {
                            showAlert('No puedes eliminar empresas activas');
                        } else {
                            showAlert('Error al eliminar la empresa', 'error');
                        }
                    });
            };

            function deleteInactiveCompanies() {
                if (
                    !confirm(
                        '¿Estás seguro de que deseas eliminar todas las empresas inactivas?',
                    )
                )
                    return;

                axios
                    .delete('/api/v1/companies/inactive')
                    .then((response) => {
                        showAlert(
                            `${response.data.count} empresas inactivas eliminadas`,
                        );
                        loadCompanies();
                    })
                    .catch((error) => {
                        showAlert('Error al eliminar empresas inactivas');
                    });
            }

            function resetForm() {
                companyForm.reset();
                isEditingInput.value = 'false';
                editingNitInput.value = '';
                formTitle.textContent = 'Crear Empresa';
                document.getElementById('nit').disabled = false;
            }

            function showAlert(message) {
                alert(message);
            }
        });
    </script>
@endpush
