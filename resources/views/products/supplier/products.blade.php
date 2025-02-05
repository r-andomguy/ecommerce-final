<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Produtos</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.24/dist/tailwind.min.css">
</head>
<body>
<x-app-layout>

    <div x-data="{ openCreateModal: false, openEditModal: false, openDeleteModal: false, successMessage: '', product: {} }" class="container mx-auto px-6 py-10">
        <h1 class="text-4xl font-bold text-gray-900 text-center mb-6">Meus Produtos</h1>

        <div x-show="successMessage" x-transition class="bg-green-100 text-green-800 border border-green-200 rounded-md px-4 py-3 mb-6 shadow">
            <span x-text="successMessage"></span>
        </div>

        <div class="text-end mt-4 mb-4">

            <button @click="openCreateModal = true" class="py-2 px-4 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>

            </button>
        </div>

        @if($products->count() === 0)
            <div class="text-center">
                <p class="text-gray-600 text-lg mb-4">Você não possui produtos cadastrados no momento.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">ID</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Nome</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Preço</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Estoque</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Categoria</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-600">Ações</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $product->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $product->stock }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $product->category['name'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center space-x-4">

                                    <button @click="openEditModal = true; product = {{ $product }}"
                                            class="text-blue-500 hover:text-blue-700 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H5a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-6M9 15l8-8m-3 8H9v-3" />
                                        </svg>
                                    </button>

                                    <button @click="openDeleteModal = true; product = {{ json_encode($product) }}"
                                            class="text-red-500 hover:text-red-700 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m4 0H5" />
                                        </svg>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div x-show="openCreateModal" @click.away="openCreateModal = false" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-semibold mb-4">Criar novo produto</h2>

                <form method="POST" action="{{ route('products.store') }}" @submit.prevent="createProduct">
                    @csrf

                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700">Estoque</label>
                        <input type="number" name="stock" id="stock" required min="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Preço</label>
                        <input type="number" step="0.01" name="price" id="price" required min="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Categoria</label>
                        <select name="category" id="category" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="image_url" class="block text-sm font-medium text-gray-700">URL da Imagem</label>
                        <input type="url" name="image_url" id="image_url" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="https://exemplo.com/imagem.jpg">
                    </div>

                    <div class="text-center mt-6 flex justify-center space-x-4">
                        <button type="button"
                                @click="openCreateModal = false"
                                class="bg-gray-500 text-white py-2 px-4 rounded-md shadow-sm hover:bg-gray-600">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="bg-blue-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-blue-700">
                            Salvar Produto
                        </button>
                    </div>

                    <div x-show="successMessage" x-transition
                         id="create-success-message"
                         class="bg-green-50 border border-green-400 text-green-800 rounded-md px-4 py-3 mb-4 shadow-md">
                        <span x-text="successMessage"></span>
                    </div>

                    <div id="create-success-message" class="hidden fixed bottom-4 right-4 bg-green-500 text-white py-2 px-4 rounded-lg shadow-lg">
                        <span></span>
                    </div>
                </form>
            </div>
        </div>

        <div  x-data="{product: {{$product->toJson()}}, originalProduct: {{$product->toJson()}}}"x-show="openEditModal" @click.away="openEditModal = false" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-xl font-semibold mb-4">Editar Produto</h2>

                <form method="POST" action="{{ route('products.update', $product) }}" @submit.prevent="editProduct" data-product-id="{{ $product->id }}">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="edit-name" class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="name" id="edit-name" required x-model="product.name"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="edit-stock" class="block text-sm font-medium text-gray-700">Estoque</label>
                        <input type="number" name="stock" id="edit-stock" required min="0" x-model="product.stock"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="edit-price" class="block text-sm font-medium text-gray-700">Preço</label>
                        <input type="number" step="0.01" name="price" id="edit-price" required min="0" x-model="product.price"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="edit-category" class="block text-sm font-medium text-gray-700">Categoria</label>
                        <select name="category" id="edit-category" required x-model="product.category_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="edit-image-url" class="block text-sm font-medium text-gray-700">URL da Imagem</label>
                        <input type="url" name="image_url" id="edit-image-url" required x-model="product.image_url"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="https://exemplo.com/imagem.jpg">
                    </div>

                    <div class="text-center mt-6 flex justify-center space-x-4">
                        <button type="button"
                                @click="openEditModal = false"
                                class="bg-gray-500 text-white py-2 px-4 rounded-md shadow-sm hover:bg-gray-600">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="bg-blue-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-blue-700">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="openDeleteModal"
             @click.away="openDeleteModal = false"
             class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 transition-opacity">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
                <h2 class="text-2xl font-bold text-gray-900">Confirmar Exclusão</h2>
                <p class="mt-4 text-gray-700">
                    Tem certeza que deseja excluir o produto
                    <span x-text="product.name" class="font-bold text-gray-900"></span>?
                </p>

                <div class=" text-center mt-6 flex justify-center space-x-4">
                    <button @click="openDeleteModal = false"
                            class="py-2 px-4 bg-gray-100 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button @click="deleteItem(product.id)"
                            class="py-2 px-4 bg-red-500 text-white font-semibold rounded-md hover:bg-red-600">
                        Confirmar
                    </button>
                </div>

                <button @click="openDeleteModal = false"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div x-show="deleteSuccessMessage" x-transition
                     id="delete-success-message"
                     class="bg-green-50 border border-green-400 text-green-800 rounded-md px-4 py-3 mt-4 shadow-md">
                    <span x-text="deleteSuccessMessage"></span>
                </div>
            </div>
        </div>


    </div>


    <script>
        function createProduct(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            let openCreateModal;

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (response.status === 201) {
                        return response.json();
                    } else {
                        return response.json().then(data => {
                            throw new Error(data.error || 'Erro ao criar produto.');
                        });
                    }
                })
                .then(data => {
                    showMessage('create-success-message', data.message);
                    openCreateModal = false;
                    setTimeout(() => location.reload(), 2500);
                })
                .catch(error => alert(error.message));
        }

        function editProduct(event) {
            event.preventDefault();

            const form = event.target.closest('form');
            const productId = form.getAttribute('data-product-id');

            const formData = new FormData(form);

            console.log(formData);

            fetch(`/products/${productId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na atualização do produto');
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Produto atualizado com sucesso!');
                    location.reload();
                })
                .catch(error => alert(error.message));
        }

        function deleteItem(itemId) {
            fetch(`/products/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
                .then(response => {
                    if (response.status === 204) {
                        document.querySelector(`[data-negotiation-id="${itemId}"]`)?.remove();
                        showMessage('delete-success-message', 'Produto excluído com sucesso!');
                        setTimeout(() => location.reload(), 2500);
                    } else {
                        return response.json().then(data => {
                            alert(data.error || 'Erro ao excluir produto.');
                        });
                    }
                })
                .catch(error => alert('Erro ao excluir produto.'));
        }

        function showMessage(containerId, message, timeout = 5000) {
            const messageContainer = document.getElementById(containerId);
            if (messageContainer) {
                messageContainer.querySelector('span').innerText = message;
                messageContainer.style.display = 'block';
                setTimeout(() => {
                    messageContainer.style.display = 'none';
                }, timeout);
            }
        }
    </script>
</x-app-layout>
</body>
</html>
