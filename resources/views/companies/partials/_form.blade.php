<div class="h-max rounded-lg bg-white p-6 shadow-md md:col-span-1">
    <h2 class="mb-4 text-xl font-semibold" id="formTitle">Crear Empresa</h2>
    <form id="companyForm" class="space-y-4">
        <input type="hidden" id="isEditing" value="false" />
        <input type="hidden" id="editingNit" value="" />

        <div>
            <label for="nit" class="block text-sm font-medium text-gray-700">
                NIT
            </label>
            <input
                type="text"
                id="nit"
                name="nit"
                class="mt-1 block h-10 w-full rounded-md border-gray-300 px-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
        </div>

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
                Nombre
            </label>
            <input
                type="text"
                id="name"
                name="name"
                class="mt-1 block h-10 w-full rounded-md border-gray-300 px-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
        </div>

        <div>
            <label
                for="address"
                class="block text-sm font-medium text-gray-700"
            >
                Dirección
            </label>
            <input
                type="text"
                id="address"
                name="address"
                class="mt-1 block h-10 w-full rounded-md border-gray-300 px-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">
                Teléfono
            </label>
            <input
                type="text"
                id="phone"
                name="phone"
                class="mt-1 block h-10 w-full rounded-md border-gray-300 px-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">
                Estado
            </label>
            <select
                id="status"
                name="status"
                class="mt-1 block h-10 w-full rounded-md border-gray-300 px-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="active">Activo</option>
                <option value="inactive">Inactivo</option>
            </select>
        </div>

        <div class="flex space-x-3">
            <button
                type="submit"
                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
            >
                Guardar
            </button>
            <button
                type="button"
                id="resetFormBtn"
                class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
            >
                Cancelar
            </button>
        </div>
    </form>
</div>
